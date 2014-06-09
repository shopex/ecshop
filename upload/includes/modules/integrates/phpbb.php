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
 * $Id: phpbb.php 17217 2011-01-19 06:29:08Z liubo $
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
    $modules[$i]['code']    = 'phpbb';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'phpBB';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '2.0.x';

    /* 插件的作者 */
    $modules[$i]['author']  = 'ECSHOP R&D TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 插件的初始的默认值 */
    $modules[$i]['default']['db_host'] = 'localhost';
    $modules[$i]['default']['db_user'] = 'root';
    $modules[$i]['default']['prefix'] = 'phpbb_';
    //$modules[$i]['default']['cookie_prefix'] = 'xn_';

    return;
}

require_once(ROOT_PATH . 'includes/modules/integrates/integrate.php');
class phpbb extends integrate
{
    var $cookie_prefix = '';

    function __construct($cfg)
    {
        $this->phpbb($cfg);
    }

    /**
     *
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function phpbb($cfg)
    {
        parent::integrate($cfg);
        if ($this->error)
        {
            /* 数据库连接出错 */
            return false;
        }
        //$this->cookie_prefix = $cfg['cookie_prefix'];
        $this->field_id = 'user_id';
        $this->field_name = 'username';
        $this->field_email = 'user_email';
        $this->field_gender = 'NULL';
        $this->field_bday = 'NULL';
        $this->field_pass = 'user_password';
        $this->field_reg_date = 'user_regdate';
        $this->user_table = 'users';

        /* 检查数据表是否存在 */
        $sql = "SHOW TABLES LIKE '" . $this->prefix . "%'";

        $exist_tables = $this->db->getCol($sql);

        if (empty($exist_tables) || (!in_array($this->prefix.$this->user_table, $exist_tables)) || (!in_array($this->prefix.'config', $exist_tables)))
        {
            $this->error = 2;
            /* 缺少数据表 */
            return false;
        }

        $this->cookie_prefix = $this->db->getOne("SELECT config_value FROM " .$this->table('config'). " WHERE config_name='cookie_name'");
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
            setcookie($this->cookie_prefix.'_data', '', $time, $this->cookie_path, $this->cookie_domain);
            setcookie($this->cookie_prefix.'_sid', '', $time, $this->cookie_path, $this->cookie_domain);
        }
        else
        {
            if ($this->charset != 'UTF8')
            {
                $username = ecs_iconv('UTF8', $this->charset, $username);
            }

            $sql = "SELECT " .$this->field_id. " AS user_id, " .$this->field_name. " AS user_name, " .$this->field_email." AS email ".
                   " FROM " .$this->table($this->user_table).
                   " WHERE " .$this->field_name. " = '$username'";

            $row = $this->db->getRow($sql);

            $auto_login_key = md5($this->dss_rand() . $this->dss_rand());

            /* 向整合对象的数据表里写入cookie值 */
            $this->db->query("INSERT INTO " .$this->table('sessions_keys')." (key_id, user_id, last_login) ".
                             "VALUES ('" .$auto_login_key. "', '$row[user_id]', '".time()."')");

            $client_ip = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
            $sql = "INSERT INTO ".$this->table('sessions')." (session_id, session_user_id, session_start, session_time, session_ip, session_logged_in, session_admin) VALUES('$auto_login_key', '".$row[$this->field_id]."','".time()."','".time()."','".$this->encode_ip($client_ip)."',1, 0)";
            $this->db->query($sql);

            $sessiondata = array('autologinid'=>$auto_login_key, 'userid'=>$row['user_id']);

            setcookie($this->cookie_prefix . '_data', serialize($sessiondata), time() + 31536000, $this->cookie_path, $this->cookie_domain);
            setcookie($this->cookie_prefix . '_sid', $auto_login_key, time() + 31536000, $this->cookie_path, $this->cookie_domain);
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
        if (empty($_COOKIE[$this->cookie_prefix . '_data']) || empty($_COOKIE[$this->cookie_prefix . '_sid']))
        {
            return '';
        }

        /* 序列化cookie,取得用户信息 */
        $cookie_data = addslashes_deep(@unserialize(stripslashes_deep($_COOKIE[$this->cookie_prefix . '_data'])));
        $cookie_session_id = addslashes_deep(trim($_COOKIE[$this->cookie_prefix . '_sid']));

        if (empty($cookie_data['userid']) || empty($cookie_data['autologinid']))
        {
            return '';
        }

        $sql = "SELECT " . $this->field_name .
               " FROM " . $this->table('sessions') . " AS s ".
               " LEFT JOIN " . $this->table($this->user_table) . " AS u ON s.session_user_id = u.user_id".
               " WHERE session_id = '$cookie_session_id' AND session_user_id = '$cookie_data[userid]'";

        $username = $this->db->getOne($sql);

        if (empty($username))
        {
            return '';
        }
        else
        {
            if ($this->charset != 'UTF8')
            {
                $username = ecs_iconv($this->charset, 'UTF8', $username);
            }

            return $username;
        }
    }

    /**
     * Our own generator of random values
     * This uses a constantly changing value as the base for generating the values
     * The board wide setting is updated once per page if this code is called
     * With thanks to Anthrax101 for the inspiration on this one
     * Added in phpBB 2.0.20
     */
    function dss_rand()
    {
        $dss_seeded = false;
        $rand_seed = $this->db->getOne("SELECT config_value FROM " .$this->table('config'). " WHERE config_name = 'rand_seed'");

        $val = $rand_seed . microtime();
        $val = md5($val);
        $rand_seed = md5($rand_seed . $val . 'a');

        if ($dss_seeded !== true)
        {
            $sql = "UPDATE ".$this->table('config')." SET config_value = '".$rand_seed."' WHERE config_name = 'rand_seed'";
            if (!$this->db->query($sql))
            {
                die('error');
            }

            $dss_seeded = true;
        }

        return substr($val, 16);
    }

    function encode_ip($dotquad_ip)
    {
        $ip_sep = explode('.', $dotquad_ip);

        return sprintf('%02x%02x%02x%02x', $ip_sep[0], $ip_sep[1], $ip_sep[2], $ip_sep[3]);
    }

}