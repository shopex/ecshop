<?php

/**
 * ECSHOP Shun Feng shipping plug-in's language file
 * ============================================================================
 * All right reserved (C) 2005-2011 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software；it means that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: liubo $
 * $Id: sf_express.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['sf_express']             = 'Shun Feng shipping';
$_LANG['sf_express_desc']        = 'Cost of Jiang, Zhe, Hu region less than 1000g is less than 15 yuan/kg, more than 1000g is 2 yuan/kg, other region 20 yuan/kg';
$_LANG['item_fee']               = 'Single commodity costs:';
$_LANG['base_fee']               = 'Cost less than 1000g';
$_LANG['step_fee']               = 'Every less than or equal to 1000g:';
$_LANG['shipping_print']         = '<table style="width:18.8cm; height:3cm;" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
  </tr>
</table>
<table style="width:18.8cm;" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="width:9.4cm" valign="top">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td valign="middle" style="width:1.5cm; height:0.8cm;">&nbsp;</td>
      <td width="85%">
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
    <td valign="middle" style="width:5cm; height:0.8cm;">{$shop_name}</td>
      <td valign="middle">&nbsp;</td>
    <td valign="middle" style="width:1.8cm; height:0.8cm;">{$order.order_sn}</td>
    </tr>
   </table>
   </td>
 </tr>
 <tr valign="middle">
 <td>&nbsp;</td>
 <td class="h">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="width:1.3cm; height:0.8cm;">{$province}</td>
    <td>&nbsp;</td>
    <td style="width:1.3cm; height:0.8cm;">{$city}</td>
    <td>&nbsp;</td>
    <td style="width:1.3cm; height:0.8cm;">&nbsp;</td>
    <td>&nbsp;</td>
    <td style="width:1.3cm; height:0.8cm;">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</td>
</tr>
<tr valign="middle">
<td>&nbsp;</td>
<td class="h">{$shop_address}</td>
</tr>
<tr valign="middle">
<td>&nbsp;</td>
<td class="h">&nbsp;</td>
</tr>
<tr valign="middle">
<td>&nbsp;</td>
<td class="h">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;</td>
    <td style="width:1.5cm; height:0.8cm;">&nbsp;</td>
    <td>&nbsp;</td>
    <td style="width:3.5cm; height:0.8cm;">{$service_phone}</td>
  </tr>
</table>
</td>
</tr>
</table>
  </td>
    <td style="width:9.4cm;" valign="top">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
<td valign="middle" style="width:1.5cm; height:0.8cm;">&nbsp;</td>
<td width="85%">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td valign="middle" style="width:5cm; height:0.8cm;">{$order.consignee}</td>
  <td valign="middle">&nbsp;</td>
  <td valign="middle" style="width:1.8cm; height:0.8cm;">&nbsp;</td>
  </tr>
</table>
</td>
</tr>
<tr valign="middle">
<td>&nbsp;</td>
<td class="h">{$order.region}</td>
</tr>
<tr valign="middle">
<td>&nbsp;</td>
<td class="h">{$order.address}</td>
</tr>
<tr valign="middle">
<td>&nbsp;</td>
<td class="h">&nbsp;</td>
</tr>
<tr valign="middle">
<td>&nbsp;</td>
<td class="h">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="width:1.7cm;">&nbsp;</td>
    <td style="width:1.5cm; height:0.8cm;">&nbsp;</td>
    <td style="width:1.7cm;">&nbsp;</td>
    <td style="width:3.5cm; height:0.8cm;">{$order.tel}</td>
  </tr>
</table>
</td>
</tr>
</table>
</td>
  </tr>
</table>
<table style="width:18.8cm; height:6.5cm;" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" style="width:7.4cm;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td colspan="2" style="height:0.5cm;"></td>
  </tr>
<tr>
<td rowspan="2" style="width:4.9cm;">&nbsp;</td>
<td style="height:0.8cm;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:0.8cm;">
  <tr>
    <td style="width:1cm;">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
</td>
</tr>
<tr>
<td style="height:1.3cm;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <td style="height:0.7cm;">&nbsp;</td>
  </tr>
  <tr>
  <td>&nbsp;</td>
  </tr>
</table>
</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:1.5cm">
<tr>
<td>&nbsp;</td>
</tr>
</table>
</td>
<td valign="top" style="width:11.4cm;">&nbsp;</td>
  </tr>
</table>';

?>