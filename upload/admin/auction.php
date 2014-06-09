<?php

/**
 * ECSHOP 管理中心拍卖活动管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: auction.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_goods.php');

$exc = new exchange($ecs->table('goods_activity'), $db, 'act_id', 'act_name');

/*------------------------------------------------------ */
//-- 活动列表页
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list')
{
    /* 检查权限 */
    admin_priv('auction');

    /* 模板赋值 */
    $smarty->assign('full_page',   1);
    $smarty->assign('ur_here',     $_LANG['auction_list']);
    $smarty->assign('action_link', array('href' => 'auction.php?act=add', 'text' => $_LANG['add_auction']));

    $list = auction_list();

    $smarty->assign('auction_list', $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    /* 显示商品列表页面 */
    assign_query_info();
    $smarty->display('auction_list.htm');
}

/*------------------------------------------------------ */
//-- 分页、排序、查询
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'query')
{
    $list = auction_list();

    $smarty->assign('auction_list', $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $sort_flag  = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('auction_list.htm'), '',
        array('filter' => $list['filter'], 'page_count' => $list['page_count']));
}

/*------------------------------------------------------ */
//-- 删除
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('auction');

    $id = intval($_GET['id']);
    $auction = auction_info($id);
    if (empty($auction))
    {
        make_json_error($_LANG['auction_not_exist']);
    }
    if ($auction['bid_user_count'] > 0)
    {
        make_json_error($_LANG['auction_cannot_remove']);
    }
    $name = $auction['act_name'];
    $exc->drop($id);

    /* 记日志 */
    admin_log($name, 'remove', 'auction');

    /* 清除缓存 */
    clear_cache_files();

    $url = 'auction.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

    ecs_header("Location: $url\n");
    exit;
}

/*------------------------------------------------------ */
//-- 批量操作
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch')
{
    /* 取得要操作的记录编号 */
    if (empty($_POST['checkboxes']))
    {
        sys_msg($_LANG['no_record_selected']);
    }
    else
    {
        /* 检查权限 */
        admin_priv('auction');

        $ids = $_POST['checkboxes'];

        if (isset($_POST['drop']))
        {
            /* 查询哪些拍卖活动已经有人出价 */
            $sql = "SELECT DISTINCT act_id FROM " . $ecs->table('auction_log') .
                    " WHERE act_id " . db_create_in($ids);
            $ids = array_diff($ids, $db->getCol($sql));
            if (!empty($ids))
            {
                /* 删除记录 */
                $sql = "DELETE FROM " . $ecs->table('goods_activity') .
                        " WHERE act_id " . db_create_in($ids) .
                        " AND act_type = '" . GAT_AUCTION . "'";
                $db->query($sql);

                /* 记日志 */
                admin_log('', 'batch_remove', 'auction');

                /* 清除缓存 */
                clear_cache_files();
            }
            $links[] = array('text' => $_LANG['back_auction_list'], 'href' => 'auction.php?act=list&' . list_link_postfix());
            sys_msg($_LANG['batch_drop_ok'], 0, $links);
        }
    }
}

/*------------------------------------------------------ */
//-- 查看出价记录
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'view_log')
{
    /* 检查权限 */
    admin_priv('auction');

    /* 参数 */
    if (empty($_GET['id']))
    {
        sys_msg('invalid param');
    }
    $id = intval($_GET['id']);
    $auction = auction_info($id);
    if (empty($auction))
    {
        sys_msg($_LANG['auction_not_exist']);
    }
    $smarty->assign('auction', auction_info($id));

    /* 出价记录 */
    $smarty->assign('auction_log', auction_log($id));

    /* 模板赋值 */
    $smarty->assign('ur_here', $_LANG['auction_log']);
    $smarty->assign('action_link', array('href' => 'auction.php?act=list&' . list_link_postfix(), 'text' => $_LANG['auction_list']));
    assign_query_info();
    $smarty->display('auction_log.htm');
}

