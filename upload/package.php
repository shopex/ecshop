<?php

/**
 * ECSHOP 超值礼包列表
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: activity.php 16056 2009-05-21 05:44:14Z liubo $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_order.php');
include_once(ROOT_PATH . 'includes/lib_transaction.php');

/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/package.php');

/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

assign_template();
assign_dynamic('package');
$position = assign_ur_here(0, $_LANG['shopping_package']);
$smarty->assign('page_title',       $position['title']);    // 页面标题
$smarty->assign('ur_here',          $position['ur_here']);  // 当前位置

/* 读出所有礼包信息 */

$now = gmtime();

$sql = "SELECT * FROM " . $ecs->table('goods_activity'). " WHERE `start_time` <= '$now' AND `end_time` >= '$now' AND `act_type` = '4' ORDER BY `end_time`";
$res = $db->query($sql);

$list = array();
while ($row = $db->fetchRow($res))
{
    $row['start_time']  = local_date('Y-m-d H:i', $row['start_time']);
    $row['end_time']    = local_date('Y-m-d H:i', $row['end_time']);
    $ext_arr = unserialize($row['ext_info']);
    unset($row['ext_info']);
    if ($ext_arr)
    {
        foreach ($ext_arr as $key=>$val)
        {
            $row[$key] = $val;
        }
    }

    $sql = "SELECT pg.package_id, pg.goods_id, pg.goods_number, pg.admin_id, ".
           " g.goods_sn, g.goods_name, g.market_price, g.goods_thumb, ".
           " IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS rank_price " .
           " FROM " . $GLOBALS['ecs']->table('package_goods') . " AS pg ".
           "   LEFT JOIN ". $GLOBALS['ecs']->table('goods') . " AS g ".
           "   ON g.goods_id = pg.goods_id ".
           " LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
           " WHERE pg.package_id = " . $row['act_id']. " ".
           " ORDER BY pg.goods_id";

    $goods_res = $GLOBALS['db']->getAll($sql);

    $subtotal = 0;
    foreach($goods_res as $key => $val)
    {
        $goods_res[$key]['goods_thumb']  = get_image_path($val['goods_id'], $val['goods_thumb'], true);
        $goods_res[$key]['market_price'] = price_format($val['market_price']);
        $goods_res[$key]['rank_price']   = price_format($val['rank_price']);
        $subtotal += $val['rank_price'] * $val['goods_number'];
    }


    $row['goods_list']    = $goods_res;
    $row['subtotal']      = price_format($subtotal);
    $row['saving']        = price_format(($subtotal - $row['package_price']));
    $row['package_price'] = price_format($row['package_price']);

    $list[] = $row;
}

$smarty->assign('list',             $list);

$smarty->assign('helps',            get_shop_help());       // 网店帮助
$smarty->assign('lang',             $_LANG);

$smarty->assign('feed_url',         ($_CFG['rewrite'] == 1) ? "feed-typepackage.xml" : 'feed.php?type=package'); // RSS URL
$smarty->display('package.dwt');

