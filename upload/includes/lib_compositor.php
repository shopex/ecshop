<?php

/**
 * ECSHOP 支付插件排序文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: lib_compositor.php 2009-07-24 09:31:42Z liuhui $
 */

if(isset($modules))
{

    /* 将财付通提升至第二个显示 */
    foreach ($modules as $k =>$v)
    {
        if($v['pay_code'] == 'tenpay')
        {
            $tenpay = $modules[$k];
            unset($modules[$k]);
            array_unshift($modules, $tenpay);
        }
    }
    /* 将快钱直连银行显示在快钱之后 */
    foreach ($modules as $k =>$v)
    {
        if(strpos($v['pay_code'], 'kuaiqian')!== false)
        {
            $tenpay = $modules[$k];
            unset($modules[$k]);
            array_unshift($modules, $tenpay);
        }
    }

    /* 将快钱提升至第一个显示 */
    foreach ($modules as $k =>$v)
    {
        if($v['pay_code'] == 'kuaiqian')
        {
            $tenpay = $modules[$k];
            unset($modules[$k]);
            array_unshift($modules, $tenpay);
        }
    }

}

?>