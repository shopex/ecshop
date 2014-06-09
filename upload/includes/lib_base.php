<?php

/**
 * ECSHOP 基础函数库
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_base.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * 截取UTF-8编码下字符串的函数
 *
 * @param   string      $str        被截取的字符串
 * @param   int         $length     截取的长度
 * @param   bool        $append     是否附加省略号
 *
 * @return  string
 */
function sub_str($str, $length = 0, $append = true)
{
    $str = trim($str);
    $strlength = strlen($str);

    if ($length == 0 || $length >= $strlength)
    {
        return $str;
    }
    elseif ($length < 0)
    {
        $length = $strlength + $length;
        if ($length < 0)
        {
            $length = $strlength;
        }
    }

    if (function_exists('mb_substr'))
    {
        $newstr = mb_substr($str, 0, $length, EC_CHARSET);
    }
    elseif (function_exists('iconv_substr'))
    {
        $newstr = iconv_substr($str, 0, $length, EC_CHARSET);
    }
    else
    {
        //$newstr = trim_right(substr($str, 0, $length));
        $newstr = substr($str, 0, $length);
    }

    if ($append && $str != $newstr)
    {
        $newstr .= '...';
    }

    return $newstr;
}

/**
 * 获得用户的真实IP地址
 *
 * @access  public
 * @return  string
 */
function real_ip()
{
    static $realip = NULL;

    if ($realip !== NULL)
    {
        return $realip;
    }

    if (isset($_SERVER))
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr AS $ip)
            {
                $ip = trim($ip);

                if ($ip != 'unknown')
                {
                    $realip = $ip;

                    break;
                }
            }
        }
        elseif (isset($_SERVER['HTTP_CLIENT_IP']))
        {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        }
        else
        {
            if (isset($_SERVER['REMOTE_ADDR']))
            {
                $realip = $_SERVER['REMOTE_ADDR'];
            }
            else
            {
                $realip = '0.0.0.0';
            }
        }
    }
    else
    {
        if (getenv('HTTP_X_FORWARDED_FOR'))
        {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif (getenv('HTTP_CLIENT_IP'))
        {
            $realip = getenv('HTTP_CLIENT_IP');
        }
        else
        {
            $realip = getenv('REMOTE_ADDR');
        }
    }

    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

    return $realip;
}

/**
 * 计算字符串的长度（汉字按照两个字符计算）
 *
 * @param   string      $str        字符串
 *
 * @return  int
 */
function str_len($str)
{
    $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));

    if ($length)
    {
        return strlen($str) - $length + intval($length / 3) * 2;
    }
    else
    {
        return strlen($str);
    }
}

/**
 * 获得用户操作系统的换行符
 *
 * @access  public
 * @return  string
 */
function get_crlf()
{
/* LF (Line Feed, 0x0A, \N) 和 CR(Carriage Return, 0x0D, \R) */
    if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win'))
    {
        $the_crlf = '\r\n';
    }
    elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac'))
    {
        $the_crlf = '\r'; // for old MAC OS
    }
    else
    {
        $the_crlf = '\n';
    }

    return $the_crlf;
}

/**
 * 邮件发送
 *
 * @param: $name[string]        接收人姓名
 * @param: $email[string]       接收人邮件地址
 * @param: $subject[string]     邮件标题
 * @param: $content[string]     邮件内容
 * @param: $type[int]           0 普通邮件， 1 HTML邮件
 * @param: $notification[bool]  true 要求回执， false 不用回执
 *
 * @return boolean
 */
