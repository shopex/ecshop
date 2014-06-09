<?php

/**
 * ECSHOP 首信易支付插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: cappay.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}
$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/cappay.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/**
 * 模块信息
 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'cappay_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.beijing.com.cn';

    /* 版本号 */
    $modules[$i]['version'] = 'V4.3';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'cappay_account',  'type' => 'text',   'value' => ''),
        array('name' => 'cappay_key',      'type' => 'text',   'value' => ''),
        array('name' => 'cappay_currency', 'type' => 'select', 'value' => 'USD')
    );

    return;
}

class cappay
{
    /**
     * 构造函数
     *
     * @access  public
     * @param
     *
     * @return void
     */
    function cappay()
    {
    }

    function __construct()
    {
        $this->cappay();
    }

    /**
     * 生成支付代码
     * @param   array   $order      订单信息
     * @param   array   $payment    支付方式信息
     */
    function get_code($order, $payment)
    {
        $v_rcvname   = trim($payment['cappay_account']);
        $m_orderid   = $order['log_id'];
        $v_amount    = $order['order_amount'];
        $v_moneytype = trim($payment['cappay_currency']);;
        $v_url       = return_url(basename(__FILE__, '.php'));
        $m_ocomment  = '欢迎使用首信易支付';
        $v_ymd       = date('Ymd',time());

          /*易支付平台*/
        $MD5Key     = $payment['cappay_key'];     //<--支付密钥--> 注:此处密钥必须与商家后台里的密钥一致
        $v_oid      = "$v_ymd-$v_rcvname-$m_orderid";
        $sourcedata = $v_moneytype.$v_ymd.$v_amount.$v_rcvname.$v_oid.$v_rcvname.$v_url;
        $result     = $this->hmac_md5($MD5Key,$sourcedata);
        $def_url  = '<form method=post action="http://pay.beijing.com.cn/prs/user_payment.checkit" target="_blank">';
        $def_url .= "<input type= 'hidden' name = 'v_mid'     value= '".$v_rcvname."'>";     //商户编号
        $def_url .= "<input type= 'hidden' name = 'v_oid'     value= '".$v_oid."'>";         //订单编号
        $def_url .= "<input type= 'hidden' name = 'v_rcvname' value= '".$v_rcvname."'>";     //收货人姓名
        $def_url .= "<input type= 'hidden' name = 'v_rcvaddr' value= '".$v_rcvname."'>";     //收货人地址
        $def_url .= "<input type= 'hidden' name = 'v_rcvtel'  value= '".$v_rcvname."'>";     //收货人电话
        $def_url .= "<input type= 'hidden' name = 'v_rcvpost'  value= '".$v_rcvname."'>";    //收货人邮编
        $def_url .= "<input type= 'hidden' name = 'v_amount'   value= '".$v_amount."'>";     //订单总金额
        $def_url .= "<input type= 'hidden' name = 'v_ymd'      value= '".$v_ymd."'>";        //订单产生日期
        $def_url .= "<input type= 'hidden' name = 'v_orderstatus' value ='0'>";              //配货状态
        $def_url .= "<input type= 'hidden' name = 'v_ordername'   value ='".$v_rcvname."'>"; //订货人姓名
        $def_url .= "<input type= 'hidden' name = 'v_moneytype'   value ='".$v_moneytype."'>"; //币种,0为人民币,1为美元
        $def_url .= "<input type= 'hidden' name='v_url' value='".$v_url."'>";             //支付动作完成后返回到该url，支付结果以GET方式发送
        $def_url .= "<input type='hidden' name='v_md5info' value=$result>";              //订单数字指纹
        $def_url .= "<input type='submit' value='" . $GLOBALS['_LANG']['cappay_button'] . "'>";

        $def_url .= '</form>';

          /*易支付会员通道
        $def_url  = "<form method=post action='http://pay.beijing.com.cn/customer/gb/pay_member.jsp' target='_blank'>";
        $def_url .= "<input type='hidden' name='v_mid' value='".$v_rcvname."'>";                   //商户编号
        $def_url .= "<input type='hidden' name='v_oid' value='".$v_oid."'>";                       //订单编号
        $def_url .= "<input type='hidden' name='v_rcvname' value='".$v_rcvname."'>";               //收货人姓名
        $def_url .= "<input type='hidden' name='v_rcvaddr' value='".$v_rcvname."'>";               //收货人地址
        $def_url .= "<input type='hidden' name='v_rcvtel' value='".$v_rcvname."'>";                //收货人电话
        $def_url .= "<input type='hidden' name='v_rcvpost' value='".$v_rcvname."'>";               //收货人邮编
        $def_url .= "<input type='hidden' name='v_amount' value='".$v_amount."'>";                 //订单总金额
        $def_url .= "<input type='hidden' name='v_ymd' value='".$v_ymd."'>";                       //订单产生日期
        $def_url .= "<input type='hidden' name='v_orderstatus' value='0'>";                        //配货状态
        $def_url .= "<input type='hidden' name='v_ordername' value='".$v_rcvname."'>";             //订货人姓名
        $def_url .= "<input type='hidden' name='v_moneytype' value='".$v_moneytype."'>";   //币种,0为人民币,1为美元
        $def_url .= "<input type='hidden' name='v_url' value='".$v_url."'>";              //支付动作完成后返回到该url，支付结果以GET方式发送
        $def_url .= "<input type='hidden' name='v_md5info' value=$result[0]>";           //订单数字指纹
        $def_url .= "<input type='submit' value='"  . $GLOBALS['_LANG']['cappay_member_button'] .  "'>";

        $def_url .= '</form>';

          //易支付手机通道
        $def_url  = "<form method=post action='http://pay.beijing.com.cn/customer/gb/pay_mobile.jsp' target='_blank'>";
        $def_url .= "<input type='hidden' name='v_mid' value='".$v_rcvname."'>";                   //商户编号
        $def_url .= "<input type='hidden' name='v_oid' value='".$v_oid."'>";                       //订单编号
        $def_url .= "<input type='hidden' name='v_rcvname' value='".$v_rcvname."'>";               //收货人姓名
        $def_url .= "<input type='hidden' name='v_rcvaddr' value='".$v_rcvname."'>";               //收货人地址
        $def_url .= "<input type='hidden' name='v_rcvtel' value='".$v_rcvname."'>";                //收货人电话
        $def_url .= "<input type='hidden' name='v_rcvpost' value='".$v_rcvname."'>";               //收货人邮编
        $def_url .= "<input type='hidden' name='v_amount' value='".$v_amount."'>";                 //订单总金额
        $def_url .= "<input type='hidden' name='v_ymd' value='".$v_ymd."'>";                       //订单产生日期
        $def_url .= "<input type='hidden' name='v_orderstatus' value='0'>";                        //配货状态
        $def_url .= "<input type='hidden' name='v_ordername' value='".$v_rcvname."'>";             //订货人姓名
        $def_url .= "<input type='hidden' name='v_moneytype' value='".$v_moneytype."'>";   //币种,0为人民币,1为美元
        $def_url .= "<input type='hidden' name='v_url' value='".$v_url."'>";              //支付动作完成后返回到该url，支付结果以GET方式发送
        $def_url .= "<input type='hidden' name='v_md5info' value=$result[0]>";           //订单数字指纹
        $def_url .= "<input type='submit' value='"  . $GLOBALS['_LANG']['cappay_mobile_button'] .  "'>";

        $def_url .= '</form>';

          //易支付英文通道
        $def_url  = "<form method=post action='http://pay.beijing.com.cn/prs/e_user_payment.checkit' target='_blank'>";
        $def_url .= "<input type='hidden' name='v_mid' value='".$v_rcvname."'>";                   //商户编号
        $def_url .= "<input type='hidden' name='v_oid' value='".$v_oid."'>";                       //订单编号
        $def_url .= "<input type='hidden' name='v_rcvname' value='".$v_rcvname."'>";               //收货人姓名
        $def_url .= "<input type='hidden' name='v_rcvaddr' value='".$v_rcvname."'>";               //收货人地址
        $def_url .= "<input type='hidden' name='v_rcvtel' value='".$v_rcvname."'>";                //收货人电话
        $def_url .= "<input type='hidden' name='v_rcvpost' value='".$v_rcvname."'>";               //收货人邮编
        $def_url .= "<input type='hidden' name='v_amount' value='".$v_amount."'>";                 //订单总金额
        $def_url .= "<input type='hidden' name='v_ymd' value='".$v_ymd."'>";                       //订单产生日期
        $def_url .= "<input type='hidden' name='v_orderstatus' value='0'>";                        //配货状态
        $def_url .= "<input type='hidden' name='v_ordername' value='".$v_rcvname."'>";             //订货人姓名
        $def_url .= "<input type='hidden' name='v_moneytype' value='".$v_moneytype."'>";   //币种,0为人民币,1为美元
        $def_url .= "<input type='hidden' name='v_url' value='".$v_url."'>";              //支付动作完成后返回到该url，支付结果以GET方式发送
        $def_url .= "<input type='hidden' name='v_md5info' value=$result[0]>";           //订单数字指纹
        $def_url .= "<input type='submit' value='"  . $GLOBALS['_LANG']['cappay_en_button'] .  "'>";

        $def_url .= '</form>';*/

        return $def_url;
    }

