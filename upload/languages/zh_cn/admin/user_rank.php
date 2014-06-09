<?php

/**
 * ECSHOP 管理中心共用语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: user_rank.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['rank_name'] = '会员等级名称';
$_LANG['integral_min'] = '积分下限';
$_LANG['integral_max'] = '积分上限';
$_LANG['discount'] = '初始折扣率';
$_LANG['add_user_rank'] = '添加会员等级';
$_LANG['special_rank'] = '特殊会员组';
$_LANG['show_price'] = '在商品详情页显示该会员等级的商品价格';
$_LANG['notice_special'] = '特殊会员组的会员不会随着积分的变化而变化。';
$_LANG['add_continue'] = '继续添加会员等级';
$_LANG['back_list'] = '返回会员等级列表';
$_LANG['show_price_short'] = '显示价格';
$_LANG['notice_discount'] = '请填写为0-100的整数,如填入80，表示初始折扣率为8折';

$_LANG['rank_name_exists'] = '会员等级名 %s 已经存在。';
$_LANG['add_rank_success'] = '会员等级已经添加成功。';
$_LANG['integral_min_exists'] = '已经存在一个等级积分下限为 %d 的会员等级';
$_LANG['integral_max_exists'] = '已经存在一个等级积分上限为 %d 的会员等级';

/* JS 语言 */
$_LANG['js_languages']['remove_confirm'] = '您确定要删除选定的会员等级吗？';
$_LANG['js_languages']['rank_name_empty'] = '您没有输入会员等级名称。';
$_LANG['js_languages']['integral_min_invalid'] = '您没有输入积分下限或者积分下限不是一个整数。';
$_LANG['js_languages']['integral_max_invalid'] = '您没有输入积分上限或者积分上限不是一个整数。';
$_LANG['js_languages']['discount_invalid'] = '您没有输入折扣率或者折扣率无效。';
$_LANG['js_languages']['integral_max_small'] = '积分上限必须大于积分下限。';
$_LANG['js_languages']['lang_remove'] = '移除';
?>