function send_mail($name, $email, $subject, $content, $type = 0, $notification=false)
{
    /* 如果邮件编码不是EC_CHARSET，创建字符集转换对象，转换编码 */
    if ($GLOBALS['_CFG']['mail_charset'] != EC_CHARSET)
    {
        $name      = ecs_iconv(EC_CHARSET, $GLOBALS['_CFG']['mail_charset'], $name);
        $subject   = ecs_iconv(EC_CHARSET, $GLOBALS['_CFG']['mail_charset'], $subject);
        $content   = ecs_iconv(EC_CHARSET, $GLOBALS['_CFG']['mail_charset'], $content);
        $shop_name = ecs_iconv(EC_CHARSET, $GLOBALS['_CFG']['mail_charset'], $GLOBALS['_CFG']['shop_name']);
    }
    $charset   = $GLOBALS['_CFG']['mail_charset'];
    /**
     * 使用mail函数发送邮件
     */
    if ($GLOBALS['_CFG']['mail_service'] == 0 && function_exists('mail'))
    {
        /* 邮件的头部信息 */
        $content_type = ($type == 0) ? 'Content-Type: text/plain; charset=' . $charset : 'Content-Type: text/html; charset=' . $charset;
        $headers = array();
        $headers[] = 'From: "' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?='.'" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
        $headers[] = $content_type . '; format=flowed';
        if ($notification)
        {
            $headers[] = 'Disposition-Notification-To: ' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?='.'" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
        }

        $res = @mail($email, '=?' . $charset . '?B?' . base64_encode($subject) . '?=', $content, implode("\r\n", $headers));

        if (!$res)
        {
            $GLOBALS['err'] ->add($GLOBALS['_LANG']['sendemail_false']);

            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     * 使用smtp服务发送邮件
     */
    else
    {
        /* 邮件的头部信息 */
        $content_type = ($type == 0) ?
            'Content-Type: text/plain; charset=' . $charset : 'Content-Type: text/html; charset=' . $charset;
        $content   =  base64_encode($content);

        $headers = array();
        $headers[] = 'Date: ' . gmdate('D, j M Y H:i:s') . ' +0000';
        $headers[] = 'To: "' . '=?' . $charset . '?B?' . base64_encode($name) . '?=' . '" <' . $email. '>';
        $headers[] = 'From: "' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?='.'" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
        $headers[] = 'Subject: ' . '=?' . $charset . '?B?' . base64_encode($subject) . '?=';
        $headers[] = $content_type . '; format=flowed';
        $headers[] = 'Content-Transfer-Encoding: base64';
        $headers[] = 'Content-Disposition: inline';
        if ($notification)
        {
            $headers[] = 'Disposition-Notification-To: ' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?='.'" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
        }

        /* 获得邮件服务器的参数设置 */
        $params['host'] = $GLOBALS['_CFG']['smtp_host'];
        $params['port'] = $GLOBALS['_CFG']['smtp_port'];
        $params['user'] = $GLOBALS['_CFG']['smtp_user'];
        $params['pass'] = $GLOBALS['_CFG']['smtp_pass'];

        if (empty($params['host']) || empty($params['port']))
        {
            // 如果没有设置主机和端口直接返回 false
            $GLOBALS['err'] ->add($GLOBALS['_LANG']['smtp_setting_error']);

            return false;
        }
        else
        {
            // 发送邮件
            if (!function_exists('fsockopen'))
            {
                //如果fsockopen被禁用，直接返回
                $GLOBALS['err']->add($GLOBALS['_LANG']['disabled_fsockopen']);

                return false;
            }

            include_once(ROOT_PATH . 'includes/cls_smtp.php');
            static $smtp;

            $send_params['recipients'] = $email;
            $send_params['headers']    = $headers;
            $send_params['from']       = $GLOBALS['_CFG']['smtp_mail'];
            $send_params['body']       = $content;

            if (!isset($smtp))
            {
                $smtp = new smtp($params);
            }

            if ($smtp->connect() && $smtp->send($send_params))
            {
                return true;
            }
            else
            {
                $err_msg = $smtp->error_msg();
                if (empty($err_msg))
                {
                    $GLOBALS['err']->add('Unknown Error');
                }
                else
                {
                    if (strpos($err_msg, 'Failed to connect to server') !== false)
                    {
                        $GLOBALS['err']->add(sprintf($GLOBALS['_LANG']['smtp_connect_failure'], $params['host'] . ':' . $params['port']));
                    }
                    else if (strpos($err_msg, 'AUTH command failed') !== false)
                    {
                        $GLOBALS['err']->add($GLOBALS['_LANG']['smtp_login_failure']);
                    }
                    elseif (strpos($err_msg, 'bad sequence of commands') !== false)
                    {
                        $GLOBALS['err']->add($GLOBALS['_LANG']['smtp_refuse']);
                    }
                    else
                    {
                        $GLOBALS['err']->add($err_msg);
                    }
                }

                return false;
            }
        }
    }
}

/**
 * 获得服务器上的 GD 版本
 *
 * @access      public
 * @return      int         可能的值为0，1，2
 */
function gd_version()
{
    include_once(ROOT_PATH . 'includes/cls_image.php');

    return cls_image::gd_version();
}

if (!function_exists('file_get_contents'))
{
    /**
     * 如果系统不存在file_get_contents函数则声明该函数
     *
     * @access  public
     * @param   string  $file
     * @return  mix
     */
    function file_get_contents($file)
    {
        if (($fp = @fopen($file, 'rb')) === false)
        {
            return false;
        }
        else
        {
            $fsize = @filesize($file);
            if ($fsize)
            {
                $contents = fread($fp, $fsize);
            }
            else
            {
                $contents = '';
            }
            fclose($fp);

            return $contents;
        }
    }
}

if (!function_exists('file_put_contents'))
{
    define('FILE_APPEND', 'FILE_APPEND');

    /**
     * 如果系统不存在file_put_contents函数则声明该函数
     *
     * @access  public
     * @param   string  $file
     * @param   mix     $data
     * @return  int
     */
    function file_put_contents($file, $data, $flags = '')
    {
        $contents = (is_array($data)) ? implode('', $data) : $data;

        if ($flags == 'FILE_APPEND')
        {
            $mode = 'ab+';
        }
        else
        {
            $mode = 'wb';
        }

        if (($fp = @fopen($file, $mode)) === false)
        {
            return false;
        }
        else
        {
            $bytes = fwrite($fp, $contents);
            fclose($fp);

            return $bytes;
        }
    }
}

if (!function_exists('floatval'))
{
    /**
     * 如果系统不存在 floatval 函数则声明该函数
     *
     * @access  public
     * @param   mix     $n
     * @return  float
     */
    function floatval($n)
    {
        return (float) $n;
    }
}

/**
 * 文件或目录权限检查函数
 *
 * @access          public
 * @param           string  $file_path   文件路径
 * @param           bool    $rename_prv  是否在检查修改权限时检查执行rename()函数的权限
 *
 * @return          int     返回值的取值范围为{0 <= x <= 15}，每个值表示的含义可由四位二进制数组合推出。
 *                          返回值在二进制计数法中，四位由高到低分别代表
 *                          可执行rename()函数权限、可对文件追加内容权限、可写入文件权限、可读取文件权限。
 */
function file_mode_info($file_path)
{
    /* 如果不存在，则不可读、不可写、不可改 */
    if (!file_exists($file_path))
    {
        return false;
    }

    $mark = 0;

    if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN')
    {
        /* 测试文件 */
        $test_file = $file_path . '/cf_test.txt';

        /* 如果是目录 */
        if (is_dir($file_path))
        {
            /* 检查目录是否可读 */
            $dir = @opendir($file_path);
            if ($dir === false)
            {
                return $mark; //如果目录打开失败，直接返回目录不可修改、不可写、不可读
            }
            if (@readdir($dir) !== false)
            {
                $mark ^= 1; //目录可读 001，目录不可读 000
            }
            @closedir($dir);

            /* 检查目录是否可写 */
            $fp = @fopen($test_file, 'wb');
            if ($fp === false)
            {
                return $mark; //如果目录中的文件创建失败，返回不可写。
            }
            if (@fwrite($fp, 'directory access testing.') !== false)
            {
                $mark ^= 2; //目录可写可读011，目录可写不可读 010
            }
            @fclose($fp);

            @unlink($test_file);

            /* 检查目录是否可修改 */
            $fp = @fopen($test_file, 'ab+');
            if ($fp === false)
            {
                return $mark;
            }
            if (@fwrite($fp, "modify test.\r\n") !== false)
            {
                $mark ^= 4;
            }
            @fclose($fp);

            /* 检查目录下是否有执行rename()函数的权限 */
            if (@rename($test_file, $test_file) !== false)
            {
                $mark ^= 8;
            }
            @unlink($test_file);
        }
        /* 如果是文件 */
        elseif (is_file($file_path))
        {
            /* 以读方式打开 */
            $fp = @fopen($file_path, 'rb');
            if ($fp)
            {
                $mark ^= 1; //可读 001
            }
            @fclose($fp);

            /* 试着修改文件 */
            $fp = @fopen($file_path, 'ab+');
            if ($fp && @fwrite($fp, '') !== false)
            {
                $mark ^= 6; //可修改可写可读 111，不可修改可写可读011...
            }
            @fclose($fp);

            /* 检查目录下是否有执行rename()函数的权限 */
            if (@rename($test_file, $test_file) !== false)
            {
                $mark ^= 8;
            }
        }
    }
    else
    {
        if (@is_readable($file_path))
        {
            $mark ^= 1;
        }

        if (@is_writable($file_path))
        {
            $mark ^= 14;
        }
    }

    return $mark;
}

function log_write($arg, $file = '', $line = '')
{
    if ((DEBUG_MODE & 4) != 4)
    {
        return;
    }

    $str = "\r\n-- ". date('Y-m-d H:i:s'). " --------------------------------------------------------------\r\n";
    $str .= "FILE: $file\r\nLINE: $line\r\n";

    if (is_array($arg))
    {
        $str .= '$arg = array(';
        foreach ($arg AS $val)
        {
            foreach ($val AS $key => $list)
            {
                $str .= "'$key' => '$list'\r\n";
            }
        }
        $str .= ")\r\n";
    }
    else
    {
        $str .= $arg;
    }

    file_put_contents(ROOT_PATH . DATA_DIR . '/log.txt', $str);
}

/**
 * 检查目标文件夹是否存在，如果不存在则自动创建该目录
 *
 * @access      public
 * @param       string      folder     目录路径。不能使用相对于网站根目录的URL
 *
 * @return      bool
 */
function make_dir($folder)
{
    $reval = false;

    if (!file_exists($folder))
    {
        /* 如果目录不存在则尝试创建该目录 */
        @umask(0);

        /* 将目录路径拆分成数组 */
        preg_match_all('/([^\/]*)\/?/i', $folder, $atmp);

        /* 如果第一个字符为/则当作物理路径处理 */
        $base = ($atmp[0][0] == '/') ? '/' : '';

        /* 遍历包含路径信息的数组 */
        foreach ($atmp[1] AS $val)
        {
            if ('' != $val)
            {
                $base .= $val;

                if ('..' == $val || '.' == $val)
                {
                    /* 如果目录为.或者..则直接补/继续下一个循环 */
                    $base .= '/';

                    continue;
                }
            }
            else
            {
                continue;
            }

            $base .= '/';

            if (!file_exists($base))
            {
                /* 尝试创建目录，如果创建失败则继续循环 */
                if (@mkdir(rtrim($base, '/'), 0777))
                {
                    @chmod($base, 0777);
                    $reval = true;
                }
            }
        }
    }
    else
    {
        /* 路径已经存在。返回该路径是不是一个目录 */
        $reval = is_dir($folder);
    }

    clearstatcache();

    return $reval;
}

/**
 * 获得系统是否启用了 gzip
 *
 * @access  public
 *
 * @return  boolean
 */
function gzip_enabled()
{
    static $enabled_gzip = NULL;

    if ($enabled_gzip === NULL)
    {
        $enabled_gzip = ($GLOBALS['_CFG']['enable_gzip'] && function_exists('ob_gzhandler'));
    }

    return $enabled_gzip;
}

/**
 * 递归方式的对变量中的特殊字符进行转义
 *
 * @access  public
 * @param   mix     $value
 *
 * @return  mix
 */
function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}

/**
 * 将对象成员变量或者数组的特殊字符进行转义
 *
 * @access   public
 * @param    mix        $obj      对象或者数组
 * @author   Xuan Yan
 *
 * @return   mix                  对象或者数组
 */
function addslashes_deep_obj($obj)
{
    if (is_object($obj) == true)
    {
        foreach ($obj AS $key => $val)
        {
            $obj->$key = addslashes_deep($val);
        }
    }
    else
    {
        $obj = addslashes_deep($obj);
    }

    return $obj;
}

/**
 * 递归方式的对变量中的特殊字符去除转义
 *
 * @access  public
 * @param   mix     $value
 *
 * @return  mix
 */
function stripslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }
    else
    {
        return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    }
}

