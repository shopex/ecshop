<?php

/**
 * ECSHOP Orders management language file
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
 * $Id: order.php 17217 2011-01-19 06:29:08Z liubo $
 */

/* Order search*/
$_LANG['order_sn'] = 'Order NO.';
$_LANG['consignee'] = 'Consignee';
$_LANG['all_status'] = 'Status';

$_LANG['cs'][OS_UNCONFIRMED] = 'Unconfirmed';
$_LANG['cs'][CS_AWAIT_PAY] = 'Await pay';
$_LANG['cs'][CS_AWAIT_SHIP] = 'Await ship';
$_LANG['cs'][CS_FINISHED] = 'Finished';
$_LANG['cs'][PS_PAYING] = 'Payment';
$_LANG['cs'][OS_CANCELED] = 'Returned';
$_LANG['cs'][OS_INVALID] = 'Invalid';
$_LANG['cs'][OS_RETURNED] = 'Returned';
$_LANG['cs'][OS_SHIPPED_PART] = 'Partial shipment';

/* Order status */
$_LANG['os'][OS_UNCONFIRMED] = 'Unconfirmed';
$_LANG['os'][OS_CONFIRMED] = 'Confirmed';
$_LANG['os'][OS_CANCELED] = 'Canceled';
$_LANG['os'][OS_INVALID] = 'Invalid';
$_LANG['os'][OS_RETURNED] = 'Returned';
$_LANG['os'][OS_SPLITED] = 'Had been a single';
$_LANG['os'][OS_SPLITING_PART] = 'Part of the sub-single-';

$_LANG['ss'][SS_UNSHIPPED] = 'Unshipped';
$_LANG['ss'][SS_PREPARING] = 'Preparing';
$_LANG['ss'][SS_SHIPPED] = 'Shipped';
$_LANG['ss'][SS_RECEIVED] = 'Received';
$_LANG['ss'][SS_SHIPPED_PART] = 'Partially Shipped';
$_LANG['ss'][SS_SHIPPED_ING] = 'No shippings';// Shipped in

$_LANG['ps'][PS_UNPAYED] = 'Unpaid';
$_LANG['ps'][PS_PAYING] = 'Paying';
$_LANG['ps'][PS_PAYED] = 'Paid';

$_LANG['ss_admin'][SS_SHIPPED_ING] = 'Shipped in（Future state：No shippings）';
/* Order operate */
$_LANG['label_operable_act'] = 'Current executable operation:';
$_LANG['label_action_note'] = 'Operate remarks:';
$_LANG['label_invoice_note'] = 'Invoice remarks:';
$_LANG['label_invoice_no'] = 'Invoice NO.:';
$_LANG['label_cancel_note'] = 'Cause of cancel:';
$_LANG['notice_cancel_note'] = '(Note in message of shopkeeper)';
$_LANG['op_confirm'] = 'Confirm';
$_LANG['op_pay'] = 'Payment';
$_LANG['op_prepare'] = 'Distribution';
$_LANG['op_ship'] = 'Shipping';
$_LANG['op_cancel'] = 'Cancel';
$_LANG['op_invalid'] = 'Invalid';
$_LANG['op_return'] = 'Refundment';
$_LANG['op_unpay'] = 'Set Unpaid';
$_LANG['op_unship'] = 'Unshipped';
$_LANG['op_cancel_ship'] = 'Cancellation Shipping';
$_LANG['op_receive'] = 'Received';
$_LANG['op_assign'] = 'Assign to';
$_LANG['op_after_service'] = 'Aftermarket';
$_LANG['act_ok'] = 'Operate successfully';
$_LANG['act_false'] = 'Operate failed';
$_LANG['act_ship_num'] = 'Shipped quantity is more then order quantity';
$_LANG['act_good_vacancy'] = 'Out of stock';
$_LANG['act_good_delivery'] = 'Shipped';
$_LANG['notice_gb_ship'] = 'Notice: You can\'t shipping until you deal with the associates successfully.';
$_LANG['back_list'] = 'Return order list.';
$_LANG['op_remove'] = 'Remove';
$_LANG['op_you_can'] = 'The operation you can do';
$_LANG['op_split'] = 'Am single';
$_LANG['op_to_delivery'] = 'To delivery';

