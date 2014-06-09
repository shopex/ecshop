<?php

/**
 * ECSHOP 管理中心優惠活動語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: favourable.php 17217 2011-01-19 06:29:08Z liubo $
 */

/* menu */
$_LANG['favourable_list'] = '優惠活動列表';
$_LANG['add_favourable'] = '添加優惠活動';
$_LANG['edit_favourable'] = '編輯優惠活動';
$_LANG['favourable_log'] = '優惠活動出價記錄';
$_LANG['continue_add_favourable'] = '繼續添加優惠活動';
$_LANG['back_favourable_list'] = '返回優惠活動列表';
$_LANG['add_favourable_ok'] = '添加優惠活動成功';
$_LANG['edit_favourable_ok'] = '編輯優惠活動成功';

/* list */
$_LANG['act_is_going'] = '僅顯示進行中的活動';
$_LANG['act_name'] = '優惠活動名稱';
$_LANG['goods_name'] = '商品名稱';
$_LANG['start_time'] = '開始時間';
$_LANG['end_time'] = '結束時間';
$_LANG['min_amount'] = '金額下限';
$_LANG['max_amount'] = '金額上限';
$_LANG['favourable_not_exist'] = '您要操作的優惠活動不存在';
$_LANG['js_languages']['batch_drop_confirm'] = '您確實要刪除選中的優惠活動嗎？';
$_LANG['batch_drop_ok'] = '批量刪除成功';
$_LANG['no_record_selected'] = '沒有選擇記錄';

/* info */
$_LANG['label_act_name'] = '優惠活動名稱：';
$_LANG['label_start_time'] = '優惠開始時間：';
$_LANG['label_end_time'] = '優惠結束時間：';
$_LANG['label_user_rank'] = '享受優惠的會員等級：';
$_LANG['not_user'] = '非會員';
$_LANG['label_act_range'] = '優惠範圍：';
$_LANG['far_all'] = '全部商品';
$_LANG['far_category'] = '以下分類';
$_LANG['far_brand'] = '以下品牌';
$_LANG['far_goods'] = '以下商品';
$_LANG['label_search_and_add'] = '搜索並加入優惠範圍';
$_LANG['js_languages']['all_need_not_search'] = '優惠範圍是全部商品，不需要此操作';
$_LANG['js_languages']['range_exists'] = '該選項已存在';
$_LANG['label_min_amount'] = '金額下限：';
$_LANG['label_max_amount'] = '金額上限：';
$_LANG['notice_max_amount'] = '0表示沒有上限';
$_LANG['label_act_type'] = '優惠方式：';
$_LANG['notice_act_type'] = '當優惠方式為「享受贈品（特惠品）」時，請輸入允許買家選擇贈品（特惠品）的最大數量，數量為0表示不限數量；' .
        '當優惠方式為「享受現金減免」時，請輸入現金減免的金額；' .
        '當優惠方式為「享受價格折扣」時，請輸入折扣（1－99），如：打9折，就輸入90。';
$_LANG['fat_goods'] = '享受贈品（特惠品）';
$_LANG['fat_price'] = '享受現金減免';
$_LANG['fat_discount'] = '享受價格折扣';
$_LANG['js_languages']['pls_search'] = '請先搜索';
$_LANG['search_result_empty'] = '沒有找到相應記錄，請重新搜索';
$_LANG['label_search_and_add_gift'] = '搜索並加入贈品（特惠品）';
$_LANG['js_languages']['price_need_not_search'] = '優惠方式是享受價格折扣，不需要此操作';
$_LANG['js_languages']['gift'] = '贈品（特惠品）';
$_LANG['js_languages']['price'] = '價格';

$_LANG['js_languages']['act_name_not_null'] = '請輸入優惠活動名稱';
$_LANG['js_languages']['min_amount_not_number'] = '金額下限格式不正確（數字）';
$_LANG['js_languages']['max_amount_not_number'] = '金額上限格式不正確（數字）';
$_LANG['js_languages']['act_type_ext_not_number'] = '優惠方式後面的值不正確（數字）';
$_LANG['js_languages']['amount_invalid'] = '金額上限小於金額下限。';
$_LANG['js_languages']['start_lt_end'] = '優惠開始時間不能大於結束時間';

/* post */
$_LANG['pls_set_user_rank'] = '請設置享受優惠的會員等級';
$_LANG['pls_set_act_range'] = '請設置優惠範圍';
$_LANG['amount_error'] = '金額下限不能大於金額上限';
$_LANG['act_name_exists'] = '該優惠活動名稱已存在，請您換一個';

$_LANG['nolimit'] = '沒有限制';
?>