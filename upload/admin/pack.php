<?php

/**
 * ECSHOP 包装管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: pack.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . 'includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

$exc = new exchange($ecs->table("pack"), $db, 'pack_id', 'pack_name');

/*------------------------------------------------------ */
//-- 包装列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here',      $_LANG['06_pack_list']);
    $smarty->assign('action_link',  array('text' => $_LANG['pack_add'], 'href'=>'pack.php?act=add'));
    $smarty->assign('full_page',  1);

    $packs_list = packs_list();

    $smarty->assign('packs_list',   $packs_list['packs_list']);
    $smarty->assign('filter',       $packs_list['filter']);
    $smarty->assign('record_count', $packs_list['record_count']);
    $smarty->assign('page_count',   $packs_list['page_count']);

    assign_query_info();
    $smarty->display('pack_list.htm');
}
/*------------------------------------------------------ */
//-- ajax 列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{
    $packs_list = packs_list();
    $smarty->assign('packs_list',    $packs_list['packs_list']);
    $smarty->assign('filter',       $packs_list['filter']);
    $smarty->assign('record_count', $packs_list['record_count']);
    $smarty->assign('page_count',   $packs_list['page_count']);

    $sort_flag  = sort_flag($packs_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('pack_list.htm'), '', array('filter' => $packs_list['filter'], 'page_count' => $packs_list['page_count']));
}
/*------------------------------------------------------ */
//-- 添加新包装
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'add')
{
    /* 权限判断 */
    admin_priv('pack');

    $pack['pack_fee'] = 0;
    $pack['free_money'] = 0;

    $smarty->assign('pack',         $pack);
    $smarty->assign('ur_here',      $_LANG['pack_add']);
    $smarty->assign('form_action','insert');
    $smarty->assign('action_link',  array('text' => $_LANG['06_pack_list'], 'href'=>'pack.php?act=list'));

    assign_query_info();
    $smarty->display('pack_info.htm');
}
if ($_REQUEST['act'] == 'insert')
{
    /* 权限判断 */
    admin_priv('pack');

    /*检查包装名是否重复*/
    $is_only = $exc->is_only('pack_name', $_POST['pack_name']);

    if (!$is_only)
    {
        sys_msg(sprintf($_LANG['packname_exist'], stripslashes($_POST['pack_name'])), 1);
    }

    /* 处理图片 */
    if (!empty($_FILES['pack_img']))
    {
        $upload_img = $image->upload_image($_FILES['pack_img'],"packimg", $_POST['old_packimg']);
        if ($upload_img == false)
        {
            sys_msg($image->error_msg);
        }
        $img_name = basename($upload_img);
    }
    else
    {
        $img_name = '';
    }

    /*插入数据*/
    $sql = "INSERT INTO ".$ecs->table('pack')."(pack_name, pack_fee, free_money, pack_desc, pack_img)
            VALUES ('$_POST[pack_name]', '$_POST[pack_fee]', '$_POST[free_money]', '$_POST[pack_desc]', '$img_name')";
    $db->query($sql);

    /*添加链接*/
    $link[0]['text'] = $_LANG['back_list'];
    $link[0]['href'] = 'pack.php?act=list';
    $link[1]['text'] = $_LANG['continue_add'];
    $link[1]['href'] = 'pack.php?act=add';
    sys_msg($_POST['pack_name'].$_LANG['packadd_succed'],0, $link);
    admin_log($_POST['pack_name'],'add','pack');

}

/*------------------------------------------------------ */
//-- 编辑包装
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'edit')
{
    /* 权限判断 */
    admin_priv('pack');

    $sql = "SELECT pack_id, pack_name, pack_fee, free_money, pack_desc, pack_img FROM " .$ecs->table('pack'). " WHERE pack_id='$_REQUEST[id]'";
    $pack = $db->GetRow($sql);
    $smarty->assign('ur_here',      $_LANG['pack_edit']);
    $smarty->assign('action_link',  array('text' => $_LANG['06_pack_list'], 'href'=>'pack.php?act=list&' . list_link_postfix()));
    $smarty->assign('pack',       $pack);
    $smarty->assign('form_action','update');
    $smarty->display('pack_info.htm');
}
if ($_REQUEST['act'] == 'update')
{
    /* 权限判断 */
    admin_priv('pack');
    if ($_POST['pack_name'] != $_POST['old_packname'])
    {
        /*检查品牌名是否相同*/
        $is_only = $exc->is_only('pack_name', $_POST['pack_name'], $_POST['id']);

        if (!$is_only)
        {
            sys_msg(sprintf($_LANG['packname_exist'], stripslashes($_POST['pack_name'])), 1);
        }
    }

    $param = "pack_name = '$_POST[pack_name]', pack_fee = '$_POST[pack_fee]', free_money= '$_POST[free_money]', pack_desc = '$_POST[pack_desc]' ";
    /* 处理图片 */
    if (!empty($_FILES['pack_img']['name']))
    {
        $upload_img = $image->upload_image($_FILES['pack_img'],"packimg", $_POST['old_packimg']);
        if ($upload_img == false)
        {
            sys_msg($image->error_msg);
        }
        $img_name = basename($upload_img);
    }
    else
    {
        $img_name = '';
    }

    if (!empty($img_name))
    {
        $param .= " ,pack_img = '$img_name' ";
    }

    if ($exc->edit($param ,  $_POST['id']))
    {
        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'pack.php?act=list&' . list_link_postfix();
        $note = sprintf($_LANG['packedit_succed'], $_POST['pack_name']);
        sys_msg($note, 0, $link);
        admin_log($_POST['pack_name'], 'edit', 'pack');
    }
    else
    {
        die($db->error());
    }

}

