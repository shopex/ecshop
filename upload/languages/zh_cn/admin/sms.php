<?php
/**
 * ECSHOP 短信模块语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: sms.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* 导航条 */
$_LANG['register_sms'] = '注册或启用短信账号';

/* 注册和启用短信功能 */
$_LANG['email'] = '电子邮箱';
$_LANG['password'] = '登录密码';
$_LANG['domain'] = '网店域名';
$_LANG['register_new'] = '注册新账号';
$_LANG['error_tips'] = '请在商店设置->短信设置，先注册短信服务并正确配置短信服务！';
$_LANG['enable_old'] = '启用已有账号';

/* 短信特服信息 */
$_LANG['sms_user_name'] = '用户名：';
$_LANG['sms_password'] = '密码：';
$_LANG['sms_domain'] = '域名：';
$_LANG['sms_num'] = '短信特服号：';
$_LANG['sms_count'] = '发送短信条数：';
$_LANG['sms_total_money'] = '总共冲值金额：';
$_LANG['sms_balance'] = '余额：';
$_LANG['sms_last_request'] = '最后一次请求时间：';
$_LANG['disable'] = '注销短信服务';

/* 发送短信 */
$_LANG['phone'] = '接收手机号码';
$_LANG['user_rand'] = '按用户等级发送短消息';
$_LANG['phone_notice'] = '多个手机号码用半角逗号分开';
$_LANG['msg'] = '消息内容';
$_LANG['msg_notice'] = '最长70字符';
$_LANG['send_date'] = '定时发送时间';
$_LANG['send_date_notice'] = '格式为YYYY-MM-DD HH:II。为空表示立即发送。';
$_LANG['back_send_history'] = '返回发送历史列表';
$_LANG['back_charge_history'] = '返回充值历史列表';

/* 记录查询界面 */
$_LANG['start_date'] = '开始日期';
$_LANG['date_notice'] = '格式为YYYY-MM-DD。可为空。';
$_LANG['end_date'] = '结束日期';
$_LANG['page_size'] = '每页显示数量';
$_LANG['page_size_notice'] = '可为空，表示每页显示20条记录';
$_LANG['page'] = '页数';
$_LANG['page_notice'] = '可为空，表示显示1页';
$_LANG['charge'] = '请输入您想要充值的金额';

/* 动作确认信息 */
$_LANG['history_query_error'] = '对不起，在查询过程中发生错误。';
$_LANG['enable_ok'] = '恭喜，您已成功启用短信服务！';
$_LANG['enable_error'] = '对不起，您启用短信服务失败。';
$_LANG['disable_ok'] = '您已经成功注销短信服务。';
$_LANG['disable_error'] = '注销短信服务失败。';
$_LANG['register_ok'] = '恭喜，您已成功注册短信服务！';
$_LANG['register_error'] = '对不起，您注册短信服务失败。';
$_LANG['send_ok'] = '恭喜，您的短信已经成功发送！';
$_LANG['send_error'] = '对不起，在发送短信过程中发生错误。';
$_LANG['error_no'] = '错误标识';
$_LANG['error_msg'] = '错误描述';
$_LANG['empty_info'] = '您的短信特服信息为空。';

/* 充值记录 */
$_LANG['order_id'] = '订单号';
$_LANG['money'] = '充值金额';
$_LANG['log_date'] = '充值日期';

/* 发送记录 */
$_LANG['sent_phones'] = '发送手机号码';
$_LANG['content'] = '发送内容';
$_LANG['charge_num'] = '计费条数';
$_LANG['sent_date'] = '发送日期';
$_LANG['send_status'] = '发送状态';
$_LANG['status'][0] = '失败';
$_LANG['status'][1] = '成功';
$_LANG['user_list'] = '全体会员';
$_LANG['please_select'] = '请选择会员等级';

/* 提示 */
$_LANG['test_now'] = '<span style="color:red;"></span>';
$_LANG['msg_price'] = '<span style="color:green;">短信每条0.1元(RMB)</span>';

