<?php

/**
 * ECSHOP 管理中心权限管理模块语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: sunxiaodong $
 * $Id: privilege.php 15587 2009-02-10 07:02:30Z sunxiaodong $
*/

/* 字段信息 */
$_LANG['user_id'] = '编号';
$_LANG['user_name'] = '角色名';
$_LANG['email'] = 'Email地址';
$_LANG['password'] = '密  码';
$_LANG['join_time'] = '加入时间';
$_LANG['last_time'] = '最后登录时间';
$_LANG['last_ip'] = '最后访问的IP';
$_LANG['action_list'] = '操作权限';
$_LANG['nav_list'] = '导航条';
$_LANG['language'] = '使用的语言';

$_LANG['allot_priv'] = '分派权限';
$_LANG['allot_list'] = '权限列表';
$_LANG['go_allot_priv'] = '设置管理员权限';

$_LANG['view_log'] = '查看日志';

$_LANG['back_home'] = '返回首页';
$_LANG['forget_pwd'] = '您忘记了密码吗?';
$_LANG['get_new_pwd'] = '找回管理员密码';
$_LANG['pwd_confirm'] = '确认密码';
$_LANG['new_password'] = '新密码';
$_LANG['old_password'] = '旧密码';
$_LANG['agency'] = '负责的办事处';
$_LANG['self_nav'] = '个人导航';
$_LANG['all_menus'] = '所有菜单';
$_LANG['add_nav'] = '增加';
$_LANG['remove_nav'] = '移除';
$_LANG['move_up'] = '上移';
$_LANG['move_down'] = '下移';
$_LANG['continue_add'] = '继续添加管理员';
$_LANG['back_list'] = '返回管理员列表';

$_LANG['admin_edit'] = '编辑管理员';
$_LANG['edit_pwd'] = '修改密码';

$_LANG['back_admin_list'] = '返回角色列表';

/* 提示信息 */
$_LANG['js_languages']['user_name_empty'] = '管理员用户名不能为空!';
$_LANG['js_languages']['password_invaild'] = '密码必须同时包含字母及数字且长度不能小于6!';
$_LANG['js_languages']['email_empty'] = 'Email地址不能为空!';
$_LANG['js_languages']['email_error'] = 'Email地址格式不正确!';
$_LANG['js_languages']['password_error'] = '两次输入的密码不一致!';
$_LANG['js_languages']['captcha_empty'] = '您没有输入验证码!';
$_LANG['action_succeed'] = '操作成功!';
$_LANG['edit_profile_succeed'] = '您已经成功的修改了个人帐号信息!';
$_LANG['edit_password_succeed'] = '您已经成功的修改了密码，因此您必须重新登录!';
$_LANG['user_name_exist'] = '该管理员已经存在!';
$_LANG['email_exist'] = 'Email地址已经存在!';
$_LANG['captcha_error'] = '您输入的验证码不正确。';
$_LANG['login_faild'] = '您输入的帐号信息不正确。';
$_LANG['user_name_drop'] = '已被成功删除!';
$_LANG['pwd_error'] = '输入的旧密码错误!';
$_LANG['old_password_empty'] = '如果要修改密码,必须先输入你的旧密码!';
$_LANG['edit_admininfo_error'] = '只能编辑自己的个人资料!';
$_LANG['edit_admininfo_cannot'] = '您不能对此管理员的权限进行任何操作!';
$_LANG['edit_remove_cannot'] = '您不能删除demo这个管理员!';
$_LANG['remove_self_cannot'] = '您不能删除自己!';
$_LANG['remove_cannot'] = '此管理员您不能进行删除操作!';
$_LANG['remove_cannot_user'] = '此角色有管理员在使用，暂时不能删除!';

$_LANG['modif_info'] = '编辑个人资料';
$_LANG['edit_navi'] = '设置个人导航';

/* 帮助信息 */
$_LANG['password_notic'] = '如果要修改密码,请先填写旧密码,如留空,密码保持不变';
$_LANG['email_notic'] = '输入管理员的Email邮箱,必须为Email格式';
$_LANG['confirm_notic'] = '输入管理员的确认密码,两次输入必须一致';

/* 登录表单 */
$_LANG['label_username'] = '管理员姓名：';
$_LANG['label_password'] = '管理员密码：';
$_LANG['label_captcha'] = '验证码：';
$_LANG['click_for_another'] = '看不清？点击更换另一个验证码。';
$_LANG['signin_now'] = '进入管理中心';
$_LANG['remember'] = '请保存我这次的登录信息。';
?>