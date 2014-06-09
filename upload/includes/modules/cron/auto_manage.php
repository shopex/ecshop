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
 * $Id: auto_manage.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}
$cron_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/cron/auto_manage.php';
if (file_exists($cron_lang))
{
    global $_LANG;

    include_once($cron_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'auto_manage_desc';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'auto_manage_count', 'type' => 'select', 'value' => '5'),
    );

    return;
}
$time = gmtime();
$limit = !empty($cron['auto_manage_count']) ? $cron['auto_manage_count'] : 5;
$sql = "SELECT * FROM " . $GLOBALS['ecs']->table('auto_manage') . " WHERE starttime > '0' AND starttime <= '$time' OR endtime > '0' AND endtime <= '$time' LIMIT $limit";
$autodb = $db->getAll($sql);
foreach ($autodb as $key => $val)
{
    $del = $up = false;
    if ($val['type'] == 'goods')
    {
        $goods = true;
        $where = " WHERE goods_id = '$val[item_id]'";
    }
    else
    {
        $goods = false;
        $where = " WHERE article_id = '$val[item_id]'";
    }


    //上下架判断
    if(!empty($val['starttime']) && !empty($val['endtime']))
    {
        //上下架时间均设置
        if($val['starttime'] <= $time && $time < $val['endtime'])
        {
            //上架时间 <= 当前时间 < 下架时间
            $up = true;
            $del = false;
        }
        elseif($val['starttime'] >= $time && $time > $val['endtime'])
        {
            //下架时间 <= 当前时间 < 上架时间
            $up = false;
            $del = false;
        }
        elseif($val['starttime'] == $time && $time == $val['endtime'])
        {
            //下架时间 == 当前时间 == 上架时间
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('auto_manage') . "WHERE item_id = '$val[item_id]' AND type = '$val[type]'";
            $db->query($sql);
            continue;
        }
        elseif($val['starttime'] > $val['endtime'])
        {
            // 下架时间 < 上架时间 < 当前时间
            $up = true;
            $del = true;
        }
        elseif($val['starttime'] < $val['endtime'])
        {
            // 上架时间 < 下架时间 < 当前时间
            $up = false;
            $del = true;
        }
        else
        {
            // 上架时间 = 下架时间 < 当前时间
            $sql = "DELETE FROM " . $GLOBALS['ecs']->table('auto_manage') . "WHERE item_id = '$val[item_id]' AND type = '$val[type]'";
            $db->query($sql);

            continue;
        }
    }
    elseif(!empty($val['starttime']))
    {
        //只设置了上架时间
        $up = true;
        $del = true;
    }
    else
    {
        //只设置了下架时间
        $up = false;
        $del = true;
    }

    if ($goods)
    {
        if ($up)
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('goods') . " SET is_on_sale = 1 $where";
        }
        else
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('goods') . " SET is_on_sale = 0 $where";
        }
    }
    else
    {
        if ($up)
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('article') . " SET is_open = 1 $where";
        }
        else
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('article') . " SET is_open = 0 $where";
        }
    }
    $db->query($sql);
    if ($del)
    {
        $sql = "DELETE FROM " . $GLOBALS['ecs']->table('auto_manage') . "WHERE item_id = '$val[item_id]' AND type = '$val[type]'";
        $db->query($sql);
    }
    else
    {
        if($up)
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('auto_manage') . " SET starttime = 0 WHERE item_id = '$val[item_id]' AND type = '$val[type]'";
        }
        else
        {
            $sql = "UPDATE " . $GLOBALS['ecs']->table('auto_manage') . " SET endtime = 0 WHERE item_id = '$val[item_id]' AND type = '$val[type]'";
        }
        $db->query($sql);
    }
}
?>