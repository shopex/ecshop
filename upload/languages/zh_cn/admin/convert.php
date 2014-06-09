<?php

/**
 * ECSHOP 转换程序语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: convert.php 17217 2011-01-19 06:29:08Z liubo $
 */

$_LANG['confirm_convert'] = '注意：执行转换程序将会使现有数据丢失，请您三思而行！！！';
$_LANG['backup_data'] = '如果现有数据对您可能还有价值，请您先做好备份。';
$_LANG['backup'] = '现在就去备份';
$_LANG['select_system'] = '请选择您要转换的系统：';
$_LANG['note_select_system'] = '（如果您的系统不在左边的列表中，您可以到<a href="http://www.ecshop.com" target="_blank"><strong>我们的网站</strong></a>寻求帮助）';
$_LANG['select_charset'] = '请选择您要转换的系统使用的字符集：';
$_LANG['note_select_charset'] = '（如果你的系统使用的不是 UTF-8 字符集，转换可能需要较长时间）';
$_LANG['dir_notes'] = '请注意原商城的根目录路径请使用相对于admin目录的路径。<br />例如：原商城的目录在根目录下的shop，而ecshop放在根目录下，则该路径为 ../shop';
$_LANG['your_config'] = '请设置原系统的配置信息：';
$_LANG['your_host'] = '主机名称或地址：';
$_LANG['your_user'] = '登录帐号：';
$_LANG['your_pass'] = '登录密码：';
$_LANG['your_db'] = '数据库名称：';
$_LANG['your_prefix'] = '数据库表前缀：';
$_LANG['your_path'] = '原商城根目录：';
$_LANG['convert'] = '转换数据';
$_LANG['remark'] = '备注：';
$_LANG['remark_info'] = '<ul>' .
        '<li>对于特价的商品，您需要编辑其原价（本店售价）和促销期；</li>' .
        '<li>请重新设置水印；</li>' .
        '<li>请重新设置广告；</li>' .
        '<li>请重新设置配送方式；</li>' .
        '<li>请重新设置支付方式；</li>' .
        '<li>请把原来不属于末级分类的商品转移到末级分类；</li>' .
        '</ul>';

$_LANG['connect_db_error'] = '无法连接数据库，请检查配置信息。';
$_LANG['table_error'] = '缺少必需的表 %s ，请检查配置信息。';
$_LANG['dir_error'] = '缺少必需的目录 %s ，请检查配置信息。';
$_LANG['dir_not_readable'] = '目录不可读 %s';
$_LANG['dir_not_writable'] = '目录不可写 %s';
$_LANG['file_not_readable'] = '文件不可读 %s';
$_LANG['js_languages']['check_your_db'] = '正在检查您的系统的数据库...';
$_LANG['js_languages']['act_ok'] = '恭喜您，操作成功！';

$_LANG['js_languages']['no_system'] = '没有可用的转换程序';
$_LANG['js_languages']['host_not_null'] = '主机名称或地址不能为空';
$_LANG['js_languages']['db_not_null'] = '数据库名称不能为空';
$_LANG['js_languages']['user_not_null'] = '登录帐号不能为空';
$_LANG['js_languages']['path_not_null'] = '原商城根目录不能为空';
?>