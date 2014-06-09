<?php

/**
 * ECSHOP 第三方程序会员数据整合插件管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: integrate.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
/*------------------------------------------------------ */
//-- 会员数据整合插件列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $modules = read_modules('../includes/modules/integrates');
    for ($i = 0; $i < count($modules); $i++)
    {
         $modules[$i]['installed'] = ($modules[$i]['code'] == $_CFG['integrate_code']) ? 1 : 0;
    }

    $allow_set_points = $_CFG['integrate_code'] == 'ecshop' ? 0 : 1;

    $smarty->assign('allow_set_points',  $allow_set_points);
    $smarty->assign('ur_here', $_LANG['06_list_integrate']);
    $smarty->assign('modules', $modules);

    assign_query_info();
    $smarty->display('integrates_list.htm');
}

/*------------------------------------------------------ */
//-- 安装会员数据整合插件
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'install')
{
    admin_priv('integrate_users', '');

    /* 增加ucenter设置时先检测uc_client与uc_client/data是否可写 */
    if ($_GET['code'] == 'ucenter')
    {
        $uc_client_dir = file_mode_info(ROOT_PATH . 'uc_client/data');
        if ($uc_client_dir === false)
        {
            sys_msg($_LANG['uc_client_not_exists'], 0);
        }
        if ($uc_client_dir < 7)
        {
            sys_msg($_LANG['uc_client_not_write'], 0);
        }
    }
    if ($_GET['code'] == 'ecshop')
    {
        $sql = "UPDATE " .$ecs->table('shop_config'). " SET value = 'ecshop' WHERE code = 'integrate_code'";
        $db->query($sql);
        $sql = "UPDATE " . $GLOBALS['ecs']->table('shop_config') . " SET value = '' WHERE code = 'points_rule'";
        $GLOBALS['db']->query($sql);

        /* 清除shopconfig表的sql的缓存 */
        clear_cache_files();

        $links[0]['text'] = $_LANG['go_back'];
        $links[0]['href'] = 'integrate.php?act=list';
        sys_msg($_LANG['update_success'], 0, $links);
    }
    else
    {
        $sql = "UPDATE " . $GLOBALS['ecs']->table('users') .
               " SET flag = 0, alias=''".
               " WHERE flag > 0";
        $db->query($sql); //如果有标记，清空标记
        $set_modules = true;
        include_once(ROOT_PATH."includes/modules/integrates/".$_GET['code'].".php");
        $set_modules = false;

//        if ($_GET['code'] == 'ucenter' && !empty($_CFG['integrate_config']))
//        {
//            $cfg = unserialize($_CFG['integrate_config']);
//        }
//        else
//        {
              $cfg = $modules[0]['default'];
              $cfg['integrate_url'] = "http://";
//        }

        /* 判断 */

        assign_query_info();

        $smarty->assign('cfg',      $cfg);
        $smarty->assign('save',     0);
        $smarty->assign('set_list', get_charset_list());
        $smarty->assign('ur_here',  $_LANG['integrate_setup']);
        $smarty->assign('code',     $_GET['code']);
        $smarty->display('integrates_setup.htm');
    }
}

if ($_REQUEST['act'] == 'view_install_log')
{
    $code = empty($_GET['code']) ? '' : trim($_GET['code']);
    if (empty($code) || file_exists(ROOT_PATH . DATA_DIR . '/integrate_' . $code . '_log.php' ))
    {
        sys_msg($_LANG['lost_intall_log'], 1);
    }

    include(ROOT_PATH . DATA_DIR . '/integrate_' . $code . '_log.php');
    if (isset($del_list) || isset($rename_list) || isset($ignore_list))
    {
        if (isset($del_list))
        {
            var_dump($del_list);
        }
        if (isset($rename_list))
        {
             var_dump($rename_list);
        }
        if (isset($ignore_list))
        {
             var_dump($ignore_list);
        }
    }
    else
    {
        sys_msg($_LANG['empty_intall_log'], 1);
    }
}

/*------------------------------------------------------ */
//-- 设置会员数据整合插件
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'setup')
{
    admin_priv('integrate_users', '');

    if ($_GET['code'] == 'ecshop')
    {
        sys_msg($_LANG['need_not_setup']);
    }
    else
    {
        $cfg = unserialize($_CFG['integrate_config']);
        assign_query_info();

        $smarty->assign('save',         1);
        $smarty->assign('set_list', get_charset_list());
        $smarty->assign('ur_here',  $_LANG['integrate_setup']);
        $smarty->assign('code',     $_GET['code']);
        $smarty->assign('cfg',      $cfg);
        $smarty->display('integrates_setup.htm');
    }
}

