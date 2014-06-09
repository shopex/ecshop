<?php

/**
 * ECSHOP 会员资金管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: testyang $
 * $Id: user_account_manage.php 15013 2008-10-23 09:31:42Z testyang $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_order.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/statistic.php');
$smarty->assign('lang', $_LANG);

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

/* 权限判断 */
admin_priv('user_account_manage');

/*------------------------------------------------------ */
//--数据查询
/*------------------------------------------------------ */
/* 时间参数 */

$start_date = $end_date = '';
if (isset($_POST) && !empty($_POST))
{
    $start_date = local_strtotime($_POST['start_date']);
    $end_date = local_strtotime($_POST['end_date']);
}
elseif (isset($_GET['start_date']) && !empty($_GET['end_date']))
{
    $start_date = local_strtotime($_GET['start_date']);
    $end_date = local_strtotime($_GET['end_date']);
}
else
{
    $today  = local_strtotime(local_date('Y-m-d'));
    $start_date = $today - 86400 * 7;
    $end_date   = $today;
}

/*------------------------------------------------------ */
//--商品明细列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    $account = $money_list = array();
    $account['voucher_amount'] = get_total_amount($start_date, $end_date);//充值总额
    $account['to_cash_amount'] = get_total_amount($start_date, $end_date, 1);//提现总额

    $sql = " SELECT IFNULL(SUM(user_money), 0) AS user_money, IFNULL(SUM(frozen_money), 0) AS frozen_money FROM " .
           $ecs->table('account_log') . " WHERE `change_time` >= " . $start_date ." AND `change_time` < " .($end_date+86400);
    $money_list = $db->getRow($sql);
    $account['user_money']     = price_format($money_list['user_money']);   //用户可用余额
    $account['frozen_money']   = price_format($money_list['frozen_money']);   //用户冻结金额

    $sql = "SELECT IFNULL(SUM(surplus), 0) AS surplus, IFNULL(SUM(integral_money), 0) AS integral_money FROM ".
           $ecs->table('order_info') ." WHERE 1 AND `add_time` >= " . $start_date ." AND `add_time` < " .($end_date+86400);
    $money_list = $db->getRow($sql);

    $account['surplus']        = price_format($money_list['surplus']);   //交易使用余额
    $account['integral_money'] = price_format($money_list['integral_money']);   //积分使用余额

    /* 赋值到模板 */
    $smarty->assign('account', $account);
    $smarty->assign('start_date',   local_date('Y-m-d', $start_date));
    $smarty->assign('end_date',     local_date('Y-m-d', $end_date));
    $smarty->assign('ur_here',      $_LANG['user_account_manage']);

    /* 显示页面 */
    assign_query_info();
    $smarty->display('user_account_manage.htm');
}

elseif ($_REQUEST['act'] == 'surplus')
{
    $order_list = order_list();

    /* 赋值到模板 */
    $smarty->assign('order_list',   $order_list['order_list']);
    $smarty->assign('ur_here',      $_LANG['order_by_surplus']);
    $smarty->assign('filter',       $order_list['filter']);
    $smarty->assign('record_count', $order_list['record_count']);
    $smarty->assign('page_count',   $order_list['page_count']);
    $smarty->assign('full_page',    1);
    $smarty->assign('action_link',  array('text' => $_LANG['user_account_manage'], 'href'=>'user_account_manage.php?act=list&start_date='.local_date('Y-m-d', $start_date).'&end_date='.local_date('Y-m-d', $end_date)));

    /* 显示页面 */
    assign_query_info();
    $smarty->display('order_surplus_list.htm');
}

/*------------------------------------------------------ */
//-- ajax返回用户列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'query')
{

    $order_list = order_list();

    $smarty->assign('order_list',   $order_list['order_list']);
    $smarty->assign('filter',       $order_list['filter']);
    $smarty->assign('record_count', $order_list['record_count']);
    $smarty->assign('page_count',   $order_list['page_count']);

    $sort_flag  = sort_flag($order_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('order_surplus_list.htm'), '', array('filter' => $order_list['filter'], 'page_count' => $order_list['page_count']));
}

/**
* 获得账户变动金额
* @param   string  $type   0,充值 1,提现
* @return  array
*/
function get_total_amount ($start_date, $end_date, $type=0)
{
    $sql = " SELECT IFNULL(SUM(amount), 0) AS total_amount FROM " . $GLOBALS['ecs']->table('user_account') . " AS a, " . $GLOBALS['ecs']->table('users') . " AS u ".
       " WHERE process_type = $type AND is_paid = 1 AND a.user_id = u.user_id AND paid_time >= '$start_date' AND paid_time < '" . ($end_date + 86400) . "'";

    $amount = $GLOBALS['db']->getone($sql);
    $amount = $type ? price_format(abs($amount)) : price_format($amount);
    return $amount;
}


/**
 *  返回用户订单列表数据
 *
 * @access  public
 * @param
 *
 * @return void
 */
function order_list()
{
    global $start_date, $end_date;

    $result = get_filter();

    if ($result === false)
    {
        /* 过滤条件 */
        $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
        {
            $filter['keywords'] = json_str_iconv($filter['keywords']);
        }

        $filter['sort_by']    = empty($_REQUEST['sort_by'])    ? 'order_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC'     : trim($_REQUEST['sort_order']);
        $filter['start_date'] = local_date('Y-m-d', $start_date);
        $filter['end_date']   = local_date('Y-m-d', $end_date);

        $ex_where = ' WHERE 1 ';
        if ($filter['keywords'])
        {
            $ex_where .= " AND user_name LIKE '%" . mysql_like_quote($filter['keywords']) ."%'";
        }
        $ex_where .= " AND o.user_id = u.user_id AND (o.surplus != 0 OR integral_money != 0) AND `add_time` >= " . $start_date ." AND `add_time` < " .($end_date + 86400);
        $filter['record_count'] = $GLOBALS['db']->getOne("SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('order_info') ." AS o, " . $GLOBALS['ecs']->table('users') ." AS u " . $ex_where);

        /* 分页大小 */
        $filter = page_and_size($filter);

        $sql = "SELECT o.order_id, o.order_sn, u.user_name, o.surplus, o.integral_money, o.add_time FROM ".
            $GLOBALS['ecs']->table('order_info') ." AS o," . $GLOBALS['ecs']->table('users')." AS u " . $ex_where .
            " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
            " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

        $filter['keywords'] = stripslashes($filter['keywords']);
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $order_list = $GLOBALS['db']->getAll($sql);

    $count = count($order_list);
    for ($i=0; $i<$count; $i++)
    {
        $order_list[$i]['add_time'] = local_date($GLOBALS['_CFG']['date_format'], $order_list[$i]['add_time']);
    }

    $arr = array('order_list' => $order_list, 'filter' => $filter,
        'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}
?>