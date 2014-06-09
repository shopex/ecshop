<?php

/**
 * ECSHOP 申通快递插件的语言文件
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

$_LANG['sto_express']            = '申通快递';
$_LANG['sto_express_desc']       = '江、浙、沪地区首重为15元/KG，其他地区18元/KG， 续重均为5-6元/KG， 云南地区为8元';
$_LANG['item_fee']              = '单件商品费用：';
$_LANG['base_fee']              = '1000克以内费用';
$_LANG['step_fee']               = '续重每1000克或其零数的费用';
$_LANG['shipping_print']         = '<table border="0" cellspacing="0" cellpadding="0" style="width:18.9cm;">
  <tr>
    <td colspan="3" style="height:2cm;">&nbsp;</td>
  </tr>
  <tr>
    <td style="width:8cm; height:4cm; padding-top:0.3cm;" align="center" valign="middle">
     <table border="0" cellspacing="0" cellpadding="0" style="width:7.5cm;" align="center">
  <tr>
    <td style="width:2.3cm;">&nbsp;</td>
    <td style="height:1.5cm;">{$shop_address}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style="height:0.9cm;">{$shop_name}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style="height:0.9cm;">{$shop_name}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style="height:0.9cm;">{$service_phone}</td>
  </tr>
</table>

    </td>
    <td style="width:8cm; height:4cm; padding-top:0.3cm;" align="center" valign="middle">
    <table border="0" cellspacing="0" cellpadding="0" style="width:7.5cm;" align="center">
  <tr>
    <td style="width:2.3cm;">&nbsp;</td>
    <td style="height:1.5cm;">{$order.address}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style="height:0.9cm;"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style="height:0.9cm;">{$order.consignee}</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td style="height:0.9cm;">{$order.tel}</td>
  </tr>
</table>
    </td>
    <td rowspan="2" style="width:3cm;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" style="height:3.5cm;">&nbsp;</td>
  </tr>
</table>';

?>