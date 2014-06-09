<?php

/**
 * PHPwind6.3.2整合插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这是一个免费开源的软件；这意味着您可以在不用于商业目的的前提下对程序代码
 * 进行修改、使用和再发布。
 * ============================================================================
 * $Author: testyang $
 * $Id: phpwind6.php 14769 2008-07-31 07:10:31Z testyang $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 会员数据整合插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = 'phpwind6';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'PHPWind';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '6.32/7.0';

    /* 插件的作者 */
    $modules[$i]['author']  = 'ECSHOP R&D TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 插件的初始的默认值 */
    $modules[$i]['default']['db_host'] = 'localhost';
    $modules[$i]['default']['db_user'] = 'root';
    $modules[$i]['default']['prefix'] = 'pw_';

    return;
}

require_once(ROOT_PATH . 'includes/modules/integrates/integrate.php');
class phpwind6 extends integrate
{
    /* 论坛加密密钥 */
    var $db_hash = '';
    var $db_sitehash = '';

    function __construct($cfg)
    {
        $this->phpwind6($cfg);
    }

    /**
     *  插件类初始化函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function phpwind6 ($cfg)
    {
        parent::integrate($cfg);
        if ($this->error)
        {
            /* 数据库连接出错 */
            return false;
        }
        $this->field_id = 'uid';
        $this->field_name = 'username';
        $this->field_email = 'email';
        $this->field_gender = 'gender';
        $this->field_safecv = 'safecv';
        $this->field_bday = 'bday';
        $this->field_pass = 'password';
        $this->field_reg_date = 'regdate';
        $this->user_table = 'members';

        /* 检查数据表是否存在 */
        $sql = "SHOW TABLES LIKE '" . $this->prefix . "%'";

        $exist_tables = $this->db->getCol($sql);

        if (empty($exist_tables) || (!in_array($this->prefix.$this->user_table, $exist_tables)) || (!in_array($this->prefix.'config', $exist_tables)))
        {
            $this->error = 2;
            /* 缺少数据表 */
            return false;
        }

        /* 设置论坛的加密密钥 */
        $this->db_hash = $this->db->GetOne("SELECT `db_value` FROM ".$this->table('config')." WHERE `db_name` = 'db_hash'");
        $this->db_sitehash = $this->db->GetOne("SELECT `db_value` FROM ".$this->table('config')." WHERE `db_name` = 'db_sitehash'");
    }



    /**
     *  设置论坛cookie
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function set_cookie ($username="")
    {
        parent::set_cookie($username);
        $cookie_name = substr(md5($this->db_sitehash), 0, 5) . '_winduser';
        if (empty($username))
        {
            $time = time() - 3600;
            setcookie($cookie_name, '', $time, $this->cookie_path, $this->cookie_domain);
        }
        else
        {

            $sql = "SELECT " . $this->field_id . " AS user_id, " . $this->field_pass . " As password," . $this->field_safecv ." AS safecv".
                   " FROM " . $this->table($this->user_table) .
                   " WHERE " . $this->field_name . "='$username'";

            $row = $this->db->getRow($sql);

            $cookie_name = substr(md5($this->db_sitehash), 0, 5) . '_winduser';
            $salt =  md5($_SERVER["HTTP_USER_AGENT"] . $row['password'] . $this->db_hash);

            $auto_login_key = $this->code_string($row['user_id']."\t".$salt."\t".$row['safecv'], 'ENCODE');

            setcookie($cookie_name, $auto_login_key, time()+3600*24*30, $this->cookie_path, $this->cookie_domain);
        }
    }

    /**
     * 检查cookie
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function check_cookie ()
    {
        $cookie_name = substr(md5($this->db_sitehash), 0, 5) . '_winduser';

        if (!isset($_COOKIE[$cookie_name]))
        {
            return '';
        }

        $arr = addslashes_deep(explode("\t", $this->code_string($_COOKIE[$cookie_name], 'DECODE')));

        if (count($arr) != 3)
        {
            return false;
        }
        list($user_id, $salt_probe) = $arr;

        $sql = "SELECT " .$this->field_id. " AS user_id, " . $this->field_name . " As user_name, ".
               $this->field_pass . " AS password ".
               " FROM ".$this->table($this->user_table).
               " WHERE " . $this->field_id . " = '$user_id'";
        $row = $this->db->getRow($sql);

        if (!$row)
        {
            return '';
        }

        $salt = md5($_SERVER["HTTP_USER_AGENT"] . $row['password'] . $this->db_hash);

        if ($salt != $salt_probe)
        {
            return '';
        }

        return $row['user_name'];

    }

    /* 加密解密函数，自动登录密钥也是用该函数进行加密解密 */
    function code_string($string, $action='ENCODE')
    {
        $key    = substr(md5($_SERVER["HTTP_USER_AGENT"] . $this->db_hash), 8, 18);

        $string = $action == 'ENCODE' ? $string : base64_decode($string);
        $keylen = strlen($key);
        $strlen = strlen($string);
        $code   = '';
        for ($i = 0; $i < $strlen; $i++)
        {
            $k     = $i % $keylen;
            $code .= $string[$i] ^ $key[$k];
        }

        $code = $action == 'DECODE' ? $code : base64_encode($code);

        return $code;
    }


    /**
     *  获取论坛有效积分及单位
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_points_name ()
    {
        static $ava_credits = NULL;
        if ($ava_credits === NULL)
        {
            $sql = "SELECT db_value FROM " . $this->table('config') . " WHERE db_name='db_credits'";
            $str = $this->db->getOne($sql);
            if (empty($str))
            {
                $change_arr = array(
                                'credit' => 'db_credit',
                                'money'=>'db_money',
                                'rvrc' => 'db_rvrc',
                              );
                foreach ($change_arr as $key => $name)
                {
                    $sql = "SELECT db_value FROM " . $this->table('config') . " WHERE db_name='".$name."unit'";
                    $ava_credits[$key]['unit'] = $this->db->getOne($sql);

                    $sql = "SELECT db_value FROM " . $this->table('config') . " WHERE db_name='".$name."name'";
                    $ava_credits[$key]['title'] = $this->db->getOne($sql);
                }
            }
            else
            {
                list($ava_credits['money']['title'], $ava_credits['money']['unit'],$ava_credits['rvrc']['title'],$ava_credits['rvrc']['unit'],$ava_credits['credit']['title'], $ava_credits['credit']['unit'])=explode("\t",$str);

            }

        }

        return $ava_credits;
    }

    /**
     *  获取用户积分
     *
     * @access  public
     * @param
     *
     * @return array
     */
    function get_points($username)
    {
        $credits = $this->get_points_name();
        $fileds = array_keys($credits);
        if ($fileds)
        {
            $sql = "SELECT ud." . $this->field_id . ', ' . implode(', ',$fileds).
                   " FROM " . $this->table('memberdata'). "AS ud, ".
                   $this->table($this->user_table). " AS u ".
                   " WHERE u." . $this->field_id . "= ud." .$this->field_id . " AND u." . $this->field_name . "='$username'";
            $row = $this->db->getRow($sql);
            if (isset($row['rvrc']))
            {
                $row['rvrc'] = floor($row['rvrc'] /10);
            }
            return $row;
        }
        else
        {
            return false;
        }
    }

    /**
     * 积分设置
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function set_points ($username, $credits)
    {
        if (isset($credits['rvrc']))
        {
            $credits['rvrc'] = $credits['rvrc'] * 10;
        }

        $sql = "SELECT " . $this->field_id .
               " FROM " . $this->table($this->user_table).
               " WHERE " . $this->field_name . "='$username'";
        $uid = $this->db->getOne($sql);

        $user_set = array_keys($credits);
        $points_set = array_keys($this->get_points_name());

        $set = array_intersect($user_set, $points_set);

        if ($set)
        {
            $tmp = array();
            foreach ($set as $credit)
            {
               $tmp[] = $credit . '=' . $credit . '+' . $credits[$credit];
            }
            $sql = "UPDATE " . $this->table('memberdata').
                   " SET " . implode(', ', $tmp).
                   " WHERE " . $this->field_id . " = '$uid'";
            $this->db->query($sql);
        }

        return true;
    }


    /**
     * 添加新用户的函数
     *
     * @access      public
     * @param       string      username    用户名
     * @param       string      password    登录密码
     * @param       string      email       邮件地址
     * @param       string      bday        生日
     * @param       string      gender      性别
     * @return      int         返回最新的ID
     */
    function add_user($username, $password, $email, $gender = -1, $bday = 0, $reg_date=0, $md5password='')
    {
        $result = parent::add_user($username, $password, $email, $gender, $bday, $reg_date, $md5password);

        if (!$result)
        {
            return false;
        }

        /* 更新memberdata表 */
        $sql = 'INSERT INTO '. $this->table('memberdata') .' ('. $this->field_id .") " .
               " SELECT " . $this->field_id .
               " FROM " . $this->table($this->user_table) .
               " WHERE " . $this->field_name . "='$username'";
        $this->db->query($sql);

        return true;
    }
}

?>