/*------------------------------------------------------ */
//-- 检查用户填写资料
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'check_config')
{
    $code = $_POST['code'];

    include_once(ROOT_PATH."includes/modules/integrates/".$code.".php");
    $_POST['cfg']['quiet'] = 1;
    $cls_user = new $code ($_POST['cfg']);

    if ($cls_user->error)
    {
        /* 出错提示 */
        if ($cls_user->error == 1)
        {
            sys_msg($_LANG['error_db_msg']);
        }
        elseif ($cls_user->error == 2)
        {
            sys_msg($_LANG['error_table_exist']);
        }
        elseif ($cls_user->error ==  1049)
        {
            sys_msg($_LANG['error_db_exist']);
        }
        else
        {
            sys_msg($cls_user->db->error());
        }
    }

    if ($cls_user->db->version >= '4.1')
    {
        /* 检测数据表字符集 */
        $sql = "SHOW TABLE STATUS FROM `" . $cls_user->db_name . "` LIKE '" . $cls_user->prefix . $cls_user->user_table . "'";
        $row = $cls_user->db->getRow($sql);
        if (isset($row['Collation']))
        {
            $db_charset = trim(substr($row['Collation'], 0, strpos($row['Collation'], '_')));

            if ($db_charset == 'latin1')
            {
                if (empty($_POST['cfg']['is_latin1']))
                {
                    sys_msg($_LANG['error_is_latin1'], null, null, false);
                }
            }
            else
            {
                $user_db_charset = $_POST['cfg']['db_charset'] == 'GB2312' ? 'GBK' : $_POST['cfg']['db_charset'];
                if (!empty($_POST['cfg']['is_latin1']))
                {
                    sys_msg($_LANG['error_not_latin1'], null, null, false);
                }
                if ($user_db_charset != strtoupper($db_charset))
                {
                    sys_msg(sprintf($_LANG['invalid_db_charset'], strtoupper($db_charset), $user_db_charset), null, null, false);
                }
            }
        }
    }
    /* 中文检测 */
    $test_str = '测试中文字符';
    if ($_POST['cfg']['db_charset'] != 'UTF8')
    {
        $test_str = ecs_iconv('UTF8', $_POST['cfg']['db_charset']);
    }

    $sql = "SELECT " . $cls_user->field_name .
           " FROM " . $cls_user->table($cls_user->user_table) .
           " WHERE " . $cls_user->field_name . " = '$test_str'";
    $test = $cls_user->db->query($sql, 'SILENT');

    if (!$test)
    {
        sys_msg($_LANG['error_latin1'] , null, null, false);
    }

    if (!empty($_POST['save']))
    {
        /* 直接保存修改 */
        if (save_integrate_config($code, $_POST['cfg']))
        {
            sys_msg($_LANG['save_ok'],0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
        }
        else
        {
            sys_msg($_LANG['save_error'],0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
        }
    }

    $sql = "SELECT COUNT(*) FROM " . $ecs->table('users');
    $total = $db->getOne($sql);

    if ($total == 0)
    {
        /* 商城没有用户时，直接保存完成整合 */
        save_integrate_config($_POST['code'], $_POST['cfg']);
        ecs_header("Location: integrate.php?act=complete\n");
        exit;
    }

    /* 检测成功临时保存论坛配置参数 */
    $_SESSION['cfg'] = $_POST['cfg'];
    $_SESSION['code'] = $code;

    $size = 100;

    $smarty->assign('ur_here',$_LANG['conflict_username_check']);
    $smarty->assign('domain', '@ecshop');
    $smarty->assign('lang_total', sprintf($_LANG['shop_user_total'], $total));
    $smarty->assign('size', $size);
    $smarty->display('integrates_check.htm');
}

/*------------------------------------------------------ */
//-- 保存UCenter填写的资料
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'save_uc_config')
{
    $code = $_POST['code'];

    $cfg = unserialize($_CFG['integrate_config']);

    include_once(ROOT_PATH."includes/modules/integrates/".$code.".php");
    $_POST['cfg']['quiet'] = 1;
    $cls_user = new $code ($_POST['cfg']);

    if ($cls_user->error)
    {
        /* 出错提示 */
        if ($cls_user->error == 1)
        {
            sys_msg($_LANG['error_db_msg']);
        }
        elseif ($cls_user->error == 2)
        {
            sys_msg($_LANG['error_table_exist']);
        }
        elseif ($cls_user->error ==  1049)
        {
            sys_msg($_LANG['error_db_exist']);
        }
        else
        {
            sys_msg($cls_user->db->error());
        }
    }

    /* 合并数组，保存原值 */
    $cfg = array_merge($cfg, $_POST['cfg']);

    /* 直接保存修改 */
    if (save_integrate_config($code, $cfg))
    {
        sys_msg($_LANG['save_ok'],0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
    }
    else
    {
        sys_msg($_LANG['save_error'],0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
    }
}

/*------------------------------------------------------ */
//-- 第一次保存UCenter安装的资料
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'save_uc_config_first')
{
    $code = $_POST['code'];

    include_once(ROOT_PATH."includes/modules/integrates/".$code.".php");
    $_POST['cfg']['quiet'] = 1;
    $cls_user = new $code ($_POST['cfg']);

    if ($cls_user->error)
    {
        /* 出错提示 */
        if ($cls_user->error == 1)
        {
            sys_msg($_LANG['error_db_msg']);
        }
        elseif ($cls_user->error == 2)
        {
            sys_msg($_LANG['error_table_exist']);
        }
        elseif ($cls_user->error ==  1049)
        {
            sys_msg($_LANG['error_db_exist']);
        }
        else
        {
            sys_msg($cls_user->db->error());
        }
    }
    list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $_POST['ucconfig']);
    $uc_ip = !empty($ucip)? $ucip : trim($_POST['uc_ip']);
    $uc_url = !empty($ucapi)? $ucapi : trim($_POST['uc_url']);
    $cfg = array(
                    'uc_id' => $appid,
                    'uc_key' => $appauthkey,
                    'uc_url' => $uc_url,
                    'uc_ip' => $uc_ip,
                    'uc_connect' => 'mysql',
                    'uc_charset' => $uccharset,
                    'db_host' => $ucdbhost,
                    'db_user' => $ucdbuser,
                    'db_name' => $ucdbname,
                    'db_pass' => $ucdbpw,
                    'db_pre' => $uctablepre,
                    'db_charset' => $ucdbcharset,
                );
    /* 增加UC语言项 */
    $cfg['uc_lang'] = $_LANG['uc_lang'];

    /* 检测成功临时保存论坛配置参数 */
    $_SESSION['cfg'] = $cfg;
    $_SESSION['code'] = $code;

    /* 直接保存修改 */
    if (!empty($_POST['save']))
    {
        if (save_integrate_config($code, $cfg))
        {
            sys_msg($_LANG['save_ok'],0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
        }
        else
        {
            sys_msg($_LANG['save_error'],0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
        }
    }

    $query = $db->query("SHOW TABLE STATUS LIKE '" . $GLOBALS['prefix'] . 'users' . "'");
    $data = $db->fetch_array($query);
    if($data["Auto_increment"]) {
        $maxuid = $data["Auto_increment"] - 1;
    } else {
        $maxuid = 0;
    }

    /* 保存完成整合 */
    save_integrate_config($code, $cfg);

    $smarty->assign('ur_here',$_LANG['ucenter_import_username']);
    $smarty->assign('user_startid_intro', sprintf($_LANG['user_startid_intro'], $maxuid, $maxuid));
    $smarty->display('integrates_uc_import.htm');
}

/*------------------------------------------------------ */
//-- 用户重名检查
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'check_user')
{
    $code = $_SESSION['code'];
    include_once(ROOT_PATH . 'includes/cls_json.php');
    include_once(ROOT_PATH."includes/modules/integrates/".$code.".php");
    $cls_user = new $code ($_SESSION['cfg']);
    $json = new JSON();

    $start = empty($_GET['start']) ? 0 : intval($_GET['start']);
    $size = empty($_GET['size']) ? 100 : intval($_GET['size']);
    $method = empty($_GET['method']) ? 1 : intval($_GET['method']);
    $domain = empty($_GET['domain']) ? '@ecshop' : trim($_GET['domain']);
    if ($size <2)
    {
        $size = 2;
    }
    $_SESSION['domain'] = $domain;

    $sql = "SELECT COUNT(*) FROM " . $ecs->table('users');
    $total = $db->getOne($sql);

    $result = array('error'=>0, 'message'=>'', 'start'=>0, 'size'=>$size, 'content'=>'','method'=>$method, 'domain'=>$domain, 'is_end'=>0);

    $sql = "SELECT user_name FROM " . $ecs->table('users') . " LIMIT $start, $size";
    $user_list = $db->getCol($sql);

    $post_user_list = $cls_user->test_conflict($user_list);

    if ($post_user_list)
    {
        /* 标记重名用户 */
        if ($method == 2)
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET flag = '$method', alias = CONCAT(user_name, '$domain') WHERE " . db_create_in($post_user_list, 'user_name');
        }
        else
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET flag = '$method' WHERE " . db_create_in($post_user_list, 'user_name');
        }

        $GLOBALS['db']->ping();
        $GLOBALS['db']->query($sql);

        if ($method == 2 )
        {
            /* 需要改名,验证是否能成功改名 */
            $count = count($post_user_list);
            $test_user_list = array();
            for ($i=0; $i<$count; $i++)
            {
                $test_user_list[] = $post_user_list[$i] . $domain;
            }
            /* 检查改名后用户是否和论坛用户有重名 */
            $error_user_list = $cls_user->test_conflict($test_user_list);   //检查
            if ($error_user_list)
            {
                $domain_len = 0 - str_len($domain);
                $count = count($error_user_list);
                for ($i=0; $i < $count; $i++)
                {
                    $error_user_list[$i] = substr($error_user_list[$i], 0, $domain_len);
                }
                /* 将用户标记为改名失败 */
                $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET flag = '1' WHERE " . db_create_in($error_user_list, 'user_name');
            }

            /* 检查改名后用户是否与商城用户重名 */
            $sql = "SELECT user_name FROM " .$GLOBALS['ecs']->table('users') . " WHERE " . db_create_in($test_user_list, 'user_name');
            $error_user_list = $GLOBALS['db']->getCol($sql);
            if ($error_user_list)
            {
                $domain_len = 0 - str_len($domain);
                $count = count($error_user_list);
                for ($i=0; $i < $count; $i++)
                {
                    $error_user_list[$i] = substr($error_user_list[$i], 0, $domain_len);

                }
                /* 将用户标记为改名失败 */
                $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET flag = '1' WHERE " . db_create_in($error_user_list, 'user_name');
            }
        }
    }

    if (($start + $size) < $total)
    {
        $result['start'] = $start + $size;
        $result['content'] = sprintf($_LANG['notice'], $result['start'], $total);
    }
    else
    {
        $start = $total;
        $result['content'] = $_LANG['check_complete'];
        $result['is_end'] = 1;

        /* 查找有无重名用户,无重名用户则直接同步，有则查看重名用户 */
        $sql = "SELECT COUNT(*) FROM " . $ecs->table('users') . " WHERE flag > 0 ";
        if ($db->getOne($sql) > 0)
        {
            $result['href'] = "integrate.php?act=modify";
        }
        else
        {
            $result['href'] = "integrate.php?act=sync";
        }
    }
    die($json->encode($result));
}