/**
 *  将一个字串中含有全角的数字字符、字母、空格或'%+-()'字符转换为相应半角字符
 *
 * @access  public
 * @param   string       $str         待转换字串
 *
 * @return  string       $str         处理后字串
 */
function make_semiangle($str)
{
    $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
                 '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
                 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
                 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
                 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
                 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
                 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
                 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
                 'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
                 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
                 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
                 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
                 'ｙ' => 'y', 'ｚ' => 'z',
                 '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
                 '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
                 '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
                 '》' => '>',
                 '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
                 '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.',
                 '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
                 '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
                 '　' => ' ');

    return strtr($str, $arr);
}

/**
 * 过滤用户输入的基本数据，防止script攻击
 *
 * @access      public
 * @return      string
 */
function compile_str($str)
{
    $arr = array('<' => '＜', '>' => '＞','"'=>'”',"'"=>'’');

    return strtr($str, $arr);
}

/**
 * 检查文件类型
 *
 * @access      public
 * @param       string      filename            文件名
 * @param       string      realname            真实文件名
 * @param       string      limit_ext_types     允许的文件类型
 * @return      string
 */
function check_file_type($filename, $realname = '', $limit_ext_types = '')
{
    if ($realname)
    {
        $extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
    }
    else
    {
        $extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $extname . '|') === false)
    {
        return '';
    }

    $str = $format = '';

    $file = @fopen($filename, 'rb');
    if ($file)
    {
        $str = @fread($file, 0x400); // 读取前 1024 个字节
        @fclose($file);
    }
    else
    {
        if (stristr($filename, ROOT_PATH) === false)
        {
            if ($extname == 'jpg' || $extname == 'jpeg' || $extname == 'gif' || $extname == 'png' || $extname == 'doc' ||
                $extname == 'xls' || $extname == 'txt'  || $extname == 'zip' || $extname == 'rar' || $extname == 'ppt' ||
                $extname == 'pdf' || $extname == 'rm'   || $extname == 'mid' || $extname == 'wav' || $extname == 'bmp' ||
                $extname == 'swf' || $extname == 'chm'  || $extname == 'sql' || $extname == 'cert'|| $extname == 'pptx' || 
                $extname == 'xlsx' || $extname == 'docx')
            {
                $format = $extname;
            }
        }
        else
        {
            return '';
        }
    }

    if ($format == '' && strlen($str) >= 2 )
    {
        if (substr($str, 0, 4) == 'MThd' && $extname != 'txt')
        {
            $format = 'mid';
        }
        elseif (substr($str, 0, 4) == 'RIFF' && $extname == 'wav')
        {
            $format = 'wav';
        }
        elseif (substr($str ,0, 3) == "\xFF\xD8\xFF")
        {
            $format = 'jpg';
        }
        elseif (substr($str ,0, 4) == 'GIF8' && $extname != 'txt')
        {
            $format = 'gif';
        }
        elseif (substr($str ,0, 8) == "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A")
        {
            $format = 'png';
        }
        elseif (substr($str ,0, 2) == 'BM' && $extname != 'txt')
        {
            $format = 'bmp';
        }
        elseif ((substr($str ,0, 3) == 'CWS' || substr($str ,0, 3) == 'FWS') && $extname != 'txt')
        {
            $format = 'swf';
        }
        elseif (substr($str ,0, 4) == "\xD0\xCF\x11\xE0")
        {   // D0CF11E == DOCFILE == Microsoft Office Document
            if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'doc')
            {
                $format = 'doc';
            }
            elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xls')
            {
                $format = 'xls';
            } elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'ppt')
            {
                $format = 'ppt';
            }
        } elseif (substr($str ,0, 4) == "PK\x03\x04")
        {
            if (substr($str,0x200,4) == "\xEC\xA5\xC1\x00" || $extname == 'docx')
            {
                $format = 'docx';
            }
            elseif (substr($str,0x200,2) == "\x09\x08" || $extname == 'xlsx')
            {
                $format = 'xlsx';
            } elseif (substr($str,0x200,4) == "\xFD\xFF\xFF\xFF" || $extname == 'pptx')
            {
                $format = 'pptx';
            }else
            {
                $format = 'zip';
            }
        } elseif (substr($str ,0, 4) == 'Rar!' && $extname != 'txt')
        {
            $format = 'rar';
        } elseif (substr($str ,0, 4) == "\x25PDF")
        {
            $format = 'pdf';
        } elseif (substr($str ,0, 3) == "\x30\x82\x0A")
        {
            $format = 'cert';
        } elseif (substr($str ,0, 4) == 'ITSF' && $extname != 'txt')
        {
            $format = 'chm';
        } elseif (substr($str ,0, 4) == "\x2ERMF")
        {
            $format = 'rm';
        } elseif ($extname == 'sql')
        {
            $format = 'sql';
        } elseif ($extname == 'txt')
        {
            $format = 'txt';
        }
    }

    if ($limit_ext_types && stristr($limit_ext_types, '|' . $format . '|') === false)
    {
        $format = '';
    }

    return $format;
}

