<?php

/**
 * ECSHOP 银联在线支付
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: douqinghua $
 * $Id: upop.php 17063 2010-03-25 06:35:46Z douqinghua $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

// 包含配置文件
$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/upop.php';

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
    $modules[$i]['desc']    = 'upop_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 网址 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 版本号 */
    $modules[$i]['version'] = '1.0.0';

    /* 配置信息 */
    $modules[$i]['config'] = array(
        array('name' => 'upop_merAbbr', 'type' => 'text', 'value' => '商户名称'),
        array('name' => 'upop_account', 'type' => 'text', 'value' => ''),
        array('name' => 'upop_security_key', 'type' => 'text', 'value' => ''),
    );

    return;
}

/**
 * 类
 */
class UPOP
{
    /**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */

    function get_code($order, $payment)
    {
        // 初始化变量
        if (!defined('EC_CHARSET'))
        {
            $charset = 'UTF-8';
        }
        else
        {
            $charset = strtoupper(EC_CHARSET);
        }

        $front_pay_url         = 'https://unionpaysecure.com/api/Pay.action';
        $security_key          = $payment['upop_security_key'];
        $merId                 = $payment['upop_account'];
        $orderNumber           = $order['order_sn'] . '-' . $this->_formatSN($order['log_id']);	
        $frontEndUrl           = return_url(basename(__FILE__, '.php'));
        $backEndUrl            = return_url(basename(__FILE__, '.php'));
        $merAbbr               = $payment['upop_merAbbr'];

        $params = array(
                "version"            =>  '1.0.0',                      //接口版本
                "signMethod"         =>  'md5',                        //加密方式
                "charset"            =>  $charset,                     //编码
                "transType"          =>  '01',                         //交易类型
                "origQid"            =>  '',
                "merId"              =>  $merId,                       //收款账号
                "merAbbr"            =>  $merAbbr,                     //商户名称
                "acqCode"            =>  '',
                "merCode"            =>  '',
                "commodityUrl"       =>  '',                           //商品url
                "commodityName"      =>  '',                           //商品名字
                "commodityUnitPrice" =>  '',                           //商品单价
                "commodityQuantity"  =>  '',                           //商品数量
                "commodityDiscount"  =>  '',
                "transferFee"        =>  '',
                "orderNumber"        =>  $orderNumber,                 //订单号，必须唯一
                "orderAmount"        =>  $order['order_amount'] * 100, //交易金额 转化为分
                "orderCurrency"      =>  '156',                        //交易币种，CURRENCY_CNY=>人民币
                "orderTime"          =>  date('YmdHis'),               //交易时间, YYYYmmhhddHHMMSS
                "customerIp"         =>  $_SERVER['REMOTE_ADDR'],      //用户IP
                "customerName"       =>  '',
                "defaultPayType"     =>  '',
                "defaultBankNumber"  =>  '',
                "transTimeout"       =>  '',
                "frontEndUrl"        =>  $frontEndUrl,                 // 前台回调URL
                "backEndUrl"         =>  $backEndUrl,                  // 后台回调URL
                "merReserved"        =>  ''             
        );
        
        $params['signature']    =$this->sign($params, $security_key,'md5');
        
        $button = "<input type='submit' value='" . $GLOBALS['_LANG']['upop_button'] . "' />";
        $html = $this->create_html($params,$front_pay_url,$button);

        return $html;
    }

    /**
     * 响应操作
     */
    function respond()
    {
            $payment        = get_payment('upop');

            $arr_args = array();
            $arr_reserved = array();

            if (is_array($_POST)) 
            {
                $arr_args       = $_POST;
                $cupReserved    = isset($arr_args['cupReserved']) ? $arr_args['cupReserved'] : '';
                parse_str(substr($cupReserved, 1, -1), $arr_reserved); //去掉前后的{}
            }
            else 
            {
                $cupReserved = '';
                $pattern = '/cupReserved=(\{.*?\})/';
                if (preg_match($pattern, $_POST, $match)) { //先提取cupReserved
                   $cupReserved = $match[1];
                }
                //将cupReserved的value清除(因为含有&, parse_str没法正常处理)
                $args_r         = preg_replace($pattern, 'cupReserved=', $_POST);
                parse_str($args_r, $arr_args);
                $arr_args['cupReserved'] = $cupReserved;
                parse_str(substr($cupReserved, 1, -1), $arr_reserved); //去掉前后的{}
            }
            //提取服务器端的签名
            if (!isset($arr_args['signature']))
            {
                 return false;
            }
     
            //验证签名
            $signature=$this->sign($arr_args, $payment['upop_security_key'],'md5');
            if ($signature != $arr_args['signature']) 
            {
                return false;
            }

            $arr_ret = array_merge($arr_args, $arr_reserved);
            unset($arr_ret['cupReserved']);

            if ($arr_ret['respCode'] != '00') 
            {
                return false;
            }
            if(!strpos($arr_ret['orderNumber'], '-')) 
            {
                return false;
            }
            $order_sn_arr = explode('-', $arr_ret['orderNumber']);
            
            $order_sn    = $order_sn_arr['0'];
            $pay_id = intval($order_sn_arr['1']);
            $payment_amount = intval($arr_ret['settleAmount']);
          
            // 检查商户账号是否一致。
            if ($payment['upop_account'] != $arr_ret['merId'])
            {
               return false;
            }
            // 检查价格是否一致
            if (!check_money($pay_id, $payment_amount/100))
            {
               
               return false;
            }
            // 如果未支付成功。
            if ($arr_ret['respCode'] != '00')
            {
               return false;
            }

            $action_note = $arr_ret['respCode'] . ':' 
                    . $arr_ret['respMsg'] 
                    . $GLOBALS['_LANG']['upop_txn_id'] . ':' 
                    . $arr_ret['qid'];

            // 完成订单。
            order_paid($pay_id, PS_PAYED, $action_note);

            //告诉用户交易完成
            return true;

    }
    /**
    * 格式订单号
    */
    function _formatSN($sn)
    {
        return str_repeat('0', 9 - strlen($sn)) . $sn;
    }
    function create_html($params,$front_pay_url,$button)
    {
        $html = <<<eot
    <br />
    <form style="text-align:center;" id="pay_form" name="pay_form" action="{$front_pay_url}" method="post" target="_blank">
eot;
        foreach ($params as $key => $value) 
        {
            $html .= " <input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$value}\" />\n";
        }
        $html .= $button . "</form><br />";
        return $html;
    }
    function sign($params,$security_key,$sign_method)
    {
        if (strtolower($sign_method) == "md5") 
        {
            ksort($params);
            $sign_str = "";
            $sign_ignore_params=array('bank','signMethod','signature');
            foreach ($params as $key => $val)
            {
                if (in_array($key,$sign_ignore_params)) 
                {
                    continue;
                }
                $sign_str .= sprintf("%s=%s&", $key, $val);
            }
            return md5($sign_str . md5($security_key));
        }
        else 
        {
            exit("Unknown sign_method set in quickpay_conf");
        }
    }

}
?>