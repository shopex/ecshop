<?php

/**
 * ECSHOP 虚拟卡管理
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: virtual_card.php 17217 2011-01-19 06:29:08Z liubo $
*/

/*------------------------------------------------------ */
//-- 卡片信息
/*------------------------------------------------------ */
$_LANG['virtual_card_list'] = '虚拟商品列表';
$_LANG['lab_goods_name'] = '商品名称';
$_LANG['replenish'] = '补货';
$_LANG['lab_card_id'] = '编号';
$_LANG['lab_card_sn'] = '卡片序号';
$_LANG['lab_card_password'] = '卡片密码';
$_LANG['lab_end_date'] = '截至使用日期';
$_LANG['lab_is_saled'] = '是否已出售';
$_LANG['lab_order_sn'] = '订单号';
$_LANG['action_success'] = '操作成功';
$_LANG['action_fail'] = '操作失败';
$_LANG['card'] = '卡片列表';

$_LANG['batch_card_add'] = '批量添加补货';
$_LANG['download_file'] = '下载批量CSV文件';
$_LANG['separator'] = '分隔符';
$_LANG['uploadfile'] = '上传文件';
$_LANG['sql_error'] = '第 %s 条信息出错：<br /> ';

/* 提示信息 */
$_LANG['replenish_no_goods_id'] = '缺少商品ID参数，无法进行补货操作';
$_LANG['replenish_no_get_goods_name'] = '商品ID参数有误，无法获取商品名';
$_LANG['drop_card_success'] = '该记录已成功删除';
$_LANG['batch_drop'] = '批量删除';
$_LANG['drop_card_confirm'] = '你确定要删除该记录吗？';
$_LANG['card_sn_exist'] = '卡片序号 %s 已经存在，请重新输入';
$_LANG['go_list'] = '返回补货列表';
$_LANG['continue_add'] = '继续补货';
$_LANG['uploadfile_fail'] = '文件上传失败';
$_LANG['batch_card_add_ok'] = '已成功添加了 %s 条补货信息';

$_LANG['js_languages']['no_card_sn'] = '卡片序号和卡片密码不能都为空。';
$_LANG['js_languages']['separator_not_null'] = '分隔符号不能为空。';
$_LANG['js_languages']['uploadfile_not_null'] = '请选择要上传的文件。';

$_LANG['use_help'] = '使用说明：' .
        '<ol>' .
          '<li>上传文件应为CSV文件<br />' .
              'CSV文件第一列为卡片序号；第二列为卡片密码；第三列为使用截至日期。<br />'.
              '(用EXCEL创建csv文件方法：在EXCEL中按卡号、卡片密码、截至日期的顺序填写数据，完成后直接保存为csv文件即可)'.
          '<li>密码，和截至日期可以为空，截至日期格式为2006-11-6或2006/11/6'.
          '<li>卡号、卡片密码、截至日期中不要使用中文</li>' .
        '</ol>';

/*------------------------------------------------------ */
//-- 改变加密串
/*------------------------------------------------------ */

$_LANG['virtual_card_change'] = '更改加密串';
$_LANG['user_guide'] = '使用说明：' .
        '<ol>' .
          '<li>加密串是在加密虚拟卡类商品的卡号和密码时使用的</li>' .
          '<li>加密串保存在文件 data/config.php 中，对应的常量是 AUTH_KEY</li>' .
          '<li>如果要更改加密串在下面的文本框中输入原加密串和新加密串，点\'确定\'按钮后即可</li>' .
        '</ol>';
$_LANG['label_old_string'] = '原加密串';
$_LANG['label_new_string'] = '新加密串';

$_LANG['invalid_old_string'] = '原加密串不正确';
$_LANG['invalid_new_string'] = '新加密串不正确';
$_LANG['change_key_ok'] = '更改加密串成功';
$_LANG['same_string'] = '新加密串跟原加密串相同';

$_LANG['update_log'] = '更新记录';
$_LANG['old_stat'] = '总共有记录 %s 条。已使用新串加密的记录有 %s 条，使用原串加密（待更新）的记录有 %s 条，使用未知串加密的记录有 %s 条。';
$_LANG['new_stat'] = '<strong>更新完毕</strong>，现在使用新串加密的记录有 %s 条，使用未知串加密的记录有 %s 条。';
$_LANG['update_error'] = '更新过程中出错：%s';
$_LANG['js_languages']['updating_info'] = '<strong>正在更新</strong>（每次 100 条记录）';
$_LANG['js_languages']['updated_info'] = '<strong>已更新</strong> <span id=\"updated\">0</span> 条记录。';
?>