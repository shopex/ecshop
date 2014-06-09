<?php

/**
 * ECSHOP 申通快递 配送方式插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: sto_express.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$shipping_lang = ROOT_PATH.'languages/' .$GLOBALS['_CFG']['lang']. '/shipping/sto_express.php';
if (file_exists($shipping_lang))
{
    global $_LANG;
    include_once($shipping_lang);
}


/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    include_once(ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/admin/shipping.php');

    $i = (isset($modules)) ? count($modules) : 0;

    /* 配送方式插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    $modules[$i]['version'] = '1.0.0';

    /* 配送方式的描述 */
    $modules[$i]['desc']    = 'sto_express_desc';

    /* 配送方式是否支持货到付款 */
    $modules[$i]['cod']     = false;

    /* 插件的作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 配送接口需要的参数 */
    $modules[$i]['configure'] = array(
                                    array('name' => 'item_fee',     'value'=>15), /* 单件商品的配送费用 */
                                    array('name' => 'base_fee',    'value'=>15), /* 1000克以内的价格           */
                                    array('name' => 'step_fee',     'value'=>5),  /* 续重每1000克增加的价格 */
                                );

    /* 模式编辑器 */
    $modules[$i]['print_model'] = 2;

    /* 打印单背景 */
    $modules[$i]['print_bg'] = '/images/receipt/dly_sto_express.jpg';

   /* 打印快递单标签位置信息 */
    $modules[$i]['config_lable'] = 't_shop_address,' . $_LANG['lable_box']['shop_address'] . ',235,48,131,152,b_shop_address||,||t_shop_name,' . $_LANG['lable_box']['shop_name'] . ',237,26,131,200,b_shop_name||,||t_shop_tel,' . $_LANG['lable_box']['shop_tel'] . ',96,36,144,257,b_shop_tel||,||t_customer_post,' . $_LANG['lable_box']['customer_post'] . ',86,23,578,268,b_customer_post||,||t_customer_address,' . $_LANG['lable_box']['customer_address'] . ',232,49,434,149,b_customer_address||,||t_customer_name,' . $_LANG['lable_box']['customer_name'] . ',151,27,449,231,b_customer_name||,||t_customer_tel,' . $_LANG['lable_box']['customer_tel'] . ',90,32,452,261,b_customer_tel||,||';

    return;
}

/**
 * 申通快递费用计算方式:
 * ====================================================================================
 * - 江浙沪地区统一资费： 1公斤以内15元， 每增加1公斤加5-6元, 云南为8元
 * - 其他地区统一资费:    1公斤以内18元， 每增加1公斤加5-6元, 云南为8元
 * - 对于体大质轻的包裹，我们将按照航空运输协会的规定，根据体积和实际重量中较重的一种收费，需将包的长、宽、高、相乘，再除以6000
 * - (具体资费请上此网站查询:http://www.car365.cn/fee.asp 客服电话:021-52238886)
 * -------------------------------------------------------------------------------------
 *
 */
class sto_express
{
    /*------------------------------------------------------ */
    //-- PUBLIC ATTRIBUTEs
    /*------------------------------------------------------ */

    /**
     * 配置信息参数
     */
    var $configure;

    /*------------------------------------------------------ */
    //-- PUBLIC METHODs
    /*------------------------------------------------------ */

    /**
     * 构造函数
     *
     * @param: $configure[array]    配送方式的参数的数组
     *
     * @return null
     */
    function sto_express($cfg=array())
    {
        foreach ($cfg AS $key=>$val)
        {
            $this->configure[$val['name']] = $val['value'];
        }
    }

    /**
     * 计算订单的配送费用的函数
     *
     * @param   float   $goods_weight   商品重量
     * @param   float   $goods_amount   商品金额
     * @param   float   $goods_amount   商品件数
     * @return  decimal
     */
    function calculate($goods_weight, $goods_amount, $goods_number)
    {
        if ($this->configure['free_money'] > 0 && $goods_amount >= $this->configure['free_money'])
        {
            return 0;
        }
        else
        {
            @$fee = $this->configure['base_fee'];
            $this->configure['fee_compute_mode'] = !empty($this->configure['fee_compute_mode']) ? $this->configure['fee_compute_mode'] : 'by_weight';

             if ($this->configure['fee_compute_mode'] == 'by_number')
            {
                $fee = $goods_number * $this->configure['item_fee'];
            }
            else
            {
                if ($goods_weight > 1)
                {
                    $fee += (ceil(($goods_weight - 1))) * $this->configure['step_fee'];
                }
            }

            return $fee;
        }
    }

    /**
     * 查询快递状态
     *
     * @access  public
     * @param   string  $invoice_sn     发货单号
     * @return  string  查询窗口的链接地址
     */
    function query($invoice_sn)
    {
        $str = '<form style="margin:0px" methods="post" '.
            'action="http://115.238.100.211:8081/result.aspx" name="queryForm_' .$invoice_sn. '" target="_blank">'.
            '<input type="hidden" name="wen" value="' .str_replace("<br>","\n",$invoice_sn). '" />'.
            '<a href="javascript:document.forms[\'queryForm_' .$invoice_sn. '\'].submit();">' .$invoice_sn. '</a>'.
            '</form>';

        return $str;
    }
}

?>