    /**
     * 响应操作
     */

    function respond()
    {
        $payment    = get_payment(basename(__FILE__, '.php'));
        $v_tempdate = explode('-', $_REQUEST['v_oid']);

        //接受返回数据验证开始
        //v_md5info验证
        $md5info_paramet = $_REQUEST['v_oid'].$_REQUEST['v_pstatus'].$_REQUEST['v_pstring'].$_REQUEST['v_pmode'];
        $md5info_tem     = $this->hmac_md5($payment['cappay_key'],$md5info_paramet);

        //v_md5money验证
        $md5money_paramet = $_REQUEST['v_amount'].$_REQUEST['v_moneytype'];
        $md5money_tem     = $this->hmac_md5($payment['cappay_key'],$md5money_paramet);
        if ($md5info_tem == $_REQUEST['v_md5info'] && $md5money_tem == $_REQUEST['v_md5money'])
        {
            //改变订单状态
            order_paid($v_tempdate[2]);

            return true;
         }
         else
         {
            return false;
         }

    }
    function hmac_md5($key, $data)
    {
        if (extension_loaded('mhash'))
        {
            return bin2hex(mhash(MHASH_MD5, $data, $key));
        }

        // RFC 2104 HMAC implementation for php. Hacked by Lance Rushing
        $b = 64;
        if (strlen($key) > $b)
        {
            $key = pack('H*', md5($key));
        }
        $key  = str_pad($key, $b, chr(0x00));
        $ipad = str_pad('', $b, chr(0x36));
        $opad = str_pad('', $b, chr(0x5c));

        $k_ipad = $key ^ $ipad;
        $k_opad = $key ^ $opad;

        return md5($k_opad . pack('H*', md5($k_ipad . $data)));
    }

}

?>