/* order list */
$_LANG['order_amount'] = 'Orders money';
$_LANG['total_fee'] = 'Total money';
$_LANG['shipping_name'] = 'Shipping method';
$_LANG['pay_name'] = 'Payment method';
$_LANG['address'] = 'Address';
$_LANG['order_time'] = 'Time';
$_LANG['detail'] = 'View';
$_LANG['phone'] = 'Phone';
$_LANG['group_buy'] = '(Associates)';
$_LANG['error_get_goods_info'] = 'Orders for merchandise to obtain information error';
$_LANG['exchange_goods'] = '(Points Exchange)';

$_LANG['js_languages']['remove_confirm']='All informations will be deleted if you delete the order. Are you sure delete it?';

/* The order search*/
$_LANG['label_order_sn'] = 'Order NO.:';
$_LANG['label_all_status'] = 'Status:';
$_LANG['label_user_name'] = 'Buyer:';
$_LANG['label_consignee'] = 'Consignee:';
$_LANG['label_email'] = 'Email:';
$_LANG['label_address'] = 'Address:';
$_LANG['label_zipcode'] = 'Postalcode:';
$_LANG['label_tel'] = 'Telephone:';
$_LANG['label_mobile'] = 'Mobile phone:';
$_LANG['label_shipping'] = 'Shipping method:';
$_LANG['label_payment'] = 'Payment method:';
$_LANG['label_order_status'] = 'Order status:';
$_LANG['label_pay_status'] = 'Payment status:';
$_LANG['label_shipping_status'] = 'Shipping status:';
$_LANG['label_area'] = 'Location:';
$_LANG['label_time'] = 'Under a single time:';

/* Order details */
$_LANG['prev'] = 'Previous order';
$_LANG['next'] = 'Next order';
$_LANG['print_order'] = 'Print order';
$_LANG['print_shipping'] = 'Print Express Single';
$_LANG['print_order_sn'] = 'Order NO.:';
$_LANG['print_buy_name'] = 'Buyer:';
$_LANG['label_consignee_address'] = 'Receipt address';
$_LANG['no_print_shipping'] = 'sorry,not print';
$_LANG['suppliers_no'] = 'At my disposal(without suppliers)';
$_LANG['restaurant'] = 'Restaurant';

$_LANG['order_info'] = 'Order information';
$_LANG['base_info'] = 'Essential information';
$_LANG['other_info'] = 'Other information';
$_LANG['consignee_info'] = 'Consignee information';
$_LANG['fee_info'] = 'Money information';
$_LANG['action_info'] = 'Operate information';
$_LANG['shipping_info'] = 'Shipping Info';

$_LANG['label_how_oos'] = 'Out of Stock dispose:';
$_LANG['label_how_surplus'] = 'Balance dispose:';
$_LANG['label_pack'] = 'Packing:';
$_LANG['label_card'] = 'Card:';
$_LANG['label_card_message'] = 'Blessing card:';
$_LANG['label_order_time'] = 'Order time:';
$_LANG['label_pay_time'] = 'Payment time:';
$_LANG['label_shipping_time'] = 'Shipping time:';
$_LANG['label_sign_building'] = 'Sign building:';
$_LANG['label_best_time'] = 'Optimal shipping time:';
$_LANG['label_inv_type'] = 'Invoice Type:';
$_LANG['label_inv_payee'] = 'Invoice title:';
$_LANG['label_inv_content'] = 'Invoice content:';
$_LANG['label_postscript'] = 'Postscript:';
$_LANG['label_region'] = 'Region:';

$_LANG['label_shop_url'] = 'URL:';
$_LANG['label_shop_address'] = 'Address:';
$_LANG['label_service_phone'] = 'Service phone:';
$_LANG['label_print_time'] = 'Print time:';

$_LANG['label_suppliers'] ='Choose suppliers:';
$_LANG['label_agency'] = 'Agency:';
$_LANG['suppliers_name'] = 'Suppliers';

$_LANG['product_sn'] = 'Item No';
$_LANG['goods_info'] = 'Product information';
$_LANG['goods_name'] = 'Product name';
$_LANG['goods_name_brand'] = 'Product name [ Brand ]';
$_LANG['goods_sn'] = 'NO.';
$_LANG['goods_price'] = 'Price';
$_LANG['goods_number'] = 'Quantity';
$_LANG['goods_attr'] = 'Attribute';
$_LANG['goods_delivery'] = 'Shipped quantity';
$_LANG['goods_delivery_curr'] = 'Invoice quantity';
$_LANG['storage'] = 'Storage';
$_LANG['subtotal'] = 'Subtotal';
$_LANG['label_total'] = 'Total:';
$_LANG['label_total_weight'] = 'Total products weight:';

