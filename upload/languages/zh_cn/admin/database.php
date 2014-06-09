<?php

/**
 * ECSHOP
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: database.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['db_manage'] = '数据库管理';
$_LANG['start_backup'] = '开始备份';
$_LANG['backup_name'] = '备份名称';
$_LANG['backup_time'] = '备份时间';
$_LANG['backup_size'] = '备份大小';
$_LANG['restore'] = '恢复备份';
$_LANG['restore_ok'] = '恢复成功';
$_LANG['download'] = '下载';
$_LANG['restored'] = '备份已经恢复过了';
$_LANG['upload_sql'] = '上传备份文件';

$_LANG['table'] = '数据表';
$_LANG['type'] = '数据表类型';
$_LANG['rec_num'] = '记录数';
$_LANG['rec_size'] = '数据';
$_LANG['rec_chip'] = '碎片';
$_LANG['start_optimize'] = '开始进行数据表优化';
$_LANG['chip_count'] = '总碎片数';
$_LANG['charset'] = '字符集';
$_LANG['status'] = '状态';

$_LANG['backup_type'] ='备份类型';
$_LANG['full_backup'] ='全部备份';
$_LANG['full_backup_note'] ='备份数据库所有表';
$_LANG['stand_backup'] ='标准备份(推荐)';
$_LANG['stand_backup_note'] ='备份常用的数据表';
$_LANG['min_backup'] ='最小备份';
$_LANG['min_backup_note'] ='仅包括商品表，订单表，用户表';
$_LANG['custom_backup'] ='自定义备份';
$_LANG['custom_backup_note'] ='根据自行选择备份数据表';

$_LANG['option'] = '其他选项';
$_LANG['ext_insert'] = '使用扩展插入(Extended Insert)方式';
$_LANG['is_pack'] = '是否将备份数据打包';
$_LANG['notice_is_pack'] = '打包能减小备份大小，但恢复备份时需要先解压备份才能上传';
$_LANG['vol_size'] = '分卷备份 - 文件长度限制(kb)';
$_LANG['sql_name'] = '备份文件名';
$_LANG['backup_failure'] = '备份出错';

$_LANG['sqlfile'] = '本地sql文件';
$_LANG['update_table_pre'] = '更改表前缀';
$_LANG['old_table_pre'] = '原表前缀';
$_LANG['new_table_pre'] = '新表前缀';
$_LANG['use_new_pre'] = '使用新表前缀';
$_LANG['notice_use_new_pre'] = '只有在恢复全部备份时才可以选择“是”，否则没有备份的表将无法使用。<br />您也可以手动修改 data/config.php 中的 $prefix 变量来决定使用哪个表前缀';$_LANG['upload_and_exe'] = '上传并执行sql文件';

/* 提示信息 */
$_LANG['fail_get_tables'] = '获取备份数据表失败';
$_LANG['fail_open_file'] = '文件打开失败';
$_LANG['fail_remove'] = '文件删除失败';
$_LANG['fail_get_content'] = '获取数据表内容失败';
$_LANG['fail_upload'] = '文件上传失败';
$_LANG['fail_upload_move'] = '文件上传移动失败';
$_LANG['unrecognize_version'] = '不能识别备份sql的ECShop版本';
$_LANG['unrecognize_mysql_version'] = '不能识别备份sql的mysql版本';
$_LANG['mysql_version_error'] = '当前mysql版本%s与备份数据的mysql版本%s不同，你确认要导入该备份文件吗?';
$_LANG['confirm_ver'] = '是，确认导入';
$_LANG['unconfirm_ver'] = '否，取消导入';
$_LANG['version_error'] = 'ECShop 当前版本%s与备份数据版本%s不同，备份恢复失败';
$_LANG['not_sql_file'] = '你上传的好象不是sql文件，如果文件确实是sql文件，请将文件扩展名改为.sql';
$_LANG['sqlfile_error'] = '你上传的sql文件执行出错，备份恢复失败';
$_LANG['restore_success'] = '恢复成功';
$_LANG['fail_optimize'] = '优化数据表 %s 失败';
$_LANG['optimize_ok'] = '数据表优化成功，共清理碎片 %d';
$_LANG['restore_confirm'] = '恢复数据库会清除现有的所有内容，您确定要恢复吗？';
$_LANG['fail_import'] = '数据导入失败';
$_LANG['no_file'] = '文件不存在';
$_LANG['not_support_zip_format'] = '服务器不支持zip格式，请将文件解压后再上传';

/* js */
$_LANG['js_languages']['remove_confirm'] = '你确认要删除该备份吗？';
$_LANG['js_languages']['lang_remove'] = '移除';
$_LANG['js_languages']['lang_restore'] = '恢复备份';
$_LANG['js_languages']['lang_download'] = '下载';
$_LANG['js_languages']['sql_name_not_null'] = '文件名不能为空';
$_LANG['js_languages']['vol_size_not_null'] = '请填入备份大小';

/* 数据备份 */
$_LANG['backup_title'] = '数据文件 %s 成功创建，程序将自动继续。';
$_LANG['backup_notice'] = '如果您的浏览器没有自动跳转，请点击这里';
$_LANG['backup_success'] = '备份成功';

$_LANG['name'] = '文件名';
$_LANG['ver'] = '版本';
$_LANG['add_time'] = '时间';
$_LANG['file_size'] = '大小';
$_LANG['empty_upload'] = '你上传了一个空文件';
$_LANG['fail_write_file'] = '备份文件 %s 无法写入';
$_LANG['vol'] = '卷';
$_LANG['import'] = '导入';
$_LANG['server_sql'] = '服务器上备份文件';
$_LANG['submit_remove'] = '删除';
$_LANG['remove_success'] = '删除成功';
$_LANG['confirm_import'] = '导入一个分卷可能导致数据不完整，是否一起导入其他分卷数据';
$_LANG['also_continue'] = '是，我要导入其他分卷数据';

$_LANG['dir_priv'] = '目录 %s 权限有以下问题：';
$_LANG['dir_not_exist'] = '目录 %s 不存在,请手动创建';
$_LANG['cannot_read'] = '不可读';
$_LANG['cannot_write'] = '不可写';
$_LANG['cannot_add'] = '追加数据';
$_LANG['cannot_modify'] = '不能修改文件';

$_LANG['confirm_remove'] = '你确定要删除选中数据吗？';

?>