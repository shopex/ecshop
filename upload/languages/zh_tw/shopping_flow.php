<?php

/**
 * ECSHOP 購物流程相關語言
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: shopping_flow.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['flow_login_register']['username_not_null'] = '請您輸入用戶名。';
$_LANG['flow_login_register']['username_invalid'] = '您輸入了一個無效的用戶名。';
$_LANG['flow_login_register']['password_not_null'] = '請您輸入密碼。';
$_LANG['flow_login_register']['email_not_null'] = '請您輸入電子郵件。';
$_LANG['flow_login_register']['email_invalid'] = '您輸入的電子郵件不正確。';
$_LANG['flow_login_register']['password_not_same'] = '您輸入的密碼和確認密碼不一致。';
$_LANG['flow_login_register']['password_lt_six'] = '密碼不能小於6個字符。';

$_LANG['regist_success'] = "恭喜您，%s 賬號註冊成功!";
$_LANG['login_success'] = '恭喜！您已經成功登陸本站！';

/* 購物車 */
$_LANG['update_cart'] = '更新購物車';
$_LANG['back_to_cart'] = '返回購物車';
$_LANG['update_cart_notice'] = '購物車更新成功，請您重新選擇您需要的贈品。';
$_LANG['direct_shopping'] = '不打算登錄，直接購買';
$_LANG['goods_not_exists'] = '對不起，指定的商品不存在';
$_LANG['drop_goods_confirm'] = '您確實要把該商品移出購物車嗎？';
$_LANG['goods_number_not_int'] = '請您輸入正確的商品數量。';
$_LANG['stock_insufficiency'] = '非常抱歉，您選擇的商品 %s 的庫存數量只有 %d，您最多只能購買 %d 件。';
$_LANG['package_stock_insufficiency'] = '非常抱歉，您選擇的超值禮包數量已經超出庫存。請您減少購買量或聯繫商家。';
$_LANG['shopping_flow'] = '購物流程';
$_LANG['username_exists'] = '您輸入的用戶名已存在，請換一個試試。';
$_LANG['email_exists'] = '您輸入的電子郵件已存在，請換一個試試。';
$_LANG['surplus_not_enough'] = '您使用的餘額不能超過您現有的餘額。';
$_LANG['integral_not_enough'] = '您使用的積分不能超過您現有的積分。';
$_LANG['integral_too_much'] = "您使用的積分不能超過%d";
$_LANG['invalid_bonus'] = "您選擇的紅包並不存在。";
$_LANG['no_goods_in_cart'] = '您的購物車中沒有商品！';
$_LANG['not_submit_order'] = '您參與本次團購商品的訂單已提交，請勿重複操作！';
$_LANG['pay_success'] = '本次支付已經成功，我們將盡快為您發貨。';
$_LANG['pay_fail'] = '本次支付失敗，請及時和我們取得聯繫。';
$_LANG['pay_disabled'] = '您選用的支付方式已經被停用。';
$_LANG['pay_invalid'] = '您選用了一個無效的支付方式。該支付方式不存在或者已經被停用。請您立即和我們取得聯繫。';
$_LANG['flow_no_shipping'] = '您必須選定一個配送方式。';
$_LANG['flow_no_payment'] = '您必須選定一個支付方式。';
$_LANG['pay_not_exist'] = '選用的支付方式不存在。';
$_LANG['storage_short'] = '庫存不足';
$_LANG['subtotal'] = '小計';
$_LANG['accessories'] = '配件';
$_LANG['largess'] = '贈品';
$_LANG['shopping_money'] = '購物金額小計 %s';
$_LANG['than_market_price'] = '比市場價 %s 節省了 %s (%s)';
$_LANG['your_discount'] = '根據優惠活動<a href="activity.php"><font color=red>%s</font></a>，您可以享受折扣 %s';
$_LANG['no'] = '無';
$_LANG['not_support_virtual_goods'] = '購物車中存在非實體商品,不支持匿名購買,請登陸後在購買';
$_LANG['not_support_insure'] = '不支持保價';
$_LANG['clear_cart'] = '清空購物車';
$_LANG['drop_to_collect'] = '放入收藏夾';
$_LANG['password_js']['show_div_text'] = '請點擊更新購物車按鈕';
$_LANG['password_js']['show_div_exit'] = '關閉';
$_LANG['goods_fittings'] = '商品相關配件';
$_LANG['parent_name'] = '相關商品：';
$_LANG['remark_package'] = '禮包';

/* 優惠活動 */
$_LANG['favourable_name'] = '活動名稱：';
$_LANG['favourable_period'] = '優惠期限：';
$_LANG['favourable_range'] = '優惠範圍：';
$_LANG['far_ext'][FAR_ALL] = '全部商品';
$_LANG['far_ext'][FAR_BRAND] = '以下品牌';
$_LANG['far_ext'][FAR_CATEGORY] = '以下分類';
$_LANG['far_ext'][FAR_GOODS] = '以下商品';
$_LANG['favourable_amount'] = '金額區間：';
$_LANG['favourable_type'] = '優惠方式：';
$_LANG['fat_ext'][FAT_DISCOUNT] = '享受 %d%% 的折扣';
$_LANG['fat_ext'][FAT_GOODS] = '從下面的贈品（特惠品）中選擇 %d 個（0表示不限制數量）';
$_LANG['fat_ext'][FAT_PRICE] = '直接減少現金 %d';

