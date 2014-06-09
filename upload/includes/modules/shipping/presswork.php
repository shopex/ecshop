<?php

/**
 * ECSHOP 挂号印刷品插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: presswork.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$shipping_lang = ROOT_PATH.'languages/' .$GLOBALS['_CFG']['lang']. '/shipping/presswork.php';
if (file_exists($shipping_lang))
{
    global $_LANG;
    include_once($shipping_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = (isset($modules)) ? count($modules) : 0;

    /* 配送方式插件的代码必须和文件名保持一致 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    $modules[$i]['version'] = '1.0.0';

    /* 配送方式的描述 */
    $modules[$i]['desc']    = 'presswork_desc';

    /* 保价比例,如果不支持保价则填入false,支持则还需加入calculate_insure()函数。固定价格直接填入固定数字，按商品总价则在数值后加上%  */
    $modules[$i]['insure']  = '1%';

    /* 配送方式是否支持货到付款 */
    $modules[$i]['cod']     = false;

    /* 插件的作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 配送接口需要的参数 */
    $modules[$i]['configure'] = array();

    /* 模式编辑器 */
    $modules[$i]['print_model'] = 2;

    /* 打印单背景 */
    $modules[$i]['print_bg'] = '';

   /* 打印快递单标签位置信息 */
    $modules[$i]['config_lable'] = '';

    return;
}

class presswork
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
    function presswork($cfg=array())
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
     * @return  decimal
     */
    function calculate($goods_weight, $goods_amount)
    {
        if ($this->configure['free_money'] > 0 && $goods_amount >= $this->configure['free_money'])
        {
            return 0;
        }
        else
        {
            $fee    = $goods_weight * 4 + 3.4;

            if ($goods_weight > 0.1)
            {
                $fee += (ceil(($goods_weight - 0.1) / 0.1)) * 0.4;
            }

            return $fee;
        }
    }

    /**
     * 查询发货状态
     * 该配送方式不支持查询发货状态
     *
     * @access  public
     * @param   string  $invoice_sn     发货单号
     * @return  string
     */
    function query($invoice_sn)
    {
        return $invoice_sn;
    }

    /**
     *  当保价比例以%出现时，计算保价费用
     *
     * @access  public
     * @param   decimal $tatal_price  需要保价的商品总价
     * @param   decimal $insure_rate  保价计算比例
     *
     * @return  decimal $price        保价费用
     */
    function calculate_insure($total_price, $insure_rate)
    {
        $total_price = ceil($total_price);
        $price = $total_price * $insure_rate;
        if ($price < 1)
        {
            $price = 1;
        }
        return ceil($price);
    }
}

?>