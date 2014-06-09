<?php

/**
 * ECSHOP 时间函数
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_time.php 17217 2011-01-19 06:29:08Z liubo $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * 获得当前格林威治时间的时间戳
 *
 * @return  integer
 */
function gmtime()
{
    return (time() - date('Z'));
}

/**
 * 获得服务器的时区
 *
 * @return  integer
 */
function server_timezone()
{
    if (function_exists('date_default_timezone_get'))
    {
        return date_default_timezone_get();
    }
    else
    {
        return date('Z') / 3600;
    }
}


/**
 *  生成一个用户自定义时区日期的GMT时间戳
 *
 * @access  public
 * @param   int     $hour
 * @param   int     $minute
 * @param   int     $second
 * @param   int     $month
 * @param   int     $day
 * @param   int     $year
 *
 * @return void
 */
function local_mktime($hour = NULL , $minute= NULL, $second = NULL,  $month = NULL,  $day = NULL,  $year = NULL)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /**
    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
    * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
    **/
    $time = mktime($hour, $minute, $second, $month, $day, $year) - $timezone * 3600;

    return $time;
}


/**
 * 将GMT时间戳格式化为用户自定义时区日期
 *
 * @param  string       $format
 * @param  integer      $time       该参数必须是一个GMT的时间戳
 *
 * @return  string
 */

function local_date($format, $time = NULL)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    if ($time === NULL)
    {
        $time = gmtime();
    }
    elseif ($time <= 0)
    {
        return '';
    }

    $time += ($timezone * 3600);

    return date($format, $time);
}


/**
 * 转换字符串形式的时间表达式为GMT时间戳
 *
 * @param   string  $str
 *
 * @return  integer
 */
function gmstr2time($str)
{
    $time = strtotime($str);

    if ($time > 0)
    {
        $time -= date('Z');
    }

    return $time;
}

/**
 *  将一个用户自定义时区的日期转为GMT时间戳
 *
 * @access  public
 * @param   string      $str
 *
 * @return  integer
 */
function local_strtotime($str)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /**
    * $time = mktime($hour, $minute, $second, $month, $day, $year) - date('Z') + (date('Z') - $timezone * 3600)
    * 先用mktime生成时间戳，再减去date('Z')转换为GMT时间，然后修正为用户自定义时间。以下是化简后结果
    **/
    $time = strtotime($str) - $timezone * 3600;

    return $time;

}

/**
 * 获得用户所在时区指定的时间戳
 *
 * @param   $timestamp  integer     该时间戳必须是一个服务器本地的时间戳
 *
 * @return  array
 */
function local_gettime($timestamp = NULL)
{
    $tmp = local_getdate($timestamp);
    return $tmp[0];
}

/**
 * 获得用户所在时区指定的日期和时间信息
 *
 * @param   $timestamp  integer     该时间戳必须是一个服务器本地的时间戳
 *
 * @return  array
 */
function local_getdate($timestamp = NULL)
{
    $timezone = isset($_SESSION['timezone']) ? $_SESSION['timezone'] : $GLOBALS['_CFG']['timezone'];

    /* 如果时间戳为空，则获得服务器的当前时间 */
    if ($timestamp === NULL)
    {
        $timestamp = time();
    }

    $gmt        = $timestamp - date('Z');       // 得到该时间的格林威治时间
    $local_time = $gmt + ($timezone * 3600);    // 转换为用户所在时区的时间戳

    return getdate($local_time);
}

?>