/**
 * 对 MYSQL LIKE 的内容进行转义
 *
 * @access      public
 * @param       string      string  内容
 * @return      string
 */
function mysql_like_quote($str)
{
    return strtr($str, array("\\\\" => "\\\\\\\\", '_' => '\_', '%' => '\%', "\'" => "\\\\\'"));
}

/**
 * 获取服务器的ip
 *
 * @access      public
 *
 * @return string
 **/
function real_server_ip()
{
    static $serverip = NULL;

    if ($serverip !== NULL)
    {
        return $serverip;
    }

    if (isset($_SERVER))
    {
        if (isset($_SERVER['SERVER_ADDR']))
        {
            $serverip = $_SERVER['SERVER_ADDR'];
        }
        else
        {
            $serverip = '0.0.0.0';
        }
    }
    else
    {
        $serverip = getenv('SERVER_ADDR');
    }

    return $serverip;
}

/**
 * 自定义 header 函数，用于过滤可能出现的安全隐患
 *
 * @param   string  string  内容
 *
 * @return  void
 **/
function ecs_header($string, $replace = true, $http_response_code = 0)
{
    if (strpos($string, '../upgrade/index.php') === 0)
    {
        echo '<script type="text/javascript">window.location.href="' . $string . '";</script>';
    }
    $string = str_replace(array("\r", "\n"), array('', ''), $string);

    if (preg_match('/^\s*location:/is', $string))
    {
        @header($string . "\n", $replace);

        exit();
    }

    if (empty($http_response_code) || PHP_VERSION < '4.3')
    {
        @header($string, $replace);
    }
    else
    {
        @header($string, $replace, $http_response_code);
    }
}

