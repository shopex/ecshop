<?php

/**
 * ECSHOP 商品页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: order.php 15013 2008-10-23 09:31:42Z liuhui $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_order.php');
require(ROOT_PATH . 'includes/lib_payment.php');

$flow_type = 0;
    $_LANG['gram'] = '克';
    $_LANG['kilogram'] = '千克';
    $tips = '订单已经生成，如果您已经登陆，可以在用户中心查看订单详情!';

if ($_SESSION['user_id'] > 0)
{
    $smarty->assign('user_name', $_SESSION['user_name']);
}

if($_REQUEST['act'] == 'order_lise')
{
    if(empty($_POST['country']) || empty($_POST['province']) || empty($_POST['city']))
    {
        echo '配送区域不可为空！';
        exit;
    }
    if(empty($_POST['consignee']))
    {
        echo '收货人姓名不可为空！';
        exit;
    }
    if(empty($_POST['email']))
    {
        echo 'email不可为空！';
        exit;
    }
    if(empty($_POST['address']))
    {
        echo '详细地址不可为空！';
        exit;
    }
    if(empty($_POST['zipcode']))
    {
        echo '邮编不可为空！';
        exit;
    }
    if(empty($_POST['tel']))
    {
        echo '电话不可为空！';
        exit;
    }
        /*
         * 保存收货人信息
         */
        $consignee = array(
            'address_id'    => empty($_POST['address_id']) ? 0  : intval($_POST['address_id']),
            'consignee'     => empty($_POST['consignee'])  ? '' : compile_str(trim($_POST['consignee'])),
            'country'       => empty($_POST['country'])    ? '' : intval($_POST['country']),
            'province'      => empty($_POST['province'])   ? '' : intval($_POST['province']),
            'city'          => empty($_POST['city'])       ? '' : intval($_POST['city']),
            'district'      => empty($_POST['district'])   ? '' : intval($_POST['district']),
            'email'         => empty($_POST['email'])      ? '' : compile_str($_POST['email']),
            'address'       => empty($_POST['address'])    ? '' : compile_str($_POST['address']),
            'zipcode'       => empty($_POST['zipcode'])    ? '' : compile_str(make_semiangle(trim($_POST['zipcode']))),
            'tel'           => empty($_POST['tel'])        ? '' : compile_str(make_semiangle(trim($_POST['tel']))),
            'mobile'        => empty($_POST['mobile'])     ? '' : compile_str(make_semiangle(trim($_POST['mobile']))),
            'sign_building' => empty($_POST['sign_building']) ? '' : compile_str($_POST['sign_building']),
            'best_time'     => empty($_POST['best_time'])  ? '' : compile_str($_POST['best_time']),
        );

        if ($_SESSION['user_id'] > 0)
        {
            include_once(ROOT_PATH . 'includes/lib_transaction.php');

            /* 如果用户已经登录，则保存收货人信息 */
            $consignee['user_id'] = $_SESSION['user_id'];

            save_consignee($consignee, true);
        }

        /* 保存到session */
        $_SESSION['flow_consignee'] = stripslashes_deep($consignee);

    /* 检查购物车中是否有商品 */
    $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
        " WHERE session_id = '" . SESS_ID . "' " .
        "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";

    if ($db->getOne($sql) == 0)
    {
        $tips = '您的购物车中没有商品';
    }

    $consignee = get_consignee($_SESSION['user_id']);

    $_SESSION['flow_consignee'] = $consignee;
    $smarty->assign('consignee', $consignee);

    /* 对商品信息赋值 */
    $cart_goods = cart_goods($flow_type); // 取得商品列表，计算合计
    $smarty->assign('goods_list', $cart_goods);



    /*
     * 取得订单信息
     */
    $order = flow_order_info();
    $smarty->assign('order', $order);

    $_LANG['shopping_money'] = '购物金额小计 %s';
    $_LANG['than_market_price'] = '比市场价 %s 节省了 %s (%s)';
    /*
     * 计算订单的费用
     */
    $total = order_fee($order, $cart_goods, $consignee);
    $smarty->assign('shopping_money', sprintf($_LANG['shopping_money'], $total['formated_goods_price']));
    $smarty->assign('market_price_desc', sprintf($_LANG['than_market_price'], $total['formated_market_price'], $total['formated_saving'], $total['save_rate']));

    $smarty->assign('total', $total);

    /* 取得配送列表 */
    $region            = array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']);
    $shipping_list     = available_shipping_list($region);
    $cart_weight_price = cart_weight_price($flow_type);
    $insure_disabled   = true;
    $cod_disabled      = true;

    // 查看购物车中是否全为免运费商品，若是则把运费赋为零
    $sql = 'SELECT count(*) FROM ' . $ecs->table('cart') . " WHERE `session_id` = '" . SESS_ID. "' AND `extension_code` != 'package_buy' AND `is_shipping` = 0";
    $shipping_count = $db->getOne($sql);

    foreach ($shipping_list AS $key => $val)
    {
        $shipping_cfg = unserialize_config($val['configure']);
        $shipping_fee = ($shipping_count == 0 AND $cart_weight_price['free_shipping'] == 1) ? 0 : shipping_fee($val['shipping_code'], unserialize($val['configure']),
        $cart_weight_price['weight'], $cart_weight_price['amount'], $cart_weight_price['number']);

        $shipping_list[$key]['format_shipping_fee'] = price_format($shipping_fee, false);
        $shipping_list[$key]['shipping_fee']        = $shipping_fee;
        $shipping_list[$key]['free_money']          = price_format($shipping_cfg['free_money'], false);
        $shipping_list[$key]['insure_formated']     = strpos($val['insure'], '%') === false ?
            price_format($val['insure'], false) : $val['insure'];

    }

    $smarty->assign('shipping_list',   $shipping_list);
    $smarty->assign('insure_disabled', $insure_disabled);
    $smarty->assign('cod_disabled',    $cod_disabled);
    
        /* 取得支付列表 */
    if ($order['shipping_id'] == 0)
    {
        $cod        = true;
        $cod_fee    = 0;
    }
    else
    {
        $shipping = shipping_info($order['shipping_id']);
        $cod = $shipping['support_cod'];

        if ($cod)
        {
            /* 如果是团购，且保证金大于0，不能使用货到付款 */
            if ($flow_type == CART_GROUP_BUY_GOODS)
            {
                $group_buy_id = $_SESSION['extension_id'];
                if ($group_buy_id <= 0)
                {
                    show_message('error group_buy_id');
                }
                $group_buy = group_buy_info($group_buy_id);
                if (empty($group_buy))
                {
                    show_message('group buy not exists: ' . $group_buy_id);
                }

                if ($group_buy['deposit'] > 0)
                {
                    $cod = false;
                    $cod_fee = 0;

                    /* 赋值保证金 */
                    $smarty->assign('gb_deposit', $group_buy['deposit']);
                }
            }

            if ($cod)
            {
                $shipping_area_info = shipping_area_info($order['shipping_id'], $region);
                $cod_fee            = $shipping_area_info['pay_fee'];
            }
        }
        else
        {
            $cod_fee = 0;
        }
    }

    // 给货到付款的手续费加<span id>，以便改变配送的时候动态显示
    $payment_list = available_payment_list(1, $cod_fee);
    if(isset($payment_list))
    {
        foreach ($payment_list as $key => $payment)
        {
            if ($payment['is_cod'] == '1')
            {
                $payment_list[$key]['format_pay_fee'] = '<span id="ECS_CODFEE">' . $payment['format_pay_fee'] . '</span>';
            }
            /* 如果有易宝神州行支付 如果订单金额大于300 则不显示 */
            if ($payment['pay_code'] == 'yeepayszx' && $total['amount'] > 300)
            {
                unset($payment_list[$key]);
            }
            /* 如果有余额支付 */
            if ($payment['pay_code'] == 'balance')
            {
                /* 如果未登录，不显示 */
                if ($_SESSION['user_id'] == 0)
                {
                    unset($payment_list[$key]);
                }
                else
                {
                    if ($_SESSION['flow_order']['pay_id'] == $payment['pay_id'])
                    {
                        $smarty->assign('disable_surplus', 1);
                    }
                }
            }
        }
    }
    $smarty->assign('payment_list', $payment_list);

    $user_info = user_info($_SESSION['user_id']);

    /* 如果使用余额，取得用户余额 */
    if ((!isset($_CFG['use_surplus']) || $_CFG['use_surplus'] == '1')
        && $_SESSION['user_id'] > 0
        && $user_info['user_money'] > 0)
    {
        // 能使用余额
        $smarty->assign('allow_use_surplus', 1);
        $smarty->assign('your_surplus', $user_info['user_money']);
    }

    /* 保存 session */
    $_SESSION['flow_order'] = $order;

    $smarty->assign('footer', get_footer());
    $smarty->display('order.html');
    exit;
}
elseif($_REQUEST['act'] = 'done')
{
    /*------------------------------------------------------ */
    //-- 完成所有订单操作，提交到数据库
    /*------------------------------------------------------ */

    include_once('includes/lib_clips.php');

    /* 检查购物车中是否有商品 */
    $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
        " WHERE session_id = '" . SESS_ID . "' " .
        "AND parent_id = 0 AND is_gift = 0 AND rec_type = '$flow_type'";
    if ($db->getOne($sql) == 0)
    {
        $tips = '您的购物车中没有商品';

    }

    /* 检查商品库存 */
    /* 如果使用库存，且下订单时减库存，则减少库存 */
    if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE)
    {
        $cart_goods_stock = get_cart_goods();
        $_cart_goods_stock = array();
        foreach ($cart_goods_stock['goods_list'] as $value)
        {
            $_cart_goods_stock[$value['rec_id']] = $value['goods_number'];
        }
        flow_cart_stock($_cart_goods_stock);
        unset($cart_goods_stock, $_cart_goods_stock);
    }



    $consignee = get_consignee($_SESSION['user_id']);



    $_POST['how_oos'] = isset($_POST['how_oos']) ? intval($_POST['how_oos']) : 0;
    $_POST['card_message'] = isset($_POST['card_message']) ? htmlspecialchars($_POST['card_message']) : '';
    $_POST['inv_type'] = !empty($_POST['inv_type']) ? htmlspecialchars($_POST['inv_type']) : '';
    $_POST['inv_payee'] = isset($_POST['inv_payee']) ? htmlspecialchars($_POST['inv_payee']) : '';
    $_POST['inv_content'] = isset($_POST['inv_content']) ? htmlspecialchars($_POST['inv_content']) : '';
    $_POST['postscript'] = isset($_POST['postscript']) ? htmlspecialchars($_POST['postscript']) : '';

    $order = array(
        'shipping_id'     => intval($_POST['shipping']),
        'pay_id'          => isset($_POST['payment']) ? intval($_POST['payment']) : '0',
        'pack_id'         => isset($_POST['pack']) ? intval($_POST['pack']) : 0,
        'card_id'         => isset($_POST['card']) ? intval($_POST['card']) : 0,
        'card_message'    => trim($_POST['card_message']),
        'surplus'         => isset($_POST['surplus']) ? floatval($_POST['surplus']) : 0.00,
        'integral'        => isset($_POST['integral']) ? intval($_POST['integral']) : 0,
        'bonus_id'        => isset($_POST['bonus']) ? intval($_POST['bonus']) : 0,
        'need_inv'        => empty($_POST['need_inv']) ? 0 : 1,
        'inv_type'        => $_POST['inv_type'],
        'inv_payee'       => trim($_POST['inv_payee']),
        'inv_content'     => $_POST['inv_content'],
        'postscript'      => trim($_POST['postscript']),
        'how_oos'         => isset($_LANG['oos'][$_POST['how_oos']]) ? addslashes($_LANG['oos'][$_POST['how_oos']]) : '',
        'need_insure'     => isset($_POST['need_insure']) ? intval($_POST['need_insure']) : 0,
        'user_id'         => $_SESSION['user_id'],
        'add_time'        => gmtime(),
        'order_status'    => OS_UNCONFIRMED,
        'shipping_status' => SS_UNSHIPPED,
        'pay_status'      => PS_UNPAYED,
        'agency_id'       => get_agency_by_regions(array($consignee['country'], $consignee['province'], $consignee['city'], $consignee['district']))
        );

    /* 扩展信息 */
    if (isset($_SESSION['flow_type']) && intval($_SESSION['flow_type']) != CART_GENERAL_GOODS)
    {
        $order['extension_code'] = $_SESSION['extension_code'];
        $order['extension_id'] = $_SESSION['extension_id'];
    }
    else
    {
        $order['extension_code'] = '';
        $order['extension_id'] = 0;
    }

    /* 检查积分余额是否合法 */
    $user_id = $_SESSION['user_id'];
    if ($user_id > 0)
    {
        $user_info = user_info($user_id);

        $order['surplus'] = min($order['surplus'], $user_info['user_money'] + $user_info['credit_line']);
        if ($order['surplus'] < 0)
        {
            $order['surplus'] = 0;
        }

        // 查询用户有多少积分
        $flow_points = flow_available_points();  // 该订单允许使用的积分
        $user_points = $user_info['pay_points']; // 用户的积分总数

        $order['integral'] = min($order['integral'], $user_points, $flow_points);
        if ($order['integral'] < 0)
        {
            $order['integral'] = 0;
        }
    }
    else
    {
        $order['surplus']  = 0;
        $order['integral'] = 0;
    }


    /* 订单中的商品 */
    $cart_goods = cart_goods($flow_type);

    if (empty($cart_goods))
    {
        $tips = '您的购物车中没有商品';
    }


    /* 收货人信息 */
    foreach ($consignee as $key => $value)
    {
        $order[$key] = addslashes($value);
    }

    /* 订单中的总额 */
    $total = order_fee($order, $cart_goods, $consignee);

    $order['bonus']        = $total['bonus'];
    $order['goods_amount'] = $total['goods_price'];
    $order['discount']     = $total['discount'];
    $order['surplus']      = $total['surplus'];
    $order['tax']          = $total['tax'];


    /* 配送方式 */
    if ($order['shipping_id'] > 0)
    {
        $shipping = shipping_info($order['shipping_id']);
        $order['shipping_name'] = addslashes($shipping['shipping_name']);
    }
    $order['shipping_fee'] = $total['shipping_fee'];
    $order['insure_fee']   = $total['shipping_insure'];

    /* 支付方式 */
    if ($order['pay_id'] > 0)
    {
        $payment = payment_info($order['pay_id']);
        $order['pay_name'] = addslashes($payment['pay_name']);
    }
    $order['pay_fee'] = $total['pay_fee'];
    $order['cod_fee'] = $total['cod_fee'];

    $order['integral_money']   = $total['integral_money'];
    $order['integral']         = $total['integral'];

    if ($order['extension_code'] == 'exchange_goods')
    {
        $order['integral_money']   = 0;
        $order['integral']         = $total['exchange_integral'];
    }

    $order['from_ad']          = !empty($_SESSION['from_ad']) ? $_SESSION['from_ad'] : '0';
    $order['referer']          = !empty($_SESSION['referer']) ? addslashes($_SESSION['referer']) : '';
    
    $order['order_amount']  = number_format($total['amount'], 2, '.', '');
    /* 如果全部使用余额支付，检查余额是否足够 */
    if ($payment['pay_code'] == 'balance' && $order['order_amount'] > 0)
    {
        if($order['surplus'] >0) //余额支付里如果输入了一个金额
        {
            $order['order_amount'] = $order['order_amount'] + $order['surplus'];
            $order['surplus'] = 0;
        }
        if ($order['order_amount'] > ($user_info['user_money'] + $user_info['credit_line']))
        {
            $tips = '您的余额不足以支付整个订单，以未付款方式下单';

        }
        else
        {
            $order['surplus'] = $order['order_amount'];
            $order['order_amount'] = 0;
        }
    }
    
     /* 如果订单金额为0（使用余额或积分或红包支付），修改订单状态为已确认、已付款 */
    if ($order['order_amount'] <= 0)
    {
        $order['order_status'] = OS_CONFIRMED;
        $order['confirm_time'] = gmtime();
        $order['pay_status']   = PS_PAYED;
        $order['pay_time']     = gmtime();
        $order['order_amount'] = 0;
    }

    $order['integral_money']   = $total['integral_money'];
    $order['integral']         = $total['integral'];

    if ($order['extension_code'] == 'exchange_goods')
    {
        $order['integral_money']   = 0;
        $order['integral']         = $total['exchange_integral'];
    }

    $order['from_ad']          = !empty($_SESSION['from_ad']) ? $_SESSION['from_ad'] : '0';
    $order['referer']          = !empty($_SESSION['referer']) ? addslashes($_SESSION['referer']) : '';

    /* 记录扩展信息 */
    if ($flow_type != CART_GENERAL_GOODS)
    {
        $order['extension_code'] = $_SESSION['extension_code'];
        $order['extension_id'] = $_SESSION['extension_id'];
    }

    $affiliate = unserialize($_CFG['affiliate']);


    /* 插入订单表 */
    $error_no = 0;
    do
    {
        $order['order_sn'] = get_order_sn(); //获取新订单号
        $GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $order, 'INSERT');

        $error_no = $GLOBALS['db']->errno();

        if ($error_no > 0 && $error_no != 1062)
        {
            die($GLOBALS['db']->errorMsg());
        }
    }
    while ($error_no == 1062); //如果是订单号重复则重新提交数据

    $new_order_id = $db->insert_id();
    $order['order_id'] = $new_order_id;

    /* 插入订单商品 */
    $sql = "INSERT INTO " . $ecs->table('order_goods') . "( " .
                "order_id, goods_id, goods_name, goods_sn, goods_number, market_price, ".
                "goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id) ".
            " SELECT '$new_order_id', goods_id, goods_name, goods_sn, goods_number, market_price, ".
                "goods_price, goods_attr, is_real, extension_code, parent_id, is_gift, goods_attr_id".
            " FROM " .$ecs->table('cart') .
            " WHERE session_id = '".SESS_ID."' AND rec_type = '$flow_type'";
    $db->query($sql);


    /* 处理余额、积分、红包 */
    if ($order['user_id'] > 0 && $order['surplus'] > 0)
    {
        log_account_change($order['user_id'], $order['surplus'] * (-1), 0, 0, 0, sprintf('支付订单 %s', $order['order_sn']));
    }
    if ($order['user_id'] > 0 && $order['integral'] > 0)
    {
        log_account_change($order['user_id'], 0, 0, 0, $order['integral'] * (-1), sprintf('支付订单 %s', $order['order_sn']));
    }
    /* 如果使用库存，且下订单时减库存，则减少库存 */
    if ($_CFG['use_storage'] == '1' && $_CFG['stock_dec_time'] == SDT_PLACE)
    {
        change_order_goods_storage($order['order_id'], true, SDT_PLACE);
    }


    /* 清空购物车 */
    clear_cart($flow_type);
    /* 清除缓存，否则买了商品，但是前台页面读取缓存，商品数量不减少 */
    clear_all_files();

    if(!empty($order['shipping_name']))
    {
        $order['shipping_name']=trim(stripcslashes($order['shipping_name']));
    }
    /* 取得支付信息，生成支付代码 */
    if ($order['order_amount'] > 0)
    {
        $payment = payment_info($order['pay_id']);

        include_once('includes/modules/payment/' . $payment['pay_code'] . '.php');

        $pay_obj    = new $payment['pay_code'];
        $order['log_id'] = insert_pay_log($new_order_id, $order['order_amount'], PAY_ORDER);
        $pay_online = $pay_obj->get_code($order, unserialize_config($payment['pay_config']));

        $order['pay_desc'] = $payment['pay_desc'];

        $smarty->assign('pay_online', $pay_online);
    }

    /* 订单信息 */
    $smarty->assign('order',      $order);
    $smarty->assign('total',      $total);
    $smarty->assign('goods_list', $cart_goods);
    $smarty->assign('order_submit_back', sprintf('您可以 %s 或去 %s', '<a href="index.php">返回首页</a>', '<a href="user.php">用户中心</a>')); // 返回提示

    unset($_SESSION['flow_consignee']); // 清除session中保存的收货人信息
    unset($_SESSION['flow_order']);
    unset($_SESSION['direct_shopping']);

    if ($_SESSION['user_id'] > 0)
    {
        $smarty->assign('user_name', $_SESSION['user_name']);
    }
    $smarty->assign('footer', get_footer());
    $smarty->assign('tips', $tips);
    $smarty->display('order_done.html');
    exit;

}

