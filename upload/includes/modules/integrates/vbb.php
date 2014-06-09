<?php

/**
 * ECSHOP 会员数据处理类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这是一个免费开源的软件；这意味着您可以在不用于商业目的的前提下对程序代码
 * 进行修改、使用和再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: vbb.php 17217 2011-01-19 06:29:08Z liubo $
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
    $modules[$i]['code']    = 'vbb';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'vBulletin';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '3.x';

    /* 插件的作者 */
    $modules[$i]['author']  = 'ECSHOP R&D TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 插件的初始的默认值 */
    $modules[$i]['default']['db_host'] = 'localhost';
    $modules[$i]['default']['db_user'] = 'root';
    $modules[$i]['default']['prefix'] = 'vbb_';
    $modules[$i]['default']['cookie_salt'] = 'NiGHTNiNG'; //cookie验证串 在inclues/fuctions.php define('COOKIE_SALT', 'NiGHTNiNG');

    return;
}

require_once(ROOT_PATH . 'includes/modules/integrates/integrate.php');
class vbb extends integrate
{
    var $cookie_salt = '';

    function __construct($cfg)
    {
        $this->vbb($cfg);
    }

    /**
     *
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function vbb($cfg)
    {
        parent::integrate($cfg);
        if ($this->error)
        {
            /* 数据库连接出错 */
            return false;
        }

        $this->cookie_salt = $cfg['cookie_salt'];
        $this->field_id = 'userid';
        $this->field_name = 'username';
        $this->field_email = 'email';
        $this->field_gender = 'NULL';
        $this->field_bday = 'birthday';
        $this->field_pass = 'password';
        $this->field_reg_date = 'joindate';
        $this->user_table = 'user';

        /* 检查数据表是否存在 */
        $sql = "SHOW TABLES LIKE '" . $this->prefix . "%'";

        $exist_tables = $this->db->getCol($sql);

        if (empty($exist_tables) || (!in_array($this->prefix.$this->user_table, $exist_tables)))
        {
            $this->error = 2;
            /* 缺少数据表 */
            return false;
        }
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
        if (empty($username))
        {
            $time = time() - 3600;
            setcookie('bbuserid', '', $time, $this->cookie_path, $this->cookie_domain);
            setcookie('bbpassword', '', $time, $this->cookie_path, $this->cookie_domain);
        }
        else
        {
            if ($this->charset != 'UTF8')
            {
                $username = ecs_iconv('UTF8', $this->charset, $username);
            }
            $sql = "SELECT " . $this->field_id . " AS user_id, " . $this->field_pass . " As password ".
                   " FROM " . $this->table($this->user_table) . " WHERE " . $this->field_name . "='$username'";

            $row = $this->db->getRow($sql);

            setcookie('bbuserid', $row['user_id'], time() + 3600 * 24 * 30, $this->cookie_path, $this->cookie_domain);
            setcookie('bbpassword', md5($row['password'] . $this->cookie_salt), time() + 3600 * 24 * 30, $this->cookie_path, $this->cookie_domain);
        }
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

        $user_title = $this->db->GetOne("SELECT title FROM " .$this->table('usertitle'). " ORDER BY minposts LIMIT 1");

        if ($this->charset != 'UTF8')
        {
            $username = ecs_iconv('UTF8', $this->charset, $username);
        }


        /* 编译密码 */
        $salt     = addslashes($this->fetch_user_salt());

        /* 更新数据 */
        $sql = "UPDATE " . $this->table($this->user_table) .
               " SET " . $this->field_pass . " = '" . $this->compile_password(array('type'=>PWD_SUF_SALT, 'password'=>$password, 'salt'=>$salt)) . "', ".
               " salt = '$salt', ".
               " ipaddress = '" . real_ip() . "', ".
               " usergroupid = 2, ".
               " usertitle = '$user_title' ".
               " WHERE " . $this->field_name . "='$username'";

        $this->db->query($sql);

        $sql = 'INSERT INTO '. $this->table('userfield') .' ('. $this->field_id .") " .
               " SELECT " . $this->field_id .
               " FROM " . $this->table($this->user_table) .
               " WHERE " . $this->field_name . "='$username'";
        $this->db->query($sql);

        $sql = 'INSERT INTO '. $this->table('usertextfield') .' ('. $this->field_id .") " .
               " SELECT " . $this->field_id .
               " FROM " . $this->table($this->user_table) .
               " WHERE " . $this->field_name . "='$username'";
        $this->db->query($sql);

        return true;
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
        if (empty($_COOKIE['bbuserid']) || empty($_COOKIE['bbpassword']))
        {
            return '';
        }

        $user_id = intval($_COOKIE['bbuserid']);
        $bbpassword = addslashes_deep($_COOKIE['bbpassword']);

        $row = $this->db->getRow("SELECT " . $this->field_name . " AS user_name, " . $this->field_pass . " As password ".
                                 " FROM " . $this->table($this->user_table) . " WHERE " . $this->field_id . "='$user_id'");
        if (empty($row))
        {
            return '';
        }

        if ($bbpassword != md5($row['password'].$this->cookie_salt))
        {
            return '';
        }

        if ($this->charset != 'UTF8')
        {
            $row['user_name'] = ecs_iconv($this->charset, 'UTF8', $row['user_name']);
        }

        return $row['user_name'];

    }


    /**
     *  检查指定用户是否存在及密码是否正确
     *
     * @access  public
     * @param   string  $username   用户名
     *
     * @return  int
     */
    function check_user($username, $password = null)
    {
        if ($this->charset != 'UTF8')
        {
            $post_username = ecs_iconv('UTF8', $this->charset, $username);
        }
        else
        {
            $post_username = $username;
        }

        if ($password === null)
        {
            $sql = "SELECT " . $this->field_id .
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_name . "='" . $post_username . "'";

            return $this->db->getOne($sql);
        }
        else
        {
            $sql = "SELECT " . $this->field_id . " AS user_id, " . $this->field_pass . " AS password, salt".
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_name . "='" . $post_username . "'";
            $row = $this->db->getRow($sql);

            if (empty($row))
            {
               return 0;
            }

            if ($row['password'] != $this->compile_password(array('type'=>PWD_SUF_SALT, 'password'=>$password, 'salt'=>$row['salt'])))
            {
                return 0;
            }

            return $row['user_id'];

        }
    }

    /**
     * 生成密码种子的函数
     *
     * @access      private
     * @param       int     length        长度
     * @return      string
     */
    function fetch_user_salt($length = 3)
    {
        $salt = '';
        for ($i = 0; $i < $length; $i++)
        {
            $salt .= chr(mt_rand(32, 126));
        }

        return $salt;
    }

}