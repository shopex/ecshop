<?php

/**
 * ECSHOP 属性规格管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: attribute.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* act操作项的初始化 */
$_REQUEST['act'] = trim($_REQUEST['act']);
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}

$exc = new exchange($ecs->table("attribute"), $db, 'attr_id', 'attr_name');

/*------------------------------------------------------ */
//-- 属性列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $goods_type = isset($_GET['goods_type']) ? intval($_GET['goods_type']) : 0;

    $smarty->assign('ur_here',          $_LANG['09_attribute_list']);
    $smarty->assign('action_link',      array('href' => 'attribute.php?act=add&goods_type='.$goods_type , 'text' => $_LANG['10_attribute_add']));
    $smarty->assign('goods_type_list',  goods_type_list($goods_type)); // 取得商品类型
    $smarty->assign('full_page',        1);

    $list = get_attrlist();

    $smarty->assign('attr_list',    $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    /* 显示模板 */
    assign_query_info();
    $smarty->display('attribute_list.htm');
}

/*------------------------------------------------------ */
//-- 排序、翻页
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'query')
{
    $list = get_attrlist();

    $smarty->assign('attr_list',    $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('attribute_list.htm'), '',
        array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/*------------------------------------------------------ */
//-- 添加/编辑属性
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv('attr_manage');

    /* 添加还是编辑的标识 */
    $is_add = $_REQUEST['act'] == 'add';
    $smarty->assign('form_act', $is_add ? 'insert' : 'update');

    /* 取得属性信息 */
    if ($is_add)
    {
        $goods_type = isset($_GET['goods_type']) ? intval($_GET['goods_type']) : 0;
        $attr = array(
            'attr_id' => 0,
            'cat_id' => $goods_type,
            'attr_name' => '',
            'attr_input_type' => 0,
            'attr_index'  => 0,
            'attr_values' => '',
            'attr_type' => 0,
            'is_linked' => 0,
        );
    }
    else
    {
        $sql = "SELECT * FROM " . $ecs->table('attribute') . " WHERE attr_id = '$_REQUEST[attr_id]'";
        $attr = $db->getRow($sql);
    }

    $smarty->assign('attr', $attr);
    $smarty->assign('attr_groups', get_attr_groups($attr['cat_id']));

    /* 取得商品分类列表 */
    $smarty->assign('goods_type_list', goods_type_list($attr['cat_id']));

    /* 模板赋值 */
    $smarty->assign('ur_here', $is_add ?$_LANG['10_attribute_add']:$_LANG['52_attribute_add']);
    $smarty->assign('action_link', array('href' => 'attribute.php?act=list', 'text' => $_LANG['09_attribute_list']));

    /* 显示模板 */
    assign_query_info();
    $smarty->display('attribute_info.htm');
}

/*------------------------------------------------------ */
//-- 插入/更新属性
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('attr_manage');

    /* 插入还是更新的标识 */
    $is_insert = $_REQUEST['act'] == 'insert';

    /* 检查名称是否重复 */
    $exclude = empty($_POST['attr_id']) ? 0 : intval($_POST['attr_id']);
    if (!$exc->is_only('attr_name', $_POST['attr_name'], $exclude, " cat_id = '$_POST[cat_id]'"))
    {
        sys_msg($_LANG['name_exist'], 1);
    }

    $cat_id = $_REQUEST['cat_id'];

    /* 取得属性信息 */
    $attr = array(
        'cat_id'          => $_POST['cat_id'],
        'attr_name'       => $_POST['attr_name'],
        'attr_index'      => $_POST['attr_index'],
        'attr_input_type' => $_POST['attr_input_type'],
        'is_linked'       => $_POST['is_linked'],
        'attr_values'     => isset($_POST['attr_values']) ? $_POST['attr_values'] : '',
        'attr_type'       => empty($_POST['attr_type']) ? '0' : intval($_POST['attr_type']),
        'attr_group'      => isset($_POST['attr_group']) ? intval($_POST['attr_group']) : 0
    );

    /* 入库、记录日志、提示信息 */
    if ($is_insert)
    {
        $db->autoExecute($ecs->table('attribute'), $attr, 'INSERT');
        admin_log($_POST['attr_name'], 'add', 'attribute');
        $links = array(
            array('text' => $_LANG['add_next'], 'href' => '?act=add&goods_type=' . $_POST['cat_id']),
            array('text' => $_LANG['back_list'], 'href' => '?act=list'),
        );
        sys_msg(sprintf($_LANG['add_ok'], $attr['attr_name']), 0, $links);
    }
    else
    {
        $db->autoExecute($ecs->table('attribute'), $attr, 'UPDATE', "attr_id = '$_POST[attr_id]'");
        admin_log($_POST['attr_name'], 'edit', 'attribute');
        $links = array(
            array('text' => $_LANG['back_list'], 'href' => '?act=list&amp;goods_type='.$_POST['cat_id'].''),
        );
        sys_msg(sprintf($_LANG['edit_ok'], $attr['attr_name']), 0, $links);
    }
}

