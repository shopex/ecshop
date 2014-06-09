<?php

/**
 * ECSHOP 自动安装程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: testyang $
 * $Id: index.php 15013 2008-10-23 09:31:42Z testyang $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/auto_init.php');
require_once('../includes/cls_json.php');

/* 初始化语言变量 */
$installer_lang = isset($_REQUEST['lang']) ? trim($_REQUEST['lang']) : 'zh_cn';

if ($installer_lang != 'zh_cn' && $installer_lang != 'zh_tw' && $installer_lang != 'en_us')
{
    $installer_lang = 'zh_cn';
}

/* 加载安装程序所使用的语言包 */
$installer_lang_package_path = ROOT_PATH . 'install/languages/' . $installer_lang . '.php';
if (file_exists($installer_lang_package_path))
{
    include_once($installer_lang_package_path);
    $smarty->assign('lang', $_LANG);
}
else
{
    die('Can\'t find language package!');
}

/* 初始化流程控制变量 */
if (file_exists(ROOT_PATH . 'data/install.lock'))
{
    data_back($_LANG['has_locked_installer']);
}

    include_once(ROOT_PATH . 'install/includes/lib_env_checker.php');
    include_once(ROOT_PATH . 'install/includes/checking_dirs.php');
    $dir_checking = check_dirs_priv($checking_dirs);

    $templates_root = array(
        'dwt' => ROOT_PATH . 'themes/default/',
        'lbi' => ROOT_PATH . 'themes/default/library/');
    $template_checking = check_templates_priv($templates_root);

    $rename_priv = check_rename_priv();

    $disabled = '';
    if ($dir_checking['result'] === 'ERROR'
            || !empty($template_checking)
            || !empty($rename_priv)
            || !function_exists('mysql_connect'))
    {
        data_back('安装目录的某些权限不够');
    }


    $db_host    = isset($_POST['dbhost'])      ?   trim($_POST['dbhost']) : '';
    $db_port    = isset($_POST['db_port'])      ?   trim($_POST['db_port']) : '';
    $db_user    = isset($_POST['dbuser'])      ?   trim($_POST['dbuser']) : '';
    $db_pass    = isset($_POST['dbpass'])      ?   trim($_POST['dbpass']) : '';
    $db_name    = isset($_POST['dbname'])      ?   trim($_POST['dbname']) : '';
    $prefix     = isset($_POST['db_prefix'])    ?   trim($_POST['db_prefix']) : 'ecs_';
    $timezone   = isset($_POST['timezone'])     ?   trim($_POST['timezone']) : 'Asia/Shanghai';

    if (empty($db_host) || empty($db_user) || empty($db_pass) || empty($db_name))
    {
        data_back('缺少必要的参数');
    }

    $result = create_config_file($db_host, $db_port, $db_user, $db_pass, $db_name, $prefix,  $timezone);
    if ($result === false)
    {
        data_back('构建配置文件失败');
    }


    $result = create_database($db_host, $db_port, $db_user, $db_pass, $db_name);
    if ($result === false)
    {
        data_back('创建数据库失败');
    }


    $system_lang = isset($_POST['system_lang']) ? $_POST['system_lang'] : 'zh_cn';

    if (file_exists(ROOT_PATH . 'install/data/data_' . $system_lang . '.sql'))
    {
        $data_path = ROOT_PATH . 'install/data/data_' . $system_lang . '.sql';
    }
    else
    {
        $data_path = ROOT_PATH . 'install/data/data_zh_cn.sql';
    }

    $sql_files = array(
        ROOT_PATH . 'install/data/structure.sql',
        $data_path
    );

    $result = install_data($sql_files);

    if ($result === false)
    {
        data_back('构建数据库内容失败');
    }


    $admin_name         = isset($_POST['admin_name'])       ? json_str_iconv(trim($_POST['admin_name'])) : 'admin';
    $admin_password     = isset($_POST['password'])   ? trim($_POST['password']) : '549c6dd086d5c7127745';  //ecshop123654
    $admin_password2    = isset($_POST['admin_password2'])  ? trim($_POST['admin_password2']) : '549c6dd086d5c7127745';
    $admin_email        = isset($_POST['admin_email'])      ? trim($_POST['admin_email']) : '';

    $result = create_admin_passport($admin_name, $admin_password,
            $admin_password2, $admin_email);
    if ($result === false)
    {
        data_back('创建管理员失败');
    }


    $system_lang = isset($_POST['system_lang'])     ? $_POST['system_lang'] : 'zh_cn';
    $captcha = isset($_POST['disable_captcha'])     ? intval($_POST['disable_captcha']) : '0';
    $install_demo = isset($_POST['data'])           ? $_POST['data'] : 1;
    $integrate = isset($_POST['userinterface'])   ? trim($_POST['userinterface']) : 'ecshop';
    $goods_types = empty($install_demo)     ? array() : array('book','book','movie','mobile','notebook','dc','dv','cosmetics','mobile2');

    $result = do_others($system_lang, $captcha, $goods_types, $install_demo, $integrate);
    if ($result === false)
    {
        data_back('其他安装过程错误');
    }


    $result = deal_aftermath();
    if ($result === false)
    {
        data_back('善后处理失败');
    }
    else
    {
        @unlink(ROOT_PATH .'data/config_temp.php');
        data_back('install succ', 'true');
    }

function data_back($msg, $result = 'false')
{
    $data_arr = array('res'=>$result, 'rsp'=>$msg);

    $json  = new JSON;
    die($json->encode($data_arr));    //把生成的返回字符串打印出来
}

?>