$_LANG['favourable_not_exist'] = '您要加入購物車的優惠活動不存在';
$_LANG['favourable_not_available'] = '您不能享受該優惠';
$_LANG['favourable_used'] = '該優惠活動已加入購物車了';
$_LANG['pls_select_gift'] = '請選擇贈品（特惠品）';
$_LANG['gift_count_exceed'] = '您選擇的贈品（特惠品）數量超過上限了';
$_LANG['gift_in_cart'] = '您選擇的贈品（特惠品）已經在購物車中了：%s';
$_LANG['label_favourable'] = '优惠活动';
$_LANG['label_collection'] = '我的收藏';
$_LANG['collect_to_flow'] = '立即购买';

/* 登錄註冊 */
$_LANG['forthwith_login'] = '登錄';
$_LANG['forthwith_register'] = '註冊新用戶';
$_LANG['signin_failed'] = '對不起，登錄失敗，請檢查您的用戶名和密碼是否正確';
$_LANG['gift_remainder'] = '說明：在您登錄或註冊後，請到購物車頁面重新選擇贈品。';

/* 收貨人信息 */
$_LANG['flow_js']['consignee_not_null'] = '收貨人姓名不能為空！';
$_LANG['flow_js']['country_not_null'] = '請您選擇收貨人所在國家！';
$_LANG['flow_js']['province_not_null'] = '請您選擇收貨人所在省份！';
$_LANG['flow_js']['city_not_null'] = '請您選擇收貨人所在城市！';
$_LANG['flow_js']['district_not_null'] = '請您選擇收貨人所在區域！';
$_LANG['flow_js']['invalid_email'] = '您輸入的郵件地址不是一個合法的郵件地址。';
$_LANG['flow_js']['address_not_null'] = '收貨人的詳細地址不能為空！';
$_LANG['flow_js']['tele_not_null'] = '電話不能為空！';
$_LANG['flow_js']['shipping_not_null'] = '請您選擇配送方式！';
$_LANG['flow_js']['payment_not_null'] = '請您選擇支付方式！';
$_LANG['flow_js']['goodsattr_style'] = 1;
$_LANG['flow_js']['tele_invaild'] = '電話號碼不有效的號碼';
$_LANG['flow_js']['zip_not_num'] = '郵政編碼只能填寫數字';
$_LANG['flow_js']['mobile_invaild'] = '手機號碼不是合法號碼';

$_LANG['new_consignee_address'] = '新收貨地址';
$_LANG['consignee_address'] = '收貨地址';
$_LANG['consignee_name'] = '收貨人姓名';
$_LANG['country_province'] = '配送區域';
$_LANG['please_select'] = '請選擇';
$_LANG['city_district'] = '城市/地區';
$_LANG['email_address'] = '電子郵件地址';
$_LANG['detailed_address'] = '詳細地址';
$_LANG['postalcode'] = '郵政編碼';
$_LANG['phone'] = '電話';
$_LANG['mobile'] = '手機';
$_LANG['backup_phone'] = '手機';
$_LANG['sign_building'] = '標誌建築';
$_LANG['deliver_goods_time'] = '最佳送貨時間';
$_LANG['default'] = '默認';
$_LANG['default_address'] = '默認地址';
$_LANG['confirm_submit'] = '確認提交';
$_LANG['confirm_edit'] = '確認修改';
$_LANG['country'] = '國家';
$_LANG['province'] = '省份';
$_LANG['city'] = '城市';
$_LANG['area'] = '所在區域';
$_LANG['consignee_add'] = '添加新收貨地址';
$_LANG['shipping_address'] = '配送至這個地址';
$_LANG['address_amount'] = '您的收貨地址最多只能是三個';
$_LANG['not_fount_consignee'] = '對不起，您選定的收貨地址不存在。';

/*------------------------------------------------------ */
//-- 訂單提交
/*------------------------------------------------------ */

$_LANG['goods_amount_not_enough'] = '您購買的商品沒有達到本店的最低限購金額 %s ，不能提交訂單。';
$_LANG['balance_not_enough'] = '您的餘額不足以支付整個訂單，請選擇其他支付方式';
$_LANG['select_shipping'] = '您選定的配送方式為';
$_LANG['select_payment'] = '您選定的支付方式為';
$_LANG['order_amount'] = '您的應付款金額為';
$_LANG['remember_order_number'] = '感謝您在本店購物！您的訂單已提交成功，請記住您的訂單號';
$_LANG['back_home'] = '<a href="index.php">返回首頁</a>';
$_LANG['goto_user_center'] = '<a href="user.php">用戶中心</a>';
$_LANG['order_submit_back'] = '您可以 %s 或去 %s';

$_LANG['order_placed_sms'] = "您有新訂單.收貨人:%s 電話:%s";
$_LANG['sms_paid'] = '已付款';

$_LANG['notice_gb_order_amount'] = '（備註：團購如果有保證金，第一次只需支付保證金和相應的支付費用）';

$_LANG['pay_order'] = '支付訂單 %s';
$_LANG['validate_bonus'] = '驗證紅包';
$_LANG['input_bonus_no'] = '或者輸入紅包序列號';
$_LANG['select_bonus'] = '選擇已有紅包';
$_LANG['bonus_sn_error'] = '該紅包序列號不正確';
$_LANG['bonus_min_amount_error'] = '訂單商品金額沒有達到使用該紅包的最低金額 %s';
$_LANG['bonus_is_ok'] = '該紅包序列號可以使用，可以抵扣 %s';


$_LANG['shopping_myship'] = '我的配送';
$_LANG['shopping_activity'] = '活動列表';
$_LANG['shopping_package'] = '超值禮包列表';
?>
