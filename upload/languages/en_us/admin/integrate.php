<?php

/**
 * The ECSHOP Control panel member the data integration plug-in management program language file
 * ============================================================================
 * All right reserved (C) 2005-2011 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software;it mean that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: liubo $
 * $Id: integrate.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['integrate_name'] ='Name';
$_LANG['integrate_version'] ='Version';
$_LANG['integrate_author'] ='Author';

/* Hint an information*/
$_LANG['update_success'] ='Member data integration plug-in is configed successfully.';
$_LANG['install_confirm'] ="Please don\'t edit integration plug-in in use. \nMember data will be cleared if you switch integration plug-in, and include:\n member information, member account datails, member address of receipt, member bonus, order information, cart. \r\n Are you sure install the member data integration plug-in?";
$_LANG['need_not_setup'] = 'You needn\`t set wehn you use ECSHOP systerm';
$_LANG['neednot_sync'] ='You needn\'t synchronous operation, beacuse the ECSHOP member system is in use now.';
$_LANG['different_domain'] ='The integration object and ECSHOP of[with] your setup is not under same area.<Br/>you member\'s data that can share that system, but can\'t carry out to register in the meantime.';
$_LANG['points_set'] = 'Redeem settings';
$_LANG['view_user_list'] = 'View Forum User';
$_LANG['view_install_log'] = 'See the installation log';

$_LANG['integrate_setup'] ='Setup member data integration plug-in.';
$_LANG['continue_sync'] ='Continue synchronous member\'s data.';
$_LANG['go_userslist'] ='Return list of member number.';
$_LANG['user_help'] = '<pre>
Usage:
     1:If the users need to integrate other systems, you can install the appropriate plug-ins to integrate the version number.
     2:If you need to replace the user\'s system integration, plug-ins can be installed directly target the integration at
       the same time automatically uninstall an integrated plug-ins on.
     3:If you do not need any user\'s system integration, please select ecshop installed plug-ins, you can uninstall all of
       the integration of plug-ins.
                           </pre>';
/* 查看安装日志 */
$_LANG['lost_install_log'] = 'Did not find the installation log';
$_LANG['empty_install_log'] = 'Installation log is empty';

/* The form related language item*/
$_LANG['db_notice'] = 'Click“<font color="#000000">Next</font>”Will guide you to the mall user data will be synchronized to the integration of the Forum。If no synchronous data please click“<font color="#000000">Save configuration information directly</font>”';

$_LANG['lable_db_host'] ='Database server host:';
$_LANG['lable_db_name'] ='Database:';
$_LANG['lable_db_chartset'] ='The database character list gather:';
$_LANG['lable_is_latin1'] = 'Whether to latin1 encoding';
$_LANG['lable_db_user'] ='Database account number:';
$_LANG['lable_db_pass'] ='Database password:';
$_LANG['lable_prefix'] ='Datasheet prefixion:';
$_LANG['lable_url'] ='Complete URL of the integrated system:';
/* The form related labguage item(discus5x) */
$_LANG['cookie_prefix']          = 'COOKIE prefix:';
$_LANG['cookie_salt']          = 'COOKIE Encrypted string：';
$_LANG['button_next'] = 'next';
$_LANG['button_force_save_config'] = 'Save configuration information directly';
$_LANG['save_confirm'] = 'Are you sure you want to configure the direct preservation of information?';
$_LANG['button_save_config'] = 'Save configuration information';

$_LANG['error_db_msg'] = 'Database address, user or password is incorrect';
$_LANG['error_db_exist'] = 'Database does not exist';
$_LANG['error_table_exist'] = 'Forum integration of critical data table does not exist, you have mistakenly filled out the information';

$_LANG['notice_latin1'] = 'This option will fill in error may lead to Chinese user name can not be used';
$_LANG['error_not_latin1'] = 'Integrated database latin1 encoding is not detected! Please re-select';
$_LANG['error_is_latin1'] = 'Integrated database to detect lantin1 are coding! Please re-select';
$_LANG['invalid_db_charset'] = 'Integrated database detected are% s character set, rather than% s character set';
$_LANG['error_latin1'] = 'You fill in the integration of information will lead to serious error, unable to complete the integration of';

/* 检查同名用户 */
$_LANG['conflict_username_check'] = 'Check whether the user and integrating Mall Forum users have duplicate names';
$_LANG['check_notice'] = 'This page will detect Mall has been whether or not the user and the forum users have duplicate names, click on "before the inspection began," Chong Ming for the city deal with the user to choose a default method';
$_LANG['default_method'] = 'If you have duplicate names detected Mall user, please for the user to select a default way to deal with';
$_LANG['shop_user_total'] = 'Mall a total of% s users to check to be';
$_LANG['lable_size'] = 'Each inspection, the number of users';
$_LANG['start_check'] = 'Checking';
$_LANG['next'] = 'next';
$_LANG['checking'] = 'Checking...(Please do not close your browser)';
$_LANG['notice'] = 'Has inspected %s / %s ';
$_LANG['check_complete'] = 'Inspection complete';

