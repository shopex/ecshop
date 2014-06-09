<?php

/**
 * ECSHOP 找回管理员密码
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: get_password.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* 操作项的初始化 */
if (empty($_SERVER['REQUEST_METHOD']))
{
    $_SERVER['REQUEST_METHOD'] = 'GET';
}
else
{
    $_SERVER['REQUEST_METHOD'] = trim($_SERVER['REQUEST_METHOD']);
}

/*------------------------------------------------------ */
//-- 填写管理员帐号和email页面
/*------------------------------------------------------ */
if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    //验证从邮件地址过来的链接
    if (!empty($_GET['act']) && $_GET['act'] == 'reset_pwd')
    {
        $code    = !empty($_GET['code']) ? trim($_GET['code'])  : '';
        $adminid = !empty($_GET['uid'])  ? intval($_GET['uid']) : 0;

        if ($adminid == 0 || empty($code))
        {
            ecs_header("Location: privilege.php?act=login\n");
            exit;
        }

        /* 以用户的原密码，与code的值匹配 */
        $sql = 'SELECT password FROM ' .$ecs->table('admin_user'). " WHERE user_id = '$adminid'";
        $password = $db->getOne($sql);

        if (md5($adminid . $password) <> $code)
        {
            //此链接不合法
            $link[0]['text'] = $_LANG['back'];
            $link[0]['href'] = 'privilege.php?act=login';

            sys_msg($_LANG['code_param_error'], 0, $link);
        }
        else
        {
            $smarty->assign('adminid',  $adminid);
            $smarty->assign('code',     $code);
            $smarty->assign('form_act', 'reset_pwd');
        }
    }
    elseif (!empty($_GET['act']) && $_GET['act'] == 'forget_pwd')
    {
        $smarty->assign('form_act', 'forget_pwd');
    }

    $smarty->assign('ur_here', $_LANG['get_newpassword']);

    assign_query_info();
    $smarty->display('get_pwd.htm');
}

/*------------------------------------------------------ */
//-- 验证管理员帐号和email, 发送邮件
/*------------------------------------------------------ */
else
{
    /* 发送找回密码确认邮件 */
    if (!empty($_POST['action']) && $_POST['action'] == 'get_pwd')
    {
        $admin_username = !empty($_POST['user_name']) ? trim($_POST['user_name']) : '';
        $admin_email    = !empty($_POST['email'])     ? trim($_POST['email'])     : '';

        if (empty($admin_username) || empty($admin_email))
        {
            ecs_header("Location: privilege.php?act=login\n");
            exit;
        }

        /* 管理员用户名和邮件地址是否匹配，并取得原密码 */
        $sql = 'SELECT user_id, password FROM ' .$ecs->table('admin_user').
               " WHERE user_name = '$admin_username' AND email = '$admin_email'";
        $admin_info = $db->getRow($sql);

        if (!empty($admin_info))
        {
            /* 生成验证的code */
            $admin_id = $admin_info['user_id'];
            $code     = md5($admin_id . $admin_info['password']);

            /* 设置重置邮件模板所需要的内容信息 */
            $template    = get_mail_template('send_password');
            $reset_email = $ecs->url() . ADMIN_PATH . '/get_password.php?act=reset_pwd&uid='.$admin_id.'&code='.$code;

            $smarty->assign('user_name',   $admin_username);
            $smarty->assign('reset_email', $reset_email);
            $smarty->assign('shop_name',   $_CFG['shop_name']);
            $smarty->assign('send_date',   local_date($_CFG['date_format']));
            $smarty->assign('sent_date',   local_date($_CFG['date_format']));

            $content = $smarty->fetch('str:' . $template['template_content']);

            /* 发送确认重置密码的确认邮件 */
            if (send_mail($admin_username, $admin_email, $template['template_subject'], $content,
            $template['is_html']))
            {
                //提示信息
                $link[0]['text'] = $_LANG['back'];
                $link[0]['href'] = 'privilege.php?act=login';

                sys_msg($_LANG['send_success'].$admin_email, 0, $link);
            }
            else
            {
                sys_msg($_LANG['send_mail_error'], 1);
            }
        }
        else
        {
            /* 提示信息 */
            sys_msg($_LANG['email_username_error'], 1);
        }
    }
    /* 验证新密码，更新管理员密码 */
    elseif (!empty($_POST['action']) && $_POST['action'] == 'reset_pwd')
    {
        $new_password = isset($_POST['password']) ? trim($_POST['password'])  : '';
        $adminid      = isset($_POST['adminid'])  ? intval($_POST['adminid']) : 0;
        $code         = isset($_POST['code'])     ? trim($_POST['code'])      : '';

        if (empty($new_password) || empty($code) || $adminid == 0)
        {
            ecs_header("Location: privilege.php?act=login\n");
            exit;
        }

        /* 以用户的原密码，与code的值匹配 */
        $sql = 'SELECT password FROM ' .$ecs->table('admin_user'). " WHERE user_id = '$adminid'";
        $password = $db->getOne($sql);

        if (md5($adminid . $password) <> $code)
        {
            //此链接不合法
            $link[0]['text'] = $_LANG['back'];
            $link[0]['href'] = 'privilege.php?act=login';

            sys_msg($_LANG['code_param_error'], 0, $link);
        }

        //更新管理员的密码
		$ec_salt=rand(1,9999);
        $sql = "UPDATE " .$ecs->table('admin_user'). "SET password = '".md5(md5($new_password).$ec_salt)."',`ec_salt`='$ec_salt' ".
               "WHERE user_id = '$adminid'";
        $result = $db->query($sql);
        if ($result)
        {
            $link[0]['text'] = $_LANG['login_now'];
            $link[0]['href'] = 'privilege.php?act=login';

            sys_msg($_LANG['update_pwd_success'], 0, $link);
        }
        else
        {
            sys_msg($_LANG['update_pwd_failed'], 1);
        }
    }
}

?>