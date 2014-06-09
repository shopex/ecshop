<?php

/**
 * ECSHOP 升级程序语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Date: 2008-07-16 14:54:08 +0800$
 * $Id: zh_cn_gbk.php 16882 2009-12-14 09:22:19Z liubo $
*/

$_LANG['prev_step']         = '上一步：';
$_LANG['next_step']         = '下一步：';
$_LANG['select_language_title']       =  'ECSHOP升级程序 第1步/共3步 选择语言编码';
$_LANG['readme_title']                =  'ECSHOP升级程序 第2步/共3步 说明页';
$_LANG['checking_title']                =  'ECShop升级程序 第3步/共3步 环境检测';
$_LANG['check_system_environment']          = '检测系统环境';

$_LANG['copyright']                     = '&copy; 2005-2011 <a href="http://www.ecshop.com" target="_blank">上海商派网络科技有限公司</a>。保留所有权利。';
$_LANG['is_last_version']             = '您的ECSHOP已是最新版本，无需升级。';

$_LANG['readme_page']                =  '说明页';
$_LANG['notice'] = '本程序将用于安装ECSHOP的测试数据，请务必确认以下问题。';
$_LANG['usage1'] = '安装测试数据会覆盖您的原有数据。<br />';
$_LANG['usage2']  = '<a href="../admin">登录后台</a>，<span style="color:red;font-weight:bold;font-size:18px;">备份</span>数据库资料；';
//$_LANG['usage3']  = '关闭现有的 ECSHOP %s 系统；';
//$_LANG['usage4']  = '覆盖性上传 ECSHOP %s 的全部文件到服务器；';
//$_LANG['usage5']  = '上传本程序到 ECSHOP 所在的目录中；';
$_LANG['usage3']  = '运行本程序，直到出现测试数据安装完成的提示。';
$_LANG['method']  = '安装测试数据';
$_LANG['charset']  = '编码确认';

$_LANG['faq']  = '常见问题';

$_LANG['basic_config']                           = '基本配置信息';
$_LANG['config_path']                           = '配置文件路径';
$_LANG['db_host']                           = '数据库主机';
$_LANG['db_name']                           = '数据库名';
$_LANG['db_user']                           = '用户名';
$_LANG['db_pass']                           = '密码';
$_LANG['db_prefix']                         = '表前缀';
$_LANG['timezone']                         = '时区设置';
$_LANG['cookie_path']                      = 'COOKIE路径';
$_LANG['admin_dir']                        = '管理中心根路径';

$_LANG['dir_priv_checking']                 = '目录权限检测';
$_LANG['template_writable_checking']        = '模板可写性检查';
$_LANG['rename_priv_checking']              = '特定目录修改权限检查';
$_LANG['cannt_write']                     =  '不可写';
$_LANG['can_write']                       = '可写';
$_LANG['cannt_modify']                    = '不可修改';
$_LANG['not_exists']                      = '不存在';
$_LANG['recheck']                         = '重新检查';
$_LANG['all_are_writable']                = '所有模板，全部可写';

$_LANG['update_now']                    = '立即安装测试数据';
$_LANG['done'] = '恭喜，您已经成功安装了测试数据';
$_LANG['upgrade_error_title']                    = 'ECShop测试数据安装程序 测试数据安装失败';
$_LANG['upgrade_done_title'] = 'ECShop测试数据安装程序 测试数据安装成功';
$_LANG['go_to_view_my_ecshop'] = '前往 ECSHOP 首页';
$_LANG['go_to_view_control_panel'] = '前往 ECSHOP 后台管理中心 ';
$_LANG['dir_readonly']          = '%s 文件不可写，请检查您的服务器设置。';
$_LANG['monitor_title']          = '升级程序监视器';
$_LANG['wait_please']          = '正在升级中，请稍候…………';
$_LANG['js_error']          = '客户端JavaScript脚本发生错误。';
$_LANG['create_ver_failed']          = '创建版本对象失败';
$_LANG['goto_charset_convert']  = '转向：数据库编码转换';
$_LANG['goto_members_import']  = '转向：从UCenter导入会员数据';

