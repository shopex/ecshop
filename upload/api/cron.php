<?php

/**
 * ECSHOP 程序说明
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: liubo $
 * $Id: cron.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);

require('./init.php');
//require('../includes/lib_time.php');

$timestamp = gmtime();
check_method();
$error_log = array();
if (isset($set_modules))
{
    $set_modules = false;
    unset($set_modules);
}
$crondb = get_cron_info(); // 获得需要执行的计划任务数据
foreach ($crondb AS $key => $cron_val)
{
    if (file_exists(ROOT_PATH . 'includes/modules/cron/' . $cron_val['cron_code'] . '.php'))
    {
        if (!empty($cron_val['allow_ip'])) // 设置了允许ip
        {
            $allow_ip = explode(',', $cron_val['allow_ip']);
            $server_ip = real_server_ip();
            if (!in_array($server_ip, $allow_ip))
            {
                continue;
            }
        }
        if (!empty($cron_val['minute'])) // 设置了允许分钟段
        {
            $m = explode(',', $cron_val['minute']);
            $m_now = intval(local_date('i',$timestamp));
            if (!in_array($m_now, $m))
            {
                continue;
            }
        }
        if (!empty($cron_val['alow_files'])) // 设置允许调用文件
        {
            $f_info = parse_url($_SERVER['HTTP_REFERER']);
            $f_now = basename($f_info['path']);
            $f = explode(' ', $cron_val['alow_files']);
            if (!in_array($f_now, $f))
            {
                continue;
            }
        }
        if (!empty($cron_val['cron_config']))
        {
            foreach ($cron_val['cron_config'] AS $k => $v)
            {
                $cron[$v['name']] = $v['value'];
            }
        }
        include_once(ROOT_PATH . 'includes/modules/cron/' . $cron_val['cron_code'] . '.php');
    }
    else
    {
        $error_log[] = make_error_arr('includes/modules/cron/' . $cron_val['cron_code'] . '.php not found!',__FILE__);
    }

    $close = $cron_val['run_once'] ? 0 : 1;
    $next_time = get_next_time($cron_val['cron']);
    $sql = "UPDATE " . $ecs->table('crons') .
           "SET thistime = '$timestamp', nextime = '$next_time', enable = $close " .
           "WHERE cron_id = '$cron_val[cron_id]' LIMIT 1";

    $db->query($sql);
}
write_error_arr($error_log);

function get_next_time($cron)
{
    $y  = local_date('Y', $GLOBALS['timestamp']);
    $mo = local_date('n', $GLOBALS['timestamp']);
    $d  = local_date('j', $GLOBALS['timestamp']);
    $w  = local_date('w', $GLOBALS['timestamp']);
    $h  = local_date('G', $GLOBALS['timestamp']);
    $sh = $sm = 0;
    $sy = $y;
    if ($cron['day'])
    {
        $sd = $cron['day'];
        $smo = $mo + 1;
    }
    else
    {
        $sd = $d;
        $smo = $mo;
        if ($cron['week'] != '')
        {
            $sd += $cron['week'] - $w + 7;
        }
    }
    if ($cron['hour'])
    {
        $sh = $cron['hour'];
        if (empty($cron['day']) && $cron['week']=='')
        {
            $sd++;
        }
    }
    //$next = gmmktime($sh,$sm,0,$smo,$sd,$sy);
    $next = local_strtotime("$sy-$smo-$sd $sh:$sm:0");
    if ($next < $GLOBALS['timestamp'])
    {
        if ($cron['m'])
        {
            return $GLOBALS['timestamp'] + 60 - intval(local_date('s', $GLOBALS['timestamp']));
        }
        else
        {
            return $GLOBALS['timestamp'];
        }
    }
    else
    {
        return $next;
    }
}

function get_cron_info()
{
    $crondb = array();

    $sql   = "SELECT * FROM " . $GLOBALS['ecs']->table('crons') . " WHERE enable = 1 AND nextime < $GLOBALS[timestamp]";
    $query = $GLOBALS['db']->query($sql);

    while ($rt = $GLOBALS['db']->fetch_array($query))
    {
        $rt['cron'] = array('day'=>$rt['day'],'week'=>$rt['week'],'m'=>$rt['minute'],'hour'=>$rt['hour']);
        $rt['cron_config'] = unserialize($rt['cron_config']);
        $rt['minute'] = trim($rt['minute']);
        $rt['allow_ip'] = trim($rt['allow_ip']);
        $crondb[] = $rt;
    }

    return $crondb;
}

function make_error_arr($msg,$file)
{
    $file = str_replace(ROOT_PATH, '' ,$file);

    return array('info' => $msg, 'file' => $file, 'time' => $GLOBALS['timestamp']);
}

function write_error_arr($err_arr)
{
    if (!empty($err_arr))
    {
        $query = '';
        foreach ($err_arr AS $key => $val)
        {
            $query .= $query ? ",('$val[info]', '$val[file]', '$val[time]')" : "('$val[info]', '$val[file]', '$val[time]')";
        }
        if ($query)
        {
            $sql = "INSERT INTO " . $GLOBALS['ecs']->table('error_log') . "(info, file, time) VALUES " . $query;
            $GLOBALS['db']->query($sql);
        }
    }
}

function check_method()
{
    if (PHP_VERSION >= '4.2')
    {
        $if_cron = PHP_SAPI == 'cli' ? true : false;
    }
    else
    {
        $if_cron = php_sapi_name() == 'cgi' ? true : false;
    }
    if (!empty($GLOBALS['_CFG']['cron_method']))
    {
        if (!$if_cron)
        {
            die('Hacking attempt');
        }
    }
    else
    {
        if ($if_cron)
        {
            die('Hacking attempt');
        }
        elseif (!isset($_GET['t']) || $GLOBALS['timestamp'] - intval($_GET['t']) > 60 || empty($_SERVER['HTTP_REFERER']))
        {
            exit;
        }
    }
}

?>