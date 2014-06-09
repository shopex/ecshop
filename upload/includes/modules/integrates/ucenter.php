<?php

/**
 * UCenter 会员数据处理类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这是一个免费开源的软件；这意味着您可以在不用于商业目的的前提下对程序代码
 * 进行修改、使用和再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: ucenter.php 17217 2011-01-19 06:29:08Z liubo $
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
    $modules[$i]['code']    = 'ucenter';

    /* 被整合的第三方程序的名称 */
    $modules[$i]['name']    = 'UCenter';

    /* 被整合的第三方程序的版本 */
    $modules[$i]['version'] = '1.x';

    /* 插件的作者 */
    $modules[$i]['author']  = 'ECSHOP R&D TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 插件的初始的默认值 */
    $modules[$i]['default']['db_host'] = 'localhost';
    $modules[$i]['default']['db_user'] = 'root';
    $modules[$i]['default']['prefix'] = 'uc_';
    $modules[$i]['default']['cookie_prefix'] = 'xnW_';

    return;
}

require_once(ROOT_PATH . 'includes/modules/integrates/integrate.php');
class ucenter extends integrate
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function __construct($cfg)
    {
        /* 使用默认数据库连接 */
        $this->ucenter($cfg);
    }

    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function ucenter($cfg)
    {
        parent::integrate(array());
        $this->user_table = 'users';
        $this->field_id = 'user_id';
        $this->field_name = 'user_name';
        $this->field_pass = 'password';
        $this->field_email = 'email';
        $this->field_gender = 'sex';
        $this->field_bday = 'birthday';
        $this->field_reg_date = 'reg_time';
        $this->need_sync = false;
        $this->is_ecshop = 1;

        /* 初始化UC需要常量 */
        if (!defined('UC_CONNECT') && isset($cfg['uc_id']) && isset($cfg['db_host']) && isset($cfg['db_user']) && isset($cfg['db_name']))
        {
            if(strpos($cfg['db_pre'], '`' . $cfg['db_name'] . '`') === 0)
            {
                $db_pre = $cfg['db_pre'];
            }
            else
            {
                $db_pre = '`' . $cfg['db_name'] . '`.' . $cfg['db_pre'];
            }

            define('UC_CONNECT', isset($cfg['uc_connect'])?$cfg['uc_connect']:'');
            define('UC_DBHOST', isset($cfg['db_host'])?$cfg['db_host']:'');
            define('UC_DBUSER', isset($cfg['db_user'])?$cfg['db_user']:'');
            define('UC_DBPW', isset($cfg['db_pass'])?$cfg['db_pass']:'');
            define('UC_DBNAME', isset($cfg['db_name'])?$cfg['db_name']:'');
            define('UC_DBCHARSET', isset($cfg['db_charset'])?$cfg['db_charset']:'');
            define('UC_DBTABLEPRE', $db_pre);
            define('UC_DBCONNECT', '0');
            define('UC_KEY', isset($cfg['uc_key'])?$cfg['uc_key']:'');
            define('UC_API', isset($cfg['uc_url'])?$cfg['uc_url']:'');
            define('UC_CHARSET', isset($cfg['uc_charset'])?$cfg['uc_charset']:'');
            define('UC_IP', isset($cfg['uc_ip'])?$cfg['uc_ip']:'');
            define('UC_APPID', isset($cfg['uc_id'])?$cfg['uc_id']:'');
            define('UC_PPP', '20');
        }
    }

    /**
     *  用户登录函数
     *
     * @access  public
     * @param   string  $username
     * @param   string  $password
     *
     * @return void
     */
    function login($username, $password)
    {
        list($uid, $uname, $pwd, $email, $repeat) = uc_call("uc_user_login", array($username, $password));
        $uname = addslashes($uname);

        if($uid > 0)
        {
            //检查用户是否存在,不存在直接放入用户表
            $result = $this->db->getRow("SELECT user_id,ec_salt FROM " . $GLOBALS['ecs']->table("users") . " WHERE user_name='$username'");
            $name_exist =$result['user_id'];
            if(empty($result['ec_salt']))
            {
                $user_exist = $this->db->getOne("SELECT user_id FROM " . $GLOBALS['ecs']->table("users") . " WHERE user_name='$username' AND password = '" . MD5($password) ."'");
                if(!empty($user_exist))
                {
                    $ec_salt=rand(1,9999);
                    $this->db->query('UPDATE ' . $GLOBALS['ecs']->table("users") . "SET `password`='".MD5(MD5($password). $ec_salt)."',`ec_salt`='". $ec_salt."' WHERE user_id = '" . $uid . "'");

                }
            }
            else
            {
                $user_exist = $this->db->getOne("SELECT user_id FROM " . $GLOBALS['ecs']->table("users") . " WHERE user_name='$username' AND password = '" . MD5(MD5($password). $result['ec_salt'])."'");
            }



            if (empty($user_exist))
            {
                if(empty($name_exist))
                {
                    $reg_date = time();
                    $ip = real_ip();
                    $password = $this->compile_password(array('password'=>$password));
                    $this->db->query('INSERT INTO ' . $GLOBALS['ecs']->table("users") . "(`user_id`, `email`, `user_name`, `password`, `reg_time`, `last_login`, `last_ip`) VALUES ('$uid', '$email', '$uname', '$password', '$reg_date', '$reg_date', '$ip')");
                }
                else 
                {
                    if(empty($result['ec_salt']))
                    {
                         $result['ec_salt']=0;
                    }
                    $this->db->query('UPDATE ' . $GLOBALS['ecs']->table("users") . "SET `password`='".MD5(MD5($password). $result['ec_salt'])."',`ec_salt`='". $result['ec_salt']."' WHERE user_id = '" . $uid . "'");
                }
            }
            $this->set_session($uname);
            $this->set_cookie($uname);
            $this->ucdata = uc_call("uc_user_synlogin", array($uid));
            return true;
        }
        elseif($uid == -1)
        {
            $this->error = ERR_INVALID_USERNAME;
            return false;
        }
        elseif ($uid == -2)
        {
            $this->error = ERR_INVALID_PASSWORD;
            return false;
        }
        else
        {
            return false;
        }
    }

    /**
     * 用户退出
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function logout()
    {
        $this->set_cookie();  //清除cookie
        $this->set_session(); //清除session
        $this->ucdata = uc_call("uc_user_synlogout");   //同步退出
        return true;
    }

    /*添加用户*/
    function add_user($username, $password, $email)
    {
        /* 检测用户名 */
        if ($this->check_user($username))
        {
            $this->error = ERR_USERNAME_EXISTS;
            return false;
        }

        $uid = uc_call("uc_user_register", array($username, $password, $email));
        if ($uid <= 0)
        {
            if($uid == -1)
            {
                $this->error = ERR_INVALID_USERNAME;
                return false;
            }
            elseif($uid == -2)
            {
                $this->error = ERR_USERNAME_NOT_ALLOW;
                return false;
            }
            elseif($uid == -3)
            {
                $this->error = ERR_USERNAME_EXISTS;
                return false;
            }
            elseif($uid == -4)
            {
                $this->error = ERR_INVALID_EMAIL;
                return false;
            }
            elseif($uid == -5)
            {
                $this->error = ERR_EMAIL_NOT_ALLOW;
                return false;
            }
            elseif($uid == -6)
            {
                $this->error = ERR_EMAIL_EXISTS;
                return false;
            }
            else
            {
                return false;
            }
        }
        else
        {
            //注册成功，插入用户表
            $reg_date = time();
            $ip = real_ip();
            $password = $this->compile_password(array('password'=>$password));
            $this->db->query('INSERT INTO ' . $GLOBALS['ecs']->table("users") . "(`user_id`, `email`, `user_name`, `password`, `reg_time`, `last_login`, `last_ip`) VALUES ('$uid', '$email', '$username', '$password', '$reg_date', '$reg_date', '$ip')");
            return true;
        }
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
        $userdata = uc_call("uc_user_checkname", array($username));
        if ($userdata == 1)
        {
            return false;
        }
        else
        {
            return  true;
        }
    }

    /**
     * 检测Email是否合法
     *
     * @access  public
     * @param   string  $email   邮箱
     *
     * @return  blob
     */
    function check_email($email)
    {
        if (!empty($email))
        {
            $email_exist = uc_call('uc_user_checkemail', array($email));
            if ($email_exist == 1)
            {
                return false;
            }
            else
            {
                $this->error = ERR_EMAIL_EXISTS;
                return true;
            }
        }
        return true;
    }

    /* 编辑用户信息 */
    function edit_user($cfg, $forget_pwd = '0')
    {
        $real_username = $cfg['username'];
        $cfg['username'] = addslashes($cfg['username']);
        $set_str = '';
        $valarr =array('email'=>'email', 'gender'=>'sex', 'bday'=>'birthday');
        foreach ($cfg as $key => $val)
        {
            if ($key == 'username' || $key == 'password' || $key == 'old_password')
            {
                continue;
            }
            $set_str .= $valarr[$key] . '=' . "'$val',";
        }
        $set_str = substr($set_str, 0, -1);
        if (!empty($set_str))
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET $set_str  WHERE user_name = '$cfg[username]'";
            $GLOBALS['db']->query($sql);
            $flag  = true;
        }

        if (!empty($cfg['email']))
        {
            $ucresult = uc_call("uc_user_edit", array($cfg['username'], '', '', $cfg['email'], 1));
            if ($ucresult > 0 )
            {
                $flag = true;
            }
            elseif($ucresult == -4)
            {
                //echo 'Email 格式有误';
                $this->error = ERR_INVALID_EMAIL;

                return false;
            }
            elseif($ucresult == -5)
            {
                //echo 'Email 不允许注册';
                $this->error = ERR_INVALID_EMAIL;

                return false;
            }
            elseif($ucresult == -6)
            {
                //echo '该 Email 已经被注册';
                $this->error = ERR_EMAIL_EXISTS;

                return false;
            }
            elseif ($ucresult < 0 )
            {
                return false;
            }
        }
        if (!empty($cfg['old_password']) && !empty($cfg['password']) && $forget_pwd == 0)
        {
            $ucresult = uc_call("uc_user_edit", array($real_username, $cfg['old_password'], $cfg['password'], ''));
            if ($ucresult > 0 )
            {
                return true;
            }
            else
            {
                $this->error = ERR_INVALID_PASSWORD;
                return false;
            }
        }
        elseif (!empty($cfg['password']) && $forget_pwd == 1)
        {
            $ucresult = uc_call("uc_user_edit", array($real_username, '', $cfg['password'], '', '1'));
            if ($ucresult > 0 )
            {
                $flag = true;
            }
        }

        return true;
    }

    /**
     *  获取指定用户的信息
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_profile_by_name($username)
    {
        //$username = addslashes($username);

        $sql = "SELECT user_id, user_name, email, sex, reg_time FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name='$username'";
        $row = $this->db->getRow($sql);
        return $row;
    }

    /**
     *  检查cookie是正确，返回用户名
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function check_cookie()
    {
        return '';
    }

    /**
     *  根据登录状态设置cookie
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_cookie()
    {
        $id = $this->check_cookie();
        if ($id)
        {
            if ($this->need_sync)
            {
                $this->sync($id);
            }
            $this->set_session($id);

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     *  设置cookie
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function set_cookie($username='')
    {
        if (empty($username))
        {
            /* 摧毁cookie */
            $time = time() - 3600;
            setcookie("ECS[user_id]",  '', $time, $this->cookie_path);            
            setcookie("ECS[password]", '', $time, $this->cookie_path);
        }
        else
        {
            /* 设置cookie */
            $time = time() + 3600 * 24 * 30;

            setcookie("ECS[username]", stripslashes($username), $time, $this->cookie_path, $this->cookie_domain);
            $sql = "SELECT user_id, password FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name='$username' LIMIT 1";
            $row = $GLOBALS['db']->getRow($sql);
            if ($row)
            {
                setcookie("ECS[user_id]", $row['user_id'], $time, $this->cookie_path, $this->cookie_domain);
                setcookie("ECS[password]", $row['password'], $time, $this->cookie_path, $this->cookie_domain);
            }
        }
    }

    /**
     *  设置指定用户SESSION
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function set_session ($username='')
    {
        if (empty($username))
        {
            $GLOBALS['sess']->destroy_session();
        }
        else
        {
            $sql = "SELECT user_id, password, email FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_name='$username' LIMIT 1";
            $row = $GLOBALS['db']->getRow($sql);

            if ($row)
            {
                $_SESSION['user_id']   = $row['user_id'];
                $_SESSION['user_name'] = $username;
                $_SESSION['email']     = $row['email'];
            }
        }
    }

    /**
     *  获取指定用户的信息
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_profile_by_id($id)
    {
        $sql = "SELECT user_id, user_name, email, sex, birthday, reg_time FROM " . $GLOBALS['ecs']->table('users') . " WHERE user_id='$id'";
        $row = $this->db->getRow($sql);

        return $row;
    }

    function get_user_info($username)
    {
        return $this->get_profile_by_name($username);
    }

    /**
     * 删除用户
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function remove_user($id)
    {
        if (is_array($id))
        {
            $post_id = array();
            foreach ($id as $val)
            {
                $post_id[] = $val;
            }
        }
        else
        {
            $post_id = $id;
        }

        /* 如果需要同步或是ecshop插件执行这部分代码 */
        $sql = "SELECT user_id FROM "  . $GLOBALS['ecs']->table('users') . " WHERE ";
        $sql .= (is_array($post_id)) ? db_create_in($post_id, 'user_name') : "user_name='". $post_id . "' LIMIT 1";
        $col = $GLOBALS['db']->getCol($sql);

        if ($col)
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET parent_id = 0 WHERE " . db_create_in($col, 'parent_id'); //将删除用户的下级的parent_id 改为0
            $GLOBALS['db']->query($sql);
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('users') . " WHERE " . db_create_in($col, 'user_id'); //删除用户
            $GLOBALS['db']->query($sql);
            /* 删除用户订单 */
            $sql = "SELECT order_id FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE " . db_create_in($col, 'user_id');
            $GLOBALS['db']->query($sql);
            $col_order_id = $GLOBALS['db']->getCol($sql);
            if ($col_order_id)
            {
                $sql = "DELETE FROM " . $GLOBALS['ecs']->table('order_info') . " WHERE " . db_create_in($col_order_id, 'order_id');
                $GLOBALS['db']->query($sql);
                $sql = "DELETE FROM " . $GLOBALS['ecs']->table('order_goods') . " WHERE " . db_create_in($col_order_id, 'order_id');
                $GLOBALS['db']->query($sql);
            }

            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('booking_goods') . " WHERE " . db_create_in($col, 'user_id'); //删除用户
            $GLOBALS['db']->query($sql);
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('collect_goods') . " WHERE " . db_create_in($col, 'user_id'); //删除会员收藏商品
            $GLOBALS['db']->query($sql);
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('feedback') . " WHERE " . db_create_in($col, 'user_id'); //删除用户留言
            $GLOBALS['db']->query($sql);
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('user_address') . " WHERE " . db_create_in($col, 'user_id'); //删除用户地址
            $GLOBALS['db']->query($sql);
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('user_bonus') . " WHERE " . db_create_in($col, 'user_id'); //删除用户红包
            $GLOBALS['db']->query($sql);
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('user_account') . " WHERE " . db_create_in($col, 'user_id'); //删除用户帐号金额
            $GLOBALS['db']->query($sql);
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('tag') . " WHERE " . db_create_in($col, 'user_id'); //删除用户标记
            $GLOBALS['db']->query($sql);
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('account_log') . " WHERE " . db_create_in($col, 'user_id'); //删除用户日志
            $GLOBALS['db']->query($sql);
        }

        if (isset($this->ecshop) && $this->ecshop)
        {
            /* 如果是ecshop插件直接退出 */
            return;
        }

        $sql = "DELETE FROM " . $GLOBALS['ecs']->table('users') . " WHERE ";
        if (is_array($post_id))
        {
            $sql .= db_create_in($post_id, 'user_name');
        }
        else
        {
            $sql .= "user_name='" . $post_id . "' LIMIT 1";
        }

        $this->db->query($sql);
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
        return 'ucenter';
    }
}

?>