$_LANG['label_goods_amount'] ='Total products money:';
$_LANG['label_discount'] = 'Discount:';
$_LANG['label_tax'] = 'Tax invoice:';
$_LANG['label_shipping_fee'] ='Shipping money:';
$_LANG['label_insure_fee'] ='Insurance money:';
$_LANG['label_insure_yn'] = 'Insurance?(Y/N):';
$_LANG['label_pay_fee'] ='Payment money:';
$_LANG['label_pack_fee'] ='Packing money:';
$_LANG['label_card_fee'] ='Greeting card money:';
$_LANG['label_money_paid'] ='Paid money:';
$_LANG['label_surplus'] ='Use balance:';
$_LANG['label_integral'] ='Use points:';
$_LANG['label_bonus'] ='Use bonus:';
$_LANG['label_order_amount'] ='Total orders money:';
$_LANG['label_money_dues'] ='Total payable money:';
$_LANG['label_money_refund'] ='Refundable money:';
$_LANG['label_to_buyer'] ='Shop message:';
$_LANG['save_order'] ='Save order';
$_LANG['notice_gb_order_amount'] = '(Remarks: If associates with insurance, the insurance and corresponding pay need to be paid in first payment.)';

$_LANG['action_user'] ='Customer';
$_LANG['action_time'] ='Time';
$_LANG['order_status'] ='Order status';
$_LANG['pay_status'] ='Payment status';
$_LANG['shipping_status'] ='Shipping status';
$_LANG['action_note'] ='Remarks';
$_LANG['pay_note'] = 'Pay remarks';

$_LANG['sms_time_format'] = 'j/m G o\'clock';
$_LANG['order_shipped_sms'] ='Your order %s hss already shipped in %s. [%s]';
$_LANG['order_splited_sms'] = 'Your order%s,%sIs%s [%s]';
$_LANG['order_removed'] ='Delete order successfully.';
$_LANG['return_list'] ='Return order list';

/* The order processing hint*/
$_LANG['surplus_not_enough'] ='The order use %s balance to pay, now the customer balance shortage.';
$_LANG['integral_not_enough']='The order use %s points to pay, now the customer points shortage.';
$_LANG['bonus_not_available']='The order use bonus to pay, the bonus can\'t be used now.';

/* Buy the goods person\'s information*/
$_LANG['display_buyer'] ='Display buyer information';
$_LANG['buyer_info'] ='Buyer information';
$_LANG['pay_points'] ='Consumption points';
$_LANG['rank_points'] ='Rank points';
$_LANG['user_money'] ='Account balance';
$_LANG['email'] ='E-mail';
$_LANG['rank_name'] ='Member\'s rank';
$_LANG['bonus_count'] ='Bonus quantity';
$_LANG['zipcode'] ='Postal code';
$_LANG['tel'] ='Telephone';
$_LANG['mobile'] ='Backup telephone';

/* Combine orders */
$_LANG['order_sn_not_null'] ='Please fill in combine orders NO..';
$_LANG['two_order_sn_same'] ='Combine orders\' NO. can\'t be same.';
$_LANG['order_not_exist'] ='Order %s is nonexistent.';
$_LANG['os_not_unconfirmed_or_confirmed']='% of the order status is not "Unconfirmed" or "Confirmed".';
$_LANG['ps_not_unpayed'] ='Order the %s payment status is not "Unpaid".';
$_LANG['ss_not_unshipped'] ='Order the %s shipping status is not "Unshipped".';
$_LANG['order_user_not_same']='The two orders belong to different customers';
$_LANG['merge_invalid_order'] = 'Sorry, the orders can\'t be allowed to combine that you have selected.';

$_LANG['from_order_sn'] ='Master order:';
$_LANG['to_order_sn'] ='Slave order:';
$_LANG['merge'] ='Combine';
$_LANG['notice_order_sn'] ='When two order inconformities, order information after merge with the master for standard(such as:Payment mothed, Shipping, Packing, Greeting card, Bonus...etc.).';
$_LANG['js_languages']['confirm_merge'] = 'Are you sure you want to merge these two order?';

/* Criticize a processing*/
$_LANG['pls_select_order'] = 'Please choose the operation you want to order';
$_LANG['no_fulfilled_order'] ='There is no condition satisfy to operate the order.';
$_LANG['updated_order'] ='More recent order:';
$_LANG['order'] = 'Order：';
$_LANG['confirm_order'] = 'Can not modify to confirm';
$_LANG['invalid_order'] = 'Can not modify to invalid';
$_LANG['cancel_order'] = 'Can not modify to cancel';
$_LANG['remove_order'] = 'Can not remove';

