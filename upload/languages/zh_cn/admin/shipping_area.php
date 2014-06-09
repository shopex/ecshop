<?php

/**
 * ECSHOP 管理中心配送方式管理语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: shipping_area.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['shipping_area_name'] = '配送区域名称';
$_LANG['shipping_area_districts'] = '地区列表';
$_LANG['shipping_area_regions'] = '所辖地区';
$_LANG['shipping_area_assign'] = '配送方式';
$_LANG['area_region'] = '地区';
$_LANG['area_shipping'] = '配送方式';
$_LANG['fee_compute_mode'] = '费用计算方式';
$_LANG['fee_by_weight'] = '按重量计算';
$_LANG['fee_by_number'] = '按商品件数计算';
$_LANG['new_area'] = '新建配送区域';
$_LANG['label_country'] = '国家：';
$_LANG['label_province'] = '省份：';
$_LANG['label_city'] = '城市：';
$_LANG['label_district'] = '区/县：';

$_LANG['delete_selected'] = '移除选定的配送区域';
$_LANG['btn_add_region'] = '添加选定地区';
$_LANG['free_money'] = '免费额度:';
$_LANG['pay_fee'] = '货到付款支付费用：';
$_LANG['edit_area'] = '编辑配送区域';

$_LANG['add_continue'] = '继续添加配送区域';
$_LANG['back_list'] = '返回列表页';
$_LANG['empty_regions'] = '当前区域下没有任何关联地区';

/* 提示信息 */
$_LANG['repeat_area_name'] = '已经存在一个同名的配送区域。';
$_LANG['not_find_plugin'] = '没有找到指定的配送方式的插件。';
$_LANG['remove_confirm'] = '您确定要删除选定的配送区域吗？';
$_LANG['remove_success'] = '指定的配送区域已经删除成功！';
$_LANG['no_shippings'] = '没有找到任何可用的配送方式。';
$_LANG['add_area_success'] = '添加配送区域成功。';
$_LANG['edit_area_success'] = '编辑配送区域成功。';
$_LANG['disable_shipping_success'] = '指定的配送方式已经从该配送区域中移除。';

/* 需要用到的JS语言项 */
$_LANG['js_languages']['no_area_name'] = '配送区域名称不能为空。';
$_LANG['js_languages']['del_shipping_area'] = '请先删除该配送区域，然后重新添加。';
$_LANG['js_languages']['invalid_free_mondy'] = '免费额度不能为空且必须是一个整数。';
$_LANG['js_languages']['blank_shipping_area'] = '配送区域的所辖区域不能为空。';
$_LANG['js_languages']['lang_remove'] = '移除';
$_LANG['js_languages']['lang_remove_confirm'] = '您确定要移除该地区吗？';
$_LANG['js_languages']['lang_disabled'] = '禁用';
$_LANG['js_languages']['lang_enabled'] = '启用';
$_LANG['js_languages']['lang_setup'] = '设置';
$_LANG['js_languages']['lang_region'] = '地区';
$_LANG['js_languages']['lang_shipping'] = '配送方式';
$_LANG['js_languages']['region_exists'] = '选定的地区已经存在。';
?>