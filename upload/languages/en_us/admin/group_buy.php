<?php

/**
 * ECSHOP Control panel associates product language file
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
 * $Id: group_buy.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* Current page title and useable link name */
$_LANG['group_buy_list'] = 'Associates list';
$_LANG['add_group_buy'] = 'Add Associates';
$_LANG['edit_group_buy'] = 'Edit';

/* Associates list page */
$_LANG['goods_name'] = 'Product name';
$_LANG['start_date'] = 'Start date';
$_LANG['end_date'] = 'Deadline';
$_LANG['deposit'] = 'Insurance';
$_LANG['restrict_amount'] = 'Limit quantity';
$_LANG['gift_integral'] = 'Present points';
$_LANG['valid_order'] = 'Order';
$_LANG['valid_goods'] = 'Order product';
$_LANG['current_price'] = 'Current price';
$_LANG['current_status'] = 'Status';
$_LANG['view_order'] = 'View order';

/* Add/Edit associates page */
$_LANG['goods_cat'] = 'Category';
$_LANG['all_cat'] = 'All categories';
$_LANG['goods_brand'] = 'Brand';
$_LANG['all_brand'] = 'All brand';

$_LANG['label_goods_name'] = 'Associates product:';
$_LANG['notice_goods_name'] = 'Please search product, create options list...';
$_LANG['label_start_date'] = 'Start date:';
$_LANG['label_end_date'] = 'Deadline:';
$_LANG['notice_datetime'] = '(Year Month Day - Hour)';
$_LANG['label_deposit'] = 'Insurance:';
$_LANG['label_restrict_amount'] = 'Limit quantity:';
$_LANG['notice_restrict_amount']= 'Reach the quantity, associates stop automatically. 0 means no limit quantity.';
$_LANG['label_gift_integral'] = 'Present points:';
$_LANG['label_price_ladder'] = 'Price step:';
$_LANG['notice_ladder_amount'] = 'Reach quantity';
$_LANG['notice_ladder_price'] = 'Price';
$_LANG['label_desc'] = 'Description:';
$_LANG['label_status'] = 'Status';
$_LANG['gbs'][GBS_PRE_START] = 'Preparing';
$_LANG['gbs'][GBS_UNDER_WAY] = 'In progress';
$_LANG['gbs'][GBS_FINISHED] = 'Finished but undisposed';
$_LANG['gbs'][GBS_SUCCEED] = 'Succeed';
$_LANG['gbs'][GBS_FAIL] = 'Fail';
$_LANG['label_order_qty'] = 'Order quantity / Effective order quantity:';
$_LANG['label_goods_qty'] = 'Product quantity / Effective product quantity:';
$_LANG['label_cur_price'] = 'Current price:';
$_LANG['label_end_price'] = 'End price:';
$_LANG['label_handler'] = 'Operator:';
$_LANG['error_group_buy'] = 'The associates is nonexistent.';
$_LANG['error_status'] = 'You can\'t execute the operation in the current state!';
$_LANG['button_finish'] = 'Finished';
$_LANG['notice_finish'] = '(Set the end date as current date)';
$_LANG['button_succeed'] = 'Succeed';
$_LANG['notice_succeed'] = '(Update order price)';
$_LANG['button_fail'] = 'Failed';
$_LANG['notice_fail'] = '(Cancel order, insurance refund to account balance, fail reason can be record in directions)';
$_LANG['cancel_order_reason'] = 'Associates failed.';
$_LANG['js_languages']['succeed_confirm'] = 'The operation is nonreversible, are you sure set the associates as success?';
$_LANG['js_languages']['fail_confirm'] = 'The operation is nonreversible, are you sure set the associates as failure?';
$_LANG['button_mail'] = 'Send mail';
$_LANG['notice_mail'] = '(Notice customer pay the rest money for shipping)';
$_LANG['mail_result'] = 'There are %s availably orders, send %s mails successfully.';
$_LANG['invalid_time'] = 'You have entered an invalid buy time.';

$_LANG['add_success'] = 'Add successfully';
$_LANG['edit_success'] = 'Edit successfully';
$_LANG['back_list'] = 'Return';
$_LANG['continue_add'] = 'Continue add';

/* Add/Edit associates submit */
$_LANG['error_goods_null'] = 'Please select product of associates!';
$_LANG['error_goods_exist'] = 'There is an associates for your selected product!';
$_LANG['error_price_ladder'] = 'Please enter an effective price step value!';
$_LANG['error_restrict_amount'] = 'Sales quantity is less than the price ladder should not the largest quantity';

$_LANG['js_languages']['error_goods_null'] = 'Please select product of associates!';
$_LANG['js_languages']['error_deposit'] = 'Insurance must be an integer!';
$_LANG['js_languages']['error_restrict_amount'] = 'Limit quantity must be an integer!';
$_LANG['js_languages']['error_gift_integral'] = 'Present points must be an integer!';
$_LANG['js_languages']['search_is_null'] = 'Did not search any products, please re-search';

/* Delete associates */
$_LANG['js_languages']['batch_drop_confirm'] = 'Are you sure delete the checked associates?';
$_LANG['error_exist_order'] = 'There are orders in the associates, can\'t be deleted!';
$_LANG['batch_drop_success'] = '%s records has be deleted successfully.(You can\'t delete associates with orders)';
$_LANG['no_select_group_buy'] = 'Events do not buy your records!';

/* Operation logs */
$_LANG['log_action']['group_buy'] = 'Associates product';

?>