/* Edit order to print template*/
$_LANG['edit_order_templates']='Edit order print template';
$_LANG['template_resetore'] ='Restore template';
$_LANG['edit_template_success']='Edit order print template operation successfully!';
$_LANG['remark_fittings'] ='(Accessories)';
$_LANG['remark_gift'] ='(Gift)';
$_LANG['remark_favourable'] = '(Preferential products)';
$_LANG['remark_package'] = '（Preferential Packeage）';

/* The order source statistics*/
$_LANG['from_order'] ='Order source:';
$_LANG['from_ad_js'] ='Advertisement:';
$_LANG['from_goods_js'] ='The product stand the outside JS throw in';
$_LANG['from_self_site'] ='Come from this station';
$_LANG['from'] ='Come from a station to order:';

/* Add , edit order*/
$_LANG['add_order'] ='Add order';
$_LANG['edit_order'] ='Edit order';
$_LANG['step']['user'] ='Please select which menber is you want to order.';
$_LANG['step']['goods'] ='Select product';
$_LANG['step']['consignee'] ='Config consignee information';
$_LANG['step']['shipping'] ='Select shipping method';
$_LANG['step']['payment'] ='Payment method';
$_LANG['step']['other'] ='Create other informations';
$_LANG['step']['money'] ='Setting money';
$_LANG['anonymous'] ='Guest';
$_LANG['by_useridname'] ='By member NO. or username to search';
$_LANG['button_prev'] ='Prev';
$_LANG['button_next'] ='Next';
$_LANG['button_finish'] ='Completion';
$_LANG['button_cancel'] ='Cancel';
$_LANG['name'] ='Name';
$_LANG['desc'] ='Description';
$_LANG['shipping_fee'] ='Shipping money';
$_LANG['free_money'] ='Free limit';
$_LANG['insure'] ='Insrance money';
$_LANG['pay_fee'] ='Poundage';
$_LANG['pack_fee'] ='Packing expense';
$_LANG['card_fee'] ='The greeting card money';
$_LANG['no_pack'] ='Don\'t want a packing';
$_LANG['no_card'] ='Don\'t want a greeting card';
$_LANG['add_to_order'] ='Join order';
$_LANG['calc_order_amount'] ='Calculate total orders money';
$_LANG['available_surplus'] ='Can use balance:';
$_LANG['available_integral'] ='Can use points:';
$_LANG['available_bonus'] ='Can use bonus:';
$_LANG['admin'] ='Addition by administrator';
$_LANG['search_goods'] ='Search by product ID, name, NO..';
$_LANG['category'] ='Category';
$_LANG['brand'] ='Brand';
$_LANG['user_money_not_enough']='Customer blance shortage';
$_LANG['pay_points_not_enough']='Customer points shortage';
$_LANG['money_paid_enough'] ='Paid money is more than product total of money and various cost, please refund.';
$_LANG['price_note'] ='Notice:Have already included the attribute price markup in the product price.';
$_LANG['select_pack'] ='Select packing';
$_LANG['select_card'] ='Select greeting card';
$_LANG['select_shipping'] ='Select shipping method';
$_LANG['want_insure'] ='I want to insurance';
$_LANG['update_goods'] ='Update product';
$_LANG['notice_user'] ='<Strong>Attention:</Strong>Search result only display the first 20 records, if didn\'t find correlative member, please search accurately. Moreover, if the member registers from the forum and don\'t register in shop, can\'t also find out, need register in the shop first.';
$_LANG['amount_increase'] ='Because you modified order, causing the total money of order increase, needing to be pay again.';
$_LANG['amount_decrease'] ='Because you modified order, causing the total money of order reduce, needing to be refund.';
$_LANG['continue_shipping'] ='Because you modified the consignee place region, causing to shipping method originally no longer can be used, please select shipping method again.';
$_LANG['continue_payment'] ='Because you modified the shipping method, causing to payment method originally no longer can be used, please select shipping method again.';
$_LANG['refund'] = 'Refundment';
$_LANG['cannot_edit_order_shipped']='You can\'t modify the shipped order.';
$_LANG['address_list'] ='Select from existing consignee address:';
$_LANG['order_amount_change'] ='Total orders money from %s change into %s.';
$_LANG['shipping_note'] ='Notice: Because the order has already shipped products, modify shipping method wouldn\'t change shipping money and insurance.';
$_LANG['change_use_surplus'] = 'Edit orders %s, change the use of the advance payment';
$_LANG['change_use_integral'] = 'Edit orders %s, change the use of the number of points paid';
$_LANG['return_order_surplus'] = 'Because of canceled, invalid or return operation, returned to pay for the use of orders %s advances';
$_LANG['return_order_integral'] = 'Because of canceled, invalid or return operation, returned to pay for the use of orders %s points';
$_LANG['order_gift_integral'] = 'Order %s gift points';
$_LANG['return_order_gift_integral'] = 'Returns or because of shipping operations, returned to give orders for %s points';
$_LANG['invoice_no_mall'] = '&nbsp;&nbsp;&nbsp;&nbsp;Divided a plurality of invoice No. by ","';