/*------------------------------------------------------ */
//-- 添加、编辑
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    /* 检查权限 */
    admin_priv('auction');

    /* 是否添加 */
    $is_add = $_REQUEST['act'] == 'add';
    $smarty->assign('form_action', $is_add ? 'insert' : 'update');

    /* 初始化、取得拍卖活动信息 */
    if ($is_add)
    {
        $auction = array(
            'act_id'        => 0,
            'act_name'      => '',
            'act_desc'      => '',
            'goods_id'      => 0,
            'product_id'    => 0,
            'goods_name'    => $_LANG['pls_search_goods'],
            'start_time'    => date('Y-m-d', time() + 86400),
            'end_time'      => date('Y-m-d', time() + 4 * 86400),
            'deposit'       => 0,
            'start_price'   => 0,
            'end_price'     => 0,
            'amplitude'     => 0
        );
    }
    else
    {
        if (empty($_GET['id']))
        {
            sys_msg('invalid param');
        }
        $id = intval($_GET['id']);
        $auction = auction_info($id, true);
        if (empty($auction))
        {
            sys_msg($_LANG['auction_not_exist']);
        }
        $auction['status'] = $_LANG['auction_status'][$auction['status_no']];
        $smarty->assign('bid_user_count', sprintf($_LANG['bid_user_count'], $auction['bid_user_count']));
    }
    $smarty->assign('auction', $auction);

    /* 赋值时间控件的语言 */
    $smarty->assign('cfg_lang', $_CFG['lang']);

    /* 商品货品表 */
    $smarty->assign('good_products_select', get_good_products_select($auction['goods_id']));

    /* 显示模板 */
    if ($is_add)
    {
        $smarty->assign('ur_here', $_LANG['add_auction']);
    }
    else
    {
        $smarty->assign('ur_here', $_LANG['edit_auction']);
    }
    $smarty->assign('action_link', list_link($is_add));
    assign_query_info();
    $smarty->display('auction_info.htm');
}

/*------------------------------------------------------ */
//-- 添加、编辑后提交
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    /* 检查权限 */
    admin_priv('auction');

    /* 是否添加 */
    $is_add = $_REQUEST['act'] == 'insert';

    /* 检查是否选择了商品 */
    $goods_id = intval($_POST['goods_id']);
    if ($goods_id <= 0)
    {
        sys_msg($_LANG['pls_select_goods']);
    }
    $sql = "SELECT goods_name FROM " . $ecs->table('goods') . " WHERE goods_id = '$goods_id'";
    $row = $db->getRow($sql);
    if (empty($row))
    {
        sys_msg($_LANG['goods_not_exist']);
    }
    $goods_name = $row['goods_name'];

    /* 提交值 */
    $auction = array(
        'act_id'        => intval($_POST['id']),
        'act_name'      => empty($_POST['act_name']) ? $goods_name : sub_str($_POST['act_name'], 255, false),
        'act_desc'      => $_POST['act_desc'],
        'act_type'      => GAT_AUCTION,
        'goods_id'      => $goods_id,
        'product_id'    => empty($_POST['product_id']) ? 0 : $_POST['product_id'],
        'goods_name'    => $goods_name,
        'start_time'    => local_strtotime($_POST['start_time']),
        'end_time'      => local_strtotime($_POST['end_time']),
        'ext_info'      => serialize(array(
                    'deposit'       => round(floatval($_POST['deposit']), 2),
                    'start_price'   => round(floatval($_POST['start_price']), 2),
                    'end_price'     => empty($_POST['no_top']) ? round(floatval($_POST['end_price']), 2) : 0,
                    'amplitude'     => round(floatval($_POST['amplitude']), 2),
                    'no_top'     => !empty($_POST['no_top']) ? intval($_POST['no_top']) : 0
                ))
    );

    /* 保存数据 */
    if ($is_add)
    {
        $auction['is_finished'] = 0;
        $db->autoExecute($ecs->table('goods_activity'), $auction, 'INSERT');
        $auction['act_id'] = $db->insert_id();
    }
    else
    {
        $db->autoExecute($ecs->table('goods_activity'), $auction, 'UPDATE', "act_id = '$auction[act_id]'");
    }

    /* 记日志 */
    if ($is_add)
    {
        admin_log($auction['act_name'], 'add', 'auction');
    }
    else
    {
        admin_log($auction['act_name'], 'edit', 'auction');
    }

    /* 清除缓存 */
    clear_cache_files();

    /* 提示信息 */
    if ($is_add)
    {
        $links = array(
            array('href' => 'auction.php?act=add', 'text' => $_LANG['continue_add_auction']),
            array('href' => 'auction.php?act=list', 'text' => $_LANG['back_auction_list'])
        );
        sys_msg($_LANG['add_auction_ok'], 0, $links);
    }
    else
    {
        $links = array(
            array('href' => 'auction.php?act=list&' . list_link_postfix(), 'text' => $_LANG['back_auction_list'])
        );
        sys_msg($_LANG['edit_auction_ok'], 0, $links);
    }
}

