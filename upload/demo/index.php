<?php

/**
 * ECSHOP 升级程序 之 控制器
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Date: 2009-12-14 17:22:19 +0800 (一, 2009-12-14) $
 * $Id: index.php 16882 2009-12-14 09:22:19Z liubo $
 */

require_once('./includes/init.php');

/* 初始化EC的字符集与语言变量*/
$updater_lang = $ec_charset = '';
if (!empty($_POST['lang']))
{
    $lang_charset = explode('_', $_POST['lang']);
    $updater_lang = $lang_charset[0].'_'.$lang_charset[1];
    $ec_charset = $lang_charset[2];
}
if(file_exists(ROOT_PATH ."data/install.lock"))
{
    die('You have installed! ');
}
if (empty($updater_lang))
{
    if (defined('EC_LANGUAGE'))
    {
        $updater_lang = EC_LANGUAGE;
    }
    else
    {
        $updater_lang = get_current_lang();
        if ($updater_lang === false)
        {
            die('Please set system\'s language!');
        }
    }
}
if (empty($ec_charset))
{
    if (isset($_COOKIE['ECCC']))
    {
        $ec_charset = $_COOKIE['ECCC'];
    }
    elseif (defined('EC_CHARSET'))
    {
        $ec_charset = EC_CHARSET;
    }
    elseif (get_current_version() < 'v2.6.0')
    {
        $ec_charset = 'utf-8';
    }
    else
    {
        $ec_charset = 'utf-8';
    }
}
/* 发送HTTP头部，保证浏览器识别UTF8编码 */
@header('Content-type: text/html; charset='.$ec_charset);
//echo $updater_lang . '_' . $ec_charset;
/* 加载升级程序所使用的语言包 */
$updater_lang_package_path = ROOT_PATH . 'demo/languages/' . $updater_lang . '_' . $ec_charset .'.php';

if (file_exists($updater_lang_package_path))
{
    include_once($updater_lang_package_path);
    $smarty->assign('lang', $_LANG);
}
else
{
    die('Can\'t find language package!');
}

/* 初始化流程控制变量 */

$step = isset($_REQUEST['step']) ? $_REQUEST['step'] : 'sel_lang';

