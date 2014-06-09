<?php
/**
 * ECSHOP Short message module language file
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
 * $Id: sms.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* Navigation */
$_LANG['register_sms'] = 'Register Or Enable Sms Account.';

/* Register or enable sms */
$_LANG['email'] = 'Email';
$_LANG['password'] = 'Password';
$_LANG['domain'] = 'Domain';
$_LANG['error_tips'] = 'Set in the shop - "SMS settings, first register and properly configure the SMS messaging service!';
$_LANG['register_new'] = 'Register';
$_LANG['enable_old'] = 'Enable account';

/* Sms special service message */
$_LANG['sms_user_name'] = 'Username:';
$_LANG['sms_password'] = 'Password:';
$_LANG['sms_domain'] = 'Domain:';
$_LANG['sms_num'] = 'Sms special service number:';
$_LANG['sms_count'] = 'Send smsquantity:';
$_LANG['sms_total_money'] = 'Total money:';
$_LANG['sms_balance'] = 'Balance:';
$_LANG['sms_last_request'] = 'Latest request time:';
$_LANG['disable'] = 'Disable sms service';

/* Send sms */
$_LANG['phone'] = 'Mobile phone';
$_LANG['user_rand'] = 'Send short message by the user level';
$_LANG['phone_notice'] = 'More than one phone number divided by DBC case comma.';
$_LANG['msg'] = 'Message';
$_LANG['msg_notice'] = '70 character at most';
$_LANG['send_date'] = 'Send at certain times';
$_LANG['send_date_notice'] = 'Format is YYYY-MM-DD HH:II. If it is blank then send immediately.';
$_LANG['back_send_history'] = 'Return to send history';
$_LANG['back_charge_history'] = 'Return to charge history';

/* Record query interface */
$_LANG['start_date'] = 'Start date';
$_LANG['date_notice'] = 'Format:YYYY-MM-DD. Allowed blank.';
$_LANG['end_date'] = 'Deadline';
$_LANG['page_size'] = 'Display every page';
$_LANG['page_size_notice'] = 'Can be blank, diaplay every 20 records.';
$_LANG['page'] = 'Page quantity';
$_LANG['page_notice'] = 'Can be blank,express display every 1 page.';
$_LANG['charge'] = 'Please enter the charge what you want to recharge.';

/* Confirm action information */
$_LANG['history_query_error'] = 'Sorry, error in the process of query.';
$_LANG['enable_ok'] = 'Congratulations, you have enabled sms service!';
$_LANG['enable_error'] = 'Sorry, you enable sms service has failed.';
$_LANG['disable_ok'] = 'You logout sms service successfully.';
$_LANG['disable_error'] = 'Logout sms service has failed.';
$_LANG['register_ok'] = 'Congratulations, you register sms service successfully!';
$_LANG['register_error'] = 'Sorry, you register sms service has failed.';
$_LANG['send_ok'] = 'Congratulations ,your message has be sent successfully!';
$_LANG['send_error'] = 'Sorry, error in the process of send.';
$_LANG['error_no'] = 'Error mark';
$_LANG['error_msg'] = 'Error description';
$_LANG['empty_info'] = 'Your sms special service is blank.';

/* cellphone replenishing record */
$_LANG['order_id'] = 'Order ID';
$_LANG['money'] = 'Recharge money';
$_LANG['log_date'] = 'Recharge date';

/* Send logs */
$_LANG['sent_phones'] = 'Sent cellphone number';
$_LANG['content'] = 'Content';
$_LANG['charge_num'] = 'Payments';
$_LANG['sent_date'] = 'Sent date';
$_LANG['send_status'] = 'Send status';
$_LANG['status'][0] = 'Fail';
$_LANG['status'][1] = 'Succeed';
$_LANG['user_list'] = 'All user';
$_LANG['please_select'] = 'Please choose the membership grade';

/* Prompting message */
$_LANG['test_now'] = '<span style="color:red;"></span>';
$_LANG['msg_price'] = '<span style="color:green;">0.1 yuan(RMB) every message</span>';

