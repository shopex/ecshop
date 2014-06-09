<?php

/**
 * ECSHOP 中通速递插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: zto.php 17217 2011-01-19 06:29:08Z liubo $
*/

$shipping_lang = ROOT_PATH.'languages/' .$GLOBALS['_CFG']['lang']. '/shipping/zto.php';
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
    $modules[$i]['code']    = 'zto';

    $modules[$i]['version'] = '1.0.0';

    /* 配送方式的描述 */
    $modules[$i]['desc']    = 'zto_desc';

    /* 是否支持保价 */
    $modules[$i]['insure']  = '2%';

    /* 配送方式是否支持货到付款 */
    $modules[$i]['cod']     = false;

    /* 插件的作者 */
    $modules[$i]['author']  = '蓝色黯然';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 配送接口需要的参数 */
    $modules[$i]['configure'] = array(
                                    array('name' => 'item_fee',     'value'=>15),    /* 单件商品配送的价格 */
                                    array('name' => 'base_fee',    'value'=>10),    /* 1000克以内的价格 */
                                    array('name' => 'step_fee',     'value'=>5),    /* 续重每1000克增加的价格 */
                                );

    /* 模式编辑器 */
    $modules[$i]['print_model'] = 2;

    /* 打印单背景 */
    $modules[$i]['print_bg'] = '/images/receipt/dly_zto.jpg';

   /* 打印快递单标签位置信息 */
    $modules[$i]['config_lable'] = 't_shop_province,' . $_LANG['lable_box']['shop_province'] . ',116,30,296.55,117.2,b_shop_province||,||t_customer_province,' . $_LANG['lable_box']['customer_province'] . ',114,32,649.95,114.3,b_customer_province||,||t_shop_address,' . $_LANG['lable_box']['shop_address'] . ',260,57,151.75,152.05,b_shop_address||,||t_shop_name,' . $_LANG['lable_box']['shop_name'] . ',259,28,152.65,212.4,b_shop_name||,||t_shop_tel,' . $_LANG['lable_box']['shop_tel'] . ',131,37,138.65,246.5,b_shop_tel||,||t_customer_post,' . $_LANG['lable_box']['customer_post'] . ',104,39,659.2,242.2,b_customer_post||,||t_customer_tel,' . $_LANG['lable_box']['customer_tel'] . ',158,22,461.9,241.9,b_customer_tel||,||t_customer_mobel,' . $_LANG['lable_box']['customer_mobel'] . ',159,21,463.25,265.4,b_customer_mobel||,||t_customer_name,' . $_LANG['lable_box']['customer_name'] . ',109,32,498.9,115.8,b_customer_name||,||t_customer_address,' . $_LANG['lable_box']['customer_address'] . ',264,58,499.6,150.1,b_customer_address||,||t_months,' . $_LANG['lable_box']['months'] . ',35,23,135.85,392.8,b_months||,||t_day,' . $_LANG['lable_box']['day'] . ',24,23,180.1,392.8,b_day||,||';

    return;
}

class zto
{
    /*------------------------------------------------------ */
    //-- PUBLIC ATTRIBUTEs
    /*------------------------------------------------------ */

    /**
     * 配置信息
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
    function zto($cfg = array())
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
     * @param   float   $goods_number   商品件数
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
     * 查询发货状态
     *
     * @access  public
     * @param   string  $invoice_sn     发货单号
     * @return  string
     */
    function query($invoice_sn)
    {
        $str = '<form style="margin:0px" methods="post" '.
            'action="http://www.zto.cn/bill.asp" name="queryForm_' .$invoice_sn. '" target="_blank">'.
            '<input type="hidden" name="ID" value="' .str_replace("<br>","\n",$invoice_sn). '" />'.
            '<a href="javascript:document.forms[\'queryForm_' .$invoice_sn. '\'].submit();">' .$invoice_sn. '</a>'.
            '<input type="hidden" name="imageField.x" value="26" />'.
            '<input type="hidden" name="imageField.x" value="43" />'.
            '</form>';

        return $str;
    }

    /**
     * 计算保价费用
     * 保价费不低于100元，保价金额不得高于10000元，保价金额超过10000元的，超过的部分无效
     * @access  public
     * @param   int     $goods_amount       保价费用
     * @param   int     $insure             保价比例
     *
     * @return void
     */
    function calculate_insure ($goods_amount, $insure)
    {
        if ($goods_amount > 10000)
        {
            $goods_amount = 10000;
        }

        $fee = $goods_amount * $insure;

        if ($fee < 100)
        {
            $fee = 100;
        }

        return $fee;
    }

}

?>