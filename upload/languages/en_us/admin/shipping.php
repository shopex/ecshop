<?php

/**
 * ECSHOP Mangement center shipping method management language file
 * ============================================================================
 * All right reserved (C) 2005-2011 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software；it mean that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: liubo $
 * $Id: shipping.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['shipping_name'] = 'Name';
$_LANG['shipping_version'] = 'Version';
$_LANG['shipping_desc'] = 'Description';
$_LANG['shipping_author'] = 'Author';
$_LANG['insure'] = 'Insurance';
$_LANG['support_cod'] = 'COD?';
$_LANG['shipping_area'] = 'Config area';
$_LANG['shipping_print_edit'] = 'Edit print template';
$_LANG['shipping_print_template'] = 'Express a single template';
$_LANG['shipping_template_info'] = 'Order template variable description:<br/>{$shop_name}Shop name express<br/>{$province}Shop express their respective provinces<br/>{$city}Shop express-owned urban<br/>{$shop_address}Express Shop Address<br/>{$service_phone}Express Shop top<br/>{$order.order_amount}Express orders<br/>{$order.region}Express the recipient area<br/>{$order.tel}That the recipient phone<br/>{$order.mobile}Express the recipient mobile phone<br/>{$order.zipcode}Recipient express Zip<br/>{$order.address}Express the full address of the recipient<br/>{$order.consignee}Express the recipient name<br/>{$order.order_sn}Express order number';

/* Memu */
$_LANG['shipping_install'] = 'Install shipping method';
$_LANG['install_succeess'] = 'Shipping method %s install successfully!';
$_LANG['del_lable'] = 'Delete Label';
$_LANG['upload_shipping_bg'] = 'Upload to print a single image';
$_LANG['del_shipping_bg'] = 'Remove print a single picture';
$_LANG['save_setting'] = 'Save Settings';
$_LANG['recovery_default'] = 'Restore Default';

/* Express single-part */
$_LANG['lable_select_notice'] = '--Select Insert tab--';
$_LANG['lable_box']['shop_country'] = 'Shop - National';
$_LANG['lable_box']['shop_province'] = 'Shop - Provinces';
$_LANG['lable_box']['shop_city'] = 'Shop - City';
$_LANG['lable_box']['shop_name'] = 'Shop - Name';
$_LANG['lable_box']['shop_district'] = 'Shop - District / County';
$_LANG['lable_box']['shop_tel'] = 'Shop - Telephone';
$_LANG['lable_box']['shop_address'] = 'Shop - Address';
$_LANG['lable_box']['customer_country'] = 'Recipient - National';
$_LANG['lable_box']['customer_province'] = 'Recipient - Provinces';
$_LANG['lable_box']['customer_city'] = 'Recipient - City';
$_LANG['lable_box']['customer_district'] = 'recipient - District / County';
$_LANG['lable_box']['customer_tel'] = 'Recipient - Telephone';
$_LANG['lable_box']['customer_mobel'] = 'Recipient - Mobile';
$_LANG['lable_box']['customer_post'] = 'Recipient - Zip Code';
$_LANG['lable_box']['customer_address'] = 'Recipient - full address';
$_LANG['lable_box']['customer_name'] = 'Recipient - Name';
$_LANG['lable_box']['year'] = 'Years - Date of the day';
$_LANG['lable_box']['months'] = 'Month - Day of the date';
$_LANG['lable_box']['day'] = 'Day - Date of the day';
$_LANG['lable_box']['order_no'] = 'Order number - Order';
$_LANG['lable_box']['order_postscript'] = 'Remarks - Order';
$_LANG['lable_box']['order_best_time'] = 'Delivery time - Orders';
$_LANG['lable_box']['pigeon'] = '√-Pigeon';
//$_LANG['lable_box']['custom_content'] = 'Custom content';

/* Prompting message */
$_LANG['no_shipping_name'] = 'Sorry, shipping method name can\'t be blank.';
$_LANG['no_shipping_desc'] = 'Sorry, shipping method description can\'t be blank.';
$_LANG['repeat_shipping_name'] = 'Sorry, the shipping method already exists.';
$_LANG['uninstall_success'] = 'Shipping method %s has uninstall successfully.';
$_LANG['add_shipping_area'] = 'Creat new shipping area for shipping method';
$_LANG['no_shipping_insure'] = 'Sorry, insurance money can\'t be blank, if you don\'t use it please config as 0.';
$_LANG['not_support_insure'] = 'The shipping method isn\t support insure, config insure cost has failed.';
$_LANG['invalid_insure'] = 'Shipping insurance money is invalid.';
$_LANG['no_shipping_install'] = 'Distribution means that you have not installed temporarily can not edit template';
$_LANG['edit_template_success'] = 'Express has been successfully edit the template.';

/* JS language item */
$_LANG['js_languages']['lang_removeconfirm'] = 'Are you sure uninstall the shipping method?';
$_LANG['js_languages']['shipping_area'] = 'Config area';
$_LANG['js_languages']['upload_falid'] = 'Error: file type is not correct. Please upload "%s" type of file!';
$_LANG['js_languages']['upload_del_falid'] = 'Error: Delete failed!';
$_LANG['js_languages']['upload_del_confirm'] = "Tip: Do you confirm the deletion to print a single image?";
$_LANG['js_languages']['no_select_upload'] = "Error: You do not choose to print a single image. Please use the 'Browse ...' button to select!";
$_LANG['js_languages']['no_select_lable'] = "Operation terminated! You did not choose any label.";
$_LANG['js_languages']['no_add_repeat_lable'] = "Operation failed! Not allowed to add a duplicate label.";
$_LANG['js_languages']['no_select_lable_del'] = "Delete Failed! You do not select any label.";
$_LANG['js_languages']['recovery_default_suer'] = "To restore the default do you confirm? Restore Default will display the contents of the installation.";
?>