$smarty->assign('ec_charset', $ec_charset);
$smarty->assign('updater_lang', $updater_lang);
switch($step)
{
/* 选择语言编码页面 */
case 'sel_lang' :
    $smarty->display('lang.php');
    break;

/* 说明页面 */
case 'readme' :
    write_charset_config($updater_lang, $ec_charset);
    $smarty->assign('new_version', VERSION);
    $smarty->assign('old_version', get_current_version());
    $smarty->assign('ui', empty($_REQUEST['ui'])?'ecshop':$_REQUEST['ui']);
    $smarty->assign('mysql_charset', $mysql_charset);
    $smarty->assign('ecshop_charset', $ecshop_charset);
    $smarty->display('readme.php');

    break;

/* UC 安装配置检测 */
case 'uccheck' :
    $smarty->assign('ucapi', $_POST['ucapi']);
    $smarty->assign('ucfounderpw', $_POST['ucfounderpw']);
    $smarty->assign('installer_lang', $installer_lang);
    $smarty->display('uc_check.php');

    break;

case 'setup_ucenter' :

    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON();
    $result = array('error' => 0, 'message' => '');

    $app_type   = 'ECSHOP';
    $app_name   = $db->getOne('SELECT value FROM ' . $ecs->table('shop_config') . " WHERE code = 'shop_name'");
    $app_url    = url();
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

    $ucconfig = dfopen($ucapi.'/index.php', 500, $postdata, '', 1, $ucip);
    if(empty($ucconfig))
    {
        //ucenter 验证失败
        $result['error'] = 1;
        $result['message'] = '验证失败';

    }
    elseif($ucconfig == '-1')
    {
        //管理员密码无效
        $result['error'] = 1;
        $result['message'] = '创始人密码错误';
    }
    else
    {
        list($appauthkey, $appid) = explode('|', $ucconfig);
        if(empty($appauthkey) || empty($appid))
        {
            //ucenter 安装数据错误
            $result['error'] = 1;
            $result['message'] = '安装数据错误';
        }
        elseif(($succeed = save_uc_config($ucconfig."|$ucapi|$ucip")))
        {
            $result['error'] = 0;
            $result['message'] = 'OK';
        }
        else
        {
            //config文件写入错误
            $result['error'] = 1;
            $result['message'] = '配置文件写入错误';
        }
    }

    die($json->encode($result));

    break;

/* 会员数据合并界面 */
case 'usersmerge' :

    include(ROOT_PATH . 'data/config.php');
    if (UC_CHARSET != EC_CHARSET)
    {
        $smarty->assign('not_match', true);
    }
    else
    {
        $link = @mysql_connect(UC_DBHOST, UC_DBUSER, UC_DBPW);
        if (!$link)
        {
            $smarty->assign('noucdb', true);
        }
        else
        {
            @mysql_close($link);
            $ucdb = new cls_mysql(UC_DBHOST, UC_DBUSER, UC_DBPW, UC_DBNAME, UC_DBCHARSET);
            $maxuid = intval($ucdb->getOne("SELECT MAX(uid)+1 FROM ".UC_DBTABLEPRE."members LIMIT 1"));
            $smarty->assign('maxuid', $maxuid);
        }
    }
    $smarty->display('usermerge.php');

    break;

/*将会员数据导入到uc*/
case 'userimporttouc' :
    include(ROOT_PATH . 'data/config.php');
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $ucdb = new cls_mysql(UC_DBHOST, UC_DBUSER, UC_DBPW, UC_DBNAME, UC_DBCHARSET);
    $json = new JSON();
    $result = array('error' => 0, 'message' => '');
    $maxuid = intval($ucdb->getOne("SELECT MAX(uid)+1 FROM ".UC_DBTABLEPRE."members LIMIT 1"));
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
        $uc_userinfo = $ucdb->getRow("SELECT `uid`, `password`, `salt` FROM ".UC_DBTABLEPRE."members WHERE `username`='$data[username]'");
        if(!$uc_userinfo)
        {
            $ucdb->query("INSERT LOW_PRIORITY INTO ".UC_DBTABLEPRE."members SET uid='$lastuid', username='$data[username]', password='$password', email='$data[email]', regip='$data[regip]', regdate='$data[regdate]', salt='$salt'", 'SILENT');
            $ucdb->query("INSERT LOW_PRIORITY INTO ".UC_DBTABLEPRE."memberfields SET uid='$lastuid'",'SILENT');
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
            $ucdb->query("REPLACE INTO ".UC_DBTABLEPRE."mergemembers SET appid='".UC_APPID."', username='$data[username]'", 'SILENT');
            $repeat_user[] = $data;
        }
    }
    $ucdb->query("ALTER TABLE ".UC_DBTABLEPRE."members AUTO_INCREMENT=".($lastuid + 1), 'SILENT');

    //需要更新user_id的表
    $up_user_table = array('account_log', 'affiliate_log', 'booking_goods', 'collect_goods', 'comment', 'feedback', 'order_info', 'snatch_log', 'tag', 'users', 'user_account', 'user_address', 'user_bonus');
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
    $result['message'] = 'OK';
    die($json->encode($result));

    break;



