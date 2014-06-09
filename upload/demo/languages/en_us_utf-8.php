<?php
/**
 * ECSHOP Upgrade program language file
 * ============================================================================
 * All right reserved (C) 2005-2011 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software;it means that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: liubo $
 * $Date: 2009-06-26 18:00:04 +0800 (鏄熸湡浜? 2009-06-26) $
 * $Id: en_us.php 15163 2009-06-26 10:51:04Z liubo $
*/
$_LANG['prev_step'] = 'Previous:';
$_LANG['next_step'] = 'Next:';
$_LANG['select_language_title']='ECSHOP upgrade program, the 1 step / 3 steps, Select language and charset';
$_LANG['readme_title'] = 'ECSHOP upgrade program, the 2 step / 3 steps, readme page';
$_LANG['checking_title'] = 'ECShop upgrade program, the 3 step / 3steps, check environment';
$_LANG['check_system_environment'] = 'Check system environment';

$_LANG['copyright'] = '&copy; 2005-2011 <a href="http://www.ecshop.com" target="_blank">Shanghai ShopEx Network Technology Co,.Ltd. </a>. All right reserved';
$_LANG['is_last_version'] = 'Your ECSHOP has been the newest version, needn\'t upgrade.';

$_LANG['readme_page'] = 'Readme page';
$_LANG['notice'] = 'The program will upgrade ECSHOP to <strong>%s</strong>. Please upgrade by this way, otherwise the result can\'t be restore possibility.';
$_LANG['usage1'] = '<a href="../admin">Login Control panel</a>,<span style="color:red;font-weight:bold;font-size:18px;">Backup</span>Database data';
$_LANG['usage2'] = 'Close old ECSHOP %s system;';
$_LANG['usage3'] = 'Upload ECSHOP %s all files to your server;';
$_LANG['usage4'] = 'Upload the program to ECSHOP higher directory;';
$_LANG['usage5'] = 'Execute this program, until appear upgrade finished.';

$_LANG['usage6']  = 'Run this procedure until you are prompted to upgrade completed.';
$_LANG['method']  = 'Upgrading Method';
$_LANG['charset']  = 'Confirm Charset';

$_LANG['faq']  = 'FAQ';

$_LANG['basic_config'] = 'Basic config information';
$_LANG['config_path'] = 'Config file path';
$_LANG['db_host'] = 'Database host';
$_LANG['db_name'] = 'Database name';
$_LANG['db_user'] = 'Username';
$_LANG['db_pass'] = 'Password';
$_LANG['db_prefix']  = 'Table prefixion';
$_LANG['timezone'] = 'Config timezone';
$_LANG['cookie_path'] = 'COOKIE path';
$_LANG['admin_dir'] = 'Control center root path';

$_LANG['dir_priv_checking'] = 'Check directory authorization';
$_LANG['template_writable_checking'] = 'Check template writable';
$_LANG['dir_priv_checking'] = 'Check directory authorization';
$_LANG['cannt_write'] = 'No writable';
$_LANG['can_write'] = 'Writable';
$_LANG['not_exists'] = 'No exists';
$_LANG['cannt_modify'] = 'No modifying';
$_LANG['recheck'] = 'Recheck';
$_LANG['all_are_writable'] = 'All templates, all writable';

$_LANG['update_now'] = 'Upgrade at once';
$_LANG['done'] = 'Congratulation, system has be upgraded ECSHOP <strong>%s</strong>';
$_LANG['upgrade_error_title'] = 'ECShop upgrade program, upgrade failed.';
$_LANG['upgrade_done_title'] = 'ECShop upgrade program, upgrade successfully.';
$_LANG['go_to_view_my_ecshop'] = ' ECSHOP Homepage';
$_LANG['go_to_view_control_panel'] = ' ECSHOP Control panel';
$_LANG['dir_readonly'] = '%s can\'t be wrote, please check your database config validity.';
$_LANG['monitor_title'] = 'Upgrade program monitor';
$_LANG['wait_please'] = 'UPgrading, please wait...';
$_LANG['js_error'] = 'Client JavaScript error.';
$_LANG['create_ver_failed'] = 'Create version object failed';
$_LANG['goto_charset_convert']  = 'goto:database charset convert';
$_LANG['goto_members_import']  = 'goto:Import members data form UCenter';

/* Client JS language item */
$_LANG['js_languages']['display_detail'] = 'Display details';
$_LANG['js_languages']['hide_detail'] = 'Conceal details';
$_LANG['js_languages']['exception']                   = 'Exception';
$_LANG['js_languages']['suspension_points'] = '...';
$_LANG['js_languages']['initialize'] = 'Initialize';
$_LANG['js_languages']['wait_please'] = 'Upgrading, please wait...';
$_LANG['js_languages']['has_been_stopped'] = 'Upgrade process has stopped.';
$_LANG['js_languages']['is_last_version'] = 'Your ECSHOP has been the newest version, needn\'t upgrade.';
$_LANG['js_languages']['from'] = 'from';
$_LANG['js_languages']['to'] = 'to';
$_LANG['js_languages']['update_files'] = 'Upgrade file';
$_LANG['js_languages']['update_structure'] = 'Upgrade data structure';
$_LANG['js_languages']['update_others'] = 'Upgrade others';
$_LANG['js_languages']['success'] = 'Success';
$_LANG['js_languages']['fail'] = 'Fail';

