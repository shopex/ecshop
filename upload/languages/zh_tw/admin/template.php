<?php

/**
 * ECSHOP 管理中心模板管理語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: template.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['template_manage'] = '模板管理';
$_LANG['current_template'] = '當前模板';
$_LANG['available_templates'] = '可用模板';
$_LANG['select_template'] = '請選擇一個模板：';
$_LANG['select_library'] = '請選擇一個庫項目：';
$_LANG['library_name'] = '庫項目';
$_LANG['region_name'] = '區域';
$_LANG['sort_order'] = '序號';
$_LANG['contents'] = '內容';
$_LANG['number'] = '數量';
$_LANG['display'] = '顯示';
$_LANG['select_plz'] = '請選擇...';
$_LANG['button_restore'] = '還原到上一修改';

/* 提示信息 */
$_LANG['library_not_written'] = '庫文件 %s 沒有修改權限，該庫文件將無法修改';
$_LANG['install_template_success'] = '啟用模板成功。';
$_LANG['setup_success'] = '設置模板內容成功。';
$_LANG['modify_dwt_failed'] = '模板文件 %s 無法修改';
$_LANG['update_lib_success'] = '庫項目內容已經更新成功。';
$_LANG['update_lib_failed'] = '編輯庫項目內容失敗。請檢查 %s 目錄是否可以寫入。';
$_LANG['backup_success'] = "所有模板文件已備份到templates/backup目錄下。\n您現在要下載備份文件嗎？。";
$_LANG['backup_failed'] = '備份模板文件失敗，請檢查templates/backup 目錄是否可以寫入。';
$_LANG['not_editable'] = '非可編輯區庫文件無選擇項';

/* 每一個模板文件對應的語言 */
$_LANG['template_files']['article'] = '文章內容模板';
$_LANG['template_files']['article_cat'] = '文章分類模板';
$_LANG['template_files']['brand'] = '品牌專區';
//$_LANG['template_files']['catalog'] = '所有分類頁';
$_LANG['template_files']['category'] = '商品分類頁模板';
$_LANG['template_files']['flow'] = '購物流程模板';
$_LANG['template_files']['goods'] = '商品詳情模板';
$_LANG['template_files']['group_buy_goods'] = '團購商品詳情模板';
$_LANG['template_files']['group_buy_list'] = '團購商品列表模板';
$_LANG['template_files']['index'] = '首頁模板';
$_LANG['template_files']['search'] = '商品搜索模板';
$_LANG['template_files']['compare'] = '商品比較模板';
$_LANG['template_files']['snatch'] = '奪寶奇兵';
$_LANG['template_files']['tag_cloud'] = '標籤雲模板';
$_LANG['template_files']['brand'] = '商品品牌頁';
$_LANG['template_files']['auction_list'] = '拍賣活動列表';
$_LANG['template_files']['auction'] = '拍賣活動詳情';
$_LANG['template_files']['message_board'] = '留言板';
//$_LANG['template_files']['quotation'] = '報價單';
$_LANG['template_files']['exchange_list'] = '積分商城列表';