if ($_REQUEST['act'] == 'import_user')
{
    $cfg = $_SESSION['cfg'];
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $ucdb = new cls_mysql($cfg['db_host'], $cfg['db_user'], $cfg['db_pass'], $cfg['db_name'], $cfg['db_charset']);
    $json = new JSON();
    $result = array('error' => 0, 'message' => '');
    $query = $db->query("SHOW TABLE STATUS LIKE '" . $GLOBALS['prefix'] . 'users' . "'");
    $data = $db->fetch_array($query);
    if($data["Auto_increment"]) {
        $maxuid = $data["Auto_increment"] - 1;
    } else {
        $maxuid = 0;
    }
    $merge_method = intval($_POST['merge']);
    $merge_uid = array();
    $uc_uid = array();
    $repeat_user = array();

    $query = $db->query("SELECT * FROM " . $ecs->table('users') . " ORDER BY `user_id` ASC");
    while($data = $db->fetch_array($query))
    {
        $salt = rand(100000, 999999);
        $password = md5($data['password'].$salt);
        $data['username'] = addslashes($data['user_name']);
        $lastuid = $data['user_id'] + $maxuid;
        $uc_userinfo = $ucdb->getRow("SELECT `uid`, `password`, `salt` FROM ".$cfg['db_pre']."members WHERE `username`='$data[username]'");
        if(!$uc_userinfo)
        {
            $ucdb->query("INSERT LOW_PRIORITY INTO ".$cfg['db_pre']."members SET uid='$lastuid', username='$data[username]', password='$password', email='$data[email]', regip='$data[regip]', regdate='$data[regdate]', salt='$salt'", 'SILENT');
            $ucdb->query("INSERT LOW_PRIORITY INTO ".$cfg['db_pre']."memberfields SET uid='$lastuid'",'SILENT');
        }
        else
        {
            if ($merge_method == 1)
            {
                if (md5($data['password'].$uc_userinfo['salt']) == $uc_userinfo['password'])
                {
                    $merge_uid[] = $data['user_id'];
                    $uc_uid[] = array('user_id' => $data['user_id'], 'uid' => $uc_userinfo['uid']);
                    continue;
                }
            }
            $ucdb->query("REPLACE INTO ".$cfg['db_pre']."mergemembers SET appid='".UC_APPID."', username='$data[username]'", 'SILENT');
            $repeat_user[] = $data;
        }
    }
    $ucdb->query("ALTER TABLE ".$cfg['db_pre']."members AUTO_INCREMENT=".($lastuid + 1), 'SILENT');

    //需要更新user_id的表
    $up_user_table = array('account_log', 'affiliate_log', 'booking_goods', 'collect_goods', 'comment', 'feedback', 'order_info', 'snatch_log', 'tag', 'users', 'user_account', 'user_address', 'user_bonus', 'reg_extend_info', 'user_feed', 'delivery_order', 'back_order');
    // 清空的表
    $truncate_user_table = array('cart', 'sessions', 'sessions_data');

    if (!empty($merge_uid))
    {
        $merge_uid = implode(',', $merge_uid);
    }
    else
    {
        $merge_uid = 0;
    }
    // 更新ECSHOP表
    foreach ($up_user_table as $table)
    {
        $db->query("UPDATE " . $ecs->table($table) . " SET `user_id`=`user_id`+ $maxuid ORDER BY `user_id` DESC");
        foreach ($uc_uid as $uid)
        {
            $db->query("UPDATE " . $ecs->table($table) . " SET `user_id`='" . $uid['uid'] . "' WHERE `user_id`='" . ($uid['user_id'] + $maxuid) . "'");
        }
    }
    foreach ($truncate_user_table as $table)
    {
        $db->query("TRUNCATE TABLE " . $ecs->table($table));
    }
    // 保存重复的用户信息
    if (!empty($repeat_user))
    {
        @file_put_contents(ROOT_PATH . 'data/repeat_user.php', $json->encode($repeat_user));
    }
    $result['error'] = 0;
    $result['message'] = $_LANG['import_user_success'];
    die($json->encode($result));
}

