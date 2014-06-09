<?php

/**
 * ECSHOP 客户统计
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: guest_stats.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_order.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/statistic.php');

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
//-- 客户统计列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 权限判断 */
    admin_priv('client_flow_stats');

    /* 取得会员总数 */
    $users      =& init_users();
    $sql = "SELECT COUNT(*) FROM " . $ecs->table("users");
    $res = $db->getCol($sql);
    $user_num   = $res[0];


    /* 计算订单各种费用之和的语句 */
    $total_fee = " SUM(" . order_amount_field() . ") AS turnover ";

    /* 有过订单的会员数 */
    $sql = 'SELECT COUNT(DISTINCT user_id) FROM ' .$ecs->table('order_info').
           " WHERE user_id > 0 " . order_query_sql('finished');
    $have_order_usernum = $db->getOne($sql);

    /* 会员订单总数和订单总购物额 */
    $user_all_order = array();
    $sql = "SELECT COUNT(*) AS order_num, " . $total_fee.
           "FROM " .$ecs->table('order_info').
           " WHERE user_id > 0 " . order_query_sql('finished');
    $user_all_order = $db->getRow($sql);
    $user_all_order['turnover'] = floatval($user_all_order['turnover']);

    /* 匿名会员订单总数和总购物额 */
    $guest_all_order = array();
    $sql = "SELECT COUNT(*) AS order_num, " . $total_fee.
           "FROM " .$ecs->table('order_info').
           " WHERE user_id = 0 " . order_query_sql('finished');
    $guest_all_order = $db->getRow($sql);

    /* 匿名会员平均订单额: 购物总额/订单数 */
    $guest_order_amount = ($guest_all_order['order_num'] > 0) ? floatval($guest_all_order['turnover'] / $guest_all_order['order_num']) : '0.00';

    $_GET['flag'] = isset($_GET['flag']) ? 'download' : '';
    if($_GET['flag'] == 'download')
    {
        $filename = ecs_iconv(EC_CHARSET, 'GB2312', $_LANG['guest_statistics']);

        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$filename.xls");

        /* 生成会员购买率 */
        $data  = $_LANG['percent_buy_member'] . "\t\n";
        $data .= $_LANG['member_count'] . "\t" . $_LANG['order_member_count'] . "\t" .
                $_LANG['member_order_count'] . "\t" . $_LANG['percent_buy_member'] . "\n";

        $data .= $user_num . "\t" . $have_order_usernum . "\t" .
                $user_all_order['order_num'] . "\t" . sprintf("%0.2f", ($user_num > 0 ? $have_order_usernum / $user_num : 0) * 100) . "\n\n";

        /* 每会员平均订单数及购物额 */
        $data .= $_LANG['order_turnover_peruser'] . "\t\n";

        $data .= $_LANG['member_sum'] . "\t" . $_LANG['average_member_order'] . "\t" .
                $_LANG['member_order_sum'] . "\n";

        $ave_user_ordernum = $user_num > 0 ? sprintf("%0.2f", $user_all_order['order_num'] / $user_num) : 0;
        $ave_user_turnover = $user_num > 0 ? price_format($user_all_order['turnover'] / $user_num) : 0;

        $data .= price_format($user_all_order['turnover']) . "\t" . $ave_user_ordernum . "\t" . $ave_user_turnover . "\n\n";

        /* 每会员平均订单数及购物额 */
        $data .= $_LANG['order_turnover_percus'] . "\t\n";
        $data .= $_LANG['guest_member_orderamount'] . "\t" . $_LANG['guest_member_ordercount'] . "\t" .
                $_LANG['guest_order_sum'] . "\n";

        $order_num = $guest_all_order['order_num'] > 0 ? price_format($guest_all_order['turnover'] / $guest_all_order['order_num']) : 0;
        $data .= price_format($guest_all_order['turnover']) . "\t" . $guest_all_order['order_num'] . "\t" .
                $order_num;

        echo ecs_iconv(EC_CHARSET, 'GB2312', $data) . "\t";
        exit;
    }

    /* 赋值到模板 */
    $smarty->assign('user_num',            $user_num);                    // 会员总数
    $smarty->assign('have_order_usernum',  $have_order_usernum);          // 有过订单的会员数
    $smarty->assign('user_order_turnover', $user_all_order['order_num']); // 会员总订单数
    $smarty->assign('user_all_turnover',   price_format($user_all_order['turnover']));  //会员购物总额
    $smarty->assign('guest_all_turnover',  price_format($guest_all_order['turnover'])); //匿名会员购物总额
    $smarty->assign('guest_order_num',     $guest_all_order['order_num']);              //匿名会员订单总数

    /* 每会员订单数 */
    $smarty->assign('ave_user_ordernum',  $user_num > 0 ? sprintf("%0.2f", $user_all_order['order_num'] / $user_num) : 0);

    /* 每会员购物额 */
    $smarty->assign('ave_user_turnover',  $user_num > 0 ? price_format($user_all_order['turnover'] / $user_num) : 0);

    /* 注册会员购买率 */
    $smarty->assign('user_ratio', sprintf("%0.2f", ($user_num > 0 ? $have_order_usernum / $user_num : 0) * 100));

     /* 匿名会员平均订单额 */
    $smarty->assign('guest_order_amount', $guest_all_order['order_num'] > 0 ? price_format($guest_all_order['turnover'] / $guest_all_order['order_num']) : 0);

    $smarty->assign('all_order',          $user_all_order);    //所有订单总数以及所有购物总额
    $smarty->assign('ur_here',            $_LANG['report_guest']);
    $smarty->assign('lang',               $_LANG);

    $smarty->assign('action_link',  array('text' => $_LANG['down_guest_stats'],
          'href'=>'guest_stats.php?flag=download'));

    assign_query_info();
    $smarty->display('guest_stats.htm');
}

?>