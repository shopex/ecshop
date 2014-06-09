<?php

/**
 * ECSHOP 财付通中介担保支付插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: tenpayc2c.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/tenpayc2c.php';

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
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config']  = array(
        array('name' => 'tenpay_account',   'type' => 'text', 'value' => ''),
        array('name' => 'tenpay_key',       'type' => 'text', 'value' => ''),
        array('name' => 'tenpay_type',       'type' => 'select', 'value'=>'1'),
    );

    return;
}

/**
 * 类
 */
class tenpayc2c
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function tenpayc2c()
    {
    }

    function __construct()
    {
        $this->tenpayc2c();
    }

    /**
     * 生成支付代码
     * @param   array    $order       订单信息
     * @param   array    $payment     支付方式信息
     */
    function get_code($order, $payment)
    {
        /* 版本号 */
        $version = '2';

        /* 任务代码，定值：12 */
        $cmdno = '12';

        /* 编码标准 */
        if (!defined('EC_CHARSET'))
        {
            $encode_type = 2;
        }
        else
        {
            if (EC_CHARSET == 'utf-8')
            {
                $encode_type = 2;
            }
            else
            {
                $encode_type = 1;
            }
        }

        /* 平台提供者,代理商的财付通账号 */
        $chnid = $payment['tenpay_account'];

        /* 收款方财付通账号 */
        $seller = $payment['tenpay_account'];

        /* 商品名称 */
        if (!empty($order['order_id']))
        {
            //$mch_name = get_goods_name_by_id($order['order_id']);
            $mch_name = $order['order_sn'];
        }
        else
        {
            $mch_name = $GLOBALS['_LANG']['account_voucher'];
        }

        /* 总金额 */
        $mch_price = floatval($order['order_amount']) * 100;

        /* 物流配送说明 */
        $transport_desc = '';
        $transport_fee = '';

        /* 交易说明 */
        $mch_desc = $GLOBALS['_LANG']['shop_order_sn'] . $order['order_sn'];
        $need_buyerinfo = '2' ;

        /* 交易类型：2、虚拟交易，1、实物交易 */
        $mch_type = $payment['tenpay_type'];

        /* 获得订单的流水号，补零到10位 */
        $mch_vno = $order['order_sn'];

        /* 返回的路径 */
        $mch_returl = return_url('tenpayc2c');
        $show_url   = return_url('tenpayc2c');
        $attach = '';

        /* 数字签名 */
        $sign_text = "chnid=" . $chnid . "&cmdno=" . $cmdno . "&encode_type=" . $encode_type . "&mch_desc=" . $mch_desc . "&mch_name=" . $mch_name . "&mch_price=" . $mch_price ."&mch_returl=" . $mch_returl . "&mch_type=" . $mch_type . "&mch_vno=" . $mch_vno . "&need_buyerinfo=" . $need_buyerinfo ."&seller=" . $seller . "&show_url=" . $show_url . "&version=" . $version . "&key=" . $payment['tenpay_key'];

        $sign =md5($sign_text);

        /* 交易参数 */
        $parameter = array(
            'attach'            => $attach,
            'chnid'             => $chnid,
            'cmdno'             => $cmdno,                     // 业务代码, 财付通支付支付接口填  1
            'encode_type'       => $encode_type,                //编码标准
            'mch_desc'          => $mch_desc,
            'mch_name'          => $mch_name,
            'mch_price'         => $mch_price,                  // 订单金额
            'mch_returl'        => $mch_returl,                 // 接收财付通返回结果的URL
            'mch_type'          => $mch_type,                   //交易类型
            'mch_vno'           => $mch_vno,             // 交易号(订单号)，由商户网站产生(建议顺序累加)
            'need_buyerinfo'    => $need_buyerinfo,             //是否需要在财付通填定物流信息
            'seller'            => $seller,  // 商家的财付通商户号
            'show_url'          => $show_url,
            'transport_desc'    => $transport_desc,
            'transport_fee'     => $transport_fee,
            'version'           => $version,                    //版本号 2
            'sign'              => $sign,                       // MD5签名
            'sys_id'            => '542554970'                  //ecshop C账号 不参与签名
        );

        $button  = '<br /><form style="text-align:center;" action="https://www.tenpay.com/cgi-bin/med/show_opentrans.cgi " target="_blank" style="margin:0px;padding:0px" >';

        foreach ($parameter AS $key=>$val)
        {
            $button  .= "<input type='hidden' name='$key' value='$val' />";
        }

        $button  .= '<input type="image" src="'. $GLOBALS['ecs']->url() .'images/tenpayc2c.jpg" value="' .$GLOBALS['_LANG']['pay_button']. '" /></form><br />';

        return $button;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        /*取返回参数*/
        $cmd_no         = $_GET['cmdno'];
        $retcode        = $_GET['retcode'];
        $status         = $_GET['status'];
        $seller         = $_GET['seller'];
        $total_fee      = $_GET['total_fee'];
        $trade_price    = $_GET['trade_price'];
        $transport_fee  = $_GET['transport_fee'];
        $buyer_id       = $_GET['buyer_id'];
        $chnid          = $_GET['chnid'];
        $cft_tid        = $_GET['cft_tid'];
        $mch_vno        = $_GET['mch_vno'];
        $attach         = !empty($_GET['attach']) ? $_GET['attach'] : '';
        $version        = $_GET['version'];
        $sign           = $_GET['sign'];

        $payment    = get_payment('tenpayc2c');
        $log_id     = get_order_id_by_sn($mch_vno);
        //$log_id = str_replace($attach, '', $mch_vno); //取得支付的log_id

        /* 如果$retcode大于0则表示支付失败 */
        if ($retcode > 0)
        {
            //echo '操作失败';
            return false;
        }

        /* 检查支付的金额是否相符 */
        if (!check_money($log_id, $total_fee / 100))
        {
            //echo '金额不相等';
            return false;
        }

        /* 检查数字签名是否正确 */
        $sign_text = "buyer_id=" . $buyer_id . "&cft_tid=" . $cft_tid . "&chnid=" . $chnid . "&cmdno=" . $cmd_no . "&mch_vno=" . $mch_vno . "&retcode=" . $retcode . "&seller=" .$seller . "&status=" . $status . "&total_fee=" . $total_fee . "&trade_price=" . $trade_price . "&transport_fee=" . $transport_fee . "&version=" . $version . "&key=" . $payment['tenpay_key'];
        $sign_md5 = strtoupper(md5($sign_text));
        if ($sign_md5 != $sign)
        {
            //echo '签名错误';
            return false;
        }
        elseif ($status = 3)
        {
            /* 改变订单状态为已付款 */
            order_paid($log_id, PS_PAYING);
            return true;
        }
        else
        {
            //为止error
            return false;
        }
    }
}

?>