/*------------------------------------------------------ */
//-- 重名用户处理
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'modify')
{
    /* 检查是否有改名失败的用户 */
    $sql = "SELECT COUNT(*) FROM " . $ecs->table('users') . " WHERE flag = 1";
    if ($db->getOne($sql) > 0)
    {
        $_REQUEST['flag'] = 1;
        $smarty->assign('default_flag', 1);
    }
    else
    {
        $_REQUEST['flag'] = 0;
        $smarty->assign('default_flag', 0);
    }

    /* 显示重名用户及处理方法 */
    $flags = array(0=>$_LANG['all_user'], 1=>$_LANG['error_user'], 2=>$_LANG['rename_user'], 3=>$_LANG['delete_user'], 4=>$_LANG['ignore_user'] );
    $smarty->assign('flags',      $flags);

    $arr = conflict_userlist();

    $smarty->assign('ur_here',      $_LANG['conflict_username_modify']);
    $smarty->assign('domain',       '@ecshop');
    $smarty->assign('list',         $arr['list']);
    $smarty->assign('filter',       $arr['filter']);
    $smarty->assign('record_count', $arr['record_count']);
    $smarty->assign('page_count',   $arr['page_count']);
    $smarty->assign('full_page',    1);

    $smarty->display('integrates_modify.htm');
}

/*------------------------------------------------------ */
//-- ajax 用户列表查询
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'query')
{
    $arr = conflict_userlist();
    $smarty->assign('list',         $arr['list']);
    $smarty->assign('filter',       $arr['filter']);
    $smarty->assign('record_count', $arr['record_count']);
    $smarty->assign('page_count',   $arr['page_count']);
    $smarty->assign('full_page',    0);
    make_json_result($smarty->fetch('integrates_modify.htm'), '', array('filter' => $arr['filter'], 'page_count' => $arr['page_count']));
}

/*------------------------------------------------------ */
//-- 重名用户处理过程
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'act_modify')
{
    /* 先处理要改名的用户，改名用户要先检查是否有重名情况，有则标记出来 */
    $alias = array();
    foreach ($_POST['opt'] AS $user_id=>$val)
    {
        if ($val = 2)
        {
            $alias[] = $_POST['alias'][$user_id];
        }
    }
    if ($alias)
    {
        /* 检查改名后用户名是否会重名 */
        $sql = 'SELECT user_name FROM ' . $GLOBALS['ecs']->table('users') . ' WHERE ' . db_create_in($alias, 'user_name');
        $ecs_error_list = $db->getCol($sql);

        /* 检查和商城是否有重名 */
        $code = $_SESSION['code'];
        include_once(ROOT_PATH."includes/modules/integrates/".$code.".php");
        $cls_user = new $code ($_SESSION['cfg']);

        $bbs_error_list = $cls_user->test_conflict($alias);

        $error_list = array_unique(array_merge($ecs_error_list, $bbs_error_list));

        if ($error_list)
        {
            /* 将重名用户标记 */
            foreach ($_POST['opt'] AS $user_id=>$val)
            {
                if ($val = 2)
                {
                    if (in_array($_POST['alias'][$user_id], $error_list))
                    {
                        /* 重名用户，需要标记 */
                        $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET flag = 1,  alias='' WHERE user_id = '$user_id'";
                    }
                    else
                    {
                        /* 用户名无重复，可以正常改名 */
                        $sql = "UPDATE " . $GLOBALS['ecs']->table('users').
                         " SET flag = 2, alias = '" . $_POST['alias'][$user_id] . "'".
                         " WHERE user_id = '$user_id'";
                    }
                    $db->query($sql);
                }
            }
        }
        else
        {
            /* 处理没有重名的情况 */
            foreach ($_POST['opt'] AS $user_id=>$val)
            {
                $sql = "UPDATE " . $GLOBALS['ecs']->table('users').
                       " SET flag = 2, alias = '" . $_POST['alias'][$user_id] . "'".
                       " WHERE user_id = '$user_id'";
                $db->query($sql);
            }
        }
    }

    /* 处理删除和保留情况 */
    foreach ($_POST['opt'] as $user_id=>$val)
    {
        if ($val == 3 || $val == 4)
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET flag='$val' WHERE user_id='$user_id'";
            $db->query($sql);
        }
    }

    /* 跳转  */
    ecs_header("Location: integrate.php?act=modify");
    exit;
}