function flow_available_points()
{
    $sql = "SELECT SUM(g.integral * c.goods_number) ".
            "FROM " . $GLOBALS['ecs']->table('cart') . " AS c, " . $GLOBALS['ecs']->table('goods') . " AS g " .
            "WHERE c.session_id = '" . SESS_ID . "' AND c.goods_id = g.goods_id AND c.is_gift = 0 AND g.integral > 0 " .
            "AND c.rec_type = '" . CART_GENERAL_GOODS . "'";

    $val = intval($GLOBALS['db']->getOne($sql));

    return integral_of_value($val);
}

/**
 * 检查订单中商品库存
 *
 * @access  public
 * @param   array   $arr
 *
 * @return  void
 */
function flow_cart_stock($arr)
{
    foreach ($arr AS $key => $val)
    {
        $val = intval(make_semiangle($val));
        if ($val <= 0)
        {
            continue;
        }

        $sql = "SELECT `goods_id`, `goods_attr_id`, `extension_code` FROM" .$GLOBALS['ecs']->table('cart').
               " WHERE rec_id='$key' AND session_id='" . SESS_ID . "'";
        $goods = $GLOBALS['db']->getRow($sql);

        $sql = "SELECT g.goods_name, g.goods_number, c.product_id ".
                "FROM " .$GLOBALS['ecs']->table('goods'). " AS g, ".
                    $GLOBALS['ecs']->table('cart'). " AS c ".
                "WHERE g.goods_id = c.goods_id AND c.rec_id = '$key'";
        $row = $GLOBALS['db']->getRow($sql);

        //系统启用了库存，检查输入的商品数量是否有效
        if (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] != 'package_buy')
        {
            if ($row['goods_number'] < $val)
            {
                show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'],
                $row['goods_number'], $row['goods_number']));
                exit;
            }

            /* 是货品 */
            $row['product_id'] = trim($row['product_id']);
            if (!empty($row['product_id']))
            {
                $sql = "SELECT product_number FROM " .$GLOBALS['ecs']->table('products'). " WHERE goods_id = '" . $goods['goods_id'] . "' AND product_id = '" . $row['product_id'] . "'";
                $product_number = $GLOBALS['db']->getOne($sql);
                if ($product_number < $val)
                {
                    show_message(sprintf($GLOBALS['_LANG']['stock_insufficiency'], $row['goods_name'],
                    $row['goods_number'], $row['goods_number']));
                    exit;
                }
            }
        }
        elseif (intval($GLOBALS['_CFG']['use_storage']) > 0 && $goods['extension_code'] == 'package_buy')
        {
            if (judge_package_stock($goods['goods_id'], $val))
            {
                show_message($GLOBALS['_LANG']['package_stock_insufficiency']);
                exit;
            }
        }
    }

}
?>