$_LANG['js_languages']['notice'] = 'Error';
$_LANG['js_languages']['dump_database'] = 'Dump data';
$_LANG['js_languages']['rollback'] = 'Recover data';

$_LANG['js_languages']['uc_api'] = 'Input UCenter URL';
$_LANG['js_languages']['uc_ip'] = 'Input UCenter IP';
$_LANG['js_languages']['uc_pwd'] = 'Input UCenter founder\'s password';

/* UCenter 瀹夎?閰嶇疆 */
$_LANG['configure_uc'] = 'Setup UCenter';
$_LANG['check_ucenter'] = 'Input completed,go to next step';
$_LANG['ucapi'] = 'UCenter URL:';
$_LANG['ucip'] = 'UCenter IP:';
$_LANG['ucenter'] = 'Please input information of UCenter';
$_LANG['ucfounderpw'] = 'UCenter founder\'s password:';
$_LANG['uc_intro'] = 'UCenter is a product for core services of Comsenz.
If you have installed UCenter锛孭lease fill in the following information.Otherwise please download from <a href="http://www.discuz.com" target="_blank">Comsenz download center</a> and install, and then continue.<br /><br />';
$_LANG['ucip_intro'] = 'Some errors to connect,please input the server IP address,if you have UC and ECShop mounted on the same server, we recommend that you try to fill in 127.0.0.1';

$_LANG['users_importto_ucenter'] = 'Import members data to UCenter';
$_LANG['user_startid'] = 'Member ID start value:';
$_LANG['user_startid_intro'] = '<p>Member ID start value is %s.If member\'s original ID is 888 will become %s+888.</p><br /><p><span style="color:#F00;font-size:1.2em;font-weight:bold;">Notice:Before import member data,please close other applications(for example, Discuz!, SupeSite, etc.)</span></p><br />';
$_LANG['maxuid_err'] = 'Start Member ID must be greater than or equal to';
$_LANG['ucenter_import_members'] = 'Import member data toUCenter';
$_LANG['ucenter_no_database'] = '<span style="color:#F00;font-size:1.5em;"><b>Can not connect to the database of UCenter, the upgrade can not be completed, please contact administrator!</b></span>';
$_LANG['user_merge_method'] = 'Member merger type';
$_LANG['user_merge_method_1'] = 'the same username and password of the users will force to be one user';
$_LANG['user_merge_method_2'] = 'the same username and password of the users will not force to be one user';
$_LANG['ucenter_not_match'] = '<span style="color:#F00;font-size:1.2em;"><b>UCenter\'s charset and ECShop\'s charset are different,upgrade can not be completed, please contact administrator!</b></span>';

/* 璇?█瀛楃?闆嗛€夋嫨 */
$_LANG['lang_title'] = 'ECShop\'s language and charset';
$_LANG['lang_description'] = 'Announce';
$_LANG['lang_charset']['zh_cn_gbk'] = 'Simplified Chinese GBK';
$_LANG['lang_charset']['zh_cn_utf-8'] = 'Simplified Chinese UTF-8';
$_LANG['lang_charset']['zh_tw_utf-8'] = 'Traditional Chinese UTF-8';
$_LANG['lang_desc']['desc1'] = 'Make sure ECShop\'s language and charset are same as your choice;';
$_LANG['lang_desc']['desc2'] = 'If your database\'s charset is different with ECShop program\'s charset,please covert database \'s charset first';
$_LANG['lang_desc']['desc3'] = '<font color="red">If you upgrade from ECShop v2.6.0 version, and select ECShop interface mode, first import the data to members, or former member will not be able to login.</font>';


/* 鐢ㄦ埛鎺ュ彛鎻掍欢璇?█椤 */
$_LANG['ui_title'] = 'Please select the user interface plug-ins of ECShop';
$_LANG['ui_ecshop'] = 'ECShop type';
$_LANG['ui_ucenter'] = 'UCenter type';


/* 鍗囩骇鏂囦欢浣跨敤涓?枃鐨勮?瑷€椤 */
$_LANG['update_v250']['zh_cn'] = array('甯愭埛鍐插€?, '甯愭埛鎻愭?', '璐?拱鍟嗗搧', '璁㈠崟閫€娆?, 'init' => '鍒濆?鍖?);
$_LANG['update_v250']['zh_tw'] = array('甯虫埗娌栧€?, '甯虫埗鎻愭?', '璩艰卜鍟嗗搧', '瑷傚柈閫€娆?, 'init' => '鍒濆?鍖?);
$_LANG['update_v250']['en_us'] = array('saving', 'drawing', 'buying', 'refundment',  'init' => 'initialize');
?>