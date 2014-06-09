<?php

/**
 * ECSHOP 鍗囩骇绋嬪簭 涔 妯″瀷
 * ============================================================================
 * * 鐗堟潈鎵€鏈 2005-2012 涓婃捣鍟嗘淳缃戠粶绉戞妧鏈夐檺鍏?徃锛屽苟淇濈暀鎵€鏈夋潈鍒┿€
 * 缃戠珯鍦板潃: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 杩欎笉鏄?竴涓?嚜鐢辫蒋浠讹紒鎮ㄥ彧鑳藉湪涓嶇敤浜庡晢涓氱洰鐨勭殑鍓嶆彁涓嬪?绋嬪簭浠ｇ爜杩涜?淇?敼鍜
 * 浣跨敤锛涗笉鍏佽?瀵圭▼搴忎唬鐮佷互浠讳綍褰㈠紡浠讳綍鐩?殑鐨勫啀鍙戝竷銆
 * ============================================================================
 * $Author: liubo $
 * $Date: 2009-12-14 17:22:19 +0800 (涓€, 2009-12-14) $
 * $Id: lib_updater.php 16882 2009-12-14 09:22:19Z liubo $
 */

/**
 * 鑾峰緱闇€瑕佸崌绾х殑鐗堟湰鍙峰垪琛ㄣ€
 * @param   string      $old_version    鏃х増鏈?彿
 * @param   string      $new_version    鏂扮増鏈?彿
 * @return  array
 */
function get_needup_version_list($old_version, $new_version)
{
    /* 闇€瑕佸崌绾х殑鐗堟湰鍙峰垪琛 */
    $old_version = explode(' ',$old_version);
    $old_version = $old_version[0];
    $need_list = array();
    $need = false;
    $version_history = read_version_history();

    foreach ($version_history as $version)
    {
        if ($need)
        {
            $need_list[] = $version;
            if ($version == $new_version)
            {
                $need = false;
            }
        }
        else
        {
            if ($version == $old_version)
            {
                $need = true;
            }
        }
    }

    return $need_list;
}

/**
 * 璇诲彇鐗堟湰鍘嗗彶璁板綍锛屽苟鎸夊瓧鍏稿簭鎺掑簭銆
 * @return  array
 */
function read_version_history()
{
    $ver_history = array('v2.0.5');
    $pkg_root = ROOT_PATH . 'demo/packages/';
    $ver_handle = @opendir($pkg_root);
    while (($filename = @readdir($ver_handle)) !== false)
    {
        $filepath = $pkg_root . $filename;
        if(is_dir($filepath) && strpos($filename, '.') !== 0)
        {
            $ver_history[] = $filename;
        }
    }
    asort($ver_history);

    return $ver_history;
}

/**
 * 鑾峰緱鍘熸湁绯荤粺鐨勮?瑷€銆
 * @return  mixed       鎴愬姛杩斿洖鍏蜂綋鐨勮?瑷€锛屽け璐ヨ繑鍥瀎alse銆
 */
function  get_current_lang()
{
    global $db, $ecs;

    $lang = $db->getOne('SELECT value FROM ' . $ecs->table('shop_config') . " WHERE code = 'lang'");
    $lang = $lang ? $lang : false;

    return $lang;
}

/**
 * 鑾峰緱鏈€鏂扮殑鐗堟湰鍙枫€
 * @return  string
 */
function get_new_version()
{
    return  preg_replace('/(?:\.|\s+)[a-z]*$/i', '', VERSION);
}

/**
 * 鑾峰緱鍘熸湁绯荤粺鐨勭増鏈?彿銆
 * @return  string
 */
function  get_current_version()
{
    global $db, $ecs;

    $ver = $db->getOne('SELECT value FROM ' . $ecs->table('shop_config') . " WHERE code = 'ecs_version'");
    $ver = $ver ? $ver : 'v2.0.5';
    $ver = preg_replace('/\.[a-z]*$/i', '', $ver);

    return $ver;
}

/**
 * 鑾峰緱鏌愪釜SQL鏂囦欢鐨勮?褰曟暟(SQL璇?彞鏁伴噺)銆
 * @return  int
 */