/*------------------------------------------------------ */
//-- 删除属性(一个或多个)
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch')
{
    /* 检查权限 */
    admin_priv('attr_manage');

    /* 取得要操作的编号 */
    if (isset($_POST['checkboxes']))
    {
        $count = count($_POST['checkboxes']);
        $ids   = isset($_POST['checkboxes']) ? join(',', $_POST['checkboxes']) : 0;

        $sql = "DELETE FROM " . $ecs->table('attribute') . " WHERE attr_id " . db_create_in($ids);
        $db->query($sql);

        $sql = "DELETE FROM " . $ecs->table('goods_attr') . " WHERE attr_id " . db_create_in($ids);
        $db->query($sql);

        /* 记录日志 */
        admin_log('', 'batch_remove', 'attribute');
        clear_cache_files();

        $link[] = array('text' => $_LANG['back_list'], 'href' => 'attribute.php?act=list');
        sys_msg(sprintf($_LANG['drop_ok'], $count), 0, $link);
    }
    else
    {
        $link[] = array('text' => $_LANG['back_list'], 'href' => 'attribute.php?act=list');
        sys_msg($_LANG['no_select_arrt'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 编辑属性名称
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit_attr_name')
{
    check_authz_json('attr_manage');

    $id = intval($_POST['id']);
    $val = json_str_iconv(trim($_POST['val']));

    /* 取得该属性所属商品类型id */
    $cat_id = $exc->get_name($id, 'cat_id');

    /* 检查属性名称是否重复 */
    if (!$exc->is_only('attr_name', $val, $id, " cat_id = '$cat_id'"))
    {
        make_json_error($_LANG['name_exist']);
    }

    $exc->edit("attr_name='$val'", $id);

    admin_log($val, 'edit', 'attribute');

    make_json_result(stripslashes($val));
}

/*------------------------------------------------------ */
//-- 编辑排序序号
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'edit_sort_order')
{
    check_authz_json('attr_manage');

    $id = intval($_POST['id']);
    $val = intval($_POST['val']);

    $exc->edit("sort_order='$val'", $id);

    admin_log(addslashes($exc->get_name($id)), 'edit', 'attribute');

    make_json_result(stripslashes($val));
}

/*------------------------------------------------------ */
//-- 删除商品属性
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('attr_manage');

    $id = intval($_GET['id']);

    $db->query("DELETE FROM " .$ecs->table('attribute'). " WHERE attr_id='$id'");
    $db->query("DELETE FROM " .$ecs->table('goods_attr'). " WHERE attr_id='$id'");

    $url = 'attribute.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 获取某属性商品数量
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'get_attr_num')
{
    check_authz_json('attr_manage');

    $id = intval($_GET['attr_id']);

    $sql = "SELECT COUNT(*) ".
           " FROM " . $ecs->table('goods_attr') . " AS a, ".
           $ecs->table('goods') . " AS g ".
           " WHERE g.goods_id = a.goods_id AND g.is_delete = 0 AND attr_id = '$id' ";

    $goods_num = $db->getOne($sql);

    if ($goods_num > 0)
    {
        $drop_confirm = sprintf($_LANG['notice_drop_confirm'], $goods_num);
    }
    else
    {
        $drop_confirm = $_LANG['drop_confirm'];
    }

    make_json_result(array('attr_id'=>$id, 'drop_confirm'=>$drop_confirm));
}

/*------------------------------------------------------ */
//-- 获得指定商品类型下的所有属性分组
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'get_attr_groups')
{
    check_authz_json('attr_manage');

    $cat_id = intval($_GET['cat_id']);
    $groups = get_attr_groups($cat_id);

    make_json_result($groups);
}

/*------------------------------------------------------ */
//-- PRIVATE FUNCTIONS
/*------------------------------------------------------ */

/**
 * 获取属性列表
 *
 * @return  array
 */
function get_attrlist()
{
    /* 查询条件 */
    $filter = array();
    $filter['goods_type'] = empty($_REQUEST['goods_type']) ? 0 : intval($_REQUEST['goods_type']);
    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'sort_order' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    $where = (!empty($filter['goods_type'])) ? " WHERE a.cat_id = '$filter[goods_type]' " : '';

    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('attribute') . " AS a $where";
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    /* 分页大小 */
    $filter = page_and_size($filter);

    /* 查询 */
    $sql = "SELECT a.*, t.cat_name " .
            " FROM " . $GLOBALS['ecs']->table('attribute') . " AS a ".
            " LEFT JOIN " . $GLOBALS['ecs']->table('goods_type') . " AS t ON a.cat_id = t.cat_id " . $where .
            " ORDER BY $filter[sort_by] $filter[sort_order] ".
            " LIMIT " . $filter['start'] .", $filter[page_size]";
    $row = $GLOBALS['db']->getAll($sql);

    foreach ($row AS $key => $val)
    {
        $row[$key]['attr_input_type_desc'] = $GLOBALS['_LANG']['value_attr_input_type'][$val['attr_input_type']];
        $row[$key]['attr_values']      = str_replace("\n", ", ", $val['attr_values']);
    }

    $arr = array('item' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}
?>