function ecs_iconv($source_lang, $target_lang, $source_string = '')
{
    static $chs = NULL;

    /* 如果字符串为空或者字符串不需要转换，直接返回 */
    if ($source_lang == $target_lang || $source_string == '' || preg_match("/[\x80-\xFF]+/", $source_string) == 0)
    {
        return $source_string;
    }

    if ($chs === NULL)
    {
        require_once(ROOT_PATH . 'includes/cls_iconv.php');
        $chs = new Chinese(ROOT_PATH);
    }

    return $chs->Convert($source_lang, $target_lang, $source_string);
}

function ecs_geoip($ip)
{
    static $fp = NULL, $offset = array(), $index = NULL;

    $ip    = gethostbyname($ip);
    $ipdot = explode('.', $ip);
    $ip    = pack('N', ip2long($ip));

    $ipdot[0] = (int)$ipdot[0];
    $ipdot[1] = (int)$ipdot[1];
    if ($ipdot[0] == 10 || $ipdot[0] == 127 || ($ipdot[0] == 192 && $ipdot[1] == 168) || ($ipdot[0] == 172 && ($ipdot[1] >= 16 && $ipdot[1] <= 31)))
    {
        return 'LAN';
    }

    if ($fp === NULL)
    {
        $fp = fopen(ROOT_PATH . 'includes/codetable/ipdata.dat', 'rb');
        if ($fp === false)
        {
            return 'Invalid IP data file';
        }
        $offset = unpack('Nlen', fread($fp, 4));
        if ($offset['len'] < 4)
        {
            return 'Invalid IP data file';
        }
        $index  = fread($fp, $offset['len'] - 4);
    }

    $length = $offset['len'] - 1028;
    $start  = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);
    for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8)
    {
        if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip)
        {
            $index_offset = unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
            $index_length = unpack('Clen', $index{$start + 7});
            break;
        }
    }

    fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
    $area = fread($fp, $index_length['len']);

    fclose($fp);
    $fp = NULL;

    return $area;
}

