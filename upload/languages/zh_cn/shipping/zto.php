<?php

/**
 * ECSHOP 中通速递插件的语言文件
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

$_LANG['zto']          = '中通速递';
$_LANG['zto_desc']     = '中通快递的相关说明。保价费按照申报价值的2％交纳，但是，保价费不低于100元，保价金额不得高于10000元，保价金额超过10000元的，超过的部分无效';
$_LANG['item_fee'] = '单件商品费用：';
$_LANG['base_fee'] = '首重费用';
$_LANG['step_fee'] = '续重每1000克或其零数的费用';
$_LANG['shipping_print'] = '<table style="width:18.2cm" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="height:2.2cm;">&nbsp;</td>
  </tr>
</table>
<table style="width:18.2cm" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="height:4.4cm; width:9.1cm;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td style="width:2cm; height:0.8cm;">&nbsp;</td>
    <td style="width:2.7cm;">{$shop_name}</td>
    <td style="width:1.2cm;">&nbsp;</td>
    <td>{$province}</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td colspan="3" style="height:1.6cm;">{$shop_address}</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td colspan="3" style="height:0.8cm;">{$shop_name}</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td style="height:1.2cm;">{$service_phone}</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    </tr>
    </table>
    </td>
    <td style="height:4.4cm; width:9.1cm;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td style="width:2cm; height:0.8cm;">&nbsp;</td>
    <td style="width:2.7cm;">{$order.consignee}</td>
    <td style="width:1.2cm;">&nbsp;</td>
    <td>{$order.region}</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td colspan="3" style="height:1.6cm;">{$order.address}</td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td colspan="3" style="height:0.8cm;"></td>
    </tr>
    <tr>
    <td>&nbsp;</td>
    <td style="height:1.2cm;">{$order.mobile}</td>
    <td>&nbsp;</td>
    <td>{$order.zipcode}</td>
    </tr>
    </table>
    </td>
  </tr>
</table>
<table style="width:18.2cm" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="height:4.2cm;">&nbsp;</td>
  </tr>
</table>';

?>