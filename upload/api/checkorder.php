<?php

/**
 * ECSHOP 检查订单 API
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: checkorder.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require('./init.php');
require_once(ROOT_PATH . 'includes/lib_order.php');
require_once('../includes/cls_json.php');

$json = new JSON;

$res = array('error' => 0, 'new_orders' => 0, 'new_paid' => 0);
$_REQUEST['username'] = urlencode(serialize(json_str_iconv($_REQUEST['username'])));

/* 检查密码是否正确 */
$sql = "SELECT COUNT(*) ".
        " FROM " . $ecs->table('admin_user') .
        " WHERE user_name = '" . trim($_REQUEST['username']). "' AND password = '" . md5(trim($_REQUEST['password'])) . "'";

if ($db->getOne($sql))
{
    /* 新订单 */
    $sql = 'SELECT COUNT(*) FROM ' . $ecs->table('order_info').
            " WHERE order_status = " . OS_UNCONFIRMED;
    $res['new_orders'] = $db->getOne($sql);

    /* 待发货的订单： */
    $sql   = 'SELECT COUNT(*)'.
                ' FROM ' .$ecs->table('order_info') .
                " WHERE 1 ". order_query_sql('await_ship');
    $res['new_paid']  = $db->getOne($sql);
}
else
{
    $res['error'] = 1;
}

$val = $json->encode($res);

die($val);

?>