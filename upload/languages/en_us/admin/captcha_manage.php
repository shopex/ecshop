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
 * $Id: captcha_manage.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['captcha_manage'] = 'Verification Code Set';
$_LANG['captcha_note'] = 'Open Verification Code required GD library server support, and your server does not install the GD library.';

$_LANG['captcha_setting'] = 'Verification Code Set';
$_LANG['captcha_turn_on'] = 'Enable Verification Code';
$_LANG['turn_on_note'] = 'Image Verification Code to avoid malicious bulk submit comments or information, recommend Open Verification Code function. Note: The Verification Code will make the opening part of the operation becomes complicated, it is recommended only when necessary to open';
$_LANG['captcha_register'] = 'New User Registration';
$_LANG['captcha_login'] = 'User Login';
$_LANG['captcha_comment'] = 'Comment';
$_LANG['captcha_admin'] = 'Backgrounds Administrator Login';
$_LANG['captcha_login_fail'] = 'Login Failed display Verification Code';
$_LANG['login_fail_note'] = 'Select /"Yes/" in the User Login failed 3 times before show Verification Code, select /"No/" will always be displayed when logging in Verification Code. Note: Only in the opening of the user login when the Verification Code set to be valid';
$_LANG['captcha_width'] = 'Verification Code picture width';
$_LANG['width_note'] = 'Verification Code picture width, ranging between 40 ~ 145';
$_LANG['captcha_height'] = 'Verification Code picture height';
$_LANG['height_note'] = 'Verification Code picture height, the range of between 15 ~ 50';

$_LANG['js_languages']['width_number'] = 'Please enter the picture width number!';
$_LANG['js_languages']['proper_width'] = 'The width of the picture must between 40 t0 145!';
$_LANG['js_languages']['height_number'] = 'Please enter the picture height number!';
$_LANG['js_languages']['proper_height'] = 'The height of the picture must between 40 t0 145!';

$_LANG['save_ok'] = 'Set up to preserve the success of';
$_LANG['captcha_message'] = 'Message Board Guest Book';
?>