function get_record_number($next_ver, $type)
{
    global $db, $prefix;

    $file_path = ROOT_PATH . 'demo/packages/' . $next_ver . '/' . $type . '.sql';
    $db_charset = strtolower((str_replace('-', '', EC_CHARSET)));
    $se = new sql_executor($db, $db_charset, 'ecs_', $prefix);

    $query_items = $se->parse_sql_file($file_path);

    if(empty($query_items))
    {
        return 0;
    }

    return count($query_items);
}

/**
 * 鑾峰緱閰嶇疆淇℃伅銆
 * @return  array
 */
function get_config_info()
{
    global $_LANG;
    $config = array();

    $config['config_path'] = array($_LANG['config_path'], '/data/config.php');
    $config['db_host'] = array($_LANG['db_host'], $GLOBALS['db_host']);
    $config['db_name'] = array($_LANG['db_name'], $GLOBALS['db_name']);
    $config['db_user'] = array($_LANG['db_user'], $GLOBALS['db_user']);
    $config['db_pass'] = array($_LANG['db_pass'], '*******');
    $config['prefix'] = array($_LANG['db_prefix'], $GLOBALS['prefix']);
    if (isset($GLOBALS['timezone']))
    {
        $config['timezone'] = array($_LANG['timezone'], $GLOBALS['timezone']);
    }
    if (isset($GLOBALS['cookie_path']))
    {
        $config['cookie_path'] = array($_LANG['cookie_path'], $GLOBALS['cookie_path']);
    }
    if (isset($GLOBALS['admin_dir']))
    {
        $config['admin_dir'] = array($_LANG['admin_dir'], $GLOBALS['admin_dir']);
    }

    return $config;
}

/**
 * 鍒涘缓鐗堟湰瀵硅薄銆
 * @return  mixed   鎴愬姛杩斿洖鐗堟湰瀵硅薄锛屽け璐ヨ繑鍥瀎alse銆
 */
function create_ver_obj($version)
{
    global $err, $_LANG;

    $file_path = ROOT_PATH . 'demo/packages/' . $version . '/' . $version . '.php';
    if (file_exists($file_path))
    {
        include_once($file_path);

        // 鎶 . 鏇挎崲鎴 _锛屾妸绌烘牸鍘绘帀锛屽墠闈㈠姞 up_
        $classname = 'up_' . str_replace('.', '_', str_replace(' ', '', $version));
        $ver_obj = new $classname();

        return $ver_obj;
    }
    else
    {
        $err->add($_LANG['create_ver_failed']);

        return false;
    }
}

/**
 * 鏈烘?鍖栧湴鍗囩骇鏁版嵁搴撶粨鏋勩€
 * @return  boolean
 */
function update_structure_automatically($next_ver, $cur_pos)
{
    global $db, $prefix, $err;

    $ver_obj = create_ver_obj($next_ver);
    if (!is_object($ver_obj) || empty($ver_obj->sql_files['structure']))
    {
        return true;
    }

    $structure_path = ROOT_PATH . 'demo/packages/' . $next_ver . '/' . $ver_obj->sql_files['structure'];
    $db_charset = strtolower((str_replace('-', '', EC_CHARSET)));
    $se = new sql_executor($db, $db_charset, 'ecs_', $prefix,
            ROOT_PATH . 'data/demo_'.$next_ver.'.log',
            $ver_obj->auto_match, array(1062, 1146));

    $query_item = $se->get_spec_query_item($structure_path, $cur_pos);
    $se->query($query_item);

    if (!empty($se->error))
    {
        $err->add($se->error);
        return false;
    }

    return true;
}

/**
 * 鏈烘?鍖栧湴鍗囩骇鏁版嵁搴撴暟鎹?€
 * @return  boolean
 */
