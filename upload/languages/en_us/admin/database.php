<?php

/**
 * ECSHOP
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
 * $Id: database.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['db_manage'] ='Database management';
$_LANG['start_backup'] ='Start backup';
$_LANG['backup_name'] ='Bbackup name';
$_LANG['backup_time'] ='Backup time';
$_LANG['backup_size'] ='Bbackup size';
$_LANG['restore'] ='Restore backup';
$_LANG['restore_ok'] ='Restore successfully.';
$_LANG['download'] ='Download';
$_LANG['restored'] ='The backup has already restored.';
$_LANG['upload_sql'] ='Upload backup file';

$_LANG['table'] ='Datasheet';
$_LANG['type'] ='Type';
$_LANG['rec_num'] ='Records quantity';
$_LANG['rec_size'] ='Data';
$_LANG['rec_chip'] ='Fragment';
$_LANG['start_optimize'] ='Start optimize.';
$_LANG['chip_count'] ='Total fragments quantity';
$_LANG['charset'] ='Character list';
$_LANG['status'] ='Status';

$_LANG['backup_type'] ='Backup type';
$_LANG['full_backup'] ='Full backup';
$_LANG['full_backup_note'] ='Backup all database tables';
$_LANG['stand_backup'] ='Standard backup(recommend)';
$_LANG['stand_backup_note'] ='Backup in common use datasheet';
$_LANG['min_backup'] ='Minimum backup';
$_LANG['min_backup_note'] ='Only include the product table, the order table, the customer table.';
$_LANG['custom_backup'] ='Custom backup';
$_LANG['custom_backup_note'] ='According to choose the backup datasheet by oneself.';

$_LANG['option'] ='Other options';
$_LANG['ext_insert'] ='Use expand to insert mode (the Extended Insert).';
$_LANG['is_pack'] ='Pack backup data?';
$_LANG['notice_is_pack'] ='Pack can minish the backup size, but you have to decompress the backup before upload when restoring.';
$_LANG['vol_size'] ='Volume backup - The file limit size(kb).';
$_LANG['sql_name'] ='Backup file';
$_LANG['backup_failure'] ='Backup failure';

$_LANG['sqlfile'] ='Local sql file';
$_LANG['update_table_pre'] ='Change table prefixion';
$_LANG['old_table_pre'] ='At first table prefixion';
$_LANG['new_table_pre'] ='New table prefixion';
$_LANG['use_new_pre'] ='Use the new table prefixion.';
$_LANG['notice_use_new_pre'] ='Only just can choose while recover all backups "Yes", otherwise it will can\'t be used that the table without backup  .<Br/>you can also modify data/config.php in the $prefix to decide to use prefixion.';
$_LANG['upload_and_exe'] = 'Upload the sql file and execute.';

/* Prompting message */
$_LANG['fail_get_tables'] ='Obtain the backup datasheet failure.';
$_LANG['fail_open_file'] ='Open file failure.';
$_LANG['fail_remove'] ='Delete file failure.';
$_LANG['fail_get_content'] ='Obtain the datasheet contents failure.';
$_LANG['fail_upload'] ='Upload file failure.';
$_LANG['fail_upload_move'] ='Upload to move file failure.';
$_LANG['unrecognize_version'] ='Can\'t identify the ECShop version of the backup sql.';
$_LANG['unrecognize_mysql_version'] ='Can\'t identify the mysql version of the backup sql.';
$_LANG['mysql_version_error'] ='Current mysql version the %s and the mysql version of the backup data the %s is different, may appear a problem, are you sure import it?';
$_LANG['confirm_ver'] = 'Yes, please import it!';
$_LANG['unconfirm_ver'] = 'Cancel!';
$_LANG['version_error'] ='ECShop current version the %s and the backup data version the %s is different, the backup restore failure.';
$_LANG['not_sql_file'] ='This isn\'t a sql file, if the file is true, please change the file suffixal name as .sql.';
$_LANG['sqlfile_error'] ='You upload the file failure, the backup restore failure.';
$_LANG['restore_success'] ='Restore successfully.';
$_LANG['fail_optimize'] ='Optimize datasheet %s failure.';
$_LANG['optimize_ok'] ='Optimize datasheet successfully, tidying up fragment totally %d.';
$_LANG['restore_confirm'] ='Restore database will clear existing all contents, are you sure recover it?';
$_LANG['fail_import'] ='The data ducting failure.';
$_LANG['no_file'] ='The file is nonexistent.';
$_LANG['not_support_zip_format'] ='The server nonsupport zip format, please decompress the file and upload again.';

/* js */
$_LANG['js_languages']['remove_confirm'] ='Are you sure delete the backup?';
$_LANG['js_languages']['lang_remove'] ='Remove';
$_LANG['js_languages']['lang_restore'] ='Restore backup';
$_LANG['js_languages']['lang_download'] ='Download';
$_LANG['js_languages']['sql_name_not_null'] ='The file can\'t be blank.';
$_LANG['js_languages']['vol_size_not_null'] ='Please enter the backup size.';

/* buckup center */
$_LANG['backup_title'] = 'The part %s of backup is created, It is running automatism.';
$_LANG['backup_notice'] = 'Please chick here ,if your browse has redirect automatism.';
$_LANG['backup_success'] = 'Success';

$_LANG['name'] = 'File Name';
$_LANG['ver'] = 'Version';
$_LANG['add_time'] = 'Time';
$_LANG['file_size'] = 'Size';
$_LANG['empty_upload'] = 'It is empty';
$_LANG['fail_write_file'] = 'The file %s can not writting';
$_LANG['vol'] = 'VOL';
$_LANG['import'] = 'Import';
$_LANG['server_sql'] = 'Server Backup';
$_LANG['submit_remove'] = 'Delete';
$_LANG['remove_success'] = 'Delete Success';
$_LANG['confirm_import'] = 'Do you want to import other backup';
$_LANG['also_continue'] = 'Yes, I want do it';

$_LANG['dir_priv'] = 'The problem with directory %s :';
$_LANG['dir_not_exist'] = 'This directory %s is not exist, please create it';
$_LANG['cannot_read'] = 'Can not Read';
$_LANG['cannot_write'] = 'Can not Write';
$_LANG['cannot_add'] = 'Can not Add data';
$_LANG['cannot_modify'] = 'Can not Modify';

$_LANG['confirm_remove'] = 'Are you sure delete Items?';

?>