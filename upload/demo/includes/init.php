<?php

define('IN_ECS', true);

/* 鎶ュ憡鎵€鏈夐敊璇 */
ini_set('display_errors',  1);
error_reporting(E_ALL ^ E_NOTICE);

/* 娓呴櫎鎵€鏈夊拰鏂囦欢鎿嶄綔鐩稿叧鐨勭姸鎬佷俊鎭 */
clearstatcache();

/* 瀹氫箟绔欑偣鏍 */
define('ROOT_PATH', str_replace('demo/includes/init.php', '', str_replace('\\', '/', __FILE__)));

require(ROOT_PATH . 'includes/lib_common.php');
@include(ROOT_PATH . 'includes/lib_base.php');
require(ROOT_PATH . 'admin/includes/lib_main.php');
require(ROOT_PATH . 'includes/lib_time.php');
clear_all_files();

/* 鍔犺浇鏁版嵁搴撻厤缃?枃浠 */
if (file_exists(ROOT_PATH . 'data/config.php'))
{
    include(ROOT_PATH . 'data/config.php');
}
elseif (file_exists(ROOT_PATH . 'includes/config.php'))
{
    if (!rename(ROOT_PATH . 'includes/config.php', ROOT_PATH . 'data/config.php'))
    {
        die('Can\'t move config.php, please move it from includes/ to data/ manually!');
    }
    include(ROOT_PATH . 'data/config.php');
}
else
{
    die('Can\'t find config.php!');
}

require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/cls_mysql.php');
/* 鍒涘缓 ECSHOP 瀵硅薄 */
$ecs = new ECS($db_name, $prefix);

/* 鐗堟湰瀛楃?闆嗗彉閲
$ec_version_charset = 'gbk';
*/

$mysql_charset = $ecshop_charset = '';
/* 鑷?姩鑾峰彇鏁版嵁琛ㄧ殑瀛楃?闆 */
$tmp_link = @mysql_connect($db_host, $db_user, $db_pass);
if (!$tmp_link)
{
    die("Can't pConnect MySQL Server($db_host)!");
}
else
{
    mysql_select_db($db_name);
    $query = mysql_query(" SHOW CREATE TABLE " . $ecs->table('users'), $tmp_link) or die(mysql_error());
    $tablestruct = mysql_fetch_row($query);
    preg_match("/CHARSET=(\w+)/", $tablestruct[1], $m);
    if (strpos($m[1], 'utf') === 0)
    {
        $mysql_charset = str_replace('utf', 'utf-', $m[1]);
    }
    else
    {
        $mysql_charset = $m[1];
    }
}
if (defined('EC_CHARSET'))
{
    $ecshop_charset = EC_CHARSET;
}
/*
if (empty($tmp_charset))
{
    $check_charset = false;
    $tmp_charset = 'gbk';
}
else
{
    $check_charset = true;
}
if (!defined('EC_CHARSET'))
{
    define('EC_CHARSET', $tmp_charset);
}

if ($ec_version_charset != EC_CHARSET)
{
    die('Database Charset not match!');
}
*/

/* 鍒濆?鍖栨暟鎹?簱绫 */
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name, $ecshop_charset);

/* 鍒涘缓閿欒?澶勭悊瀵硅薄 */
require(ROOT_PATH . 'includes/cls_error.php');
$err = new ecs_error('message.dwt');

require(ROOT_PATH . 'includes/cls_sql_executor.php');

/* 鍒濆?鍖栨ā鏉垮紩鎿 */
require(ROOT_PATH . 'demo/includes/cls_template.php');
$smarty = new template(ROOT_PATH . 'demo/templates/');

require(ROOT_PATH . 'demo/includes/lib_updater.php');

@set_time_limit(360);
?>