/**
 * 去除字符串右侧可能出现的乱码
 *
 * @param   string      $str        字符串
 *
 * @return  string
 */
function trim_right($str)
{
    $len = strlen($str);
    /* 为空或单个字符直接返回 */
    if ($len == 0 || ord($str{$len-1}) < 127)
    {
        return $str;
    }
    /* 有前导字符的直接把前导字符去掉 */
    if (ord($str{$len-1}) >= 192)
    {
       return substr($str, 0, $len-1);
    }
    /* 有非独立的字符，先把非独立字符去掉，再验证非独立的字符是不是一个完整的字，不是连原来前导字符也截取掉 */
    $r_len = strlen(rtrim($str, "\x80..\xBF"));
    if ($r_len == 0 || ord($str{$r_len-1}) < 127)
    {
        return sub_str($str, 0, $r_len);
    }

    $as_num = ord(~$str{$r_len -1});
    if ($as_num > (1<<(6 + $r_len - $len)))
    {
        return $str;
    }
    else
    {
        return substr($str, 0, $r_len-1);
    }
}

/**
 * 将上传文件转移到指定位置
 *
 * @param string $file_name
 * @param string $target_name
 * @return blog
 */
function move_upload_file($file_name, $target_name = '')
{
    if (function_exists("move_uploaded_file"))
    {
        if (move_uploaded_file($file_name, $target_name))
        {
            @chmod($target_name,0755);
            return true;
        }
        else if (copy($file_name, $target_name))
        {
            @chmod($target_name,0755);
            return true;
        }
    }
    elseif (copy($file_name, $target_name))
    {
        @chmod($target_name,0755);
        return true;
    }
    return false;
}

