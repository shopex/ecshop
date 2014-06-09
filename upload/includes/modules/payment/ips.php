<?php

/**
 * ECSHOP ips支付系统插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author:     xuan yan <xuanyan1983@gmail.com>
 * @version:    v1.0
 * ---------------------------------------------
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/ips.php';
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
    $modules[$i]['desc']    = 'ips_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.ips.com.cn';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'ips_account',  'type' => 'text',   'value' => ''),
        array('name' => 'ips_key',      'type' => 'text',   'value' => ''),
        array('name' => 'ips_currency', 'type' => 'select', 'value' => '01'),
        array('name' => 'ips_lang',     'type' => 'select', 'value' => 'GB')
    );

    return;
}

class ips
{
   /**
    * 构造函数
    *
    * @access  public
    * @param
    *
    * @return void
    */
    function ips()
    {

    }

    function __construct()
    {
        $this->ips();
    }

    /**
    * 生成支付代码
    * @param   array   $order  订单信息
    * @param   array   $payment    支付方式信息
    */

    function get_code($order, $payment)
    {
        $billstr    = date('His', time());
        $datestr    = date('Ymd', time());
        $mer_code   = $payment['ips_account'];
        $billno     = str_pad($order['log_id'], 10, '0', STR_PAD_LEFT) . $billstr;
        $amount     = sprintf("%0.02f", $order['order_amount']);
        $strcert    = $payment['ips_key'];
        $strcontent = $billno . $amount . $datestr . 'RMB' . $strcert; // 签名验证串 //
        $signmd5    = MD5($strcontent);

        $def_url  = '<br /><form style="text-align:center;" action="https://pay.ips.com.cn/ipayment.aspx" method="post" target="_blank">';
        $def_url .= "<input type='hidden' name='Mer_code' value='" . $mer_code . "'>\n";
        $def_url .= "<input type='hidden' name='Billno' value='" . $billno . "'>\n";
        $def_url .= "<input type='hidden' name='Gateway_type' value='" . $payment['ips_currency'] . "'>\n";
        $def_url .= "<input type='hidden' name='Currency_Type'  value='RMB'>\n";
        $def_url .= "<input type='hidden' name='Lang'  value='" . $payment['ips_lang'] . "'>\n";
        $def_url .= "<input type='hidden' name='Amount'  value='" . $amount . "'>\n";
        $def_url .= "<input type='hidden' name='Date' value='" . $datestr . "'>\n";
        $def_url .= "<input type='hidden' name='DispAmount' value='" . $amount . "'>\n";
        $def_url .= "<input type='hidden' name='OrderEncodeType' value='2'>\n";
        $def_url .= "<input type='hidden' name='RetEncodeType' value='12'>\n";
        $def_url .= "<input type='hidden' name='Merchanturl' value='" . return_url(basename(__FILE__, '.php')) . "'>\n";
        $def_url .= "<input type='hidden' name='SignMD5' value='" . $signmd5 . "'>\n";
        $def_url .= "<input type='submit' value='" . $GLOBALS['_LANG']['pay_button'] . "'>";
        $def_url .= "</form><br />";

        return $def_url;
    }

    function respond()
    {
        $payment       = get_payment($_GET['code']);
        $billno        = $_GET['billno'];
        $amount        = $_GET['amount'];
        $mydate        = $_GET['date'];
        $succ          = $_GET['succ'];
        $msg           = $_GET['msg'];
        $ipsbillno     = $_GET['ipsbillno'];
        $retEncodeType = $_GET['retencodetype'];
        $currency_type = $_GET['Currency_type'];
        $signature     = $_GET['signature'];
        $order_sn      = intval(substr($billno, 0, 10));

        if ($succ == 'Y')
        {
            $content = $billno . $amount . $mydate . $succ . $ipsbillno . $currency_type;
            $cert = $payment['ips_key'];
            $signature_1ocal = md5($content . $cert);

            if ($signature_1ocal == $signature)
            {
                if (!check_money($order_sn, $amount))
                {
                   return false;
                }
                order_paid($order_sn);

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
}

?>