<?php

/**
 * ECSHOP 快钱神州行支付插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: shenzhou.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/shenzhou.php';

if (file_exists($payment_lang))
{
   global $_LANG;

   include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == true)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'shenzhou_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.99bill.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.1';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'shenzhou_account', 'type' => 'text', 'value' => ''),
        array('name' => 'shenzhou_key',     'type' => 'text', 'value' => ''),
    );

    return;

}

class shenzhou
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */

    function shenzhou()
    {
    }

    function __construct()
    {
        $this->shenzhou();
    }

   /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        $merchant_acctid    = trim($payment['shenzhou_account']);                 //快钱神州行账号 不可空
        $key                = trim($payment['shenzhou_key']);                     //密钥 不可空
        $input_charset      = 1;                                               //字符集 默认1=utf-8
        $bg_url             = '';
        $page_url           = $GLOBALS['ecs']->url() . 'respond.php';
        $version            = 'v2.0';
        $language           = 1;
        $sign_type          = 1;                                               //签名类型 不可空 固定值 1:md5
        $payer_name         = '';
        $payer_contact_type = '';
        $payer_contact      = '';
        $order_id           = $order['order_sn'];                                //商户订单号 不可空
        $order_amount       = $order['order_amount'] * 100;                    //商户订单金额 不可空
        $pay_type           = '00';                                            //支付方式 不可空
        $card_number        = '';
        $card_pwd           = '';
        $full_amount_flag   = '0';
        $order_time         = local_date('YmdHis', $order['add_time']);        //商户订单提交时间 不可空 14位
        $product_name       = '';
        $product_num        = '';
        $product_id         = '';
        $product_desc       = '';
        $ext1               = $order['log_id'];
        $ext2               = 'ecshop';

        /* 生成加密签名串 请务必按照如下顺序和规则组成加密串！*/
        $signmsgval = '';
        $signmsgval = $this->append_param($signmsgval, "inputCharset", $input_charset);
        $signmsgval = $this->append_param($signmsgval, "bgUrl", $bg_url);
        $signmsgval = $this->append_param($signmsgval, "pageUrl", $page_url);
        $signmsgval = $this->append_param($signmsgval, "version", $version);
        $signmsgval = $this->append_param($signmsgval, "language", $language);
        $signmsgval = $this->append_param($signmsgval, "signType", $sign_type);
        $signmsgval = $this->append_param($signmsgval, "merchantAcctId", $merchant_acctid);
        $signmsgval = $this->append_param($signmsgval, "payerName", urlencode($payer_name));
        $signmsgval = $this->append_param($signmsgval, "payerContactType", $payer_contact_type);
        $signmsgval = $this->append_param($signmsgval, "payerContact", $payer_contact);
        $signmsgval = $this->append_param($signmsgval, "orderId", $order_id);
        $signmsgval = $this->append_param($signmsgval, "orderAmount", $order_amount);
        $signmsgval = $this->append_param($signmsgval, "payType", $pay_type);
        $signmsgval = $this->append_param($signmsgval, "cardNumber", $card_number);
        $signmsgval = $this->append_param($signmsgval, "cardPwd", $card_pwd);
        $signmsgval = $this->append_param($signmsgval, "fullAmountFlag", $full_amount_flag);
        $signmsgval = $this->append_param($signmsgval, "orderTime", $order_time);
        $signmsgval = $this->append_param($signmsgval, "productName", urlencode($product_name));
        $signmsgval = $this->append_param($signmsgval, "productNum", $product_num);
        $signmsgval = $this->append_param($signmsgval, "productId", $product_id);
        $signmsgval = $this->append_param($signmsgval, "productDesc", urlencode($product_desc));
        $signmsgval = $this->append_param($signmsgval, "ext1", urlencode($ext1));
        $signmsgval = $this->append_param($signmsgval, "ext2", urlencode($ext2));
        $signmsgval = $this->append_param($signmsgval, "key", $key);
        $sign_msg    = strtoupper(md5($signmsgval));    //安全校验域 不可空

        $def_url  = '<div style="text-align:center"><form name="kqPay" style="text-align:center;" method="post"'.
        'action="https://www.99bill.com/szxgateway/recvMerchantInfoAction.htm" target="_blank">';
        $def_url .= "<input type= 'hidden' name='inputCharset' value='" . $input_charset . "' />";
        $def_url .= "<input type='hidden' name='bgUrl' value='" . $bg_url . "' />";
        $def_url .= "<input type='hidden' name='pageUrl' value='" . $page_url . "' />";
        $def_url .= "<input type='hidden' name='version' value='" . $version . "' />";
        $def_url .= "<input type='hidden' name='language' value='" . $language . "' />";
        $def_url .= "<input type='hidden' name='signType' value='" . $sign_type . "' />";
        $def_url .= "<input type='hidden' name='merchantAcctId' value='" . $merchant_acctid . "' />";
        $def_url .= "<input type='hidden' name='payerName' value='" . $payer_name . "' />";
        $def_url .= "<input type='hidden' name='payerContactType' value='" . $payer_contact_type . "' />";
        $def_url .= "<input type='hidden' name='payerContact' value='" . $payer_contact . "' />";
        $def_url .= "<input type='hidden' name='orderId' value='" . $order_id . "' />";
        $def_url .= "<input type='hidden' name='orderAmount' value='" . $order_amount . "' />";
        $def_url .= "<input type='hidden' name='payType' value='" . $pay_type . "' />";
        $def_url .= "<input type='hidden' name='cardNumber' value='" . $card_number . "' />";
        $def_url .= "<input type='hidden' name='cardPwd' value='" . $card_pwd . "' />";
        $def_url .= "<input type='hidden' name='fullAmountFlag' value='" .$full_amount_flag ."' />";
        $def_url .= "<input type='hidden' name='orderTime' value='" . $order_time . "' />";
        $def_url .= "<input type='hidden' name='productName' value='" . urlencode($product_name) . "' />";
        $def_url .= "<input type='hidden' name='productNum' value='" . $product_num . "' />";
        $def_url .= "<input type='hidden' name='productId' value='" . $product_id . "' />";
        $def_url .= "<input type='hidden' name='productDesc' value='" . urlencode($product_desc) . "' />";
        $def_url .= "<input type='hidden' name='ext1' value='" . urlencode($ext1) . "' />";
        $def_url .= "<input type='hidden' name='ext2' value='" . urlencode($ext2) . "' />";
        $def_url .= "<input type='hidden' name='signMsg' value='" . $sign_msg ."' />";
        $def_url .= "<input type='submit' name='submit' value='".$GLOBALS['_LANG']['pay_button']."' />";
        $def_url .= "</form></div><br />";

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        $payment             = get_payment(basename(__FILE__, '.php'));
        $merchant_acctid     = $payment['shenzhou_account'];                 //收款帐号 不可空
        $key                 = $payment['shenzhou_key'];
        $get_merchant_acctid = trim($_REQUEST['merchantAcctId']);     //接收的收款帐号
        $pay_result          = trim($_REQUEST['payResult']);
        $version             = trim($_REQUEST['version']);
        $language            = trim($_REQUEST['language']);
        $sign_type           = trim($_REQUEST['signType']);
        $pay_type            = trim($_REQUEST['payType']);            //20代表神州行卡密直接支付；22代表快钱账户神州行余额支付
        $card_umber          = trim($_REQUEST['cardNumber']);
        $card_pwd            = trim($_REQUEST['cardPwd']);
        $order_id            = trim($_REQUEST['orderId']);            //订单号
        $order_time          = trim($_REQUEST['orderTime']);
        $order_amount        = trim($_REQUEST['orderAmount']);
        $deal_id             = trim($_REQUEST['dealId']);             //获取该交易在快钱的交易号
        $ext1                = trim($_REQUEST['ext1']);
        $ext2                = trim($_REQUEST['ext2']);
        $pay_amount          = trim($_REQUEST['payAmount']);          //获取实际支付金额
        $bill_order_time     = trim($_REQUEST['billOrderTime']);
        $pay_result          = trim($_REQUEST['payResult']);         //10代表支付成功； 11代表支付失败
        $sign_type           = trim($_REQUEST['signType']);
        $sign_msg            = trim($_REQUEST['signMsg']);

        //生成加密串。必须保持如下顺序。
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "merchantAcctId", $merchant_acctid);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "version", $version);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "language", $language);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "payType", $pay_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "cardNumber", $card_number);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "cardPwd", $card_pwd);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "orderId", $order_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "orderAmount", $order_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "dealId", $deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "orderTime", $order_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "ext1", $ext1);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "ext2", $ext2);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "payAmount", $pay_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "billOrderTime", $bill_order_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "payResult", $pay_result);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "signType", $sign_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval, "key", $key);
        $merchant_signmsg    = md5($merchant_signmsgval);

        //首先对获得的商户号进行比对
        if ($get_merchant_acctid != $merchant_acctid)
        {
            //'商户号错误';
            return false;
        }

        if (strtoupper($sign_msg) == strtoupper($merchant_signmsg))
        {
            if ($pay_result == 10)  //有成功支付的结果返回10
            {
                order_paid($ext1);

                return true;
            }
            elseif ($pay_result == 11  && $pay_amount > 0)
            {
                $sql = "SELECT order_amount FROM " . $GLOBALS['ecs']->table('order_info') ."WHERE order_id = '$order_id'";
                $get_order_amount = $GLOBALS['db']->getOne($sql);
                if ($get_order_amount == $pay_amount && $get_order_amount == $order_amount) //检查订单金额、实际支付金额和订单是否相等
                {
                    order_paid($ext1);

                    return true;
                }
                elseif ($get_order_amount == $order_amount && $pay_amount > 0) //订单金额相等 实际支付金额 > 0的情况
                {
                    $surplus_amount = $get_order_amount - $pay_amount;        //计算订单剩余金额
                    $sql = 'UPDATE' . $GLOBALS['ecs']->table('order_info') . "SET `money_paid` = (money_paid  + '$pay_amount')," .
                        " order_amount = (order_amount - '$pay_amount') WHERE order_id = '$order_id'";
                    $result = $GLOBALS['db']->query($sql);
                    $sql = 'UPDATE' . $GLOBALS['ecs']->table('order_info') . "SET `order_status` ='" . OS_CONFIRMED . "' WHERE order_id = '$orderId'";
                    $result = $GLOBALS['db']->query($sql);
                    //order_paid($orderId, PS_UNPAYED);
                    //'订单金额小于0';
                    return false;
                }
                else
                {
                    //'订单金额不相等';
                    return false;
                }
            }
            else
            {
                //'实际支付金额不能小于0';
                return false;
            }
        }
        else
        {
            //'签名校对错误';
            return false;
        }
    }

    /**
     * 将变量值不为空的参数组成字符串
     * @param   string   $strs  参数字符串
     * @param   string   $key   参数键名
     * @param   string   $val   参数键对应值
    */
    function append_param($strs,$key,$val)
    {
        if($strs != "")
        {
            if($val != "")
            {
                $strs .= '&' . $key . '=' . $val;
            }
        }
        else
        {
            if($val != "")
            {
                $strs = $key . '=' . $val;
            }
        }

        return $strs;
    }
}

?>