function update_data_automatically($next_ver)
{
    global $db, $ecs, $prefix, $err;

    $ver_obj = create_ver_obj($next_ver);
    if (!is_object($ver_obj) || empty($ver_obj->sql_files['data']))
    {
        return true;
    }

    $db_charset = strtolower((str_replace('-', '', EC_CHARSET)));
    $se = new sql_executor($db, $db_charset, 'ecs_', $prefix,
            ROOT_PATH . 'data/demo_'.$next_ver.'.log',
            $ver_obj->auto_match, array(1062, 1146));

    $data_path = '';
    $ver_root = ROOT_PATH . 'demo/packages/' . $next_ver . '/';
    if (is_array($ver_obj->sql_files['data']))
    {
        $lang = EC_LANGUAGE . '_' . EC_CHARSET;
        if (!isset($ver_obj->sql_files['data'][$lang]))
        {
           $lang = 'zh_cn_utf-8';
        }
        $data_path = $ver_root . $ver_obj->sql_files['data'][$lang];
    }
    else
    {
        $data_path =  $ver_root . $ver_obj->sql_files['data'];
    }
    $se->run_all(array($data_path));

    if (!empty($se->error))
    {
        $err->add($se->error);
        return false;
    }

    return true;
}

/**
 * 闅忓績鎵€娆插湴鍗囩骇鏁版嵁搴撱€
 * @return  boolean
 */
function update_database_optionally($next_ver)
{
    $ver_obj = create_ver_obj($next_ver);
    if ($ver_obj === false)
    {
        return false;
    }

    $ver_obj->update_database_optionally();

    return true;
}

/**
 * 鍗囩骇鏂囦欢銆
 * @return  array
 */
function update_files($next_ver)
{
    global $err;

    $ver_obj = create_ver_obj($next_ver);
    if ($ver_obj === false)
    {
        return array('msg'=>'OK');
    }

    $result = $ver_obj->update_files();
    if ($result === false)
    {
        $msg = $err->last_message();
        if (is_array($msg)
                && isset($msg['type'])
                && $msg['type'] === 'NOTICE')
        {
            return array('type'=>'NOTICE', 'msg'=>$msg);
        }
    }

    return array('msg'=>'OK');
}

/**
 * 鍗囩骇鐗堟湰銆
 * @return  void
 */
function update_version($next_ver)
{
    global $db, $ecs;

    $db->query('UPDATE ' . $ecs->table('shop_config') . "  SET value='$next_ver' WHERE code='ecs_version'");
}

