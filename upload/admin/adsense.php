<?php

/**
 * ECSHOP 站外JS投放的统计程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: adsense.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_order.php');
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

/*------------------------------------------------------ */
//-- 站外投放广告的统计
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list' || $_REQUEST['act'] == 'download')
{
    admin_priv('ad_manage');

    /* 获取广告数据 */
    $ads_stats = array();
    $sql = "SELECT a.ad_id, a.ad_name, b.* ".
           "FROM " .$ecs->table('ad'). " AS a, " .$ecs->table('adsense'). " AS b ".
           "WHERE b.from_ad = a.ad_id ORDER by a.ad_name DESC";
    $res = $db->query($sql);
    while ($rows = $db->fetchRow($res))
    {
        /* 获取当前广告所产生的订单总数 */
        $rows['referer']=addslashes($rows['referer']);
        $sql2 = 'SELECT COUNT(order_id) FROM ' .$ecs->table('order_info'). " WHERE from_ad='$rows[ad_id]' AND referer='$rows[referer]'";
        $rows['order_num'] = $db->getOne($sql2);

        /* 当前广告所产生的已完成的有效订单 */
        $sql3 = "SELECT COUNT(order_id) FROM " .$ecs->table('order_info').
               " WHERE from_ad    = '$rows[ad_id]'" .
               " AND referer = '$rows[referer]' ". order_query_sql('finished');
        $rows['order_confirm'] = $db->getOne($sql3);

        $ads_stats[] = $rows;
    }
    $smarty->assign('ads_stats',        $ads_stats);

    /* 站外JS投放商品的统计数据 */
    $goods_stats    = array();
    $goods_sql      = "SELECT from_ad, referer, clicks FROM " .$ecs->table('adsense').
              " WHERE from_ad = '-1' ORDER by referer DESC";
    $goods_res = $db->query($goods_sql);
    while ($rows2 = $db->fetchRow($goods_res))
    {
        /* 获取当前广告所产生的订单总数 */
        $rows2['referer']=addslashes($rows2['referer']);
        $rows2['order_num'] = $db->getOne("SELECT COUNT(order_id) FROM " .$ecs->table('order_info'). " WHERE referer='$rows2[referer]'");

        /* 当前广告所产生的已完成的有效订单 */

        $sql = "SELECT COUNT(order_id) FROM " .$ecs->table('order_info').
               " WHERE referer='$rows2[referer]'" . order_query_sql('finished');
        $rows2['order_confirm'] = $db->getOne($sql);

        $rows2['ad_name']  = $_LANG['adsense_js_goods'];
        $goods_stats[]  = $rows2;
    }
    if ($_REQUEST['act'] == 'download')
    {
        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=ad_statistics.xls");
        $data = "$_LANG[adsense_name]\t$_LANG[cleck_referer]\t$_LANG[click_count]\t$_LANG[confirm_order]\t$_LANG[gen_order_amount]\n";
        $res = array_merge($goods_stats, $ads_stats);
        foreach ($res AS $row)
        {
            $data .= "$row[ad_name]\t$row[referer]\t$row[clicks]\t$row[order_confirm]\t$row[order_num]\n";
        }
        echo ecs_iconv(EC_CHARSET, 'GB2312', $data);
        exit;
    }
    $smarty->assign('goods_stats', $goods_stats);

    /* 赋值给模板 */
    $smarty->assign('action_link', array('href' => 'ads.php?act=list', 'text' => $_LANG['ad_list']));
    $smarty->assign('action_link2', array('href' => 'adsense.php?act=download', 'text' => $_LANG['download_ad_statistics']));
    $smarty->assign('ur_here',     $_LANG['adsense_js_stats']);
    $smarty->assign('lang',        $_LANG);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('adsense.htm');
}

?>