/* API return error information */
//--register
$_LANG['api_errors']['register'][1] = 'Domain name can\'t be blank.';
$_LANG['api_errors']['register'][2] = 'Mailbox is invalid.';
$_LANG['api_errors']['register'][3] = 'Username already exists.';
$_LANG['api_errors']['register'][4] = 'Unknown error.';
$_LANG['api_errors']['register'][5] = 'Port error.';
//--Gain balance
$_LANG['api_errors']['get_balance'][1] = 'Password is invalid.';
$_LANG['api_errors']['get_balance'][2] = 'User disable.';
//--Send sms
$_LANG['api_errors']['send'][1] = 'Password is invalid.';
$_LANG['api_errors']['send'][2] = 'Sms content length is invalid.';
$_LANG['api_errors']['send'][3] = 'Send time should later than current time.';
$_LANG['api_errors']['send'][4] = 'Error number.';
$_LANG['api_errors']['send'][5] = 'Balance not enough.';
$_LANG['api_errors']['send'][6] = 'Account is stoped.';
$_LANG['api_errors']['send'][7] = 'Port error.';
//--History record
$_LANG['api_errors']['get_history'][1] = 'Password is invalid.';
$_LANG['api_errors']['get_history'][2] = 'No record.';
//--User verify
$_LANG['api_errors']['auth'][1] = 'Password error.';
$_LANG['api_errors']['auth'][2] = 'No user.';

/* User server detected error information */
$_LANG['server_errors'][1] = 'error invalid register information.';//ERROR_INVALID_REGISTER_INFO
$_LANG['server_errors'][2] = 'error invalid enable information.';//ERROR_INVALID_ENABLE_INFO
$_LANG['server_errors'][3] = 'error invalid send information.';//ERROR_INVALID_SEND_INFO
$_LANG['server_errors'][4] = 'error invalid history query.';//ERROR_INVALID_HISTORY_QUERY
$_LANG['server_errors'][5] = 'error invalid passport.';//ERROR_INVALID_PASSPORT
$_LANG['server_errors'][6] = 'error invalid URL.';//ERROR_INVALID_URL
$_LANG['server_errors'][7] = 'error empty response.';//ERROR_EMPTY_RESPONSE
$_LANG['server_errors'][8] = 'error invalid xml file.';//ERROR_INVALID_XML_FILE
$_LANG['server_errors'][9] = 'error invalid node name.';//ERROR_INVALID_NODE_NAME
$_LANG['server_errors'][10] = 'error cant store.';//ERROR_CANT_STORE
$_LANG['server_errors'][11] = 'SMS feature is not yet activated.';//ERROR_INVALID_PASSPORT

/* Client JS language item */
//--Register or  invocation
$_LANG['js_languages']['password_empty_error'] = 'Please enter password.';
$_LANG['js_languages']['username_empty_error'] = 'Please enter username.';
$_LANG['js_languages']['username_format_error'] = 'Username format is invalid.';
$_LANG['js_languages']['domain_empty_error'] = 'Domain can\'t be blank.';
$_LANG['js_languages']['domain_format_error'] = 'Domain format is invalid.';
$_LANG['js_languages']['send_empty_error'] = 'Send phone number and send at least fill out a rating！';

//--Send
$_LANG['js_languages']['phone_empty_error'] = 'Please enter phone number.';
$_LANG['js_languages']['phone_format_error'] = 'Phone member format is invalid.';
$_LANG['js_languages']['msg_empty_error'] = 'Please enter meaasge content.';
$_LANG['js_languages']['send_date_format_error'] = 'Timing format is invalid.';
//--History record
$_LANG['js_languages']['start_date_format_error'] = 'Start time format is invalid.';
$_LANG['js_languages']['end_date_format_error'] = 'Deadline format is invalid.';
//--Recharge
$_LANG['js_languages']['money_empty_error'] = 'Please enter charge what you want to recharge.';
$_LANG['js_languages']['money_format_error'] = 'Money format is invalid.';
?>