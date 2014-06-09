<?php

/**
 * ECSHOP 管理中心共用語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: user_rank.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['rank_name'] = '會員等級名稱';
$_LANG['integral_min'] = '積分下限';
$_LANG['integral_max'] = '積分上限';
$_LANG['discount'] = '初始折扣率';
$_LANG['add_user_rank'] = '添加會員等級';
$_LANG['special_rank'] = '特殊會員組';
$_LANG['show_price'] = '在商品詳情頁顯示該會員等級的商品價格';
$_LANG['notice_special'] = '特殊會員組的會員不會隨著積分的變化而變化。';
$_LANG['add_continue'] = '繼續添加會員等級';
$_LANG['back_list'] = '返回會員等級列表';
$_LANG['show_price_short'] = '顯示價格';
$_LANG['notice_discount'] = '請填寫為0-100的整數,如填入80，表示初始折扣率為8折';

$_LANG['rank_name_exists'] = '會員等級名 %s 已經存在。';
$_LANG['add_rank_success'] = '會員等級已經添加成功。';
$_LANG['integral_min_exists'] = '已經存在一個等級積分下限為 %d 的會員等級';
$_LANG['integral_max_exists'] = '已經存在一個等級積分上限為 %d 的會員等級';

/* JS 語言 */
$_LANG['js_languages']['remove_confirm'] = '您確定要刪除選定的會員等級嗎？';
$_LANG['js_languages']['rank_name_empty'] = '您沒有輸入會員等級名稱。';
$_LANG['js_languages']['integral_min_invalid'] = '您沒有輸入積分下限或者積分下限不是一個整數。';
$_LANG['js_languages']['integral_max_invalid'] = '您沒有輸入積分上限或者積分上限不是一個整數。';
$_LANG['js_languages']['discount_invalid'] = '您沒有輸入折扣率或者折扣率無效。';
$_LANG['js_languages']['integral_max_small'] = '積分上限必須大於積分下限。';
$_LANG['js_languages']['lang_remove'] = '移除';
?>