/**
 * 将JSON传递的参数转码
 *
 * @param string $str
 * @return string
 */
function json_str_iconv($str)
{
    if (EC_CHARSET != 'utf-8')
    {
        if (is_string($str))
        {
            return addslashes(stripslashes(ecs_iconv('utf-8', EC_CHARSET, $str)));
        }
        elseif (is_array($str))
        {
            foreach ($str as $key => $value)
            {
                $str[$key] = json_str_iconv($value);
            }
            return $str;
        }
        elseif (is_object($str))
        {
            foreach ($str as $key => $value)
            {
                $str->$key = json_str_iconv($value);
            }
            return $str;
        }
        else
        {
            return $str;
        }
    }
    return $str;
}

/**
 * 循环转码成utf8内容
 *
 * @param string $str
 * @return string
 */
function to_utf8_iconv($str)
{
    if (EC_CHARSET != 'utf-8')
    {
        if (is_string($str))
        {
            return ecs_iconv(EC_CHARSET, 'utf-8', $str);
        }
        elseif (is_array($str))
        {
            foreach ($str as $key => $value)
            {
                $str[$key] = to_utf8_iconv($value);
            }
            return $str;
        }
        elseif (is_object($str))
        {
            foreach ($str as $key => $value)
            {
                $str->$key = to_utf8_iconv($value);
            }
            return $str;
        }
        else
        {
            return $str;
        }
    }
    return $str;
}

/**
 * 获取文件后缀名,并判断是否合法
 *
 * @param string $file_name
 * @param array $allow_type
 * @return blob
 */
function get_file_suffix($file_name, $allow_type = array())
{
    $file_suffix = strtolower(array_pop(explode('.', $file_name)));
    if (empty($allow_type))
    {
        return $file_suffix;
    }
    else
    {
        if (in_array($file_suffix, $allow_type))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}

/**
 * 读结果缓存文件
 *
 * @params  string  $cache_name
 *
 * @return  array   $data
 */
function read_static_cache($cache_name)
{
    if ((DEBUG_MODE & 2) == 2)
    {
        return false;
    }
    static $result = array();
    if (!empty($result[$cache_name]))
    {
        return $result[$cache_name];
    }
    $cache_file_path = ROOT_PATH . '/temp/static_caches/' . $cache_name . '.php';
    if (file_exists($cache_file_path))
    {
        include_once($cache_file_path);
        $result[$cache_name] = $data;
        return $result[$cache_name];
    }
    else
    {
        return false;
    }
}

/**
 * 写结果缓存文件
 *
 * @params  string  $cache_name
 * @params  string  $caches
 *
 * @return
 */
function write_static_cache($cache_name, $caches)
{
    if ((DEBUG_MODE & 2) == 2)
    {
        return false;
    }
    $cache_file_path = ROOT_PATH . '/temp/static_caches/' . $cache_name . '.php';
    $content = "<?php\r\n";
    $content .= "\$data = " . var_export($caches, true) . ";\r\n";
    $content .= "?>";
    file_put_contents($cache_file_path, $content, LOCK_EX);
}

?>