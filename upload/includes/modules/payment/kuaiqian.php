<?php

/**
 * ECSHOP 快钱插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: kuaiqian.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/payment/kuaiqian.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/**
 * 模块信息
 */
if (isset($set_modules) && $set_modules == true)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'kuaiqian_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod'] = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online'] = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.99bill.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.2';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'kq_account', 'type' => 'text', 'value' => ''),
        array('name' => 'kq_key', 'type' => 'text', 'value' => ''),
    );

    return;

}

class kuaiqian
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */

    function kuaiqian()
    {
    }

    function __construct()
    {
        $this->kuaiqian();
    }

   /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
   function get_code($order, $payment)
   {
       $merchant_acctid    = trim($payment['kq_account']);                 //人民币账号 不可空
       $key                = trim($payment['kq_key']);
       $input_charset      = 1;                                                //字符集 默认1=utf-8
       $page_url           = return_url(basename(__FILE__, '.php'));
       $bg_url             = '';
       $version            = 'v2.0';
       $language           = 1;
       $sign_type          = 1;                                                //签名类型 不可空 固定值 1:md5
       $payer_name         = '';
       $payer_contact_type = '';
       $payer_contact      = '';
       $order_id           = $order['order_sn'];                                    //商户订单号 不可空
       $order_amount       = $order['order_amount'] * 100;                        //商户订单金额 不可空
       $order_time         = local_date('YmdHis', $order['add_time']);            //商户订单提交时间 不可空 14位
       $product_name       = '';
       $product_num        = '';
       $product_id         = '';
       $product_desc       = '';
       $ext1               = $order['log_id'];
       $ext2               = '';
       $pay_type           = '00';                                                //支付方式 不可空
       $bank_id            = '';
       $redo_flag          = '0';
       $pid                = '';

        /* 生成加密签名串 请务必按照如下顺序和规则组成加密串！*/
        $signmsgval = '';
        $signmsgval = $this->append_param($signmsgval, "inputCharset", $input_charset);
        $signmsgval = $this->append_param($signmsgval, "pageUrl", $page_url);
        $signmsgval = $this->append_param($signmsgval, "bgUrl", $bg_url);
        $signmsgval = $this->append_param($signmsgval, "version", $version);
        $signmsgval = $this->append_param($signmsgval, "language", $language);
        $signmsgval = $this->append_param($signmsgval, "signType", $sign_type);
        $signmsgval = $this->append_param($signmsgval, "merchantAcctId", $merchant_acctid);
        $signmsgval = $this->append_param($signmsgval, "payerName", $payer_name);
        $signmsgval = $this->append_param($signmsgval, "payerContactType", $payer_contact_type);
        $signmsgval = $this->append_param($signmsgval, "payerContact", $payer_contact);
        $signmsgval = $this->append_param($signmsgval, "orderId", $order_id);
        $signmsgval = $this->append_param($signmsgval, "orderAmount", $order_amount);
        $signmsgval = $this->append_param($signmsgval, "orderTime", $order_time);
        $signmsgval = $this->append_param($signmsgval, "productName", $product_name);
        $signmsgval = $this->append_param($signmsgval, "productNum", $product_num);
        $signmsgval = $this->append_param($signmsgval, "productId", $product_id);
        $signmsgval = $this->append_param($signmsgval, "productDesc", $product_desc);
        $signmsgval = $this->append_param($signmsgval, "ext1", $ext1);
        $signmsgval = $this->append_param($signmsgval, "ext2", $ext2);
        $signmsgval = $this->append_param($signmsgval, "payType", $pay_type);
        $signmsgval = $this->append_param($signmsgval, "bankId", $bank_id);
        $signmsgval = $this->append_param($signmsgval, "redoFlag", $redo_flag);
        $signmsgval = $this->append_param($signmsgval, "pid", $pid);
        $signmsgval = $this->append_param($signmsgval, "key", $key);
        $signmsg    = strtoupper(md5($signmsgval));    //签名字符串 不可空


        $def_url  = '<div style="text-align:center"><form name="kqPay" style="text-align:center;" method="post" action="https://www.99bill.com/gateway/recvMerchantInfoAction.htm" target="_blank">';
        $def_url .= "<input type='hidden' name='inputCharset' value='" . $input_charset . "' />";
        $def_url .= "<input type='hidden' name='bgUrl' value='" . $bg_url . "' />";
        $def_url .= "<input type='hidden' name='pageUrl' value='" . $page_url . "' />";
        $def_url .= "<input type='hidden' name='version' value='" . $version . "' />";
        $def_url .= "<input type='hidden' name='language' value='" . $language . "' />";
        $def_url .= "<input type='hidden' name='signType' value='" . $sign_type . "' />";
        $def_url .= "<input type='hidden' name='signMsg' value='" . $signmsg . "' />";
        $def_url .= "<input type='hidden' name='merchantAcctId' value='" . $merchant_acctid . "' />";
        $def_url .= "<input type='hidden' name='payerName' value='" . $payer_name . "' />";
        $def_url .= "<input type='hidden' name='payerContactType' value='" . $payer_contact_type . "' />";
        $def_url .= "<input type='hidden' name='payerContact' value='" . $payer_contact . "' />";
        $def_url .= "<input type='hidden' name='orderId' value='" . $order_id . "' />";
        $def_url .= "<input type='hidden' name='orderAmount' value='" . $order_amount . "' />";
        $def_url .= "<input type='hidden' name='orderTime' value='" . $order_time . "' />";
        $def_url .= "<input type='hidden' name='productName' value='" . $product_name . "' />";
        $def_url .= "<input type='hidden' name='payType' value='" . $pay_type . "' />";
        $def_url .= "<input type='hidden' name='productNum' value='" . $product_num . "' />";
        $def_url .= "<input type='hidden' name='productId' value='" . $product_id . "' />";
        $def_url .= "<input type='hidden' name='productDesc' value='" . $product_desc . "' />";
        $def_url .= "<input type='hidden' name='ext1' value='" . $ext1 . "' />";
        $def_url .= "<input type='hidden' name='ext2' value='" . $ext2 . "' />";
        $def_url .= "<input type='hidden' name='bankId' value='" . $bank_id . "' />";
        $def_url .= "<input type='hidden' name='redoFlag' value='" . $redo_flag ."' />";
        $def_url .= "<input type='hidden' name='pid' value='" . $pid . "' />";
        $def_url .= "<input type='submit' name='submit' value='" . $GLOBALS['_LANG']['pay_button'] . "' />";
        $def_url .= "</form></div></br>";

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        $payment             = get_payment($_GET['code']);
        $merchant_acctid     = $payment['kq_account'];                 //人民币账号 不可空
        $key                 = $payment['kq_key'];
        $get_merchant_acctid = trim($_REQUEST['merchantAcctId']);
        $pay_result          = trim($_REQUEST['payResult']);
        $version             = trim($_REQUEST['version']);
        $language            = trim($_REQUEST['language']);
        $sign_type           = trim($_REQUEST['signType']);
        $pay_type            = trim($_REQUEST['payType']);
        $bank_id             = trim($_REQUEST['bankId']);
        $order_id            = trim($_REQUEST['orderId']);
        $order_time          = trim($_REQUEST['orderTime']);
        $order_amount        = trim($_REQUEST['orderAmount']);
        $deal_id             = trim($_REQUEST['dealId']);
        $bank_deal_id        = trim($_REQUEST['bankDealId']);
        $deal_time           = trim($_REQUEST['dealTime']);
        $pay_amount          = trim($_REQUEST['payAmount']);
        $fee                 = trim($_REQUEST['fee']);
        $ext1                = trim($_REQUEST['ext1']);
        $ext2                = trim($_REQUEST['ext2']);
        $err_code            = trim($_REQUEST['errCode']);
        $sign_msg            = trim($_REQUEST['signMsg']);

        //生成加密串。必须保持如下顺序。
        $merchant_signmsgval = '';
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"merchantAcctId",$merchant_acctid);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"version",$version);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"language",$language);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"signType",$sign_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payType",$pay_type);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"bankId",$bank_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderId",$order_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderTime",$order_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"orderAmount",$order_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"dealId",$deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"bankDealId",$bank_deal_id);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"dealTime",$deal_time);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payAmount",$pay_amount);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"fee",$fee);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"ext1",$ext1);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"ext2",$ext2);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"payResult",$pay_result);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"errCode",$err_code);
        $merchant_signmsgval = $this->append_param($merchant_signmsgval,"key",$key);
        $merchant_signmsg    = md5($merchant_signmsgval);

        //首先对获得的商户号进行比对
        if ($get_merchant_acctid != $merchant_acctid)
        {
            //商户号错误
            return false;
        }

        if (strtoupper($sign_msg) == strtoupper($merchant_signmsg))
        {
            if ($pay_result == 10 || $pay_result == 00)
            {
                order_paid($ext1);

                return true;
            }
            else
            {
                //'支付结果失败';
                return false;
            }

        }
        else
        {
            //'密钥校对错误';
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
            if($key != '' && $val != '')
            {
                $strs .= '&' . $key . '=' . $val;
            }
        }
        else
        {
            if($val != '')
            {
                $strs = $key . '=' . $val;
            }
        }
            return $strs;
    }

}

?>