/* 客户端JS语言项 */
$_LANG['js_languages']['display_detail']                   = '显示细节';
$_LANG['js_languages']['exception']                   = '发生异常';
$_LANG['js_languages']['hide_detail']                   = '隐藏细节';
$_LANG['js_languages']['suspension_points']                   = '…………';
$_LANG['js_languages']['initialize']                   = '初始化';
$_LANG['js_languages']['wait_please']               = '正在安装测试数据，请稍候…………';
$_LANG['js_languages']['has_been_stopped']                    = '测试数据安装进程已中止';
$_LANG['js_languages']['is_last_version']                   = '您的ECSHOP已是最新版本，无需升级。';
$_LANG['js_languages']['from']                   = '正在从';
$_LANG['js_languages']['to']                   = '升级到';
$_LANG['js_languages']['update_files']                   = '升级文件';
$_LANG['js_languages']['update_structure']                   = '升级数据结构';
$_LANG['js_languages']['update_others']                   = '升级其它';
$_LANG['js_languages']['success']                   = '完成';
$_LANG['js_languages']['fail']                      = '失败';
$_LANG['js_languages']['notice']                      = '出错';
$_LANG['js_languages']['dump_database'] = '备份数据';
$_LANG['js_languages']['rollback'] = '恢复数据';
$_LANG['js_languages']['uc_api'] = '请填写 UCenter 的URL';
$_LANG['js_languages']['uc_ip'] = '请填写 UCenter 的IP';
$_LANG['js_languages']['uc_pwd'] = '请填写 UCenter 创始人的密码';

/* UCenter 安装配置 */
$_LANG['configure_uc'] = '配置UCenter';
$_LANG['check_ucenter'] = '填写完毕，进行下一步';
$_LANG['ucapi'] = 'UCenter 的 URL：';
$_LANG['ucip'] = 'UCenter 的 IP：';
$_LANG['ucenter'] = '请填写 UCenter 相关信息：';
$_LANG['ucfounderpw'] = 'UCenter 创始人密码：';
$_LANG['uc_intro'] = 'UCenter 是 Comsenz 公司产品的核心服务程序，Discuz! Board 的安装和运行依赖此程序。如果您已经安装了 UCenter，请填写以下信息。否则，请到 <a href="http://www.discuz.com" target="_blank">Comsenz 产品中心</a> 下载并且安装，然后再继续。<br /><br />';
$_LANG['ucip_intro'] = '连接的过程中出了点问题，请您填写服务器 IP 地址，如果您的 UC 与 ECShop 装在同一服务器上，我们建议您尝试填写 127.0.0.1';

$_LANG['users_importto_ucenter'] = '会员数据导入到 UCenter';
$_LANG['user_startid'] = '会员 ID 起始值：';
$_LANG['user_startid_intro'] = '<p>此起始会员ID为%s。如原 ID 为 888 的会员将变为 %s+888 的值。</p><br /><p><span style="color:#F00;font-size:1.2em;font-weight:bold;">提醒：导入会员数据前请暂停各个应用(如Discuz!, SupeSite等)</span></p><br />';
$_LANG['maxuid_err'] = '起始会员 ID 必须大于等于';
$_LANG['ucenter_import_members'] = '导入会员数据到UCenter';
$_LANG['ucenter_no_database'] = '<span style="color:#F00;font-size:1.5em;"><b>不能连接到UCenter的数据库，升级不能完成，请联系管理员！</b></span>';
$_LANG['user_merge_method'] = '会员合并方式：';
$_LANG['user_merge_method_1'] = '将与UC用户名和密码相同的用户强制为同一用户';
$_LANG['user_merge_method_2'] = '将与UC用户名和密码相同的用户不导入UC用户';
$_LANG['ucenter_not_match'] = '<span style="color:#F00;font-size:1.2em;"><b>UCenter与ECShop字符编码不匹配，升级不能完成，请联系管理员！</b></span>';

/* 语言字符集选择 */
$_LANG['lang_title'] = 'ECShop语言编码';
$_LANG['lang_description'] = '声明';
$_LANG['lang_charset']['zh_cn_gbk'] = '简体中文 GBK';
$_LANG['lang_charset']['zh_cn_utf-8'] = '简体中文 UTF-8';
$_LANG['lang_charset']['zh_tw_utf-8'] = '繁体中文 UTF-8';
$_LANG['lang_desc']['desc1'] = '请确认您的ECShop程序与您选择的语言编码一致；';
$_LANG['lang_desc']['desc2'] = '如果您的数据库与ECShop程序编码不一致，可以先进行数据库编码转换。';
//$_LANG['lang_desc']['desc3'] = '<font color="red">如果您是从ECShop v2.6.0版本进行升级，并选择ECShop接口方式，请先进行会员数据的导入，否则原会员将无法登录。</font>';

/* 用户接口插件语言项 */
$_LANG['ui_title'] = '请选择ECShop使用的用户接口插件';
$_LANG['ui_ecshop'] = 'ECShop方式';
$_LANG['ui_ucenter'] = 'UCenter方式';



/* 升级文件使用中文的语言项 */
$_LANG['update_v250']['zh_cn'] = array('帐户冲值', '帐户提款', '购买商品', '订单退款', 'init' => '初始化');
$_LANG['update_v250']['zh_tw'] = array('帳戶沖值', '帳戶提款', '購買商品', '訂單退款', 'init' => '初始化');
$_LANG['update_v250']['en_us'] = array('saving', 'drawing', 'buying', 'refundment',  'init' => 'initialize');
?>