/*------------------------------------------------------ */
//-- 将商城数据同步到论坛
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'sync')
{
    $size = 100;
    $total = $db->getOne("SELECT COUNT(*) FROM " . $ecs->table("users"));
    $task_del = $db->getOne("SELECT COUNT(*) FROM " . $ecs->table("users") . " WHERE flag = 3");
    $task_rename = $db->getOne("SELECT COUNT(*) FROM " . $ecs->table("users") . " WHERE flag = 2");
    $task_ignore = $db->getOne("SELECT COUNT(*) FROM " . $ecs->table("users") . " WHERE flag = 4");
    $task_sync = $total - $task_del - $task_ignore;

    $_SESSION['task'] = array('del'=>array('total'=>$task_del, 'start'=>0), 'rename'=>array('total'=>$task_rename, 'start'=>0), 'sync'=>array('total'=>$task_sync, 'start'=>0));

    $del_list    = "";
    $rename_list = "";
    $ignore_list = "";

    $tasks = array();
    if ($task_del > 0)
    {
        $tasks[] = array('task_name'=>sprintf($_LANG['task_del'], $task_del),'task_status'=>'<span id="task_del">' . $_LANG['task_uncomplete'] . '<span>');
        $sql = "SELECT user_name FROM " . $ecs->table('users') . " WHERE flag = 2";
        $del_list = $db->getCol($sql);
    }

    if ($task_rename > 0)
    {
        $tasks[] = array('task_name'=>sprintf($_LANG['task_rename'], $task_rename),'task_status'=>'<span id="task_rename">' . $_LANG['task_uncomplete'] . '</span>');
        $sql = "SELECT user_name, alias FROM " . $ecs->table('users') . " WHERE flag = 3";
        $rename_list = $db->getAll($sql);
    }

    if ($task_ignore >0)
    {
        $sql = "SELECT user_name FROM " . $ecs->table('users') . " WHERE flag = 4";
        $ignore_list = $db->getCol($sql);
    }

    if ($task_sync > 0)
    {
         $tasks[] = array('task_name'=>sprintf($_LANG['task_sync'], $task_sync),'task_status'=>'<span id="task_sync">' . $_LANG['task_uncomplete'] . '</span>');
    }

    $tasks[] = array('task_name'=>$_LANG['task_save'],'task_status'=>'<span id="task_save">' . $_LANG['task_uncomplete'] . '</span>');

    /* 保存修改日志 */
    $fp = @fopen(ROOT_PATH . DATA_DIR . '/integrate_' . $_SESSION['code'] . '_log.php', 'wb');
    $log = '';
    if (isset($del_list))
    {
        $log .= '$del_list=' . var_export($del_list,true) . ';';
    }
    if (isset($rename_list))
    {
        $log .= '$rename_list=' . var_export($rename_list, true) . ';';
    }
    if (isset($ignore_list))
    {
        $log .= '$ignore_list=' . var_export($ignore_list, true) . ';';
    }
    fwrite($fp, $log);
    fclose($fp);

    $smarty->assign('tasks', $tasks);
    $smarty->assign('ur_here',$_LANG['user_sync']);
    $smarty->assign('size', $size);
    $smarty->display('integrates_sync.htm');
}

