<?php

/**
 * ECSHOP
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: database.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['db_manage'] = '數據庫管理';
$_LANG['start_backup'] = '開始備份';
$_LANG['backup_name'] = '備份名稱';
$_LANG['backup_time'] = '備份時間';
$_LANG['backup_size'] = '備份大小';
$_LANG['restore'] = '恢復備份';
$_LANG['restore_ok'] = '恢復成功';
$_LANG['download'] = '下載';
$_LANG['restored'] = '備份已經恢復過了';
$_LANG['upload_sql'] = '上傳備份文件';

$_LANG['table'] = '數據表';
$_LANG['type'] = '數據表類型';
$_LANG['rec_num'] = '記錄數';
$_LANG['rec_size'] = '數據';
$_LANG['rec_chip'] = '碎片';
$_LANG['start_optimize'] = '開始進行數據表優化';
$_LANG['chip_count'] = '總碎片數';
$_LANG['charset'] = '字符集';
$_LANG['status'] = '狀態';

$_LANG['backup_type'] ='備份類型';
$_LANG['full_backup'] ='全部備份';
$_LANG['full_backup_note'] ='備份數據庫所有表';
$_LANG['stand_backup'] ='標準備份(推薦)';
$_LANG['stand_backup_note'] ='備份常用的數據表';
$_LANG['min_backup'] ='最小備份';
$_LANG['min_backup_note'] ='僅包括商品表，訂單表，用戶表';
$_LANG['custom_backup'] ='自定義備份';
$_LANG['custom_backup_note'] ='根據自行選擇備份數據表';

$_LANG['option'] = '其他選項';
$_LANG['ext_insert'] = '使用擴展插入(Extended Insert)方式';
$_LANG['is_pack'] = '是否將備份數據打包';
$_LANG['notice_is_pack'] = '打包能減小備份大小，但恢復備份時需要先解壓備份才能上傳';
$_LANG['vol_size'] = '分卷備份 - 文件長度限制(kb)';
$_LANG['sql_name'] = '備份文件名';
$_LANG['backup_failure'] = '備份出錯';

$_LANG['sqlfile'] = '本地sql文件';
$_LANG['update_table_pre'] = '更改表前綴';
$_LANG['old_table_pre'] = '原表前綴';
$_LANG['new_table_pre'] = '新表前綴';
$_LANG['use_new_pre'] = '使用新表前綴';
$_LANG['notice_use_new_pre'] = '只有在恢復全部備份時才可以選擇「是」，否則沒有備份的表將無法使用。<br />您也可以手動修改 data/config.php 中的 $prefix 變量來決定使用哪個表前綴';$_LANG['upload_and_exe'] = '上傳並執行sql文件';

/* 提示信息 */
$_LANG['fail_get_tables'] = '獲取備份數據表失敗';
$_LANG['fail_open_file'] = '文件打開失敗';
$_LANG['fail_remove'] = '文件刪除失敗';
$_LANG['fail_get_content'] = '獲取數據表內容失敗';
$_LANG['fail_upload'] = '文件上傳失敗';
$_LANG['fail_upload_move'] = '文件上傳移動失敗';
$_LANG['unrecognize_version'] = '不能識別備份sql的ECShop版本';
$_LANG['unrecognize_mysql_version'] = '不能識別備份sql的mysql版本';
$_LANG['mysql_version_error'] = '當前mysql版本%s與備份數據的mysql版本%s不同，你確認要導入該備份文件嗎?';
$_LANG['confirm_ver'] = '是，確認導入';
$_LANG['unconfirm_ver'] = '否，取消導入';
$_LANG['version_error'] = 'ECShop 當前版本%s與備份數據版本%s不同，備份恢復失敗';
$_LANG['not_sql_file'] = '你上傳的好像不是sql文件，如果文件確實是sql文件，請將文件擴展名改為.sql';
$_LANG['sqlfile_error'] = '你上傳的sql文件執行出錯，備份恢復失敗';
$_LANG['restore_success'] = '恢復成功';
$_LANG['fail_optimize'] = '優化數據表 %s 失敗';
$_LANG['optimize_ok'] = '數據表優化成功，共清理碎片 %d';
$_LANG['restore_confirm'] = '恢復數據庫會清除現有的所有內容，您確定要恢復嗎？';
$_LANG['fail_import'] = '數據導入失敗';
$_LANG['no_file'] = '文件不存在';
$_LANG['not_support_zip_format'] = '服務器不支持zip格式，請將文件解壓後再上傳';

/* js */
$_LANG['js_languages']['remove_confirm'] = '你確認要刪除該備份嗎？';
$_LANG['js_languages']['lang_remove'] = '移除';
$_LANG['js_languages']['lang_restore'] = '恢復備份';
$_LANG['js_languages']['lang_download'] = '下載';
$_LANG['js_languages']['sql_name_not_null'] = '文件名不能為空';
$_LANG['js_languages']['vol_size_not_null'] = '請填入備份大小';

/* 數據備份 */
$_LANG['backup_title'] = '數據文件 %s 成功創建，程序將自動繼續。';
$_LANG['backup_notice'] = '如果您的瀏覽器沒有自動跳轉，請點擊這裡';
$_LANG['backup_success'] = '備份成功';

$_LANG['name'] = '文件名';
$_LANG['ver'] = '版本';
$_LANG['add_time'] = '時間';
$_LANG['file_size'] = '大小';
$_LANG['empty_upload'] = '你上傳了一個空文件';
$_LANG['fail_write_file'] = '備份文件 %s 無法寫入';
$_LANG['vol'] = '卷';
$_LANG['import'] = '導入';
$_LANG['server_sql'] = '服務器上備份文件';
$_LANG['submit_remove'] = '刪除';
$_LANG['remove_success'] = '刪除成功';
$_LANG['confirm_import'] = '導入一個分卷可能導致數據不完整，是否一起導入其他分卷數據';
$_LANG['also_continue'] = '是，我要導入其他分卷數據';

$_LANG['dir_priv'] = '目錄 %s 權限有以下問題：';
$_LANG['dir_not_exist'] = '目錄 %s 不存在,請手動創建';
$_LANG['cannot_read'] = '不可讀';
$_LANG['cannot_write'] = '不可寫';
$_LANG['cannot_add'] = '追加數據';
$_LANG['cannot_modify'] = '不能修改文件';

$_LANG['confirm_remove'] = '你確定要刪除選中數據嗎？';

?>