/* 每一個庫項目的描述 */
$_LANG['template_libs']['ad_position'] = '廣告位';
$_LANG['template_libs']['index_ad'] = '首頁主廣告位';
$_LANG['template_libs']['cat_articles'] = '文章列表';
$_LANG['template_libs']['articles'] = '文章列表';
$_LANG['template_libs']['goods_attrlinked'] = '屬性關聯的商品';
$_LANG['template_libs']['recommend_best'] = '精品推薦';
$_LANG['template_libs']['recommend_promotion'] = '促銷商品';
$_LANG['template_libs']['recommend_hot'] = '熱賣商品';
$_LANG['template_libs']['recommend_new'] = '新品上架';
$_LANG['template_libs']['bought_goods'] = '購買過此商品的人還買過的商品';
$_LANG['template_libs']['bought_note_guide'] = '購買記錄';
$_LANG['template_libs']['brand_goods'] = '品牌的商品';
$_LANG['template_libs']['brands'] = '品牌專區';
$_LANG['template_libs']['cart'] = '購物車';
$_LANG['template_libs']['cat_goods'] = '分類下的商品';
$_LANG['template_libs']['category_tree'] = '商品分類樹';
$_LANG['template_libs']['comments'] = '用戶評論列表';
$_LANG['template_libs']['consignee'] = '收貨地址表單';
$_LANG['template_libs']['goods_fittings'] = '相關配件';
$_LANG['template_libs']['page_footer'] = '頁腳';
$_LANG['template_libs']['goods_gallery'] = '商品相冊';
$_LANG['template_libs']['goods_article'] = '相關文章';
$_LANG['template_libs']['goods_list'] = '商品列表';
$_LANG['template_libs']['goods_tags'] = '商品標記';
$_LANG['template_libs']['group_buy'] = '團購商品';
$_LANG['template_libs']['group_buy_fee'] = '團購商品費用總計';
$_LANG['template_libs']['help'] = '幫助內容';
$_LANG['template_libs']['history'] = '商品瀏覽歷史';
$_LANG['template_libs']['comments_list'] = '評論內容';
$_LANG['template_libs']['invoice_query'] = '發貨單查詢';
$_LANG['template_libs']['member'] = '會員區';
$_LANG['template_libs']['member_info'] = '會員信息';
$_LANG['template_libs']['new_articles'] = '最新文章';
$_LANG['template_libs']['order_total'] = '訂單費用總計';
$_LANG['template_libs']['page_header'] = '頁面頂部';
$_LANG['template_libs']['pages'] = '列表分頁';
$_LANG['template_libs']['goods_related'] = '相關商品';
$_LANG['template_libs']['search_form'] = '搜索表單';
$_LANG['template_libs']['signin'] = '登錄表單';
$_LANG['template_libs']['snatch'] = '奪寶奇兵出價';
$_LANG['template_libs']['snatch_price'] = '奪寶奇兵最新出價';
$_LANG['template_libs']['top10'] = '銷售排行';
$_LANG['template_libs']['ur_here'] = '當前位置';
$_LANG['template_libs']['user_menu'] = '用戶中心菜單';
$_LANG['template_libs']['vote'] = '調查';
$_LANG['template_libs']['auction'] = '拍賣商品';
$_LANG['template_libs']['article_category_tree'] = '文章分類樹';
$_LANG['template_libs']['order_query'] = '前台訂單狀態查詢';
$_LANG['template_libs']['email_list'] = '前台郵件訂閱';
$_LANG['template_libs']['vote_list'] = '在線調查';
$_LANG['template_libs']['price_grade'] = '價格範圍';
$_LANG['template_libs']['filter_attr'] = '屬性篩選';
$_LANG['template_libs']['promotion_info'] = '促銷信息';
$_LANG['template_libs']['categorys'] = '商品分類';
$_LANG['template_libs']['myship'] = '配送方式';
$_LANG['template_libs']['online'] = '統計在線人數';
$_LANG['template_libs']['relatetag'] = '其他應用關聯標籤數據';
$_LANG['template_libs']['message_list'] = '留言列表';
$_LANG['template_libs']['exchange_hot'] = '積分商城熱賣商品';
$_LANG['template_libs']['exchange_list'] = '積分商城列表商品';

/* 模板佈局備份 */
$_LANG['backup_setting'] = '備份模板設置';
$_LANG['cur_setting_template'] = '當前可備份的模板設置';
$_LANG['no_setting_template'] = '沒有可備份的模板設置';
$_LANG['cur_backup'] = '可使用的模板設置備份';
$_LANG['no_backup'] = '沒有模板設置備份';
$_LANG['remarks'] = '備份註釋';
$_LANG['backup_setting'] = '備份模板設置';
$_LANG['select_all'] = '全選';
$_LANG['remarks_exist'] = '備份註釋 %s 已經用過，請換個註釋名稱';
$_LANG['backup_template_ok'] = '備份設置成功';
$_LANG['del_backup_ok'] = '備份刪除成功';
$_LANG['restore_backup_ok'] = '恢復備份成功';

/* JS 語言項 */
$_LANG['js_languages']['setupConfirm'] = '啟用新的模板將覆蓋原來的模板。\n您確定要啟用選定的模板嗎？';
$_LANG['js_languages']['reinstall'] = '重新安裝當前模板';
$_LANG['backup'] = '備份當前模板';
$_LANG['js_languages']['selectPlease'] = '請選擇...';
$_LANG['js_languages']['removeConfirm'] = '您確定要刪除選定的內容嗎？';
$_LANG['js_languages']['empty_content'] = '對不起，庫項目的內容不能為空。';
$_LANG['js_languages']['save_confirm'] = '您已經修改了模板內容，您確定不保存麼？';

?>