/*------------------------------------------------------ */
//-- 完成任务
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'task')
{
    if (empty($_GET['size']) || $_GET['size'] < 0)
    {
        $size = 100;
    }
    else
    {
        $size = intval($_GET['size']);
    }

    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON();
    $result = array('message'=>'', 'error'=>0, 'content'=>'', 'id'=>'', 'end'=>0, 'size'=>$size);

    if ($_SESSION['task']['del']['start'] < $_SESSION['task']['del']['total'])
    {
        /* 执行操作 */
        /* 查找要删除用户 */
        $arr = $db->getCol("SELECT user_name FROM " . $ecs->table('users') . " WHERE flag = 3 LIMIT " . $_SESSION['task']['del']['start'] . ',' . $result['size']);
        $db->query("DELETE FROM " . $ecs->table('users') . " WHERE " . db_create_in($arr,'user_name'));

        /* 保存设置 */
        $result['id'] = 'task_del';
        if ($_SESSION['task']['del']['start'] + $result['size'] >= $_SESSION['task']['del']['total'])
        {
            $_SESSION['task']['del']['start'] = $_SESSION['task']['del']['total'];
            $result['content'] = $_LANG['task_complete'];
        }
        else
        {
            $_SESSION['task']['del']['start'] += $result['size'];
            $result['content'] = sprintf($_LANG['task_run'], $_SESSION['task']['del']['start'], $_SESSION['task']['del']['total']);
        }

        die($json->encode($result));
    }
    else if ($_SESSION['task']['rename']['start'] < $_SESSION['task']['rename']['total'])
    {
        /* 查找要改名用户 */
        $arr = $db->getCol("SELECT user_name FROM " . $ecs->table('users') . " WHERE flag = 2 LIMIT " . $_SESSION['task']['del']['start'] . ',' . $result['size']);
        $db->query("UPDATE " . $ecs->table('users') . " SET user_name=alias, alias='' WHERE " . db_create_in($arr,'user_name'));

        /* 保存设置 */
        $result['id'] = 'task_rename';
        if ($_SESSION['task']['rename']['start'] + $result['size'] >= $_SESSION['task']['rename']['total'])
        {
            $_SESSION['task']['rename']['start'] = $_SESSION['task']['rename']['total'];
            $result['content'] = $_LANG['task_complete'];
        }
        else
        {
            $_SESSION['task']['rename']['start'] += $result['size'];
            $result['content'] = sprintf($_LANG['task_run'], $_SESSION['task']['rename']['start'], $_SESSION['task']['rename']['total']);
        }
        die($json->encode($result));
    }
    else if ($_SESSION['task']['sync']['start'] < $_SESSION['task']['sync']['total'])
    {
        $code = $_SESSION['code'];
        include_once(ROOT_PATH."includes/modules/integrates/".$code.".php");
        $cls_user = new $code ($_SESSION['cfg']);
        $cls_user->need_sync = false;

        $sql = "SELECT user_name, password, email, sex, birthday, reg_time ".
                "FROM " . $ecs->table('users') . " LIMIT " . $_SESSION['task']['del']['start'] . ',' . $result['size'];
        $arr = $db->getAll($sql);
        foreach ($arr as $user)
        {
            @$cls_user->add_user($user['user_name'], '', $user['email'], $user['sex'], $user['birthday'], $user['reg_time'], $user['password']);
        }

        /* 保存设置 */
        $result['id'] = 'task_sync';
        if ($_SESSION['task']['sync']['start'] + $result['size'] >= $_SESSION['task']['sync']['total'])
        {
            $_SESSION['task']['sync']['start'] = $_SESSION['task']['sync']['total'];
            $result['content'] = $_LANG['task_complete'];
        }
        else
        {
            $_SESSION['task']['sync']['start'] += $result['size'];
            $result['content'] = sprintf($_LANG['task_run'], $_SESSION['task']['sync']['start'], $_SESSION['task']['sync']['total']);
        }
        die($json->encode($result));
    }
    else
    {
        /* 记录合并用户 */

        /* 插入code到shop_config表 */
        $sql = "SELECT COUNT(*) FROM " .$ecs->table('shop_config'). " WHERE code = 'integrate_code'";

        if ($db->GetOne($sql) == 0)
        {
            $sql = "INSERT INTO " .$ecs->table('shop_config'). " (code, value) ".
                    "VALUES ('integrate_code', '$_SESSION[code]')";
        }
        else
        {
            $sql = "UPDATE " .$ecs->table('shop_config'). " SET value = '$_SESSION[code]' WHERE code = 'integrate_code'";
        }
        $db->query($sql);

        /* 序列化设置信息，并保存到数据库 */
        save_integrate_config($_SESSION['code'], $_SESSION['cfg']);

        $result['content'] = $_LANG['task_complete'];
        $result['id'] = 'task_save';
        $result['end'] = 1;

        /* 清理多余信息 */
        unset($_SESSION['cfg']);
        unset($_SESSION['code']);
        unset($_SESSION['task']);
        unset($_SESSION['domain']);
        $sql = "UPDATE " . $ecs->table('users') . " set flag = 0, alias = '' WHERE flag > 0";
        $db->query($sql);
        die($json->encode($result));
    }
}

/*------------------------------------------------------ */
//-- 保存UCenter设置
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'setup_ucenter')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    include_once(ROOT_PATH . 'includes/cls_transport.php');
    $json = new JSON();
    $result = array('error' => 0, 'message' => '');

    $app_type   = 'ECSHOP';
    $app_name   = $db->getOne('SELECT value FROM ' . $ecs->table('shop_config') . " WHERE code = 'shop_name'");
    $app_url    = $GLOBALS['ecs']->url();
    $app_charset = EC_CHARSET;
    $app_dbcharset = strtolower((str_replace('-', '', EC_CHARSET)));
    $ucapi = !empty($_POST['ucapi']) ? trim($_POST['ucapi']) : '';
    $ucip = !empty($_POST['ucip']) ? trim($_POST['ucip']) : '';
    $dns_error = false;
    if(!$ucip)
    {
        $temp = @parse_url($ucapi);
        $ucip = gethostbyname($temp['host']);
        if(ip2long($ucip) == -1 || ip2long($ucip) === FALSE)
        {
            $ucip = '';
            $dns_error = true;
        }
    }
    if($dns_error){
        $result['error'] = 2;
        $result['message'] = '';
        die($json->encode($result));
    }

    $ucfounderpw = trim($_POST['ucfounderpw']);
    $app_tagtemplates = 'apptagtemplates[template]='.urlencode('<a href="{url}" target="_blank">{goods_name}</a>').'&'.
        'apptagtemplates[fields][goods_name]='.urlencode($_LANG['tagtemplates_goodsname']).'&'.
        'apptagtemplates[fields][uid]='.urlencode($_LANG['tagtemplates_uid']).'&'.
        'apptagtemplates[fields][username]='.urlencode($_LANG['tagtemplates_username']).'&'.
        'apptagtemplates[fields][dateline]='.urlencode($_LANG['tagtemplates_dateline']).'&'.
        'apptagtemplates[fields][url]='.urlencode($_LANG['tagtemplates_url']).'&'.
        'apptagtemplates[fields][image]='.urlencode($_LANG['tagtemplates_image']).'&'.
        'apptagtemplates[fields][goods_price]='.urlencode($_LANG['tagtemplates_price']);
    $postdata ="m=app&a=add&ucfounder=&ucfounderpw=".urlencode($ucfounderpw)."&apptype=".urlencode($app_type).
        "&appname=".urlencode($app_name)."&appurl=".urlencode($app_url)."&appip=&appcharset=".$app_charset.
        '&appdbcharset='.$app_dbcharset.'&apptagtemplates='.$app_tagtemplates;
    $t = new transport;
    $ucconfig = $t->request($ucapi.'/index.php', $postdata);
    $ucconfig = $ucconfig['body'];
    if(empty($ucconfig))
    {
        //ucenter 验证失败
        $result['error'] = 1;
        $result['message'] = $_LANG['uc_msg_verify_failur'];

    }
    elseif($ucconfig == '-1')
    {
        //管理员密码无效
        $result['error'] = 1;
        $result['message'] = $_LANG['uc_msg_password_wrong'];
    }
    else
    {
        list($appauthkey, $appid) = explode('|', $ucconfig);
        if(empty($appauthkey) || empty($appid))
        {
            //ucenter 安装数据错误
            $result['error'] = 1;
            $result['message'] = $_LANG['uc_msg_data_error'];
        }
        else
        {
            $result['error'] = 0;
            $result['message'] = $ucconfig;
        }
    }

    die($json->encode($result));
}

