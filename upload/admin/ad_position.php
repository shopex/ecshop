<?php

/**
 * ECSHOP 广告位置管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: ad_position.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/ads.php');

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

$smarty->assign('lang', $_LANG);
$exc = new exchange($ecs->table("ad_position"), $db, 'position_id', 'position_name');

/*------------------------------------------------------ */
//-- 广告位置列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',     $_LANG['ad_position']);
    $smarty->assign('action_link', array('text' => $_LANG['position_add'], 'href' => 'ad_position.php?act=add'));
    $smarty->assign('full_page',   1);

    $position_list = ad_position_list();

    $smarty->assign('position_list',   $position_list['position']);
    $smarty->assign('filter',          $position_list['filter']);
    $smarty->assign('record_count',    $position_list['record_count']);
    $smarty->assign('page_count',      $position_list['page_count']);

    assign_query_info();
    $smarty->display('ad_position_list.htm');
}

/*------------------------------------------------------ */
//-- 添加广告位页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add')
{
    admin_priv('ad_manage');

    /* 模板赋值 */
    $smarty->assign('ur_here',     $_LANG['position_add']);
    $smarty->assign('form_act',    'insert');

    $smarty->assign('action_link', array('href' => 'ad_position.php?act=list', 'text' => $_LANG['ad_position']));
    $smarty->assign('posit_arr',   array('position_style' => '<table cellpadding="0" cellspacing="0">' ."\n". '{foreach from=$ads item=ad}' ."\n". '<tr><td>{$ad}</td></tr>' ."\n". '{/foreach}' ."\n". '</table>'));

    assign_query_info();
    $smarty->display('ad_position_info.htm');
}
elseif ($_REQUEST['act'] == 'insert')
{
    admin_priv('ad_manage');

    /* 对POST上来的值进行处理并去除空格 */
    $position_name = !empty($_POST['position_name']) ? trim($_POST['position_name']) : '';
    $position_desc = !empty($_POST['position_desc']) ? nl2br(htmlspecialchars($_POST['position_desc'])) : '';
    $ad_width      = !empty($_POST['ad_width'])      ? intval($_POST['ad_width'])  : 0;
    $ad_height     = !empty($_POST['ad_height'])     ? intval($_POST['ad_height']) : 0;

    /* 查看广告位是否有重复 */
    if ($exc->num("position_name", $position_name) == 0)
    {
        /* 将广告位置的信息插入数据表 */
        $sql = 'INSERT INTO '.$ecs->table('ad_position').' (position_name, ad_width, ad_height, position_desc, position_style) '.
               "VALUES ('$position_name', '$ad_width', '$ad_height', '$position_desc', '$_POST[position_style]')";

        $db->query($sql);
        /* 记录管理员操作 */
        admin_log($position_name, 'add', 'ads_position');

        /* 提示信息 */
        $link[0]['text'] = $_LANG['ads_add'];
        $link[0]['href'] = 'ads.php?act=add';

        $link[1]['text'] = $_LANG['continue_add_position'];
        $link[1]['href'] = 'ad_position.php?act=add';

        $link[2]['text'] = $_LANG['back_position_list'];
        $link[2]['href'] = 'ad_position.php?act=list';

        sys_msg($_LANG['add'] . "&nbsp;" . stripslashes($position_name) . "&nbsp;" . $_LANG['attradd_succed'], 0, $link);
    }
    else
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg($_LANG['posit_name_exist'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 广告位编辑页面
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit')
{
    admin_priv('ad_manage');

    $id = !empty($_GET['id']) ? intval($_GET['id']) : 0;

    /* 获取广告位数据 */
    $sql = 'SELECT * FROM ' .$ecs->table('ad_position'). " WHERE position_id='$id'";
    $posit_arr = $db->getRow($sql);

    $smarty->assign('ur_here',     $_LANG['position_edit']);
    $smarty->assign('action_link', array('href' => 'ad_position.php?act=list', 'text' => $_LANG['ad_position']));
    $smarty->assign('posit_arr',   $posit_arr);
    $smarty->assign('form_act',    'update');

    assign_query_info();
    $smarty->display('ad_position_info.htm');
}
elseif ($_REQUEST['act'] == 'update')
{
    admin_priv('ad_manage');

    /* 对POST上来的值进行处理并去除空格 */
    $position_name = !empty($_POST['position_name']) ? trim($_POST['position_name']) : '';
    $position_desc = !empty($_POST['position_desc']) ? nl2br(htmlspecialchars($_POST['position_desc'])) : '';
    $ad_width      = !empty($_POST['ad_width'])      ? intval($_POST['ad_width'])  : 0;
    $ad_height     = !empty($_POST['ad_height'])     ? intval($_POST['ad_height']) : 0;
    $position_id   = !empty($_POST['id'])            ? intval($_POST['id'])        : 0;
    /* 查看广告位是否与其它有重复 */
    $sql = 'SELECT COUNT(*) FROM ' .$ecs->table('ad_position').
           " WHERE position_name = '$position_name' AND position_id <> '$position_id'";
    if ($db->getOne($sql) == 0)
    {
        $sql = "UPDATE " .$ecs->table('ad_position'). " SET ".
               "position_name    = '$position_name', ".
               "ad_width         = '$ad_width', ".
               "ad_height        = '$ad_height', ".
               "position_desc    = '$position_desc', ".
               "position_style   = '$_POST[position_style]' ".
               "WHERE position_id = '$position_id'";
        if ($db->query($sql))
        {
           /* 记录管理员操作 */
           admin_log($position_name, 'edit', 'ads_position');

           /* 清除缓存 */
           clear_cache_files();

           /* 提示信息 */
           $link[] = array('text' => $_LANG['back_position_list'], 'href' => 'ad_position.php?act=list');
           sys_msg($_LANG['edit'] . ' ' .stripslashes($position_name).' '. $_LANG['attradd_succed'], 0, $link);
        }
    }
    else
    {
        $link[] = array('text' => $_LANG['go_back'], 'href' => 'javascript:history.back(-1)');
        sys_msg($_LANG['posit_name_exist'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 排序、分页、查询
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $position_list = ad_position_list();

    $smarty->assign('position_list',   $position_list['position']);
    $smarty->assign('filter',          $position_list['filter']);
    $smarty->assign('record_count',    $position_list['record_count']);
    $smarty->assign('page_count',      $position_list['page_count']);

    make_json_result($smarty->fetch('ad_position_list.htm'), '',
        array('filter' => $position_list['filter'], 'page_count' => $position_list['page_count']));
}

/*------------------------------------------------------ */
//-- 编辑广告位置名称
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_position_name')
{
    check_authz_json('ad_manage');

    $id     = intval($_POST['id']);
    $position_name   = json_str_iconv(trim($_POST['val']));

    /* 检查名称是否重复 */
    if ($exc->num("position_name", $position_name, $id) != 0)
    {
        make_json_error(sprintf($_LANG['posit_name_exist'], $position_name));
    }
    else
    {
        if ($exc->edit("position_name = '$position_name'", $id))
        {
            admin_log($position_name,'edit','ads_position');
            make_json_result(stripslashes($position_name));
        }
        else
        {
            make_json_result(sprintf($_LANG['brandedit_fail'], $position_name));
        }
    }
}

/*------------------------------------------------------ */
//-- 编辑广告位宽高
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_ad_width')
{
    check_authz_json('ad_manage');

    $id         = intval($_POST['id']);
    $ad_width   = json_str_iconv(trim($_POST['val']));

    /* 宽度值必须是数字 */
    if (!preg_match("/^[\.0-9]+$/",$ad_width))
    {
        make_json_error($_LANG['width_number']);
    }

    /* 广告位宽度应在1-1024之间 */
    if ($ad_width > 1024 || $ad_width < 1)
    {
        make_json_error($_LANG['width_value']);
    }

    if ($exc->edit("ad_width = '$ad_width'", $id))
    {
        clear_cache_files(); // 清除模版缓存
        admin_log($ad_width, 'edit', 'ads_position');
        make_json_result(stripslashes($ad_width));
    }
    else
    {
        make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
//-- 编辑广告位宽高
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_ad_height')
{
    check_authz_json('ad_manage');

    $id         = intval($_POST['id']);
    $ad_height  = json_str_iconv(trim($_POST['val']));

    /* 高度值必须是数字 */
    if (!preg_match("/^[\.0-9]+$/",$ad_height))
    {
        make_json_error($_LANG['height_number']);
    }

    /* 广告位宽度应在1-1024之间 */
    if ($ad_height > 1024 || $ad_height < 1)
    {
        make_json_error($_LANG['height_value']);
    }

    if ($exc->edit("ad_height = '$ad_height'", $id))
    {
        clear_cache_files(); // 清除模版缓存
        admin_log($ad_height, 'edit', 'ads_position');
        make_json_result(stripslashes($ad_height));
    }
    else
    {
        make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
//-- 删除广告位置
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('ad_manage');

    $id = intval($_GET['id']);

    /* 查询广告位下是否有广告存在 */
    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('ad'). " WHERE position_id = '$id'";

    if ($db->getOne($sql) > 0)
    {
        make_json_error($_LANG['not_del_adposit']);
    }
    else
    {
        $exc->drop($id);
        admin_log('', 'remove', 'ads_position');
    }

    $url = 'ad_position.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/* 获取广告位置列表 */
function ad_position_list()
{
    $filter = array();

    /* 记录总数以及页数 */
    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('ad_position');
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    /* 查询数据 */
    $arr = array();
    $sql = 'SELECT * FROM ' .$GLOBALS['ecs']->table('ad_position'). ' ORDER BY position_id DESC';
    $res = $GLOBALS['db']->selectLimit($sql, $filter['page_size'], $filter['start']);
    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        $position_desc = !empty($rows['position_desc']) ? sub_str($rows['position_desc'], 50, true) : '';
        $rows['position_desc'] = nl2br(htmlspecialchars($position_desc));

        $arr[] = $rows;
    }

    return array('position' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

?>