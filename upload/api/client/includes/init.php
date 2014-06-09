<?php

/**
 * ECSHOP 前台公用文件
 * ============================================================================
 * 版权所有 (C) 2005-2011 康盛创想（北京）科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这是一个免费开源的软件；这意味着您可以在不用于商业目的的前提下对程序代码
 * 进行修改、使用和再发布。
 * ============================================================================
 * $Author: liubo $
 * $Date: 2011-01-19 14:29:08 +0800 (周三, 2011-01-19) $
 * $Id: init.php 17217 2011-01-19 06:29:08Z liubo $
*/

error_reporting(7);

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/* 取得当前client所在的根目录 */
define('CLIENT_PATH', substr(__FILE__, 0, -17));

/* 设置maifou.net所在的根目录 */
define('ROOT_PATH', substr(__FILE__, 0, -28));

$php_self = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
if ('/' == substr($php_self, -1))
{
    $php_self .= 'index.php';
}
define('PHP_SELF', $php_self);

// 通用包含文件
require(ROOT_PATH . 'data/config.php');
require(ROOT_PATH . 'includes/lib_common.php');
require(ROOT_PATH . 'includes/cls_mysql.php');
/* 兼容ECShopV2.5.1版本载入库文件 */
if (!function_exists('addslashes_deep'))
{
    require(ROOT_PATH . 'includes/lib_base.php');
}
require(CLIENT_PATH . 'includes/lib_api.php');    // API库文件
require(CLIENT_PATH . 'includes/lib_struct.php'); // 结构库文件

// json类文件
require(ROOT_PATH . 'includes/cls_json.php');

/* 对用户传入的变量进行转义操作。*/
if (!get_magic_quotes_gpc())
{
    $_COOKIE   = addslashes_deep($_COOKIE);
}

/* 兼容ECShopV2.5.1版本 */
if (!defined('EC_CHARSET'))
{
    define('EC_CHARSET', 'utf-8');
}

/* 初始化JSON对象 */
$json = new JSON;

/* 分析JSON数据 */
parse_json($json, $_POST['Json']);

/* 初始化包含文件 */
require(ROOT_PATH . 'includes/inc_constant.php');
require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/lib_time.php');
require(ROOT_PATH . 'includes/lib_main.php');
require(ROOT_PATH . 'includes/lib_insert.php');
require(ROOT_PATH . 'includes/lib_goods.php');

/* 创建 ECSHOP 对象 */
$ecs = new ECS($db_name, $prefix);

/* 初始化数据库类 */
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
$db->set_disable_cache_tables(array($ecs->table('sessions'), $ecs->table('sessions_data'), $ecs->table('cart')));
$db_host = $db_user = $db_pass = $db_name = NULL;

/* 载入系统参数 */
$_CFG = load_config();

/* 载入语言包 */
require(ROOT_PATH.'languages/' .$_CFG['lang']. '/admin/common.php');
require(ROOT_PATH.'languages/' .$_CFG['lang']. '/admin/log_action.php');

/* 初始化session */
include(ROOT_PATH . 'includes/cls_session.php');

$sess = new cls_session($db, $ecs->table('sessions'), $ecs->table('sessions_data'), 'CL_ECSCP_ID');

define('SESS_ID', $sess->get_session_id());

/* 判断是否登录了 */
if ((!isset($_SESSION['admin_id']) || intval($_SESSION['admin_id']) <= 0) && ($_POST['Action'] != 'UserLogin'))
{
    client_show_message(110);
}

if ($_CFG['shop_closed'] == 1)
{
    /* 商店关闭了，输出关闭的消息 */
    client_show_message(105);
}

?>