$_LANG['js_languages']['input_price'] = 'Costom price';
$_LANG['js_languages']['pls_search_user'] ='Please search and select a user.';
$_LANG['js_languages']['confirm_drop'] ='Confirm and delete the product?';
$_LANG['js_languages']['invalid_goods_number']='Product quantity inaccuracy';
$_LANG['js_languages']['pls_search_goods'] ='Please search and select product.';
$_LANG['js_languages']['pls_select_area'] = 'Please select the area';
$_LANG['js_languages']['pls_select_shipping']='Please select shipping method.';
$_LANG['js_languages']['pls_select_payment'] ='Please select payment method.';
$_LANG['js_languages']['pls_select_pack'] ='Please select packing.';
$_LANG['js_languages']['pls_select_card'] ='Please select card.';
$_LANG['js_languages']['pls_input_note'] = 'Please enter remarks.';
$_LANG['js_languages']['pls_input_cancel'] = 'Please fill out the cancellation of the reasons!';
$_LANG['js_languages']['pls_select_refund'] = 'Please select refundment method.';
$_LANG['js_languages']['pls_select_agency'] = 'Please select an agency.';
$_LANG['js_languages']['pls_select_other_agency'] = 'The order does belong to the agency selected. Please select another agency.';
$_LANG['js_languages']['loading'] = 'Loading...';

/* Order operate */
$_LANG['order_operate'] = 'Order operate:';
$_LANG['label_refund_amount'] = 'Refundment money:';
$_LANG['label_handle_refund'] = 'Refundment method:';
$_LANG['label_refund_note'] = 'Refundment note:';
$_LANG['return_user_money'] = 'Return user balance';
$_LANG['create_user_account'] = 'Create user\'s refundment application.';
$_LANG['not_handle'] = 'Don\'t process, choose this item when made an error';

$_LANG['order_refund'] = 'Order refundment: %s.';
$_LANG['order_pay'] = 'Order payment: %s.';

$_LANG['send_mail_fail'] = 'Send e-mail failure';

$_LANG['send_message'] = 'Send/view message';

/* 发货单操作 */
$_LANG['delivery_operate'] = 'Invoice Operation:';
$_LANG['delivery_sn_number'] = 'Serial number of Invoice:';
$_LANG['invoice_no_sms'] = 'Input Invoice NO.';

/* 发货单搜索 */
$_LANG['delivery_sn'] = 'Invoice';

/* 发货单状态 */
$_LANG['delivery_status'][0] = 'Normal';
$_LANG['delivery_status'][1] = 'Refunded';
$_LANG['delivery_status'][2] = 'Has shipped';

/* 发货单标签 */
$_LANG['label_delivery_status'] = 'Invoice Status';
$_LANG['label_delivery_time'] = 'Generation Time';
$_LANG['label_delivery_sn'] = 'Serial number of Invoice';
$_LANG['label_add_time'] = 'Order Time';
$_LANG['label_update_time'] = 'Shipped Time';
$_LANG['label_send_number'] = 'Shipped quantity';

/* 发货单提示 */
$_LANG['tips_delivery_del'] = 'Delete invoice success!';

/* 退货单操作 */
$_LANG['back_operate'] = 'Returned Note Operation：';

/* 退货单标签 */
$_LANG['return_time'] = 'Returned Time:';
$_LANG['label_return_time'] = 'Returned Time';

/* 退货单提示 */
$_LANG['tips_back_del'] = 'Return a single deletion of success!';

$_LANG['goods_num_err'] = 'Stocks, please re-select!';
?>