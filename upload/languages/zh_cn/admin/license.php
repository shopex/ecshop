<?php

/**
 * ECSHOP 授权证书管理语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: testyang $
 * $Id: agency.php 15013 2008-10-23 09:31:42Z testyang $
 */

/* 菜单 */
$_LANG['upload_license'] = '上传证书';
$_LANG['download_license'] = '下载证书';
$_LANG['back'] = '返回';

/* 列表页 */
$_LANG['license_here'] = 'license证书';

/* 标签 */
$_LANG['label_certificate_download'] = 'ECshop证书下载备份';
$_LANG['label_license_key'] = '授权码：';
$_LANG['label_certificate_reset'] = 'ECshop证书上传恢复';
$_LANG['label_delete_license'] = '错误证书删除';
$_LANG['label_select_license'] = '选择上传证书：';

/* 系统提示 */
$_LANG['delete_license_notice'] = '当您上传了错误的ECshop证书导致证书功能失效时，请先在此处清空错误的证书，再使用证书上传恢复功能恢复正确的证书。';
$_LANG['license_notice'] = 'ECShop证书是您享受ECShop软件服务的唯一标识，它记录了您的网店的授权信息、购买官方服务记录、短信帐户等重要信息。您需要通过“证书下载备份”功能备份证书，并妥善保管。在您遇到商店系统需要重新安装时，可以在新安装的系统中使用“证书上传恢复”功能将之前备份的证书恢复，这样新系统就可以继续使用证书内的重要信息。';
$_LANG['delete_license'] = "错误的证书已删除。";
$_LANG['fail_license'] = "证书内容不全。请先确定证书是否正确然后重新上传！";
$_LANG['recover_license'] = "证书恢复成功。";
$_LANG['no_license_down'] = "失败。暂无证书可下载！";
$_LANG['fail_license_login'] = "证书登录失败。上传证书不正确！";

/* JS提示 */
$_LANG['js_languages']['del_confirm'] = '确认删除错误的证书吗？';

?>