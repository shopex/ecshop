<?php

/**
 * ECSHOP 财付通插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: tenpay.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/tenpay.php';

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
    $modules[$i]['desc']    = 'tenpay_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.tenpay.com';

    /* 版本号 */
    $modules[$i]['version'] = '2.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'tenpay_account',   'type' => 'text', 'value' => ''),
        array('name' => 'tenpay_key',       'type' => 'text', 'value' => ''),
        array('name' => 'magic_string',     'type' => 'text', 'value' => '')
    );

    return;
}

/**
 * 类
 */
class tenpay
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function tenpay()
    {
    }

    function __construct()
    {
        $this->tenpay();
    }

    /**
     * 生成支付代码
     * @param   array    $order       订单信息
     * @param   array    $payment     支付方式信息
     */
    function get_code($order, $payment)
    {
        $cmd_no = '1';

        /* 获得订单的流水号，补零到10位 */
        $sp_billno = $order['order_sn'];

        /* 交易日期 */
        $today = date('Ymd');

        /* 将商户号+年月日+流水号 */
        $bill_no = str_pad($order['log_id'], 10, 0, STR_PAD_LEFT);
        $transaction_id = $payment['tenpay_account'].$today.$bill_no;

        /* 银行类型:支持纯网关和财付通 */
        $bank_type = '0';

        /* 订单描述，用订单号替代 */
        if (!empty($order['order_id']))
        {
            //$desc = get_goods_name_by_id($order['order_id']);
            $desc = $order['order_sn'];
            $attach = '';
        }
        else
        {
            $desc = $GLOBALS['_LANG']['account_voucher'];
            $attach = 'voucher';
        }
        /* 编码标准 */
        if (!defined('EC_CHARSET') || EC_CHARSET == 'utf-8')
        {
            $desc = ecs_iconv('utf-8', 'gbk', $desc);
        }

        /* 返回的路径 */
        $return_url = return_url('tenpay');

        /* 总金额 */
        $total_fee = floatval($order['order_amount']) * 100;

        /* 货币类型 */
        $fee_type = '1';

        /* 财付通风险防范参数 */
        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];

        /* 数字签名 */
        $sign_text = "cmdno=" . $cmd_no . "&date=" . $today . "&bargainor_id=" . $payment['tenpay_account'] .
          "&transaction_id=" . $transaction_id . "&sp_billno=" . $sp_billno .
          "&total_fee=" . $total_fee . "&fee_type=" . $fee_type . "&return_url=" . $return_url .
          "&attach=" . $attach . "&spbill_create_ip=" . $spbill_create_ip . "&key=" . $payment['tenpay_key'];
        $sign = strtoupper(md5($sign_text));

        /* 交易参数 */
        $parameter = array(
            'cmdno'             => $cmd_no,                     // 业务代码, 财付通支付支付接口填  1
            'date'              => $today,                      // 商户日期：如20051212
            'bank_type'         => $bank_type,                  // 银行类型:支持纯网关和财付通
            'desc'              => $desc,                       // 交易的商品名称
            'purchaser_id'      => '',                          // 用户(买方)的财付通帐户,可以为空
            'bargainor_id'      => $payment['tenpay_account'],  // 商家的财付通商户号
            'transaction_id'    => $transaction_id,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'sp_billno'         => $sp_billno,                  // 商户系统内部的定单号,最多10位
            'total_fee'         => $total_fee,                  // 订单金额
            'fee_type'          => $fee_type,                   // 现金支付币种
            'return_url'        => $return_url,                 // 接收财付通返回结果的URL
            'attach'            => $attach,                     // 用户自定义签名
            'sign'              => $sign,                       // MD5签名
            'spbill_create_ip'  => $spbill_create_ip,           //财付通风险防范参数
            'sys_id'            => '542554970',                 //ecshop C账号 不参与签名
            'sp_suggestuser'    => '1202822001'                 //财付通分配的商户号

        );

        $button  = '<br /><form style="text-align:center;" action="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" target="_blank" style="margin:0px;padding:0px" >';

        foreach ($parameter AS $key=>$val)
        {
            $button  .= "<input type='hidden' name='$key' value='$val' />";
        }

        $button  .= '<input type="image" src="'. $GLOBALS['ecs']->url() .'images/tenpay.gif" value="' .$GLOBALS['_LANG']['pay_button']. '" /></form><br />';

        return $button;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        /*取返回参数*/
        $cmd_no         = $_GET['cmdno'];
        $pay_result     = $_GET['pay_result'];
        $pay_info       = $_GET['pay_info'];
        $bill_date      = $_GET['date'];
        $bargainor_id   = $_GET['bargainor_id'];
        $transaction_id = $_GET['transaction_id'];
        $sp_billno      = $_GET['sp_billno'];
        $total_fee      = $_GET['total_fee'];
        $fee_type       = $_GET['fee_type'];
        $attach         = $_GET['attach'];
        $sign           = $_GET['sign'];

        $payment    = get_payment('tenpay');
        //$order_sn   = $bill_date . str_pad(intval($sp_billno), 5, '0', STR_PAD_LEFT);
        //$log_id = preg_replace('/0*([0-9]*)/', '\1', $sp_billno); //取得支付的log_id
        if ($attach == 'voucher')
        {
            $log_id = get_order_id_by_sn($sp_billno, "true");
        }
        else
        {
            $log_id = get_order_id_by_sn($sp_billno);
        }

        /* 如果pay_result大于0则表示支付失败 */
        if ($pay_result > 0)
        {
            return false;
        }

        /* 检查支付的金额是否相符 */
        if (!check_money($log_id, $total_fee / 100))
        {
            return false;
        }

        /* 检查数字签名是否正确 */
        $sign_text  = "cmdno=" . $cmd_no . "&pay_result=" . $pay_result .
                          "&date=" . $bill_date . "&transaction_id=" . $transaction_id .
                            "&sp_billno=" . $sp_billno . "&total_fee=" . $total_fee .
                            "&fee_type=" . $fee_type . "&attach=" . $attach .
                            "&key=" . $payment['tenpay_key'];
        $sign_md5 = strtoupper(md5($sign_text));
        if ($sign_md5 != $sign)
        {
            return false;
        }
        else
        {
            /* 改变订单状态 */
            order_paid($log_id);
            return true;
        }
    }
}

?>