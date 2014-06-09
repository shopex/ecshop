<?php

/**
 * ECSHOP Virtual card management
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
 * $Id: virtual_card.php 17217 2011-01-19 06:29:08Z liubo $
*/

/*------------------------------------------------------ */
//-- Card information
/*------------------------------------------------------ */
$_LANG['virtual_card_list'] = 'Virtual Goods List';
$_LANG['lab_goods_name'] = 'Name:';
$_LANG['replenish'] = 'Replenish';
$_LANG['lab_card_id'] = 'ID';
$_LANG['lab_card_sn'] = 'NO.';
$_LANG['lab_card_password'] = 'Password';
$_LANG['lab_end_date'] = 'Deadline';
$_LANG['lab_is_saled'] = 'Saled';
$_LANG['lab_order_sn'] = 'Order NO.';
$_LANG['action_success'] = 'Operation success';
$_LANG['action_fail'] = 'Operation fail';
$_LANG['card'] = 'Card list';

$_LANG['batch_card_add'] = 'Batch add products';
$_LANG['download_file'] ='Download batch CSV files.';
$_LANG['separator'] = 'Separating character';
$_LANG['uploadfile'] = 'Upload file';
$_LANG['sql_error'] = 'NO. %s information was wrong:<br /> ';

/*  Prompting message */
$_LANG['replenish_no_goods_id'] = 'Lack of product ID parameter, can\'t replenish products';
$_LANG['replenish_no_get_goods_name'] = 'Product ID parameter was wrong, can\'t get product name';
$_LANG['drop_card_success'] = 'Delete success';
$_LANG['batch_drop'] = 'Batch delete';
$_LANG['drop_card_confirm'] = 'Are you sure delete the record?';
$_LANG['card_sn_exist'] = 'Card NO. %s already exist,please enter again';
$_LANG['go_list'] = 'Return';
$_LANG['continue_add'] = 'Continue add';
$_LANG['uploadfile_fail'] = 'Upload file failure';
$_LANG['batch_card_add_ok'] = 'Already added %s records';

$_LANG['js_languages']['no_card_sn'] = 'Card NO. or Card Password is blank.';
$_LANG['js_languages']['separator_not_null'] = 'Separating character can\'t be blank.';
$_LANG['js_languages']['uploadfile_not_null'] = 'Please select upload file.';



$_LANG['use_help'] = 'Help:' .
        '<ol>' .
          '<li>Upload file should be CSV file<br />' .
              'Sequential fill in every row by card ID, password, deadline, these item set off by \',\' or \';\' . But nonsupport \'blank\'<br />'.
          '<li>Password and deadline can be blank, deadline format should be \'2006-11-6\' or \'2006/11/6\''.
          '<li>You had better not use chinese in the file to avoid junk.</li>' .
        '</ol>';

/*------------------------------------------------------ */
//-- Change encrypt string
/*------------------------------------------------------ */

$_LANG['virtual_card_change'] = 'Change encrypt string';
$_LANG['user_guide'] = 'Direction:' .
        '<ol>' .
          '<li>Encrypt string use for ID and passwrod of encrypt virtual card</li>' .
          '<li>Encrypt string saved in data/config.php, corresponding constants is AUTH_KEY</li>' .
          '<li>If you want to change encrypt string, enter old encrypt string and new encrypt string in the textbox, check \'Confirm\' push the button</li>' .
        '</ol>';
$_LANG['label_old_string'] = 'Old encrypt string';
$_LANG['label_new_string'] = 'New encrypt string';

$_LANG['invalid_old_string'] = 'Old encrypt string was wrong';
$_LANG['invalid_new_string'] = 'New encrypt string was wrong';
$_LANG['change_key_ok'] = 'Change encrypt string success';
$_LANG['same_string'] = 'New encrypt string and old encrypt string are the same';

$_LANG['update_log'] = 'Update logs';
$_LANG['old_stat'] = 'Total %s records. %s records are encrypted by new string, %s records are encrypted by old string(wait for update), %s records are encrypted by unknown string.';
$_LANG['new_stat'] = '<strong>Update success</strong>, now %s records are encrypted by new string, %s records are encrypted by unknown string.';
$_LANG['update_error'] = 'Update was wrong: %s';
$_LANG['js_languages']['updating_info'] = '<strong>Updating</strong>(Each 100 records)';
$_LANG['js_languages']['updated_info'] = '<strong>Updated</strong> <span id=\"updated\">0</span> records.';
?>