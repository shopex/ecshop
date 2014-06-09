<?php

/**
 * ECSHOP EMS插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: ems.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$shipping_lang = ROOT_PATH.'languages/' .$GLOBALS['_CFG']['lang']. '/shipping/ems.php';
if (file_exists($shipping_lang))
{
    global $_LANG;
    include_once($shipping_lang);
}

include_once(ROOT_PATH . 'languages/' . $GLOBALS['_CFG']['lang'] . '/admin/shipping.php');

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 配送方式插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    $modules[$i]['version'] = '1.0.0';

    /* 配送方式的描述 */
    $modules[$i]['desc']    = 'ems_express_desc';

    /* 配送方式是否支持货到付款 */
    $modules[$i]['cod']     = false;

    /* 插件的作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 配送接口需要的参数 */
    $modules[$i]['configure'] = array(
                                    array('name' => 'item_fee',     'value'=>20),
                                    array('name' => 'base_fee',     'value'=>20),
                                    array('name' => 'step_fee',     'value'=>15),
                                );

    /* 模式编辑器 */
    $modules[$i]['print_model'] = 2;

    /* 打印单背景 */
    $modules[$i]['print_bg'] = '/images/receipt/dly_ems.jpg';

   /* 打印快递单标签位置信息 */
    $modules[$i]['config_lable'] = 't_shop_name,' . $_LANG['lable_box']['shop_name'] . ',236,32,182,161,b_shop_name||,||t_shop_tel,' . $_LANG['lable_box']['shop_tel'] . ',127,21,295,135,b_shop_tel||,||t_shop_address,' . $_LANG['lable_box']['shop_address'] . ',296,68,124,190,b_shop_address||,||t_pigeon,' . $_LANG['lable_box']['pigeon'] . ',21,21,192,278,b_pigeon||,||t_customer_name,' . $_LANG['lable_box']['customer_name'] . ',107,23,494,136,b_customer_name||,||t_customer_tel,' . $_LANG['lable_box']['customer_tel'] . ',155,21,639,124,b_customer_tel||,||t_customer_mobel,' . $_LANG['lable_box']['customer_mobel'] . ',159,21,639,147,b_customer_mobel||,||t_customer_post,' . $_LANG['lable_box']['customer_post'] . ',88,21,680,258,b_customer_post||,||t_year,' . $_LANG['lable_box']['year'] . ',37,21,534,379,b_year||,||t_months,' . $_LANG['lable_box']['months'] . ',29,21,592,379,b_months||,||t_day,' . $_LANG['lable_box']['day'] . ',27,21,642,380,b_day||,||t_order_best_time,' . $_LANG['lable_box']['order_best_time'] . ',104,39,688,359,b_order_best_time||,||t_order_postscript,' . $_LANG['lable_box']['order_postscript'] . ',305,34,485,402,b_order_postscript||,||t_customer_address,' . $_LANG['lable_box']['customer_address'] . ',289,48,503,190,b_customer_address||,||';

    return;
}

/**
 * 邮政快递包裹费用计算方式
 * ====================================================================================
 * 500g及500g以内                             20元
 * -------------------------------------------------------------------------------------
 * 续重每500克或其零数                        6元/9元/15元(按分区不同收费不同，具体分区方式，请寄件人拨打电话或到当地邮局营业窗口咨询，客服电话11185。)
 * -------------------------------------------------------------------------------------
 *
 */
class ems
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
    function ems($cfg=array())
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
            $fee = $this->configure['base_fee'];
            $this->configure['fee_compute_mode'] = !empty($this->configure['fee_compute_mode']) ? $this->configure['fee_compute_mode'] : 'by_weight';

            if ($this->configure['fee_compute_mode'] == 'by_number')
            {
                $fee = $goods_number * $this->configure['item_fee'];
            }
            else
            {
                if ($goods_weight > 0.5)
                {
                    $fee += (ceil(($goods_weight - 0.5) / 0.5)) * $this->configure['step_fee'];
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
        $str = '<form style="margin:0px" method="post" '.
            'action="http://www.ems.com.cn/qcgzOutQueryAction.do" name="queryForm_' .$invoice_sn. '" target="_blank">'.
            '<input type="hidden" name="mailNum" value="' .$invoice_sn. '" />'.
            '<a href="javascript:document.forms[\'queryForm_' .$invoice_sn. '\'].submit();">' .$invoice_sn. '</a>'.
            '<input type="hidden" name="reqCode" value="browseBASE" />'.
            '<input type="hidden" name="checknum" value="0568792906411" />'.
            '</form>';

        return $str;
    }
}

?>