<?php

/**
 * ECSHOP 整合插件类的基类
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这是一个免费开源的软件；这意味着您可以在不用于商业目的的前提下对程序代码
 * 进行修改、使用和再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: integrate.php 17217 2011-01-19 06:29:08Z liubo $
*/

class integrate
{

    /*------------------------------------------------------ */
    //-- PUBLIC ATTRIBUTEs
    /*------------------------------------------------------ */

    /* 整合对象使用的数据库主机 */
    var $db_host        = '';

    /* 整合对象使用的数据库名 */
    var $db_name        = '';

    /* 整合对象使用的数据库用户名 */
    var $db_user        = '';

    /* 整合对象使用的数据库密码 */
    var $db_pass        = '';

    /* 整合对象数据表前缀 */
    var $prefix         = '';

    /* 数据库所使用编码 */
    var $charset        = '';

    /* 整合对象使用的cookie的domain */
    var $cookie_domain  = '';

    /* 整合对象使用的cookie的path */
    var $cookie_path    = '/';

    /* 整合对象会员表名 */
    var $user_table = '';

    /* 会员ID的字段名 */
    var $field_id       = '';

    /* 会员名称的字段名 */
    var $field_name     = '';

    /* 会员密码的字段名 */
    var $field_pass     = '';

    /* 会员邮箱的字段名 */
    var $field_email    = '';

    /* 会员性别 */
    var $field_gender = '';

    /* 会员生日 */
    var $field_bday = '';

    /* 注册日期的字段名 */
    var $field_reg_date = '';

    /* 是否需要同步数据到商城 */
    var $need_sync = true;

    var $error          = 0;

    /*------------------------------------------------------ */
    //-- PRIVATE ATTRIBUTEs
    /*------------------------------------------------------ */

    var $db;

    /*------------------------------------------------------ */
    //-- PUBLIC METHODs
    /*------------------------------------------------------ */