/* 检查环境页面 */
case 'check' :
    include_once(ROOT_PATH . 'demo/includes/lib_env_checker.php');
    include_once(ROOT_PATH . 'demo/includes/checking_dirs.php');

    $ui = isset($_REQUEST['ui']) ? $_REQUEST['ui'] : 'ecshop';
    if ($ui == 'ecshop')
    {
        array_shift($checking_dirs);
    }
    $dir_checking = check_dirs_priv($checking_dirs);


    $templates_root = array(
        'dwt' => ROOT_PATH . 'themes/default/',
        'lbi' => ROOT_PATH . 'themes/default/library/');
    $template_checking = check_templates_priv($templates_root);

    $rename_priv = check_rename_priv();

    $disabled = '';
    if ($dir_checking['result'] === 'ERROR'
            || !empty($template_checking)
            || !empty($rename_priv))
    {
        $disabled = 'disabled="true"';
    }

    $has_unwritable_tpl = 'yes';
    if (empty($template_checking))
    {
        $template_checking = $_LANG['all_are_writable'];
        $has_unwritable_tpl = 'no';
    }

    $smarty->assign('config_info', get_config_info());
    $smarty->assign('dir_checking', $dir_checking['detail']);
    $smarty->assign('has_unwritable_tpl', $has_unwritable_tpl);
    $smarty->assign('template_checking', $template_checking);
    $smarty->assign('rename_priv', $rename_priv);
    $smarty->assign('disabled', $disabled);
    $smarty->display('checking.php');

    break;

/* 获得版本列表 */
case 'get_ver_list' :
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON();

    $cur_ver = get_current_version();
    $new_ver = get_new_version();
    $needup_ver_list = get_needup_version_list($cur_ver, $new_ver);
    
    
    /* 安装测试数据 */

        if (file_exists(ROOT_PATH . 'demo/'. $system_lang . '.sql'))
        {
            $sql_files = array(ROOT_PATH . 'demo/'. $system_lang . '.sql');
        }
        else
        {
            $sql_files = array(ROOT_PATH . 'demo/zh_cn.sql');
        }
        if (!install_data($sql_files))
        {            
            die(implode(',', $err->last_message()));

        }
        if (!copy_files(ROOT_PATH . 'demo/brandlogo/', ROOT_PATH . 'data/brandlogo/'))
        {
            die(implode(',', $err->last_message()));
        }
        if (!copy_files(ROOT_PATH . 'demo/200905/goods_img/', ROOT_PATH . 'images/200905/goods_img/'))
        {
           die(implode(',', $err->last_message()));
        }
        if (!copy_files(ROOT_PATH . 'demo/200905/thumb_img/', ROOT_PATH . 'images/200905/thumb_img/'))
        {
            die(implode(',', $err->last_message()));
        }
        if (!copy_files(ROOT_PATH . 'demo/200905/source_img/', ROOT_PATH . 'images/200905/source_img/'))
        {
            die(implode(',', $err->last_message()));
        }
        if (!copy_files(ROOT_PATH . 'demo/afficheimg/', ROOT_PATH . 'data/afficheimg/'))
        {
            die(implode(',', $err->last_message()));
        }
        if (!copy_files(ROOT_PATH . 'demo/packimg/', ROOT_PATH . 'data/packimg/'))
        {
            die(implode(',', $err->last_message()));
        }
        if (!copy_files(ROOT_PATH . 'demo/cardimg/', ROOT_PATH . 'data/cardimg/'))
        {
            die(implode(',', $err->last_message()));
        }

    $result = array('msg'=>'OK', 'cur_ver'=>$cur_ver, 'needup_ver_list'=>$needup_ver_list);

    echo  $json->encode($result);

    break;

/* 获得某个SQL文件的SQL语句数 */
case 'get_record_number' :
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON();

    $next_ver = isset($_REQUEST['next_ver']) ? $_REQUEST['next_ver'] : '';
    $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';

    if ($next_ver === '' || $type === '')
    {
        die('EMPTY');
    }

    $result = array('msg'=>'OK', 'rec_num'=>get_record_number($next_ver, $type));
    echo  $json->encode($result);

    break;

/* 备份数据库 */
case 'dump_database' :
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON();

    $next_ver = isset($_REQUEST['next_ver']) ? $_REQUEST['next_ver'] : '';
    if ($next_ver === '')
    {
        die('EMPTY');
    }

    $result = dump_database($next_ver);

    if($result === false)
    {
        echo implode(',', $err->last_message());
    }
    else
    {
        echo 'OK';
    }

    break;
case 'rollback' :
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON();

    $next_ver = isset($_REQUEST['next_ver']) ? $_REQUEST['next_ver'] : '';
    if ($next_ver === '')
    {
        die('EMPTY');
    }

    $result = rollback($next_ver);

    if($result === false)
    {
        echo implode(',', $err->last_message());
    }
    else
    {
        echo 'OK';
    }

    break;