/* 显示整合成功信息 */
if ($_REQUEST['act'] == 'complete')
{
    sys_msg($_LANG['sync_ok'],0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
}

if ($_REQUEST['act'] == 'points_set')
{
    $rule_index = empty($_GET['rule_index']) ? '' : trim($_GET['rule_index']);

    $user = &init_users();
    $points = $user->get_points_name(); //获取商城可用积分

    if (empty($points))
    {
        sys_msg($_LANG['no_points'], 0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
    }
    elseif ($points == 'ucenter')
    {
        sys_msg($_LANG['uc_points'], 0, array(array('text'=>$_LANG['uc_set_credits'],'href'=>UC_API, 'target'=>'_blank')), false);
    }

    $rule = array(); //取得一样规则
    if ($_CFG['points_rule'])
    {
        $rule = unserialize($_CFG['points_rule']);
    }

    $points_key = array_keys($points);
    $count = count($points_key);


    $select_rule = array();
    $exist_rule = array();
    for ($i=0; $i < $count ; $i++ )
    {
        if (!isset($rule[TO_P . $points_key[$i]]))
        {
            $select_rule[TO_P . $points_key[$i]] = $_LANG['bbs'] . $points[$points_key[$i]]['title'] . '->' . $_LANG['shop_pay_points'];
        }
        else
        {
            $exist_rule[TO_P . $points_key[$i]] = $_LANG['bbs'] . $points[$points_key[$i]]['title'] . '->' . $_LANG['shop_pay_points'];
        }
    }
    for ($i=0; $i < $count ; $i++ )
    {
        if (!isset($rule[TO_R . $points_key[$i]]))
        {
            $select_rule[TO_R . $points_key[$i]] = $_LANG['bbs'] . $points[$points_key[$i]]['title'] . '->' . $_LANG['shop_rank_points'];
        }
        else
        {
            $exist_rule[TO_R . $points_key[$i]] = $_LANG['bbs'] . $points[$points_key[$i]]['title'] . '->' . $_LANG['shop_rank_points'];
        }
    }
    for ($i=0; $i < $count ; $i++ )
    {
        if (!isset($rule[FROM_P . $points_key[$i]]))
        {
            $select_rule[FROM_P . $points_key[$i]] =  $_LANG['shop_pay_points'] . '->' . $_LANG['bbs'] . $points[$points_key[$i]]['title'];
        }
        else
        {
            $exist_rule[FROM_P . $points_key[$i]] =  $_LANG['shop_pay_points'] . '->' . $_LANG['bbs'] . $points[$points_key[$i]]['title'];
        }
    }
    for ($i=0; $i < $count ; $i++ )
    {
        if (!isset($rule[FROM_R . $points_key[$i]]))
        {
            $select_rule[FROM_R . $points_key[$i]] =  $_LANG['shop_rank_points'] . '->' . $_LANG['bbs'] . $points[$points_key[$i]]['title'];
        }
        else
        {
            $exist_rule[FROM_R . $points_key[$i]] =  $_LANG['shop_rank_points'] . '->' . $_LANG['bbs'] . $points[$points_key[$i]]['title'];
        }
    }

    /* 判断是否还能添加新规则 */
    if (($rule_index && isset($rule[$rule_index])) || empty($select_rule))
    {
        $allow_add = 0;
    }
    else
    {
        $allow_add = 1;
    }

    if ($rule_index && isset($rule[$rule_index]))
    {
        list($from_val, $to_val) = explode(':', $rule[$rule_index]);

        $select_rule[$rule_index] = $exist_rule[$rule_index];
        $smarty->assign('from_val',    $from_val);
        $smarty->assign('to_val',      $to_val);
    }

    $smarty->assign('rule_index',    $rule_index);
    $smarty->assign('allow_add',     $allow_add);
    $smarty->assign('select_rule',   $select_rule);
    $smarty->assign('exist_rule',    $exist_rule);
    $smarty->assign('rule_list',     $rule);
    $smarty->assign('integral_name', $_CFG['integral_name']);
    $smarty->assign('full_page',     1);
    $smarty->assign('points', $points);
    $smarty->display('integrates_points.htm');
}

if ($_REQUEST['act'] == 'edit_points')
{
    $rule_index = empty($_REQUEST['rule_index']) ? '' : trim($_REQUEST['rule_index']);

    $rule = array(); //取得一样规则
    if ($_CFG['points_rule'])
    {
        $rule = unserialize($_CFG['points_rule']);
    }

    if (isset($_POST['from_val']) && isset($_POST['to_val']))
    {
        /* 添加rule */
        $from_val = empty($_POST['from_val']) ? 0 : intval($_POST['from_val']);
        $to_val = empty($_POST['to_val']) ? 1 : intval($_POST['to_val']);
        $old_rule_index = empty($_POST['old_rule_index']) ? '' : trim($_POST['old_rule_index']);

        if (empty($old_rule_index) || $old_rule_index == $rule_index)
        {
            $rule[$rule_index] = $from_val . ':' . $to_val;
        }
        else
        {
            $tmp_rule = array();
            foreach ($rule as $key=>$val)
            {
                if ($key == $old_rule_index)
                {
                    $tmp_rule[$rule_index] = $from_val . ':' . $to_val;
                }
                else
                {
                    $tmp_rule[$key] = $val;
                }
            }

            $rule = $tmp_rule;
        }

    }
    else
    {
        /* 删除rule */
        unset($rule[$rule_index]);
    }

    $sql = "UPDATE " . $ecs->table('shop_config') . " SET value ='" . serialize($rule) . "' WHERE code='points_rule'";

    $db->query($sql);

    clear_cache_files();

    ecs_header("Location: integrate.php?act=points_set\n");
    exit;
}

if ($_REQUEST['act'] == 'save_points')
{
    $keys = array_keys($_POST);
    $cfg = array();
    foreach ($keys as $key)
    {
        if (is_array($_POST[$key]))
        {
            $cfg[$key]['bbs_points'] = empty($_POST[$key]['bbs_points']) ? 0 : intval($_POST[$key]['bbs_points']);
            $cfg[$key]['fee_points'] = empty($_POST[$key]['fee_points']) ? 0 : intval($_POST[$key]['fee_points']);
            $cfg[$key]['pay_points'] = empty($_POST[$key]['pay_points']) ? 0 : intval($_POST[$key]['pay_points']);
            $cfg[$key]['rank_points'] = empty($_POST[$key]['rank_points']) ? 0 : intval($_POST[$key]['rank_points']);
        }
    }

    $sql = "SELECT COUNT(*) FROM " . $ecs->table('shop_config') . " WHERE code='points_set'";
    if ($db->getOne($sql) == 0)
    {
        $sql = "INSERT INTO " . $ecs->table('shop_config') . " (parent_id, type, code, value) VALUES (6, 'hidden', 'points_set', '" . serialize($cfg) . "')";
    }
    else
    {
        $sql = "UPDATE " . $ecs->table('shop_config') . " SET value ='" . serialize($cfg) . "' WHERE code='points_set'";
    }
    $db->query($sql);
    clear_cache_files();
    sys_msg($_LANG['save_ok'],0, array(array('text'=>$_LANG['06_list_integrate'],'href'=>'integrate.php?act=list')));
}

/**
 *  返回冲突用户列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function conflict_userlist()
{
    $filter['flag'] = empty($_REQUEST['flag']) ? 0 : intval($_REQUEST['flag']);
    $where = ' WHERE flag';
    if ($filter['flag'])
    {
        $where .= "=" . $filter['flag'];
    }
    else
    {
        $where .= ">" . 0;
    }

    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('users') . $where;

    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    /* 分页大小 */
    $filter = page_and_size($filter);
    $sql = "SELECT user_id, user_name, email, reg_time, flag, alias ".
            " FROM " . $GLOBALS['ecs']->table('users') . $where .
            " ORDER BY user_id ASC".
            " LIMIT " . $filter['start'] . ',' . $filter['page_size'];
    $list = $GLOBALS['db']->getAll($sql);

    $list_count = count($list);
    for ($i=0; $i < $list_count; $i++)
    {
        $list[$i]['reg_date'] = local_date($GLOBALS['_CFG']['date_format'], $list[$i]['reg_time']);
    }

    $arr = array('list' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/**
 *
 *
 * @access  public
 * @param
 *
 * @return void
 */
function save_integrate_config ($code, $cfg)
{
    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('shop_config'). " WHERE code = 'integrate_code'";

    if ($GLOBALS['db']->GetOne($sql) == 0)
    {
        $sql = "INSERT INTO " .$ecs->table('shop_config'). " (code, value) ".
                "VALUES ('integrate_code', '$code')";
    }
    else
    {
        $sql = "SELECT value FROM " . $GLOBALS['ecs']->table('shop_config') . " WHERE code = 'integrate_code'";
        if ($code != $GLOBALS['db']->getOne($sql))
        {
            /* 有缺换整合插件，需要把积分设置也清除 */
            $sql = "UPDATE " . $GLOBALS['ecs']->table('shop_config') . " SET value = '' WHERE code = 'points_rule'";
            $GLOBALS['db']->query($sql);
        }
        $sql = "UPDATE " .$GLOBALS['ecs']->table('shop_config'). " SET value = '$code' WHERE code = 'integrate_code'";
    }

    $GLOBALS['db']->query($sql);

    /* 当前的域名 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $cur_domain = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $cur_domain = $_SERVER['HTTP_HOST'];
    }
    else
    {
        if (isset($_SERVER['SERVER_NAME']))
        {
            $cur_domain = $_SERVER['SERVER_NAME'];
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $cur_domain = $_SERVER['SERVER_ADDR'];
        }
    }

    /* 整合对象的域名 */
    $int_domain = str_replace(array('http://', 'https://'), array('', ''), $cfg['integrate_url']);
    if (strrpos($int_domain, '/'))
    {
        $int_domain = substr($int_domain, 0, strrpos($int_domain, '/'));
    }

    if ($cur_domain != $int_domain)
    {
        $same_domain    = true;
        $domain         = '';

        /* 域名不一样，检查是否在同一域下 */
        $cur_domain_arr = explode(".", $cur_domain);
        $int_domain_arr = explode(".", $int_domain);

        if (count($cur_domain_arr) != count($int_domain_arr) || $cur_domain_arr[0] == '' || $int_domain_arr[0] == '')
        {
            /* 域名结构不相同 */
            $same_domain = false;
        }
        else
        {
            /* 域名结构一致，检查除第一节以外的其他部分是否相同 */
            $count = count($cur_domain_arr);

            for ($i = 1; $i < $count; $i++)
            {
                if ($cur_domain_arr[$i] != $int_domain_arr[$i])
                {
                    $domain         = '';
                    $same_domain    = false;
                    break;
                }
                else
                {
                    $domain .= ".$cur_domain_arr[$i]";
                }
            }
        }

        if ($same_domain == false)
        {
            /* 不在同一域，设置提示信息 */
            $cfg['cookie_domain']   = '';
            $cfg['cookie_path']     = '/';
        }
        else
        {
            $cfg['cookie_domain']   = $domain;
            $cfg['cookie_path']     = '/';
        }
    }
    else
    {
        $cfg['cookie_domain']   = '';
        $cfg['cookie_path']     = '/';
    }

    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('shop_config') . " WHERE code = 'integrate_config'";
    if ($GLOBALS['db']->GetOne($sql) == 0)
    {
        $sql =  "INSERT INTO " . $GLOBALS['ecs']->table('shop_config') . " (code, value) ".
                "VALUES ('integrate_config', '" . serialize($cfg) . "')";
    }
    else
    {
        $sql = "UPDATE " . $GLOBALS['ecs']->table('shop_config') . " SET value='". serialize($cfg) ."' ".
                "WHERE code='integrate_config'";
    }

    $GLOBALS['db']->query($sql);

    clear_cache_files();
    return true;
}

?>
