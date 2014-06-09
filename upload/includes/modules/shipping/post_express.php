<?php

/**
 * ECSHOP 邮政包裹插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: post_express.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

$shipping_lang = ROOT_PATH.'languages/' .$GLOBALS['_CFG']['lang']. '/shipping/post_express.php';
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
    $modules[$i]['desc']    = 'post_express_desc';

    /* 保价比例,如果不支持保价则填入false,支持则还需加入calculate_insure()函数。固定价格直接填入固定数字，按商品总价则在数值后加上%  */
    $modules[$i]['insure']  = '1%';

    /* 配送方式是否支持货到付款 */
    $modules[$i]['cod']     = false;

    /* 插件的作者 */
    $modules[$i]['author']  = 'ECSHOP TEAM';

    /* 插件作者的官方网站 */
    $modules[$i]['website'] = 'http://www.ecshop.com';

    /* 配送接口需要的参数 */
    $modules[$i]['configure'] = array(
                                    array('name' => 'item_fee',     'value'=>5),
                                    array('name' => 'base_fee',     'value'=>5),
                                    array('name' => 'step_fee',    'value'=>2),
                                    array('name' => 'step_fee1',    'value'=>1),
                                );

    /* 模式编辑器 */
    $modules[$i]['print_model'] = 2;

    /* 打印单背景 */
    $modules[$i]['print_bg'] = '';

   /* 打印快递单标签位置信息 */
    $modules[$i]['config_lable'] = '';

    return;
}

/**
 * 邮政快递包裹费用计算方式
 * ====================================================================================
 * 运距                     首重1000克      5000克以内续重每500克   5001克以上续重500克
 * -------------------------------------------------------------------------------------
 * 500公里及500公里以内     5.00            2.00                    1.00
 * 500公里以上至1000公里    6.00            2.50                    1.30
 * 1000公里以上至1500公里   7.00            3.00                    1.60
 * 1500公里以上至2000公里   8.00            3.50                    1.90
 * 2000公里以上至2500公里   9.00            4.00                    2.20
 * 2500公里以上至3000公里   10.00           4.50                    2.50
 * 3000公里以上至4000公里   12.00           5.50                    3.10
 * 4000公里以上至5000公里   14.00           6.50                    3.70
 * 5000公里以上至6000公里   16.00           7.50                    4.30
 * 6000公里以上             20.00           9.00                    6.00
 * -------------------------------------------------------------------------------------
 * 每件挂号费               3.00
 */
class post_express
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
    function post_express($cfg=array())
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
     * @param   float   $goods_number   商品数量
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
            $fee    = $this->configure['base_fee'];
            $this->configure['fee_compute_mode'] = !empty($this->configure['fee_compute_mode']) ? $this->configure['fee_compute_mode'] : 'by_weight';

            if ($this->configure['fee_compute_mode'] == 'by_number')
            {
                $fee = $goods_number * $this->configure['item_fee'];
            }
            else
            {
               if ($goods_weight > 5)
                {
                    $fee += 8 * $this->configure['step_fee'];
                    $fee += (ceil(($goods_weight - 5) / 0.5)) * $this->configure['step_fee1'];
                }
                else
                {
                    if ($goods_weight > 1)
                    {
                        $fee += (ceil(($goods_weight - 1) / 0.5)) * $this->configure['step_fee'];
                    }
                }
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