<?php

/**
 * ECSHOP 计划任务
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

require(dirname(__FILE__) . '/includes/init.php');

$_REQUEST['act'] = trim($_REQUEST['act']);
admin_priv('cron');
$exc = new exchange($ecs->table('crons'), $db, 'cron_code', 'cron_name');
if ($_REQUEST['act'] == 'list')
{
    $cron_list = array();
    $sql = "SELECT * FROM " . $ecs->table('crons');
    $res = $db->query($sql);
    while ($row = $db->fetchRow($res))
    {
        $cron_list[$row['cron_code']] = $row;
    }
    $modules = read_modules('../includes/modules/cron');
    for ($i = 0; $i < count($modules); $i++)
    {
        $code = $modules[$i]['code'];

        /* 如果数据库中有，取数据库中的名称和描述 */
        if (isset($cron_list[$code]))
        {
            $modules[$i]['name'] = $cron_list[$code]['cron_name'];
            $modules[$i]['desc'] = $cron_list[$code]['cron_desc'];
            $modules[$i]['cron_order'] = $cron_list[$code]['cron_order'];
            $modules[$i]['enable'] = $cron_list[$code]['enable'];
            $modules[$i]['nextime'] = local_date('Y-m-d/H:i:s',$cron_list[$code]['nextime']);
            $modules[$i]['thistime'] = $cron_list[$code]['thistime'] ? local_date('Y-m-d/H:i:s',$cron_list[$code]['thistime']) : '-';
            $modules[$i]['install'] = '1';
        }
        else
        {
            $modules[$i]['name'] = $_LANG[$modules[$i]['code']];
            $modules[$i]['desc'] = $_LANG[$modules[$i]['desc']];
            $modules[$i]['nextime'] = '-';
            $modules[$i]['thistime'] = '-';
            $modules[$i]['install'] = '0';
        }
    }
    assign_query_info();
    $smarty->assign('ur_here', $_LANG['07_cron_schcron']);
    $smarty->assign('modules', $modules);
    $smarty->display('cron_list.htm');
}
elseif ($_REQUEST['act'] == 'install')
{

    if (empty($_POST['step']))
    {
        /* 取相应插件信息 */
        $set_modules = true;
        include_once(ROOT_PATH.'includes/modules/cron/' . $_REQUEST['code'] . '.php');

        $data = $modules[0];

        $cron['cron_code']    = $data['code'];
        $cron['cron_act']    = 'install';
        $cron['cron_name']    = $_LANG[$data['code']];
        $cron['cron_desc']    = $_LANG[$data['desc']];
        $cron['cron_config']  = array();

        if (!empty($data['config']))
        {
            foreach ($data['config'] AS $key => $value)
            {
                $cron['cron_config'][$key] = $value + array('label' => $_LANG[$value['name']], 'value' => $value['value']);
                if ($cron['cron_config'][$key]['type'] == 'select')
                {
                    $cron['cron_config'][$key]['range'] = $_LANG[$cron['cron_config'][$key]['name'] . '_range'];
                }
            }
        }
        list($day, $week, $hours) = get_dwh();
        assign_query_info();

        $page_list = array( 'index' => 0,
                            'user' => 0,
                            'pick_out' => 0,
                            'flow' => 0,
                            'group_buy' => 0,
                            'snatch' => 0,
                            'tag_cloud' => 0,

                            'category' => 0,
                            'goods' => 0,
                            'article_cat' => 0,
                            'article' => 0,
                            'brand' => 0,
                            'search' => 0,
                                );

        $smarty->assign('days', $day);
        $smarty->assign('page_list', $page_list);
        $smarty->assign('week', $week);
        $smarty->assign('hours', $hours);
        $smarty->assign('cron', $cron);
        $smarty->display('cron_edit.htm');
    }
    elseif ($_POST['step'] == 2)
    {
        $links[] = array('text' => $_LANG['back_list'], 'href' => 'cron.php?act=list');
        if (empty($_POST['cron_name']))
        {
            sys_msg($_LANG['cron_name'] . $_LANG['empty']);
        }
        $sql = "SELECT COUNT(*) FROM " . $ecs->table('crons') .
                " WHERE  cron_code = '$_POST[cron_code]'";
        if ($db->GetOne($sql) > 0)
        {
            sys_msg($_LANG['cron_code'] . $_LANG['repeat'], 1);
        }

        /* 取得配置信息 */
        $cron_config = array();
        if (isset($_POST['cfg_value']) && is_array($_POST['cfg_value']))
        {
            $temp = count($_POST['cfg_value']);
            for ($i = 0; $i < $temp; $i++)
            {
                $cron_config[] = array('name'  => trim($_POST['cfg_name'][$i]),
                                      'type'  => trim($_POST['cfg_type'][$i]),
                                      'value' => trim($_POST['cfg_value'][$i])
                );
            }
        }
        $cron_config = serialize($cron_config);
        $cron_minute = get_minute($_POST['cron_minute']);
        if ($_POST['ttype'] == 'day')
        {
            $cron_day = $_POST['cron_day'];
            $cron_week = '';
        }
        elseif($_POST['ttype'] == 'week')
        {
            $cron_day = '';
            $cron_week = $_POST['cron_week'];
        }
        else
        {
            $cron_day = $cron_week = '';
        }
        if ($cron_week == 7)
        {
            $cron_week = 0;
        }

        $_POST['alow_files'] = isset($_POST['alow_files']) ? implode(' ', $_POST['alow_files']) : "";

        !isset($_POST['cron_run_once']) && $_POST['cron_run_once'] = 0;
        $cron_hour = $_POST['cron_hour'];
        $cron = array('day'=>$cron_day,'week'=>$cron_week,'m'=>$cron_minute,'hour'=>$cron_hour);
        $next = get_next_time($cron);
        $sql = "INSERT INTO " . $ecs->table('crons') . " (cron_code, cron_name, cron_desc, cron_config, nextime, day, week, hour, minute, run_once, allow_ip, alow_files)" .
               "VALUES ('$_POST[cron_code]', '$_POST[cron_name]', '$_POST[cron_desc]', '$cron_config', '$next', '$cron_day', '$cron_week', '$cron_hour', '$cron_minute', '$_POST[cron_run_once]', '$_POST[allow_ip]', '$_POST[alow_files]')";
        $db->query($sql);
        sys_msg($_LANG['install_ok'], 0, $links);
    }
}
elseif ($_REQUEST['act'] == 'edit')
{
    if (empty($_POST['step']))
    {
        if (isset($_REQUEST['code']))
        {
            $_REQUEST['code'] = trim($_REQUEST['code']);
        }
        else
        {
            die('invalid cron');
        }

        $sql = "SELECT * FROM " . $ecs->table('crons') . " WHERE cron_code = '$_REQUEST[code]'";
        $cron = $db->getRow($sql);
        if (empty($cron))
        {
            $links[] = array('text' => $_LANG['back_list'], 'href' => 'cron.php?act=list');
            sys_msg($_LANG['cron_not_available'], 0, $links);
        }
        /* 取相应插件信息 */
        $set_modules = true;
        include_once(ROOT_PATH . 'includes/modules/cron/' . $_REQUEST['code'] . '.php');
        $data = $modules[0];

        /* 取得配置信息 */
        $cron['cron_config'] = unserialize($cron['cron_config']);
        if (!empty($cron['cron_config']))
        {
            foreach ($cron['cron_config'] AS $key => $value)
            {
                $cron['cron_config'][$key]['label'] = $_LANG[$value['name']];
                if ($cron['cron_config'][$key]['type'] == 'select')
                {
                    $cron['cron_config'][$key]['range'] = $_LANG[$cron['cron_config'][$key]['name'] . '_range'];
                }
            }
        }
        $cron['cron_act']   = 'edit';
        $cron['cronweek']   = $cron['week'] == '0' ? 7 : $cron['week'];
        $cron['cronday']    = $cron['day'];
        $cron['cronhour']   = $cron['hour'];
        $cron['cronminute'] = $cron['minute'];
        $cron['run_once'] && $cron['autoclose'] = 'checked';
        list($day, $week, $hours) = get_dwh();
        $page_list = array( 'index' => 0,
                            'user' => 0,
                            'pick_out' => 0,
                            'flow' => 0,
                            'group_buy' => 0,
                            'snatch' => 0,
                            'tag_cloud' => 0,

                            'category' => 0,
                            'goods' => 0,
                            'article_cat' => 0,
                            'article' => 0,
                            'brand' => 0,
                            'search' => 0,
                                );
        $cron['alow_files'] .= " ";
        foreach(explode(' ', $cron['alow_files']) as $k=>$v)
        {
            $v = str_replace('.php', '', $v);
            if(!empty($v))
            {
                $page_list[$v] = 1;
            }
        }

        assign_query_info();
        $smarty->assign('ur_here', $_LANG['edit'] . $_LANG['cron_code']);
        $smarty->assign('cron', $cron);
        $smarty->assign('days', $day);
        $smarty->assign('week', $week);
        $smarty->assign('hours', $hours);
        $smarty->assign('page_list', $page_list);
        $smarty->display('cron_edit.htm');
    }
    elseif($_POST['step'] == 2)
    {
        $links[] = array('text' => $_LANG['back_list'], 'href' => 'cron.php?act=list');
        if (empty($_POST['cron_id']))
        {
            sys_msg($_LANG['cron_not_available'], 0, $links);
        }
        $cron_config = array();
        if (isset($_POST['cfg_value']) && is_array($_POST['cfg_value']))
        {
            $temp = count($_POST['cfg_value']);
            for ($i = 0; $i < $temp; $i++)
            {
                $cron_config[] = array('name'  => trim($_POST['cfg_name'][$i]),
                                      'type'  => trim($_POST['cfg_type'][$i]),
                                      'value' => trim($_POST['cfg_value'][$i])
                );
            }
        }
        $cron_config = serialize($cron_config);
        $cron_minute = get_minute($_POST['cron_minute']);
        if ($_POST['ttype'] == 'day')
        {
            $cron_day = $_POST['cron_day'];
            $cron_week = '';
        }
        elseif($_POST['ttype'] == 'week')
        {
            $cron_day = '';
            $cron_week = $_POST['cron_week'];
        }
        else
        {
            $cron_day = $cron_week = '';
        }
        if ($cron_week == 7)
        {
            $cron_week = 0;
        }

        $_POST['alow_files'] = isset($_POST['alow_files']) ? implode(' ', $_POST['alow_files']) : "";

        !isset($_POST['cron_run_once']) && $_POST['cron_run_once'] = 0;
        //$_POST['cron_run_once'] = (int)$_POST['cron_run_once'];
        $cron_hour = $_POST['cron_hour'];
        $cron = array('day'=>$cron_day,'week'=>$cron_week,'m'=>$cron_minute,'hour'=>$cron_hour);
        $next = get_next_time($cron);
        $sql = "UPDATE " . $ecs->table('crons') .
               "SET cron_name = '$_POST[cron_name]', cron_desc = '$_POST[cron_desc]', cron_config = '$cron_config', nextime='$next', day = '$cron_day', week = '$cron_week', hour = '$cron_hour', minute = '$cron_minute', run_once = '$_POST[cron_run_once]', allow_ip = '$_POST[allow_ip]', alow_files = '$_POST[alow_files]'" .
               "WHERE cron_id = '$_POST[cron_id]' LIMIT 1";
        $db->query($sql);
        sys_msg($_LANG['edit_ok'], 0 ,$links);
    }
}
elseif ($_REQUEST['act'] == 'uninstall')
{
    $sql = "DELETE FROM " . $ecs->table('crons') .
           "WHERE cron_code = '$_REQUEST[code]' LIMIT 1";
    $db->query($sql);
    $links[] = array('text' => $_LANG['back_list'], 'href' => 'cron.php?act=list');
    sys_msg($_LANG['uninstall_ok'], 0, $links);
}
elseif ($_REQUEST['act'] == 'toggle_show')
{
    $id     = trim($_POST['id']);
    $val    = intval($_POST['val']);

    $sql = "UPDATE " . $ecs->table('crons') .
           "SET enable = '$val' " .
           "WHERE cron_code = '$id' LIMIT 1";
    $db->query($sql);
    make_json_result($val);
}
elseif ($_REQUEST['act'] == 'do')
{
    if (isset($set_modules))
    {
        $set_modules = false;
        unset($set_modules);
    }
    if (file_exists(ROOT_PATH.'includes/modules/cron/' . $_REQUEST['code'] . '.php'))
    {
        $cron = array();
        $sql = "SELECT cron_config FROM " . $ecs->table('crons') . " WHERE cron_code = '$_REQUEST[code]'";
        $temp = $db->getRow($sql);
        $temp = unserialize($temp['cron_config']);
        if (!empty($temp))
        {
            foreach ($temp as $key => $val)
            {
                $cron[$val['name']] = $val['value'];
            }
        }
        include_once(ROOT_PATH.'includes/modules/cron/' . $_REQUEST['code'] . '.php');
        $timestamp = gmtime();
        $sql = "UPDATE " . $ecs->table('crons') .
               "SET thistime = '$timestamp' " .
               "WHERE cron_code = '$_REQUEST[code]' LIMIT 1";
        $db->query($sql);
    }

    $links[] = array('text' => $_LANG['back_list'], 'href' => 'cron.php?act=list');
    sys_msg($_LANG['do_ok'], 0, $links);
}
function get_next_time($cron)
{
    $timestamp = gmtime();
    $y  = local_date('Y', $timestamp);
    $mo = local_date('n', $timestamp);
    $d  = local_date('j', $timestamp);
    $w  = local_date('w', $timestamp);
    $h  = local_date('G', $timestamp);
    $sh = $sm = 0;
    $sy = $y;
    if ($cron['day'])
    {
        $sd = $cron['day'];
        $smo = $mo;
    }
    else
    {
        $sd = $d;
        $smo = $mo;
        if ($cron['week'] !== '')
        {
            $sd += $cron['week'] - $w;
        }
    }
    if ($cron['hour'])
    {
        $sh = $cron['hour'];
    }
    $next = local_strtotime("$sy-$smo-$sd $sh:$sm:0");
    //$next = gmmktime($sh,$sm,0,$smo,$sd,$sy);

    return $next;
}
function get_minute($cron_minute)
{
    $cron_minute = explode(',',$cron_minute);
    $cron_minute = array_unique($cron_minute);
    foreach ($cron_minute as $key => $val)
    {
        if ($val)
        {
            $val = intval($val);
            $val < 0 && $val = 0;
            $val > 59 && $val = 59;
            $cron_minute[$key] = $val;
        }
    }
    return trim(implode(',',$cron_minute));
}
function get_dwh()
{
    $days = $week = $hours = array();
    for ($i = 1 ; $i<=31 ; $i++)
    {
        $days[$i] = str_pad($i,2,'0',STR_PAD_LEFT);
    }
    for ($i = 1 ; $i<8 ; $i++)
    {
        $week[$i] = $GLOBALS['_LANG']['week'][$i];
    }
    for ($i = 0 ; $i<24 ; $i++)
    {
        $hours[$i] = str_pad($i,2,'0',STR_PAD_LEFT);
    }

    return array($days,$week,$hours);
}
?>