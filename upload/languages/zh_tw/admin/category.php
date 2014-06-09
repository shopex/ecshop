<?php

/**
 * ECSHOP 商品分類管理語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: category.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* 商品分類字段信息 */
$_LANG['cat_id'] = '編號';
$_LANG['cat_name'] = '分類名稱';
$_LANG['isleaf'] = '不允許';
$_LANG['noleaf'] = '允許';
$_LANG['keywords'] = '關鍵字';
$_LANG['cat_desc'] = '分類描述';
$_LANG['parent_id'] = '上級分類';
$_LANG['sort_order'] = '排序';
$_LANG['measure_unit'] = '數量單位';
$_LANG['delete_info'] = '刪除選中';
$_LANG['category_edit'] = '編輯商品分類';
$_LANG['move_goods'] = '轉移商品';
$_LANG['cat_top'] = '頂級分類';
$_LANG['show_in_nav'] = '是否顯示在導航欄';
$_LANG['cat_style'] = '分類的樣式表文件';
$_LANG['is_show'] = '是否顯示';
$_LANG['show_in_index'] = '設置為首頁推薦';
$_LANG['notice_show_in_index'] = '該設置可以在首頁的最新、熱門、推薦處顯示該分類下的推薦商品';
$_LANG['goods_number'] = '商品數量';
$_LANG['grade'] = '價格區間個數';
$_LANG['notice_grade'] = '該選項表示該分類下商品最低價與最高價之間的劃分的等級個數，填0表示不做分級，最多不能超過10個。';
$_LANG['short_grade'] = '價格分級';

$_LANG['nav'] = '導航欄';
$_LANG['index_new'] = '最新';
$_LANG['index_best'] = '精品';
$_LANG['index_hot'] = '熱門';

$_LANG['back_list'] = '返回分類列表';
$_LANG['continue_add'] = '繼續添加分類';

$_LANG['notice_style'] = '您可以為每一個商品分類指定一個樣式表文件。例如文件存放在 themes 目錄下則輸入：themes/style.css';

/* 操作提示信息 */
$_LANG['catname_empty'] = '分類名稱不能為空!';
$_LANG['catname_exist'] = '已存在相同的分類名稱!';
$_LANG["parent_isleaf"] = '所選分類不能是末級分類!';
$_LANG["cat_isleaf"] = '不是末級分類或者此分類下還存在有商品,您不能刪除!';
$_LANG["cat_noleaf"] = '底下還有其它子分類,不能修改為末級分類!';
$_LANG["is_leaf_error"] = '所選擇的上級分類不能是當前分類或者當前分類的下級分類!';
$_LANG['grade_error'] = '價格分級數量只能是0-10之內的整數';

$_LANG['catadd_succed'] = '新商品分類添加成功!';
$_LANG['catedit_succed'] = '商品分類編輯成功!';
$_LANG['catdrop_succed'] = '商品分類刪除成功!';
$_LANG['catremove_succed'] = '商品分類轉移成功!';
$_LANG['move_cat_success'] = '轉移商品分類已成功完成!';

$_LANG['cat_move_desc'] = '什麼是轉移商品分類?';
$_LANG['select_source_cat'] = '選擇要轉移的分類';
$_LANG['select_target_cat'] = '選擇目標分類';
$_LANG['source_cat'] = '從此分類';
$_LANG['target_cat'] = '轉移到';
$_LANG['start_move_cat'] = '開始轉移';
$_LANG['cat_move_notic'] = '在添加商品或者在商品管理中,如果需要對商品的分類進行變更,那麼你可以通過此功能,正確管理你的商品分類。';

$_LANG['cat_move_empty'] = '你沒有正確選擇商品分類!';

$_LANG['sel_goods_type'] = '請選擇商品類型';
$_LANG['sel_filter_attr'] = '請選擇篩選屬性';
$_LANG['filter_attr'] = '篩選屬性';
$_LANG['filter_attr_notic'] = '篩選屬性可在前分類頁面篩選商品';

/*JS 語言項*/
$_LANG['js_languages']['catname_empty'] = '分類名稱不能為空!';
$_LANG['js_languages']['unit_empyt'] = '數量單位不能為空!';
$_LANG['js_languages']['is_leafcat'] = '您選定的分類是一個末級分類。\r\n新分類的上級分類不能是一個末級分類';
$_LANG['js_languages']['not_leafcat'] = '您選定的分類不是一個末級分類。\r\n商品的分類轉移只能在末級分類之間才可以操作。';
$_LANG['js_languages']['filter_attr_not_repeated'] = '篩選屬性不可重複';
$_LANG['js_languages']['filter_attr_not_selected'] = '請選擇篩選屬性';

?>