/* 删除卡片图片 */
if ($_REQUEST['act'] == 'drop_pack_img')
{
    /* 权限判断 */
    admin_priv('pack');
    $pack_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    /* 取得logo名称 */
    $sql = "SELECT pack_img FROM " .$ecs->table('pack'). " WHERE pack_id = '$pack_id'";
    $img_name = $db->getOne($sql);

    if (!empty($img_name))
    {
        @unlink(ROOT_PATH . DATA_DIR . '/packimg/' .$img_name);
        $sql = "UPDATE " .$ecs->table('pack'). " SET pack_img = '' WHERE pack_id = '$pack_id'";
        $db->query($sql);
    }
    $link= array(array('text' => $_LANG['pack_edit_lnk'], 'href'=>'pack.php?act=edit&id=' .$pack_id), array('text' => $_LANG['pack_list_lnk'], 'href'=>'pack.php?act=list'));
     sys_msg($_LANG['drop_pack_img_success'], 0, $link);
}

/*------------------------------------------------------ */
//-- 编辑包装名称
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'edit_name')
{
    check_authz_json('pack');

    $id = intval($_POST['id']);
    $val = json_str_iconv(trim($_POST['val']));

    /* 取得该属性所属商品类型id */
    $pack_name = $exc->get_name($id);

    if (!$exc->is_only('pack_name', $val, $id))
    {
        make_json_error(sprintf($_LANG['packname_exist'], $pack_name));
    }
    else
    {
        $exc->edit("pack_name='$val'", $id);

        admin_log($val, 'edit', 'pack');
        make_json_result(stripslashes($val));
    }
}

/*------------------------------------------------------ */
//-- 编辑包装费用
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'edit_pack_fee')
{
    check_authz_json('pack');

    $id = intval($_POST['id']);
    $val = floatval($_POST['val']);

    /* 取得该属性所属商品类型id */
    $pack_name = $exc->get_name($id);

    $exc->edit("pack_fee='$val'", $id);
    admin_log(addslashes($pack_name), 'edit', 'pack');
    make_json_result(number_format($val, 2));
}

/*------------------------------------------------------ */
//-- 编辑免费额度
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'edit_free_money')
{
    check_authz_json('pack');

    $id = intval($_POST['id']);
    $val = floatval($_POST['val']);

    /* 取得该属性所属商品类型id */
    $pack_name = $exc->get_name($id);

    $exc->edit("free_money='$val'", $id);
    admin_log(addslashes($pack_name), 'edit', 'pack');
    make_json_result(number_format($val, 2));
}

/*------------------------------------------------------ */
//-- 删除包装
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'remove')
{
    check_authz_json('pack');

    $id     = intval($_GET['id']);
    $name   = $exc->get_name($id);
    $img    = $exc->get_name($id, 'pack_img');

    if ($exc->drop($id))
    {
        /* 删除图片 */
        if (!empty($img))
        {
             @unlink('../' . DATA_DIR . '/packimg/'.$img);
        }
        admin_log(addslashes($name),'remove','pack');

        $url = 'pack.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

        ecs_header("Location: $url\n");
        exit;
    }
    else
    {
        make_json_error($_LANG['packremove_falure']);
        return false;
    }
}

function packs_list()
{
    $result = get_filter();
    if ($result === false)
    {
        $filter['sort_by']      = empty($_REQUEST['sort_by']) ? 'pack_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order']   = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $sql = "SELECT count(*) FROM " .$GLOBALS['ecs']->table('pack');
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        /* 分页大小 */
        $filter = page_and_size($filter);

        /* 查询 */
        $sql = "SELECT pack_id, pack_name, pack_img, pack_fee, free_money, pack_desc".
               " FROM ".$GLOBALS['ecs']->table('pack').
               " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
               " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $packs_list = $GLOBALS['db']->getAll($sql);

    $arr = array('packs_list' => $packs_list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

?>