/* 同名用户处理 */
$_LANG['conflict_username_modify'] = 'Chong Ming Mart User List';
$_LANG['modify_notice'] = 'The following is a list of all city and Chong Ming Forum Users and treatment. If you have confirmed all operations, click "Start integration"; Chong Ming you change the user\'s operation required the click of a button "Save this page to change" to take effect.';
$_LANG['page_default_method'] = 'Chong Ming of the page the user default method of treatment';
$_LANG['lable_rename'] = 'Chong Ming Shangcheng users plus the suffix';
$_LANG['lable_delete'] = 'Mall of Chong Ming delete users and relevant data';
$_LANG['lable_ignore'] = 'Chong Ming users retain Mall, the Forum considered the same name as the user the same user';
$_LANG['short_rename'] = 'Mall user renamed';
$_LANG['short_delete'] = 'Mall delete users';
$_LANG['short_ignore'] = 'Mall users to retain';
$_LANG['user_name'] = 'Mall user name';
$_LANG['email'] = 'email';
$_LANG['reg_date'] = 'Registration Date';
$_LANG['all_user'] = 'Chong Ming users all Shangcheng';
$_LANG['error_user'] = 'Required to operate the Mall re-select the user';
$_LANG['rename_user'] = 'Required a user name of the Shopping Mall';
$_LANG['delete_user'] = 'Mall of the required delete users';
$_LANG['ignore_user'] = 'Users need to retain the Mall';

$_LANG['submit_modify'] = 'Save this page changes';
$_LANG['button_confirm_next'] = 'The beginning of the integration';


/* 用户同步 */
$_LANG['user_sync'] = 'Synchronization data to the Forum Shopping Mall, and complete integration';
$_LANG['button_pre'] = 'Previous';
$_LANG['task_name'] = 'Missions were';
$_LANG['task_status'] = 'Mission status';
$_LANG['task_del'] = '%s months Shangcheng the number of users to be deleted';
$_LANG['task_rename'] = '%s months Shangcheng user name required';
$_LANG['task_sync'] = '%s months Shangcheng users need to synchronize to the forum';
$_LANG['task_save'] = 'Save configuration information, and complete integration';
$_LANG['task_uncomplete'] = 'Unfinished';
$_LANG['task_run'] = 'Implementation (%s / %s)';
$_LANG['task_complete'] = 'Has been completed';
$_LANG['start_task'] = 'The beginning of mission';
$_LANG['sync_status'] = 'Has been synchronized %s / %s';
$_LANG['sync_size'] = 'Each treatment the number of users';
$_LANG['sync_ok'] = 'Congratulations. Successful integration';

$_LANG['save_ok'] = 'Save successfully';

/* 积分设置 */
$_LANG['no_points'] = 'Forum has not detected points can be exchanged';
$_LANG['bbs'] = 'bbs';
$_LANG['shop_pay_points'] = 'Shangcheng consumption points';
$_LANG['shop_rank_points'] = 'Mall grade points';
$_LANG['add_rule'] = 'New rules';
$_LANG['modify'] = 'Modify';
$_LANG['rule_name'] = 'Exchange rules';
$_LANG['rule_rate'] = 'Exchange ratio';

/* JS language item */
$_LANG['js_languages']['no_host'] ='The database server host can\'t be blank.';
$_LANG['js_languages']['no_user'] ='The database account number can\'t be blank.';
$_LANG['js_languages']['no_name'] ='The database can\'t be blank.';
$_LANG['js_languages']['no_integrate_url']='Please enter complete URL of conformity object.';
$_LANG['js_languages']['install_confirm']="Please don\'t optional replace conformity objectt in the system. \\nAre you sure install the member data conformity plug-in?";
$_LANG['js_languages']['num_invalid'] ='The synchronous data record a number isn\'t an integer';
$_LANG['js_languages']['start_invalid'] ='The start position of the synchronous data isn\'t an integer';
$_LANG['js_languages']['sync_confirm'] ="Synchronize member\'s data will rebuild the target data table. \\nPlease backup your data before carrying out synchronize. \\nAre you sure start to synchronize member\'s data?";

$_LANG['cookie_prefix_notice'] = 'UTF8 version of the cookie prefix is xnW_，GB2312/GBK version of the cookie prefix is KD9_。';

$_LANG['js_languages']['no_method'] = 'Please select a default way to deal with';

$_LANG['js_languages']['rate_not_null'] = 'The ratio should not be empty';
$_LANG['js_languages']['rate_not_int'] = 'The ratio of integers can only be filled';
$_LANG['js_languages']['rate_invailed'] = 'You fill a void ratio of';
$_LANG['js_languages']['user_importing'] = 'Importing users into UCenter Medium ...';

