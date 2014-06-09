<?php

/**
 * ECSHOP YTO Express plug-in language file
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
 * $Id: yto.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['yto'] = 'YTO Express';
$_LANG['yto_desc'] = 'Shanghai Yuantong Express Co., Ltd. established on May 28th, 2000, is a private-owned enterprise that combines express and logistics. Through 6 years\' development, the company now has ranked the front place among the logistics and express industry in China.';
$_LANG['item_fee'] = 'Single commodity costs:';
$_LANG['base_fee'] = 'Cost less than 1000g';
$_LANG['step_fee'] = 'Every less than or equal to 1000g:';
$_LANG['shipping_print'] = '<table border="0" cellspacing="0" cellpadding="0" style="width:18.6cm; height:11.3cm;">
  <tr>
    <td valign="top" style="width:7.2cm;">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="height:1.5cm;">&nbsp;</td>
      </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="4" style="height:0.4cm;"></td>
        </tr>
      <tr>
        <td style="width:1cm; height:1cm;">&nbsp;</td>
        <td style="width:2.4cm;">{$shop_name}</td>
        <td style="width:1cm; height:1cm;">&nbsp;</td>
        <td>{$city}</td>
      </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="6" style="height:0.4cm;">&nbsp;</td>
        </tr>
      <tr>
        <td style="width:1.6cm;">{$province}</td>
        <td style="width:0.8cm; height:0.6cm;"></td>
        <td style="width:1.6cm;">{$city}</td>
        <td style="width:0.8cm;"></td>
        <td style="width:1.6cm;">&nbsp;</td>
        <td style="width:0.8cm;"></td>
      </tr>
      <tr>
        <td colspan="6" style="height:1cm;">{$shop_address}</td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="height:0.4cm;"></td>
      </tr>
      <tr>
        <td style="height:1cm;">{$shop_name}</td>
      </tr>
    </table>
     <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="height:0.8cm; width:0.8cm;">&nbsp;</td>
        <td style="width:2.8cm;">{$service_phone}</td>
        <td style="height:0.8cm; width:0.8cm;">&nbsp;</td>
        <td style="width:2.8cm;">&nbsp;</td>
      </tr>
    </table>
    </td>
    <td valign="top" style="width:7.2cm;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td style="height:2.9cm;">&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td style="height:1cm; width:1.2cm;">&nbsp;</td>
    <td style="width:2.4cm;">{$order.consignee}</td>
    <td style="height:1cm; width:1.2cm;">&nbsp;</td>
    <td style="width:2.4cm;">{$order.region}</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="6" style="height:0.4cm;">&nbsp;</td>
        </tr>
      <tr>
        <td style="width:1.6cm;">{$province}</td>
        <td style="width:0.8cm; height:0.6cm;"></td>
        <td style="width:1.6cm;">{$city}</td>
        <td style="width:0.8cm;"></td>
        <td style="width:1.6cm;"></td>
        <td style="width:0.8cm;"></td>
      </tr>
      <tr>
        <td colspan="6" style="height:1cm;">{$order.address}</td>
        </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="height:0.4cm;"></td>
      </tr>
      <tr>
        <td style="height:1cm;">{$order.consignee}</td>
      </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="height:0.8cm; width:0.8cm;">&nbsp;</td>
        <td style="width:2.8cm;"></td>
        <td style="height:0.8cm; width:0.8cm;">&nbsp;</td>
        <td style="width:2.8cm;">{$order.mobile}</td>
      </tr>
    </table>

    </td>
    <td valign="top" style="width:4.2cm;">&nbsp;

    </td>
  </tr>
</table>';

?>