<?php

/**
 * ECSHOP paypal快速结帐
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: paypal_ec.php 16489 2009-08-03 10:14:03Z liuhui $
 */


        define('API_ENDPOINT', 'https://api-3t.paypal.com/nvp');
        define('USE_PROXY',FALSE);
        define('PROXY_HOST', '127.0.0.1');
        define('PROXY_PORT', '808');
        define('PAYPAL_URL', 'https://www.paypal.com/cgi-bin/webscr&cmd=_express-checkout&token=');

        $API_Endpoint =API_ENDPOINT;
        $version=VERSION;
if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/paypal_ec.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'paypal_ec_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.paypal.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'paypal_ec_username', 'type' => 'text', 'value' => ''),
        array('name' => 'paypal_ec_password', 'type' => 'text', 'value' => ''),
        array('name' => 'paypal_ec_signature', 'type' => 'text', 'value' => ''),
        array('name' => 'paypal_ec_currency', 'type' => 'select', 'value' => 'USD')
    );

    return;
}

/**
 * 类
 */
class paypal_ec
{


    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function paypal_ec()
    {

    }

    function __construct()
    {
        $this->paypal_ec();
    }



    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {

        $token = '';
        $serverName = $_SERVER['SERVER_NAME'];
        $serverPort = $_SERVER['SERVER_PORT'];
        $url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);
        $paymentAmount=$order['order_amount'];
        $currencyCodeType=$payment['paypal_ec_currency'];
        $paymentType='Sale';
        $data_order_id      = $order['log_id'];

        $_SESSION['paypal_username']=$payment['paypal_ec_username'];
        $_SESSION['paypal_password']=$payment['paypal_ec_password'];
        $_SESSION['paypal_signature']=$payment['paypal_ec_signature'];

        $returnURL =urlencode($url.'/respond.php?code=paypal_ec&currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType.'&paymentAmount='.$paymentAmount.'&invoice='.$data_order_id);
        $cancelURL =urlencode("$url/SetExpressCheckout.php?paymentType=$paymentType" );

        $nvpstr="&Amt=".$paymentAmount."&PAYMENTACTION=".$paymentType."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType ."&ButtonSource=ECSHOP_cart_EC_C2";

        $resArray=$this->hash_call("SetExpressCheckout",$nvpstr);

        $_SESSION['reshash']=$resArray;
        if(isset($resArray["ACK"]))
        {
            $ack = strtoupper($resArray["ACK"]);
        }
        
        if (isset($resArray["TOKEN"]))
        {
            $token = urldecode($resArray["TOKEN"]);
        }            
            $payPalURL = PAYPAL_URL.$token;
            $button = '<div style="text-align:center"><input type="button" onclick="window.open(\''.$payPalURL. '\')" value="' .$GLOBALS['_LANG']['pay_button']. '"/></div>';

        return $button;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        $order_sn = $_REQUEST['invoice'];
        $token =urlencode( $_REQUEST['token']);
        $nvpstr="&TOKEN=".$token;
        $resArray=$this->hash_call("GetExpressCheckoutDetails",$nvpstr);
        $_SESSION['reshash']=$resArray;
        $ack = strtoupper($resArray["ACK"]);
        if($ack=="SUCCESS")
        {
            $_SESSION['token']=$_REQUEST['token'];
            $_SESSION['payer_id'] = $_REQUEST['PayerID'];

            $_SESSION['paymentAmount']=$_REQUEST['paymentAmount'];
            $_SESSION['currCodeType']=$_REQUEST['currencyCodeType'];
            $_SESSION['paymentType']=$_REQUEST['paymentType'];

            $resArray=$_SESSION['reshash'];
            $token =urlencode( $_SESSION['token']);

            $paymentAmount =urlencode ($_SESSION['paymentAmount']);
            $paymentType = urlencode($_SESSION['paymentType']);
            $currCodeType = urlencode($_SESSION['currCodeType']);
            $payerID = urlencode($_SESSION['payer_id']);
            $serverName = urlencode($_SERVER['SERVER_NAME']);

            $nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION='.$paymentType.'&AMT='.$paymentAmount.'&CURRENCYCODE='.$currCodeType.'&IPADDRESS='.$serverName ;

            $resArray=$this->hash_call("DoExpressCheckoutPayment",$nvpstr);
            
            $ack = strtoupper($resArray["ACK"]);
            if($ack=="SUCCESS")
            {
                /* 改变订单状态 */
                order_paid($order_sn, 2);
                return true;
            }
            else
             {
                return false;
             }
        }
        else
         {
            return false;
         }
    }

    function hash_call($methodName,$nvpStr)
    {
        global $API_Endpoint;
        $version='53.0';
        $API_UserName=$_SESSION['paypal_username'];
        $API_Password=$_SESSION['paypal_password'];
        $API_Signature=$_SESSION['paypal_signature'];
        $nvp_Header;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);

        if(USE_PROXY)
        {
            curl_setopt ($ch, CURLOPT_PROXY, PROXY_HOST.":".PROXY_PORT);
        }

        $nvpreq="METHOD=".urlencode($methodName)."&VERSION=".urlencode($version)."&PWD=".urlencode($API_Password)."&USER=".urlencode($API_UserName)."&SIGNATURE=".urlencode($API_Signature).$nvpStr;

        curl_setopt($ch,CURLOPT_POSTFIELDS,$nvpreq);

        $response = curl_exec($ch);

        $nvpResArray=$this->deformatNVP($response);
        
        $nvpReqArray=$this->deformatNVP($nvpreq);

        $_SESSION['nvpReqArray']=$nvpReqArray;

        if (curl_errno($ch))
        {
            $_SESSION['curl_error_no']=curl_errno($ch) ;
            $_SESSION['curl_error_msg']=curl_error($ch);
        }
        else
        {
            curl_close($ch);
        }

        return $nvpResArray;
    }


    function deformatNVP($nvpstr)
    {

        $intial=0;
        $nvpArray = array();

        while(strlen($nvpstr))
        {
            $keypos= strpos($nvpstr,'=');
            $valuepos = strpos($nvpstr,'&') ? strpos($nvpstr,'&'): strlen($nvpstr);
            $keyval=substr($nvpstr,$intial,$keypos);
            $valval=substr($nvpstr,$keypos+1,$valuepos-$keypos-1);
            $nvpArray[urldecode($keyval)] =urldecode( $valval);
            $nvpstr=substr($nvpstr,$valuepos+1,strlen($nvpstr));
        }

        return $nvpArray;
    }

}

?>