/*------------------------------------------------------ */
//-- 处理冻结资金
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'settle_money')
{
    /* 检查权限 */
    admin_priv('auction');

    /* 检查参数 */
    if (empty($_POST['id']))
    {
        sys_msg('invalid param');
    }
    $id = intval($_POST['id']);
    $auction = auction_info($id);
    if (empty($auction))
    {
        sys_msg($_LANG['auction_not_exist']);
    }
    if ($auction['status_no'] != FINISHED)
    {
        sys_msg($_LANG['invalid_status']);
    }
    if ($auction['deposit'] <= 0)
    {
        sys_msg($_LANG['no_deposit']);
    }

    /* 处理保证金 */
    $exc->edit("is_finished = 2", $id); // 修改状态
    if (isset($_POST['unfreeze']))
    {
        /* 解冻 */
        log_account_change($auction['last_bid']['bid_user'], $auction['deposit'],
            (-1) * $auction['deposit'], 0, 0, sprintf($_LANG['unfreeze_auction_deposit'], $auction['act_name']));
    }
    else
    {
        /* 扣除 */
        log_account_change($auction['last_bid']['bid_user'], 0,
            (-1) * $auction['deposit'], 0, 0, sprintf($_LANG['deduct_auction_deposit'], $auction['act_name']));
    }

    /* 记日志 */
    admin_log($auction['act_name'], 'edit', 'auction');

    /* 清除缓存 */
    clear_cache_files();

    /* 提示信息 */
    sys_msg($_LANG['settle_deposit_ok']);
}

/*------------------------------------------------------ */
//-- 搜索商品
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'search_goods')
{
    check_authz_json('auction');

    include_once(ROOT_PATH . 'includes/cls_json.php');

    $json   = new JSON;
    $filter = $json->decode($_GET['JSON']);
    $arr['goods']    = get_goods_list($filter);

    if (!empty($arr['goods'][0]['goods_id']))
    {
        $arr['products'] = get_good_products($arr['goods'][0]['goods_id']);
    }

    make_json_result($arr);
}

/*------------------------------------------------------ */
//-- 搜索货品
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'search_products')
{
    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    $filters = $json->decode($_GET['JSON']);

    if (!empty($filters->goods_id))
    {
        $arr['products'] = get_good_products($filters->goods_id);
    }

    make_json_result($arr);
}

/*
 * 取得拍卖活动列表
 * @return   array
 */
function auction_list()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤条件 */
        $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['is_going']   = empty($_REQUEST['is_going']) ? 0 : 1;
        $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'act_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $where = "";
        if (!empty($filter['keyword']))
        {
            $where .= " AND goods_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }
        if ($filter['is_going'])
        {
            $now = gmtime();
            $where .= " AND is_finished = 0 AND start_time <= '$now' AND end_time >= '$now' ";
        }

        $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('goods_activity') .
                " WHERE act_type = '" . GAT_AUCTION . "' $where";
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        /* 分页大小 */
        $filter = page_and_size($filter);

        /* 查询 */
        $sql = "SELECT * ".
                "FROM " . $GLOBALS['ecs']->table('goods_activity') .
                " WHERE act_type = '" . GAT_AUCTION . "' $where ".
                " ORDER BY $filter[sort_by] $filter[sort_order] ".
                " LIMIT ". $filter['start'] .", $filter[page_size]";

        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }
    $res = $GLOBALS['db']->query($sql);

    $list = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $ext_info = unserialize($row['ext_info']);
        $arr = array_merge($row, $ext_info);

        $arr['start_time']  = local_date('Y-m-d H:i', $arr['start_time']);
        $arr['end_time']    = local_date('Y-m-d H:i', $arr['end_time']);

        $list[] = $arr;
    }
    $arr = array('item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/**
 * 列表链接
 * @param   bool    $is_add     是否添加（插入）
 * @param   string  $text       文字
 * @return  array('href' => $href, 'text' => $text)
 */
function list_link($is_add = true, $text = '')
{
    $href = 'auction.php?act=list';
    if (!$is_add)
    {
        $href .= '&' . list_link_postfix();
    }
    if ($text == '')
    {
        $text = $GLOBALS['_LANG']['auction_list'];
    }

    return array('href' => $href, 'text' => $text);
}

?>