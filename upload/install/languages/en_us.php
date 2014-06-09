<?php

/**
 * ECSHOP Install program language file
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
 * $Id: en_us.php 17217 2011-01-19 06:29:08Z liubo $
 */

/* Common language item */
$_LANG['prev_step'] = 'Previous:';
$_LANG['next_step'] = 'Next:';
$_LANG['copyright'] = '&copy; 2005-2012 <a href="http://www.ecshop.com" target="_blank">Shanghai ShopEx Network Technology Co,.Ltd. </a> <br> All right reserved.';


/* Welcome page */
$_LANG['welcome_title'] = 'You are welcome to use the ECShop e-commerce management system!';
$_LANG['select_installer_lang'] = 'Language :   ';
$_LANG['simplified_chinese'] = 'Simplified Chinese';
$_LANG['traditional_chinese'] = 'Traditional Chinese';
$_LANG['americanese'] = 'English';
$_LANG['agree_license'] = 'I have read carefully，I accept the terms of the License Agreement';
$_LANG['check_system_environment'] = 'Checking system environment';
$_LANG['setup_environment'] = 'Configuration installation environment';
$_LANG['loading'] = 'Loading,please wait...';

/* Check environment page */
$_LANG['checking_title'] = 'ECShop install program, the 2 step / 3 steps, check enviroment';
$_LANG['system_environment'] = 'System environment';
$_LANG['dir_priv_checking'] = 'Check directory authorization';
$_LANG['template_writable_checking'] = 'Check template writable';
$_LANG['rename_priv_checking'] = 'Check some Directory modifying';
$_LANG['welcome_page'] = 'Welcome page';
$_LANG['recheck'] = 'Recheck';
$_LANG['config_system'] = 'Config system';
$_LANG['does_support_mysql'] = 'Support MySQL?';
$_LANG['support'] = 'Support';
$_LANG['does_support_dld'] = 'Important file integrity?';
$_LANG['support_dld'] = 'Integrity';
$_LANG['support'] = 'Support';
$_LANG['not_support'] = 'No support';
$_LANG['cannt_support_dwt'] = 'Lack of dwt file';
$_LANG['cannt_support_lbi'] = 'Lack of lib file';
$_LANG['cannt_support_dat'] = 'Lack of dat file';
$_LANG['php_os'] = 'Operating system';
$_LANG['php_ver'] = 'PHP version';
$_LANG['mysql_ver'] = 'MySQL version';
$_LANG['gd_version'] = 'GD version';
$_LANG['jpeg'] = 'Support JPEG?';
$_LANG['gif'] = 'Support GIF?';
$_LANG['png'] = 'Support PNG?';
$_LANG['safe_mode'] = 'Safe mode?';
$_LANG['safe_mode_on'] = 'Yes';
$_LANG['safe_mode_off'] = 'No';
$_LANG['can_write'] = 'Writable';
$_LANG['cannt_write'] = 'No writable';
$_LANG['not_exists'] = 'No exists';
$_LANG['cannt_modify'] = 'No modifying';
$_LANG['all_are_writable'] = 'All templates, all writable';

/* Config system */
$_LANG['setting_title'] = 'ECShop install program, the 3 step / 3 step, config system';
$_LANG['db_account'] = 'Database accounts:';
$_LANG['db_port'] = 'Port:';
$_LANG['db_host'] = 'Database host:';
$_LANG['db_name'] = 'Database name:';
$_LANG['db_user'] = 'Username:';
$_LANG['db_pass'] = 'Password:';
$_LANG['go'] = 'Search';
$_LANG['db_list'] = 'Database list';
$_LANG['db_prefix'] = 'Prefixion:';
$_LANG['change_prefix'] = 'Propose to change your Prefixion';
$_LANG['admin_account'] = 'Administrator accounts';
$_LANG['admin_name'] = 'Administrator name:';
$_LANG['admin_password'] = 'password:';
$_LANG['admin_password2'] = 'Re-enter password:';
$_LANG['admin_email'] = 'Email:';
$_LANG['mix_options'] = 'Other options';
$_LANG['select_lang_package'] = 'Select language:';
$_LANG['set_timezone'] = 'Config timezone:';
$_LANG['timezone_list'] = 'Timezone list';
$_LANG['disable_captcha'] = 'Disable Verification Code:';
$_LANG['captcha_notice'] = 'If choose this, you needn\'t be validated to enter Control panel or publish comment.';
$_LANG['pre_goods_types'] = 'Preelection product type:';
$_LANG['install_demo'] = 'Install demo data:';
$_LANG['demo_notice'] = 'If choose this, system will check all preelection product type.';
$_LANG['book'] = 'Book';
$_LANG['music'] = 'Music';
$_LANG['movie'] = 'Movie';
$_LANG['mobile'] = 'Mobile phone';
$_LANG['notebook'] = 'Notebook PC';
$_LANG['dc'] = 'Digital camera';
$_LANG['dv'] = 'Digital vidicon';
$_LANG['cosmetics'] = 'Cosmetics';
$_LANG['install_at_once'] = 'Install at once';
$_LANG['default_friend_link'] = 'ECSHOP e-commerce management system';
$_LANG['maifou_friend_link'] = 'maifou';
$_LANG['wdwd_friend_link']='Free independent online store';
$_LANG['monitor_title'] = 'Installer Monitor';
$_LANG['admin_user'][] = 'Goods list|goods.php?act=list';
$_LANG['admin_user'][] = 'Order list|order.php?act=list';
$_LANG['admin_user'][] = 'User comments|comment_manage.php?act=list';
$_LANG['admin_user'][] = 'User list|users.php?act=list';
$_LANG['password_intensity'] = 'Password intensity';
$_LANG['pwd_lower'] = 'Lower';
$_LANG['pwd_middle'] = 'Middle';
$_LANG['pwd_high'] = 'High';

