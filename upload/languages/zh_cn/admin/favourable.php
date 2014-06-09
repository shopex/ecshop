<?php

/**
 * ECSHOP 管理中心优惠活动语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: favourable.php 17217 2011-01-19 06:29:08Z liubo $
 */

/* menu */
$_LANG['favourable_list'] = '优惠活动列表';
$_LANG['add_favourable'] = '添加优惠活动';
$_LANG['edit_favourable'] = '编辑优惠活动';
$_LANG['favourable_log'] = '优惠活动出价记录';
$_LANG['continue_add_favourable'] = '继续添加优惠活动';
$_LANG['back_favourable_list'] = '返回优惠活动列表';
$_LANG['add_favourable_ok'] = '添加优惠活动成功';
$_LANG['edit_favourable_ok'] = '编辑优惠活动成功';

/* list */
$_LANG['act_is_going'] = '仅显示进行中的活动';
$_LANG['act_name'] = '优惠活动名称';
$_LANG['goods_name'] = '商品名称';
$_LANG['start_time'] = '开始时间';
$_LANG['end_time'] = '结束时间';
$_LANG['min_amount'] = '金额下限';
$_LANG['max_amount'] = '金额上限';
$_LANG['favourable_not_exist'] = '您要操作的优惠活动不存在';
$_LANG['js_languages']['batch_drop_confirm'] = '您确实要删除选中的优惠活动吗？';
$_LANG['batch_drop_ok'] = '批量删除成功';
$_LANG['no_record_selected'] = '没有选择记录';

/* info */
$_LANG['label_act_name'] = '优惠活动名称：';
$_LANG['label_start_time'] = '优惠开始时间：';
$_LANG['label_end_time'] = '优惠结束时间：';
$_LANG['label_user_rank'] = '享受优惠的会员等级：';
$_LANG['not_user'] = '非会员';
$_LANG['label_act_range'] = '优惠范围：';
$_LANG['far_all'] = '全部商品';
$_LANG['far_category'] = '以下分类';
$_LANG['far_brand'] = '以下品牌';
$_LANG['far_goods'] = '以下商品';
$_LANG['label_search_and_add'] = '搜索并加入优惠范围';
$_LANG['js_languages']['all_need_not_search'] = '优惠范围是全部商品，不需要此操作';
$_LANG['js_languages']['range_exists'] = '该选项已存在';
$_LANG['label_min_amount'] = '金额下限：';
$_LANG['label_max_amount'] = '金额上限：';
$_LANG['notice_max_amount'] = '0表示没有上限';
$_LANG['label_act_type'] = '优惠方式：';
$_LANG['notice_act_type'] = '当优惠方式为“享受赠品（特惠品）”时，请输入允许买家选择赠品（特惠品）的最大数量，数量为0表示不限数量；' .
        '当优惠方式为“享受现金减免”时，请输入现金减免的金额；' .
        '当优惠方式为“享受价格折扣”时，请输入折扣（1－99），如：打9折，就输入90。';
$_LANG['fat_goods'] = '享受赠品（特惠品）';
$_LANG['fat_price'] = '享受现金减免';
$_LANG['fat_discount'] = '享受价格折扣';
$_LANG['js_languages']['pls_search'] = '请先搜索';
$_LANG['search_result_empty'] = '没有找到相应记录，请重新搜索';
$_LANG['label_search_and_add_gift'] = '搜索并加入赠品（特惠品）';
$_LANG['js_languages']['price_need_not_search'] = '优惠方式是享受价格折扣，不需要此操作';
$_LANG['js_languages']['gift'] = '赠品（特惠品）';
$_LANG['js_languages']['price'] = '价格';

$_LANG['js_languages']['act_name_not_null'] = '请输入优惠活动名称';
$_LANG['js_languages']['min_amount_not_number'] = '金额下限格式不正确（数字）';
$_LANG['js_languages']['max_amount_not_number'] = '金额上限格式不正确（数字）';
$_LANG['js_languages']['act_type_ext_not_number'] = '优惠方式后面的值不正确（数字）';
$_LANG['js_languages']['amount_invalid'] = '金额上限小于金额下限。';
$_LANG['js_languages']['start_lt_end'] = '优惠开始时间不能大于结束时间';

/* post */
$_LANG['pls_set_user_rank'] = '请设置享受优惠的会员等级';
$_LANG['pls_set_act_range'] = '请设置优惠范围';
$_LANG['amount_error'] = '金额下限不能大于金额上限';
$_LANG['act_name_exists'] = '该优惠活动名称已存在，请您换一个';

$_LANG['nolimit'] = '没有限制';
?>