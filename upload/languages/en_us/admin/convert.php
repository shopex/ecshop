<?php

/**
 * ECSHOP Convert a program language file
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
 * $Id: convert.php 17217 2011-01-19 06:29:08Z liubo $
 */

$_LANG['confirm_convert'] ='Attention: To convert program will lose the existing data, please operate with caution.';
$_LANG['backup_data'] ='If the existing data still has value probably to you, please backup first.';
$_LANG['backup'] ='Backup now.';
$_LANG['select_system'] ='Please choose the system that you want to convert:';
$_LANG['note_select_system'] ='(If your system not in the list, you can arrive <a href="http://www.ecshop.com" target="_ blank"> <strong> our website </strong></a>look for a help).';
$_LANG['select_charset'] ='Please choose the character list that the system that you have to convert use to gather:';
$_LANG['note_select_charset']='(If your system isn\'t UTF-8 character, converting need a long time.)';
$_LANG['dir_notes'] ='Please notice root directory path of the old shop, please use opposite path of the admin directory.<Br/>For example:The old shop directory is shop in root directory, but the ecshop put in root directory, that path is../shop.';
$_LANG['your_config'] ='Please config an original system to install an information:';
$_LANG['your_host'] ='Host name or address:';
$_LANG['your_user'] ='Username:';
$_LANG['your_pass'] ='Password:';
$_LANG['your_db'] ='Database name:';
$_LANG['your_prefix'] ='Datasheet prefixion:';
$_LANG['your_path'] ='Old shop root directory:';
$_LANG['convert'] ='Convert Data';
$_LANG['remark'] ='Remarks:';
$_LANG['remark_info'] = '<ul>' .
        '<Li>For the promotion product, you need to edit its original price(this shop selling price) and sales promotion date;</li>' .
        '<Li>Please re-create watermark;</li>' .
        '<Li>Please re-create an advertisement;</li>' .
        '<Li>Please re-create shipping method;</li>' .
        '<Li>Please re-create payment method;</li>' .
        '<li>Please move the former product of not bottom category to bottom category:</li>' .
        '</ul>';

$_LANG['connect_db_error'] ='Can\'t connect database, please check install information.';
$_LANG['table_error'] ='Shortage of essential form %s, please check install information.';
$_LANG['dir_error'] ='Shortage of essential directory %s, please check install information.';
$_LANG['dir_not_readable'] ='The directory can\'t be read %s.';
$_LANG['dir_not_writable'] ='The directory can\'t be wrote %s.';
$_LANG['file_not_readable'] ='The file can\'t be read %s';
$_LANG['js_languages']['check_your_db'] ='Checking the database of your system...';
$_LANG['js_languages']['act_ok'] ='Congratulations, operate successfully!';

$_LANG['js_languages']['no_system'] ='There is no available convert program.';
$_LANG['js_languages']['host_not_null'] ='The host name or address can\'t be blank.';
$_LANG['js_languages']['db_not_null'] ='The database name can\'t be blank.';
$_LANG['js_languages']['user_not_null'] ='Register ID can\'t be blank.';
$_LANG['js_languages']['path_not_null'] ='Original shop root directory can\'t be blank.';
?>