/* Prompting message */
$_LANG['has_locked_installer'] = '<strong>Install program has locked.</strong><br /><br />If you confirm reinstall ECSHOP, please delete install.lock in data directory.';
$_LANG['connect_failed'] = 'Connect database failed, please check your database accounts validity.';
$_LANG['query_failed'] = 'Query database failed, please check your database accounts validity.';
$_LANG['select_db_failed'] = 'Select database failed, please check your database name validity.';
$_LANG['cannt_find_db'] = 'no';
$_LANG['cannt_create_database'] = 'Can\'t create database.';
$_LANG['password_empty_error'] = 'Please enter password.';
$_LANG['passwords_not_eq'] = 'The two passwords you entered did not match.';
$_LANG['open_config_failed'] = 'Open config file failed.';
$_LANG['write_config_failed'] = 'Write config file failed.';
$_LANG['create_passport_failed'] = 'Create administrator accounts failed.';
$_LANG['cannt_mk_dir'] = 'Can\'t create directory.';
$_LANG['cannt_copy_file'] = 'Can\'t copy file.';
$_LANG['open_installlock_error'] = 'Can\'t create install.lock.';
$_LANG['write_config_file_failed'] = 'Write config file failed';

$_LANG['install_done_title'] = 'ECSHOP install program, install successfully.';
$_LANG['install_error_title'] = 'ECSHOP install program, install failed.';
$_LANG['done'] = 'Congratulation，ECSHOP has installed successfully.<br />Based on security consideration, please delete install directory at once.';
$_LANG['go_to_view_my_ecshop'] = 'ECSHOP Homepage';
$_LANG['go_to_view_control_panel'] = 'ECSHOP Control panel';
$_LANG['open_config_file_failed'] = 'Can\'t write in data/config.php, please check whether the file can be wrote.';
$_LANG['write_config_file_failed'] = 'Error, the file can\'t be wrote.';

/* Client JS language item */
$_LANG['js_languages']['success'] = 'Succeed';
$_LANG['js_languages']['fail'] = 'Fail';
$_LANG['js_languages']['total_num'] = '%s databases';
$_LANG['js_languages']['db_exists'] = 'Database exsits, overwrite?';
$_LANG['js_languages']['wait_please'] = 'Installing, please wait............';
$_LANG['js_languages']['create_config_file'] = 'Create config file............';
$_LANG['js_languages']['create_database'] = 'Create database................';
$_LANG['js_languages']['install_data'] = 'Install data.......................';
$_LANG['js_languages']['create_admin_passport'] = 'Create administrator accounts......';
$_LANG['js_languages']['do_others'] = 'Deal with others...................';
$_LANG['js_languages']['display_detail'] = 'Show detail';
$_LANG['js_languages']['hide_detail'] = 'Hide detail';
$_LANG['js_languages']['has_been_stopped'] = 'The process has been stopped.';
$_LANG['js_languages']['setup_ucenter'] = 'Register to UCenter............';
$_LANG['js_languages']['password_invaild'] = 'Must contain numbers and letters!';
$_LANG['js_languages']['password_short'] = 'Should not be less than 8!';
$_LANG['js_languages']['password_not_eq'] = 'The two passwords did not match.';

?>