/* 升级文件 */
case 'update_files' :
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON();

    $next_ver = isset($_REQUEST['next_ver']) ? $_REQUEST['next_ver'] : '';

    if ($next_ver === '')
    {
        die('EMPTY');
    }

    $result = update_files($next_ver);
    echo  $json->encode($result);

    break;

/* 升级数据结构 */
case 'update_structure' :
    $next_ver = isset($_REQUEST['next_ver']) ? $_REQUEST['next_ver'] : '';
    $cur_pos = isset($_REQUEST['cur_pos']) ? $_REQUEST['cur_pos'] : '';

    if ($next_ver === '' || intval($cur_pos) < 1)
    {
        die('EMPTY');
    }

    $result = update_structure_automatically($next_ver, intval($cur_pos)-1);
    if ($result === false)
    {
        echo implode(',', $err->last_message());
    }
    else
    {
        echo 'OK';
    }

    break;

/* 升级数据 */
case 'update_data' :
    $next_ver = isset($_REQUEST['next_ver']) ? $_REQUEST['next_ver'] : '';

    if ($next_ver === '')
    {
        die('EMPTY');
    }

    update_database_optionally($next_ver);
    $result = update_data_automatically($next_ver);
    if ($result === false)
    {
        die(implode(',', $err->last_message()));
    }

    echo 'OK';

    break;

/* 更新版本号 */
case 'update_version' :
    $next_ver = isset($_REQUEST['next_ver']) ? $_REQUEST['next_ver'] : '';

    if ($next_ver === '')
    {
        die('EMPTY');
    }

    //update_version($next_ver);

    echo 'OK';

    break;

/* 成功页面 */
case 'done' :
    $ui = isset($_REQUEST['ui']) ? $_REQUEST['ui'] : 'ecshop';
    if ($ui == 'ucenter')
    {
        change_ucenter_config();
    }
    clear_all_files();
//    remove_ucenter_config();
//    remove_lang_config();

    $smarty->display('done.php');

    break;

/* 出错页面 */
case 'error' :
    $err_msg = implode(',', $err->get_all());
    if (empty($err_msg))
    {
        $err_msg = $_LANG['js_error'];
    }
    $smarty->assign('err_msg', $err_msg);
    $smarty->display('error.php');

    break;

/* 出现异常 */
default :
    die('ERROR, unknown step!');

}

/**
 * 把一个文件从一个目录复制到另一个目录
 *
 * @access  public
 * @param   string      $source    源目录
 * @param   string      $target    目标目录
 * @return  boolean     成功返回true，失败返回false
 */
function copy_files($source, $target)
{
    global $err, $_LANG;

    if (!file_exists($target))
    {
        //if (!mkdir(rtrim($target, '/'), 0777))
        if (!mkdir($target, 0777))
        {
            $err->add($_LANG['cannt_mk_dir']);
            return false;
        }
        @chmod($target, 0777);
    }

    $dir = opendir($source);
    while (($file = @readdir($dir)) !== false)
    {
        if (is_file($source . $file))
        {
            if (!copy($source . $file, $target . $file))
            {
                $err->add($_LANG['cannt_copy_file']);
                return false;
            }
            @chmod($target . $file, 0777);
        }
    }
    closedir($dir);

    return true;
}

/**
 * 安装数据
 *
 * @access  public
 * @param   array         $sql_files        SQL文件路径组成的数组
 * @return  boolean       成功返回true，失败返回false
 */
function install_data($sql_files)
{
    global $err;

    include(ROOT_PATH . 'data/config.php');
    include_once(ROOT_PATH . 'includes/cls_mysql.php');
    include_once(ROOT_PATH . 'includes/cls_sql_executor.php');

    $db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
    $se = new sql_executor($db, EC_DB_CHARSET, 'ecs_', $prefix);
    $result = $se->run_all($sql_files);

    return true;
}
?>
