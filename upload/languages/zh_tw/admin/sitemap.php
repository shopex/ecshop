<?php

/**
 * ECSHOP 站點地圖生成程序語言文件
 * ============================================================================
 * 版權所有 2005-2011 上海商派網絡科技有限公司，並保留所有權利。
 * 網站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 這不是一個自由軟件！您只能在不用於商業目的的前提下對程序代碼進行修改和
 * 使用；不允許對程序代碼以任何形式任何目的的再發佈。
 * ============================================================================
 * $Author: liubo $
 * $Id: sitemap.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['homepage_changefreq'] = '首頁更新頻率';
$_LANG['category_changefreq'] = '分類頁更新頻率';
$_LANG['content_changefreq'] = '內容頁更新頻率';

$_LANG['priority']['always'] = '一直更新';
$_LANG['priority']['hourly'] = '小時';
$_LANG['priority']['daily'] = '天';
$_LANG['priority']['weekly'] = '周';
$_LANG['priority']['monthly'] = '月';
$_LANG['priority']['yearly'] = '年';
$_LANG['priority']['never'] = '從不更新';

$_LANG['generate_success'] = '站點地圖已經生成到相應目錄下。<br />地址為：%s';
$_LANG['generate_failed'] = '生成站點地圖失敗，請檢查 站點根目錄、/data/ 目錄是否允許寫入.';
$_LANG['sitemaps_note'] = 'Sitemaps 服務旨在使用 Feed 文件 sitemap.xml 通知 Google、Yahoo! 以及 Microsoft 等 Crawler(爬蟲)網站上哪些文件需要索引、這些文件的最後修訂時間、更改頻度、文件位置、相對優先索引權，這些信息將幫助他們建立索引範圍和索引的行為習慣。詳細信息請查看 <a href="http://www.sitemaps.org/" target="_blank">sitemaps.org</a> 網站上的說明。';
?>