function dump_database($next_ver)
{
    global $db, $err, $prefix;

    include_once(ROOT_PATH . 'admin/includes/cls_sql_dump.php');
    require_once(ROOT_PATH . 'demo/packages/' . $next_ver . '/dump_table.php');

    /* 澶囦唤琛ㄤ负绌烘椂涓嶄綔澶囦唤锛岃繑鍥炵湡 */
    if (empty($temp))
    {
        return true;
    }
    @set_time_limit(300);

    $dump = new cls_sql_dump($db);
    $run_log = ROOT_PATH . 'data/sqldata/run.log';
    $sql_file_name = $next_ver;
    $max_size = '2048';
    $vol = 1;

    /* 鍙橀噺楠岃瘉 */
    $allow_max_size = intval(@ini_get('upload_max_filesize')); //鍗曚綅M
    if ($allow_max_size > 0 && $max_size > ($allow_max_size * 1024))
    {
        $max_size = $allow_max_size * 1024; //鍗曚綅K
    }

    if ($max_size > 0)
    {
        $dump->max_size = $max_size * 1024;
    }

    $tables = array();
    foreach ($temp AS $table)
    {
        $tables[$prefix . $table] = -1;
    }

    $dump->put_tables_list($run_log, $tables);

    /* 寮€濮嬪?浠 */
    $tables = $dump->dump_table($run_log, $vol);

    if ($tables === false)
    {
        $err->add($dump->errorMsg());
        return false;
    }

    if(@file_put_contents(ROOT_PATH . 'data/sqldata/' . $sql_file_name . '.sql', $dump->dump_sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function rollback($next_ver)
{
    global $db, $prefix, $err;

    $structure_path[] = ROOT_PATH . 'data/sqldata/' . $next_ver . '.sql';

    if(!file_exists($structure_path[0]))
    {
        return false;
    }

    $db_charset = strtolower((str_replace('-', '', EC_CHARSET)));
    $se = new sql_executor($db, $db_charset, 'ecs_', $prefix);
    $result = $se->run_all($structure_path);
    if ($result === false)
    {
        $err->add($se->error);
        return false;
    }

    return true;
}


/**
 * 鑾峰緱 ECSHOP 褰撳墠鐜??鐨 HTTP 鍗忚?鏂瑰紡
 *
 * @access  public
 *
 * @return  void
 */
function http()
{
    return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}

/**
 * 鍙栧緱褰撳墠鐨勫煙鍚
 *
 * @access  public
 *
 * @return  string      褰撳墠鐨勫煙鍚
 */
function get_domain()
{
    /* 鍗忚? */
    $protocol = http();

    /* 鍩熷悕鎴朓P鍦板潃 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        /* 绔?彛 */
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
            {
                $port = '';
            }
        }
        else
        {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'] . $port;
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host;
}

/**
 * 鑾峰緱 ECSHOP 褰撳墠鐜??鐨 URL 鍦板潃
 *
 * @access  public
 *
 * @return  void
 */
function url()
{
    define(PHP_SELF, $_SERVER['PHP_SELF']);
    $curr = strpos(PHP_SELF, 'demo/') !== false ?
            preg_replace('/(.*)(demo)(\/?)(.)*/i', '\1', dirname(PHP_SELF)) :
            dirname(PHP_SELF);

    $root = str_replace('\\', '/', $curr);

    if (substr($root, -1) != '/')
    {
        $root .= '/';
    }

    return get_domain() . $root;
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
        $return = '';
        $matches = parse_url($url);
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'].'?'.$matches['query'].'#'.$matches['fragment'] : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;

        if($post) {
            $out = "POST $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: '.strlen($post)."\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.0\r\n";
            $out .= "Accept: */*\r\n";
            //$out .= "Referer: $boardurl\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }
        $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        if(!$fp) {
            return '';//note $errstr : $errno \r\n
        } else {
            stream_set_blocking($fp, $block);
            stream_set_timeout($fp, $timeout);
            @fwrite($fp, $out);
            $status = stream_get_meta_data($fp);
            if(!$status['timed_out']) {
                while (!feof($fp)) {
                    if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
                        break;
                    }
                }

                $stop = false;
                while(!feof($fp) && !$stop) {
                    $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
                    $return .= $data;
                    if($limit) {
                        $limit -= strlen($data);
                        $stop = $limit <= 0;
                    }
                }
            }
            @fclose($fp);
            return $return;
        }
    }

function write_charset_config($lang, $charset)
{
    $config_file = ROOT_PATH . 'data/config.php';
    $s = file_get_contents($config_file);
    $s = insertconfig($s, "/\?\>/","");
    $s = insertconfig($s, "/define\('EC_LANGUAGE',\s*'.*?'\);/i", "define('EC_LANGUAGE', '" . $lang . "');");
    $s = insertconfig($s, "/define\('EC_CHARSET',\s*'.*?'\);/i", "define('EC_CHARSET', '" . $charset . "');");
    $s = insertconfig($s, "/\?\>/","?>");
    return file_put_contents($config_file, $s);
}

function remove_lang_config()
{
    $config_file = ROOT_PATH . 'data/config.php';
    $s = file_get_contents($config_file);
    $s = insertconfig($s, "/\?\>/", "");
    $s = insertconfig($s, "/define\('EC_LANGUAGE',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/\?\>/", "?>");
    return file_put_contents($config_file, $s);
}

function change_ucenter_config()
{
    global $db, $ecs;
    $config_file = ROOT_PATH . 'data/config.php';
    @include ($config_file);
    if (defined('UC_CONNECT'))
    {
        $cfg = array(
            'uc_id' => UC_APPID,
            'uc_key' => UC_KEY,
            'uc_url' => UC_API,
            'uc_ip' => UC_IP,
            'uc_connect' => UC_CONNECT,
            'uc_charset' => UC_CHARSET,
            'db_host' => UC_DBHOST,
            'db_user' => UC_DBUSER,
            'db_name' => UC_DBNAME,
            'db_pass' => UC_DBPW,
            'db_pre' => UC_DBTABLEPRE,
            'db_charset' => UC_DBCHARSET,
        );
        $db->query('UPDATE ' . $ecs->table('shop_config') . "  SET value='ucenter' WHERE code='integrate_code'");
        $db->query('UPDATE ' . $ecs->table('shop_config') . "  SET value='". serialize($cfg) ."' WHERE code='integrate_config'");
    }
    return true;
}

function remove_ucenter_config()
{
    global $db, $ecs;
    $config_file = ROOT_PATH . 'data/config.php';
    $s = file_get_contents($config_file);
    $s = insertconfig($s, "/\?\>/", "");
    $s = insertconfig($s, "/\/\*\=*UCenter\=*\*\//i", "");
    $s = insertconfig($s, "/define\('UC_CONNECT',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBHOST',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBUSER',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBPW',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBNAME',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_DBCONNECT',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_KEY',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_API',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_CHARSET',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_IP',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_APPID',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/define\('UC_PPP',\s*'.*?'\);/i", "");
    $s = insertconfig($s, "/\?\>/", "?>");
    return file_put_contents($config_file, $s);
}

function save_uc_config($config)
{
    global $db, $ecs;
    $success = false;

    list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $config);

    $config_file = ROOT_PATH . 'data/config.php';
    $s = file_get_contents($config_file);
    $s = insertconfig($s, "/\?\>/","");

    $link = mysql_connect($ucdbhost, $ucdbuser, $ucdbpw, 1);
    $uc_connnect = $link && mysql_select_db($ucdbname, $link) ? 'mysql' : 'post';
    $s = insertconfig($s, "/define\('EC_CHARSET',\s*'.*?'\);/i", "define('EC_CHARSET', '" . EC_CHARSET . "');");

    $s = insertconfig($s, "/\/\*\=*UCenter\=*\*\//","/*=================UCenter=======================*/");
    $s = insertconfig($s, "/define\('UC_CONNECT',\s*'.*?'\);/i", "define('UC_CONNECT', '$uc_connnect');");
    $s = insertconfig($s, "/define\('UC_DBHOST',\s*'.*?'\);/i", "define('UC_DBHOST', '$ucdbhost');");
    $s = insertconfig($s, "/define\('UC_DBUSER',\s*'.*?'\);/i", "define('UC_DBUSER', '$ucdbuser');");
    $s = insertconfig($s, "/define\('UC_DBPW',\s*'.*?'\);/i", "define('UC_DBPW', '$ucdbpw');");
    $s = insertconfig($s, "/define\('UC_DBNAME',\s*'.*?'\);/i", "define('UC_DBNAME', '$ucdbname');");
    $s = insertconfig($s, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "define('UC_DBCHARSET', '$ucdbcharset');");
    $s = insertconfig($s, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "define('UC_DBTABLEPRE', '`$ucdbname`.$uctablepre');");
    $s = insertconfig($s, "/define\('UC_DBCONNECT',\s*'.*?'\);/i", "define('UC_DBCONNECT', '0');");
    $s = insertconfig($s, "/define\('UC_KEY',\s*'.*?'\);/i", "define('UC_KEY', '$appauthkey');");
    $s = insertconfig($s, "/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$ucapi');");
    $s = insertconfig($s, "/define\('UC_CHARSET',\s*'.*?'\);/i", "define('UC_CHARSET', '$uccharset');");
    $s = insertconfig($s, "/define\('UC_IP',\s*'.*?'\);/i", "define('UC_IP', '$ucip');");
    $s = insertconfig($s, "/define\('UC_APPID',\s*'?.*?'?\);/i", "define('UC_APPID', '$appid');");
    $s = insertconfig($s, "/define\('UC_PPP',\s*'?.*?'?\);/i", "define('UC_PPP', '20');");
    $s = insertconfig($s, "/\?\>/","?>");

    return file_put_contents($config_file, $s);
}

function insertconfig($s, $find, $replace)
{
    if(preg_match($find, $s))
    {
        $s = preg_replace($find, $replace, $s);
    }
    else
    {
        // 鎻掑叆鍒版渶鍚庝竴琛
        $s .= "\r\n".$replace;
    }
    return $s;
}

?>