/* UCenter设置语言项 */
$_LANG['ucenter_tab_base'] = 'Basic Settings';
$_LANG['ucenter_tab_show'] = 'Display Settings';
$_LANG['ucenter_lab_id'] = 'UCenter Apply ID:';
$_LANG['ucenter_lab_key'] = 'UCenter Communication key:';
$_LANG['ucenter_lab_url'] = 'UCenter Access Address:';
$_LANG['ucenter_lab_ip'] = 'UCenter IP Address:';
$_LANG['ucenter_lab_connect'] = 'UCenter Connection:';
$_LANG['ucenter_lab_db_host'] = 'UCenter Database server:';
$_LANG['ucenter_lab_db_user'] = 'UCenter Database user name:';
$_LANG['ucenter_lab_db_pass'] = 'UCenter Database password:';
$_LANG['ucenter_lab_db_name'] = 'UCenter Database Name:';
$_LANG['ucenter_lab_db_pre'] = 'UCenter Table Prefix:';
$_LANG['ucenter_lab_tag_number'] = 'TAG Tags show the quantity:';
$_LANG['ucenter_lab_credit_0'] = 'Grade Points Name:';
$_LANG['ucenter_lab_credit_1'] = 'Consumer integral Name:';
$_LANG['ucenter_opt_database'] = 'Database approach';
$_LANG['ucenter_opt_interface'] = 'Interface mode';

$_LANG['ucenter_notice_id'] = 'The value for the current store in UCenter Application ID, please do not change the general situation';
$_LANG['ucenter_notice_key'] = 'Communication key for UCenter and ECShop information between the transmission of encrypted, can contain any letters and numbers, please UCenter with identical settings ECShop communication key to ensure that the two systems, normal communication can';
$_LANG['ucenter_notice_url'] = 'The value after you finish installing UCenter will be initialized in your address or directory UCenter changing circumstances, to change this, please do not change the general situation such as: http://www.sitename.com/uc_server (Finally do not increase"/")';
$_LANG['ucenter_notice_ip'] = 'If your server can not access through the domain name UCenter, can enter the IP address of the server UCenter';
$_LANG['ucenter_notice_connect'] = 'Depending on your server network environment to choose the appropriate connection method';
$_LANG['ucenter_notice_db_host'] = 'Can be local can also be a remote database server, if instead of the default MySQL port 3306, please fill out the following form: 127.0.0.1:6033';
$_LANG['uc_notice_ip'] = 'Some effort to connect the process of problem, please fill out the server IP address, if you have UC and ECShop mounted on the same server, we recommend that you try to fill in 127.0.0.1';

$_LANG['uc_lab_url'] = 'UCenter of URL:';
$_LANG['uc_lab_pass'] = 'UCenter Founder password:';
$_LANG['uc_lab_ip'] = 'UCenter of IP:';

$_LANG['uc_msg_verify_failur'] = 'Authentication failure';
$_LANG['uc_msg_password_wrong'] = 'Founder of the password error';
$_LANG['uc_msg_data_error'] = 'Installation of data errors';

$_LANG['ucenter_import_username'] = 'Member data import into UCenter';
$_LANG['uc_import_notice'] = 'Reminder: the data before importing Member to suspend all applications (such as Discuz!, SupeSite, etc.)';
$_LANG['uc_members_merge'] = 'Member merger approach';
$_LANG['user_startid_intro'] = '<p>Member ID for this start- %s. Such as the original ID for the 888 will become a member of %s +888 value.</p>';
$_LANG['uc_members_merge_way1'] = 'UC will work with the same username and password of the user compulsory for the same user';
$_LANG['uc_members_merge_way2'] = 'UC will work with the same username and password of the user does not import UC users';
$_LANG['start_import'] = 'Began to import';
$_LANG['import_user_success'] = 'Member data successfully into UCenter';
$_LANG['uc_points'] = 'UCenter dollar set of points required in the management of the background UCenter';
$_LANG['uc_set_credits'] = 'Set points exchange program';
$_LANG['uc_client_not_exists'] = 'uc_client directory does not exist, please upload it to Shangcheng uc_client directory under the root directory and then integrate';
$_LANG['uc_client_not_write'] = 'uc_client/data directory not writable, please uc_client/data directory permissions set to 777';
$_LANG['uc_lang']['credits'][0][0] = 'Grade points';
$_LANG['uc_lang']['credits'][0][1] = '';
$_LANG['uc_lang']['credits'][1][0] = 'Consumption points';
$_LANG['uc_lang']['credits'][1][1] = '';
$_LANG['uc_lang']['exchange'] = 'UCenterRedeem';

?>