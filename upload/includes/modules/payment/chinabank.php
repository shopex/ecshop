<?php

/**
 * ECSHOP 网银在线插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: chinabank.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/chinabank.php';

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
    $modules[$i]['desc']    = 'chinabank_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 支付费用 */
    $modules[$i]['pay_fee'] = '1%';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.chinabank.com.cn';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.1';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'chinabank_account', 'type' => 'text', 'value' => ''),
        array('name' => 'chinabank_key',     'type' => 'text', 'value' => ''),
    );

    return;
}

/**
 * 类
 */
class chinabank
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function chinabank()
    {
    }

    function __construct()
    {
        $this->chinabank();
    }

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        $data_vid           = trim($payment['chinabank_account']);
        $data_orderid       = $order['order_sn'];
        $data_vamount       = $order['order_amount'];
        $data_vmoneytype    = 'CNY';
        $data_vpaykey       = trim($payment['chinabank_key']);
        $data_vreturnurl    = return_url(basename(__FILE__, '.php'));
        if (empty($order['order_id']))
        {
            $remark1    = "voucher";                      //商户需要在支付结果通知中转发的商户参数二
        }
        else
        {
            $remark1    = '';
        }

        $MD5KEY =$data_vamount.$data_vmoneytype.$data_orderid.$data_vid.$data_vreturnurl.$data_vpaykey;
        $MD5KEY = strtoupper(md5($MD5KEY));

        $def_url  = '<br /><form style="text-align:center;" method=post action="https://pay3.chinabank.com.cn/PayGate" target="_blank">';
        $def_url .= "<input type=HIDDEN name='v_mid' value='".$data_vid."'>";
        $def_url .= "<input type=HIDDEN name='v_oid' value='".$data_orderid."'>";
        $def_url .= "<input type=HIDDEN name='v_amount' value='".$data_vamount."'>";
        $def_url .= "<input type=HIDDEN name='v_moneytype'  value='".$data_vmoneytype."'>";
        $def_url .= "<input type=HIDDEN name='v_url'  value='".$data_vreturnurl."'>";
        $def_url .= "<input type=HIDDEN name='v_md5info' value='".$MD5KEY."'>";
        $def_url .= "<input type=HIDDEN name='remark1' value='".$remark1."'>";
        $def_url .= "<input type=submit value='" .$GLOBALS['_LANG']['pay_button']. "'>";
        $def_url .= "</form>";

        return $def_url;
    }

    /**
     * 响应操作
     */
    function respond()
    {
        $payment        = get_payment(basename(__FILE__, '.php'));

        $v_oid          = trim($_POST['v_oid']);
        $v_pmode        = trim($_POST['v_pmode']);
        $v_pstatus      = trim($_POST['v_pstatus']);
        $v_pstring      = trim($_POST['v_pstring']);
        $v_amount       = trim($_POST['v_amount']);
        $v_moneytype    = trim($_POST['v_moneytype']);
        $remark1        = trim($_POST['remark1' ]);
        $remark2        = trim($_POST['remark2' ]);
        $v_md5str       = trim($_POST['v_md5str' ]);

        /**
         * 重新计算md5的值
         */
        $key            = $payment['chinabank_key'];

        $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));

        /* 检查秘钥是否正确 */
        if ($v_md5str==$md5string)
        {
            //验证通过后,将订单sn转换为ID 来操作ec订单表
            if ($remark1 == 'voucher')
            {
                $v_oid = get_order_id_by_sn($v_oid, "true");
            }
            else
            {
                $v_oid = get_order_id_by_sn($v_oid);
            }

            if ($v_pstatus == '20')
            {
                /* 改变订单状态 */
                order_paid($v_oid);

                return true;
            }
        }
        else
        {
            return false;
        }
    }
}

?>