    /**
     * 会员数据整合插件类的构造函数
     *
     * @access      public
     * @param       string  $db_host    数据库主机
     * @param       string  $db_name    数据库名
     * @param       string  $db_user    数据库用户名
     * @param       string  $db_pass    数据库密码
     * @return      void
     */
    function integrate($cfg)
    {
        $this->charset = isset($cfg['db_charset']) ? $cfg['db_charset'] : 'UTF8';
        $this->prefix = isset($cfg['prefix']) ? $cfg['prefix'] : '';
        $this->db_name = isset($cfg['db_name']) ? $cfg['db_name'] : '';
        $this->cookie_domain = isset($cfg['cookie_domain']) ? $cfg['cookie_domain'] : '';
        $this->cookie_path = isset($cfg['cookie_path']) ? $cfg['cookie_path'] : '/';
        $this->need_sync = true;

        $quiet = empty($cfg['quiet']) ? 0 : 1;

        /* 初始化数据库 */
        if (empty($cfg['db_host']))
        {
            $this->db_name = $GLOBALS['ecs']->db_name;
            $this->prefix = $GLOBALS['ecs']->prefix;
            $this->db = &$GLOBALS['db'];
        }
        else
        {
            if (empty($cfg['is_latin1']))
            {
                $this->db = new cls_mysql($cfg['db_host'], $cfg['db_user'], $cfg['db_pass'], $cfg['db_name'], $this->charset, NULL,  $quiet);
            }
            else
            {
                $this->db = new cls_mysql($cfg['db_host'], $cfg['db_user'], $cfg['db_pass'], $cfg['db_name'], 'latin1', NULL, $quiet) ;
            }
        }

        if (!is_resource($this->db->link_id))
        {
            $this->error = 1; //数据库地址帐号
        }
        else
        {
            $this->error = $this->db->errno();
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
    function login($username, $password, $remember = null)
    {
        if ($this->check_user($username, $password) > 0)
        {
            if ($this->need_sync)
            {
                $this->sync($username,$password);
            }
            $this->set_session($username);
            $this->set_cookie($username, $remember);

            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     *
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function logout ()
    {
        $this->set_cookie(); //清除cookie
        $this->set_session(); //清除session
    }

    /**
     *  添加一个新用户
     *
     * @access  public
     * @param
     *
     * @return int
     */
    function add_user($username, $password, $email, $gender = -1, $bday = 0, $reg_date=0, $md5password='')
    {
        /* 将用户添加到整合方 */
        if ($this->check_user($username) > 0)
        {
            $this->error = ERR_USERNAME_EXISTS;

            return false;
        }
        /* 检查email是否重复 */
        $sql = "SELECT " . $this->field_id .
               " FROM " . $this->table($this->user_table).
               " WHERE " . $this->field_email . " = '$email'";
        if ($this->db->getOne($sql, true) > 0)
        {
            $this->error = ERR_EMAIL_EXISTS;

            return false;
        }

        $post_username = $username;

        if ($md5password)
        {
            $post_password = $this->compile_password(array('md5password'=>$md5password));
        }
        else
        {
            $post_password = $this->compile_password(array('password'=>$password));
        }

        $fields = array($this->field_name, $this->field_email, $this->field_pass);
        $values = array($post_username, $email, $post_password);

        if ($gender > -1)
        {
            $fields[] = $this->field_gender;
            $values[] = $gender;
        }
        if ($bday)
        {
            $fields[] = $this->field_bday;
            $values[] = $bday;
        }
        if ($reg_date)
        {
            $fields[] = $this->field_reg_date;
            $values[] = $reg_date;
        }

        $sql = "INSERT INTO " . $this->table($this->user_table).
               " (" . implode(',', $fields) . ")".
               " VALUES ('" . implode("', '", $values) . "')";

        $this->db->query($sql);

        if ($this->need_sync)
        {
            $this->sync($username, $password);
        }

        return true;
    }

    /**
     *  编辑用户信息($password, $email, $gender, $bday)
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function edit_user($cfg)
    {
        if (empty($cfg['username']))
        {
            return false;
        }
        else
        {
            $cfg['post_username'] = $cfg['username'];

        }

        $values = array();
        if (!empty($cfg['password']) && empty($cfg['md5password']))
        {
            $cfg['md5password'] = md5($cfg['password']);
        }
        if ((!empty($cfg['md5password'])) && $this->field_pass != 'NULL')
        {
            $values[] = $this->field_pass . "='" . $this->compile_password(array('md5password'=>$cfg['md5password'])) . "'";
        }

        if ((!empty($cfg['email'])) && $this->field_email != 'NULL')
        {
            /* 检查email是否重复 */
            $sql = "SELECT " . $this->field_id .
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_email . " = '$cfg[email]' ".
                   " AND " . $this->field_name . " != '$cfg[post_username]'";
            if ($this->db->getOne($sql, true) > 0)
            {
                $this->error = ERR_EMAIL_EXISTS;

                return false;
            }
            // 检查是否为新E-mail
            $sql = "SELECT count(*)" .
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_email . " = '$cfg[email]' ";
            if($this->db->getOne($sql, true) == 0)
            {
                // 新的E-mail
                $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET is_validated = 0 WHERE user_name = '$cfg[post_username]'";
                $this->db->query($sql);
            }
            $values[] = $this->field_email . "='". $cfg['email'] . "'";
        }

        if (isset($cfg['gender']) && $this->field_gender != 'NULL')
        {
            $values[] = $this->field_gender . "='" . $cfg['gender'] . "'";
        }

        if ((!empty($cfg['bday'])) && $this->field_bday != 'NULL')
        {
            $values[] = $this->field_bday . "='" . $cfg['bday'] . "'";
        }

        if ($values)
        {
            $sql = "UPDATE " . $this->table($this->user_table).
                   " SET " . implode(', ', $values).
                   " WHERE " . $this->field_name . "='" . $cfg['post_username'] . "' LIMIT 1";

            $this->db->query($sql);

            if ($this->need_sync)
            {
                if (empty($cfg['md5password']))
                {
                    $this->sync($cfg['username']);
                }
                else
                {
                    $this->sync($cfg['username'], '', $cfg['md5password']);
                }
            }
        }

        return true;
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
        $post_id = $id;

        if ($this->need_sync || (isset($this->is_ecshop) && $this->is_ecshop))
        {
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
        }

        if (isset($this->ecshop) && $this->ecshop)
        {
            /* 如果是ecshop插件直接退出 */
            return;
        }

        $sql = "DELETE FROM " . $this->table($this->user_table) . " WHERE ";
        if (is_array($post_id))
        {
            $sql .= db_create_in($post_id, $this->field_name);
        }
        else
        {
            $sql .= $this->field_name . "='" . $post_id . "' LIMIT 1";
        }

        $this->db->query($sql);
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
        $post_username = $username;

        $sql = "SELECT " . $this->field_id . " AS user_id," . $this->field_name . " AS user_name," .
                    $this->field_email . " AS email," . $this->field_gender ." AS sex,".
                    $this->field_bday . " AS birthday," . $this->field_reg_date . " AS reg_time, ".
                    $this->field_pass . " AS password ".
               " FROM " . $this->table($this->user_table) .
               " WHERE " .$this->field_name . "='$post_username'";
        $row = $this->db->getRow($sql);

        return $row;
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
        $sql = "SELECT " . $this->field_id . " AS user_id," . $this->field_name . " AS user_name," .
                    $this->field_email . " AS email," . $this->field_gender ." AS sex,".
                    $this->field_bday . " AS birthday," . $this->field_reg_date . " AS reg_time, ".
                    $this->field_pass . " AS password ".
               " FROM " . $this->table($this->user_table) .
               " WHERE " .$this->field_id . "='$id'";
        $row = $this->db->getRow($sql);

        return $row;
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
     *  检查指定用户是否存在及密码是否正确
     *
     * @access  public
     * @param   string  $username   用户名
     *
     * @return  int
     */
    function check_user($username, $password = null)
    {

        $post_username = $username;

        /* 如果没有定义密码则只检查用户名 */
        if ($password === null)
        {
            $sql = "SELECT " . $this->field_id .
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_name . "='" . $post_username . "'";

            return $this->db->getOne($sql);
        }
        else
        {
            $sql = "SELECT " . $this->field_id .
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_name . "='" . $post_username . "' AND " . $this->field_pass . " ='" . $this->compile_password(array('password'=>$password)) . "'";

            return  $this->db->getOne($sql);
        }
    }

    /**
     *  检查指定邮箱是否存在
     *
     * @access  public
     * @param   string  $email   用户邮箱
     *
     * @return  boolean
     */
    function check_email($email)
    {
        if (!empty($email))
        {
          /* 检查email是否重复 */
            $sql = "SELECT " . $this->field_id .
                       " FROM " . $this->table($this->user_table).
                       " WHERE " . $this->field_email . " = '$email' ";
            if ($this->db->getOne($sql, true) > 0)
            {
                $this->error = ERR_EMAIL_EXISTS;
                return true;
            }
            return false;
        }
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
     *  设置cookie
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function set_cookie($username='', $remember= null )
    {
        if (empty($username))
        {
            /* 摧毁cookie */
            $time = time() - 3600;
            setcookie("ECS[user_id]",  '', $time, $this->cookie_path);            
            setcookie("ECS[password]", '', $time, $this->cookie_path);

        }
        elseif ($remember)
        {
            /* 设置cookie */
            $time = time() + 3600 * 24 * 15;

            setcookie("ECS[username]", $username, $time, $this->cookie_path, $this->cookie_domain);
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
     * 在给定的表名前加上数据库名以及前缀
     *
     * @access  private
     * @param   string      $str    表名
     *
     * @return void
     */
    function table($str)
    {
        return '`' .$this->db_name. '`.`'.$this->prefix.$str.'`';
    }

    /**
     *  编译密码函数
     *
     * @access  public
     * @param   array   $cfg 包含参数为 $password, $md5password, $salt, $type
     *
     * @return void
     */
    function compile_password ($cfg)
    {
       if (isset($cfg['password']))
       {
            $cfg['md5password'] = md5($cfg['password']);
       }
       if (empty($cfg['type']))
       {
            $cfg['type'] = PWD_MD5;
       }

       switch ($cfg['type'])
       {
           case PWD_MD5 :
              	if(!empty($cfg['ec_salt']))
		       {
			       return md5($cfg['md5password'].$cfg['ec_salt']);
		       }
			   else
		       {
                    return $cfg['md5password'];
			   }

           case PWD_PRE_SALT :
               if (empty($cfg['salt']))
               {
                    $cfg['salt'] = '';
               }

               return md5($cfg['salt'] . $cfg['md5password']);

           case PWD_SUF_SALT :
               if (empty($cfg['salt']))
               {
                    $cfg['salt'] = '';
               }

               return md5($cfg['md5password'] . $cfg['salt']);

           default:
               return '';
       }
    }

    /**
     *  会员同步
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function sync ($username, $password='', $md5password='')
    {
        if ((!empty($password)) && empty($md5password))
        {
            $md5password = md5($password);
        }

        $main_profile = $this->get_profile_by_name($username);

        if (empty($main_profile))
        {
            return false;
        }

        $sql = "SELECT user_name, email, password, sex, birthday".
               " FROM " . $GLOBALS['ecs']->table('users').
               " WHERE user_name = '$username'";

        $profile = $GLOBALS['db']->getRow($sql);
        if (empty($profile))
        {
            /* 向商城表插入一条新记录 */
            if (empty($md5password))
            {
               $sql = "INSERT INTO " . $GLOBALS['ecs']->table('users').
                            "(user_name, email, sex, birthday, reg_time)".
                      " VALUES('$username', '" .$main_profile['email']."','".
                            $main_profile['sex'] . "','" . $main_profile['birthday'] . "','" . $main_profile['reg_time'] . "')";
            }
            else
            {
               $sql = "INSERT INTO " . $GLOBALS['ecs']->table('users').
                            "(user_name, email, sex, birthday, reg_time, password)".
                      " VALUES('$username', '" .$main_profile['email']."','".
                            $main_profile['sex'] . "','" . $main_profile['birthday'] . "','" .
                            $main_profile['reg_time'] . "', '$md5password')";

            }

            $GLOBALS['db']->query($sql);

            return true;
        }
        else
        {
            $values = array();
            if ($main_profile['email'] != $profile['email'])
            {
                $values[] = "email='" . $main_profile['email'] . "'";
            }
            if ($main_profile['sex'] != $profile['sex'])
            {
                $values[] = "sex='" . $main_profile['sex'] . "'";
            }
            if ($main_profile['birthday'] != $profile['birthday'])
            {
                $values[] = "birthday='" . $main_profile['birthday'] . "'";
            }
            if ((!empty($md5password)) && ($md5password != $profile['password']))
            {
                $values[] = "password='" . $md5password . "'";
            }

            if (empty($values))
            {
                return  true;
            }
            else
            {
                $sql = "UPDATE " . $GLOBALS['ecs']->table('users').
                       " SET " . implode(", ", $values).
                       " WHERE user_name='$username'";

                $GLOBALS['db']->query($sql);

                return true;
            }
        }
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
        return array();
    }

    /**
     *  获取用户积分
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function get_points($username)
    {
        $credits = $this->get_points_name();
        $fileds = array_keys($credits);
        if ($fileds)
        {
            $sql = "SELECT " . $this->field_id . ', ' . implode(', ',$fileds).
                   " FROM " . $this->table($this->user_table).
                   " WHERE " . $this->field_name . "='$username'";
            $row = $this->db->getRow($sql);
            return $row;
        }
        else
        {
            return false;
        }
    }

    /**
     *设置用户积分
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function set_points ($username, $credits)
    {
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
            $sql = "UPDATE " . $this->table($this->user_table).
                   " SET " . implode(', ', $tmp).
                   " WHERE " . $this->field_name . " = '$username'";
            $this->db->query($sql);
        }

        return true;
    }

    function get_user_info($username)
    {
        return $this->get_profile_by_name($username);
    }


    /**
     * 检查有无重名用户，有则返回重名用户
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function test_conflict ($user_list)
    {
        if (empty($user_list))
        {
            return array();
        }


        $sql = "SELECT " . $this->field_name . " FROM " . $this->table($this->user_table) . " WHERE " . db_create_in($user_list, $this->field_name);
        $user_list = $this->db->getCol($sql);

        return $user_list;
    }
}
