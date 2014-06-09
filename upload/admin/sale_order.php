<?php

/**
 * ECSHOP 商品销售排行
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: sale_order.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_order.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/statistic.php');
$smarty->assign('lang', $_LANG);

if (isset($_REQUEST['act']) && ($_REQUEST['act'] == 'query' ||  $_REQUEST['act'] == 'download'))
{
    /* 检查权限 */
    check_authz_json('sale_order_stats');
    if (strstr($_REQUEST['start_date'], '-') === false)
    {
        $_REQUEST['start_date'] = local_date('Y-m-d', $_REQUEST['start_date']);
        $_REQUEST['end_date'] = local_date('Y-m-d', $_REQUEST['end_date']);
    }

    /* 下载报表 */
    if ($_REQUEST['act'] == 'download')
    {
        $goods_order_data = get_sales_order(false);
        $goods_order_data = $goods_order_data['sales_order_data'];

        $filename = $_REQUEST['start_date'] . '_' . $_REQUEST['end_date'] .'sale_order';

        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename.xls");

        $data  = "$_LANG[sell_stats]\t\n";
        $data .= "$_LANG[order_by]\t$_LANG[goods_name]\t$_LANG[goods_sn]\t$_LANG[sell_amount]\t$_LANG[sell_sum]\t$_LANG[percent_count]\n";

        foreach ($goods_order_data AS $k => $row)
        {
            $order_by = $k + 1;
            $data .= "$order_by\t$row[goods_name]\t$row[goods_sn]\t$row[goods_num]\t$row[turnover]\t$row[wvera_price]\n";
        }

        if (EC_CHARSET == 'utf-8')
        {
            echo ecs_iconv(EC_CHARSET, 'GB2312', $data);
        }
        else
        {
            echo $data;
        }
        exit;
    }
    $goods_order_data = get_sales_order();
    $smarty->assign('goods_order_data', $goods_order_data['sales_order_data']);
    $smarty->assign('filter',       $goods_order_data['filter']);
    $smarty->assign('record_count', $goods_order_data['record_count']);
    $smarty->assign('page_count',   $goods_order_data['page_count']);

    $sort_flag  = sort_flag($goods_order_data['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('sale_order.htm'), '', array('filter' => $goods_order_data['filter'], 'page_count' => $goods_order_data['page_count']));
}
else
{
    /* 权限检查 */
    admin_priv('sale_order_stats');

    /* 时间参数 */
    if (!isset($_REQUEST['start_date']))
    {
        $_REQUEST['start_date'] = local_strtotime('-1 months');
    }
    if (!isset($_REQUEST['end_date']))
    {
        $_REQUEST['end_date'] = local_strtotime('+1 day');
    }
    $goods_order_data = get_sales_order();

    /* 赋值到模板 */
    $smarty->assign('ur_here',          $_LANG['sell_stats']);
    $smarty->assign('goods_order_data', $goods_order_data['sales_order_data']);
    $smarty->assign('filter',           $goods_order_data['filter']);
    $smarty->assign('record_count',     $goods_order_data['record_count']);
    $smarty->assign('page_count',       $goods_order_data['page_count']);
    $smarty->assign('filter',           $goods_order_data['filter']);
    $smarty->assign('full_page',        1);
    $smarty->assign('sort_goods_num',   '<img src="images/sort_desc.gif">');
    $smarty->assign('start_date',       local_date('Y-m-d', $_REQUEST['start_date']));
    $smarty->assign('end_date',         local_date('Y-m-d', $_REQUEST['end_date']));
    $smarty->assign('action_link',      array('text' => $_LANG['download_sale_sort'], 'href' => '#download' ));

    /* 显示页面 */
    assign_query_info();
    $smarty->display('sale_order.htm');
}

/*------------------------------------------------------ */
//--排行统计需要的函数
/*------------------------------------------------------ */
/**
 * 取得销售排行数据信息
 * @param   bool  $is_pagination  是否分页
 * @return  array   销售排行数据
 */
function get_sales_order($is_pagination = true)
{
    $filter['start_date'] = empty($_REQUEST['start_date']) ? '' : local_strtotime($_REQUEST['start_date']);
    $filter['end_date'] = empty($_REQUEST['end_date']) ? '' : local_strtotime($_REQUEST['end_date']);
    $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'goods_num' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    $where = " WHERE og.order_id = oi.order_id ". order_query_sql('finished', 'oi.');

    if ($filter['start_date'])
    {
        $where .= " AND oi.add_time >= '" . $filter['start_date'] . "'";
    }
    if ($filter['end_date'])
    {
        $where .= " AND oi.add_time <= '" . $filter['end_date'] . "'";
    }

    $sql = "SELECT COUNT(distinct(og.goods_id)) FROM " .
           $GLOBALS['ecs']->table('order_info') . ' AS oi,'.
           $GLOBALS['ecs']->table('order_goods') . ' AS og '.
           $where;
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    /* 分页大小 */
    $filter = page_and_size($filter);

    $sql = "SELECT og.goods_id, og.goods_sn, og.goods_name, oi.order_status, " .
           "SUM(og.goods_number) AS goods_num, SUM(og.goods_number * og.goods_price) AS turnover ".
           "FROM ".$GLOBALS['ecs']->table('order_goods')." AS og, " .
           $GLOBALS['ecs']->table('order_info')." AS oi  " .$where .
           " GROUP BY og.goods_id ".
           ' ORDER BY ' . $filter['sort_by'] . ' ' . $filter['sort_order'] ;
    if ($is_pagination)
    {
        $sql .= " LIMIT " . $filter['start'] . ', ' . $filter['page_size'];
    }

    $sales_order_data = $GLOBALS['db']->getAll($sql);

    foreach ($sales_order_data as $key => $item)
    {
        $sales_order_data[$key]['wvera_price'] = price_format($item['goods_num'] ? $item['turnover'] / $item['goods_num'] : 0);
        $sales_order_data[$key]['short_name']  = sub_str($item['goods_name'], 30, true);
        $sales_order_data[$key]['turnover']    = price_format($item['turnover']);
        $sales_order_data[$key]['taxis']       = $key + 1;
    }

    $arr = array('sales_order_data' => $sales_order_data, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

?>