/* API返回的错误信息 */
//--注册
$_LANG['api_errors']['register'][1] = '域名不能为空。';
$_LANG['api_errors']['register'][2] = '邮箱填写不正确。';
$_LANG['api_errors']['register'][3] = '用户名已存在。';
$_LANG['api_errors']['register'][4] = '未知错误。';
$_LANG['api_errors']['register'][5] = '接口错误。';
//--获取余额
$_LANG['api_errors']['get_balance'][1] = '用户名密码不正确。';
$_LANG['api_errors']['get_balance'][2] = '用户被禁用。';
//--发送短信
$_LANG['api_errors']['send'][1] = '用户名密码不正确。';
$_LANG['api_errors']['send'][2] = '短信内容过长。';
$_LANG['api_errors']['send'][3] = '发送日期应大于当前时间。';
$_LANG['api_errors']['send'][4] = '错误的号码。';
$_LANG['api_errors']['send'][5] = '账户余额不足。';
$_LANG['api_errors']['send'][6] = '账户已被停用。';
$_LANG['api_errors']['send'][7] = '接口错误。';
//--历史记录
$_LANG['api_errors']['get_history'][1] = '用户名密码不正确。';
$_LANG['api_errors']['get_history'][2] = '查无记录。';
//--用户验证
$_LANG['api_errors']['auth'][1] = '密码错误。';
$_LANG['api_errors']['auth'][2] = '用户不存在。';

/* 用户服务器检测到的错误信息 */
$_LANG['server_errors'][1] = '注册信息无效。';//ERROR_INVALID_REGISTER_INFO
$_LANG['server_errors'][2] = '启用信息无效。';//ERROR_INVALID_ENABLE_INFO
$_LANG['server_errors'][3] = '发送的信息有误。';//ERROR_INVALID_SEND_INFO
$_LANG['server_errors'][4] = '填写的查询信息有误。';//ERROR_INVALID_HISTORY_QUERY
$_LANG['server_errors'][5] = '无效的身份信息。';//ERROR_INVALID_PASSPORT
$_LANG['server_errors'][6] = 'URL不对。';//ERROR_INVALID_URL
$_LANG['server_errors'][7] = 'HTTP响应体为空。';//ERROR_EMPTY_RESPONSE
$_LANG['server_errors'][8] = '无效的XML文件。';//ERROR_INVALID_XML_FILE
$_LANG['server_errors'][9] = '无效的节点名字。';//ERROR_INVALID_NODE_NAME
$_LANG['server_errors'][10] = '存储失败。';//ERROR_CANT_STORE
$_LANG['server_errors'][11] = '短信功能尚未激活。';//ERROR_INVALID_PASSPORT

/* 客户端JS语言项 */
//--注册或启用
$_LANG['js_languages']['password_empty_error'] = '密码不能为空。';
$_LANG['js_languages']['username_empty_error'] = '用户名不能为空。';
$_LANG['js_languages']['username_format_error'] = '用户名格式不对。';
$_LANG['js_languages']['domain_empty_error'] = '域名不能为空。';
$_LANG['js_languages']['domain_format_error'] = '域名格式不对。';
$_LANG['js_languages']['send_empty_error'] = '发送手机号与发送等级至少填写一项！';
//--发送
$_LANG['js_languages']['phone_empty_error'] = '请填写手机号。';
$_LANG['js_languages']['phone_format_error'] = '手机号码格式不对。';
$_LANG['js_languages']['msg_empty_error'] = '请填写消息内容。';
$_LANG['js_languages']['send_date_format_error'] = '定时发送时间格式不对。';
//--历史记录
$_LANG['js_languages']['start_date_format_error'] = '开始日期格式不对。';
$_LANG['js_languages']['end_date_format_error'] = '结束日期格式不对。';
//--充值
$_LANG['js_languages']['money_empty_error'] = '请输入您要充值的金额。';
$_LANG['js_languages']['money_format_error'] = '金额格式不对。';

?>