<?php

/**
 * ECSHOP 站点地图生成程序语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: sitemap.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['homepage_changefreq'] = '首页更新频率';
$_LANG['category_changefreq'] = '分类页更新频率';
$_LANG['content_changefreq'] = '内容页更新频率';

$_LANG['priority']['always'] = '一直更新';
$_LANG['priority']['hourly'] = '小时';
$_LANG['priority']['daily'] = '天';
$_LANG['priority']['weekly'] = '周';
$_LANG['priority']['monthly'] = '月';
$_LANG['priority']['yearly'] = '年';
$_LANG['priority']['never'] = '从不更新';

$_LANG['generate_success'] = '站点地图已经生成到相应目录下。<br />地址为：%s';
$_LANG['generate_failed'] = '生成站点地图失败，请检查 站点根目录、/data/ 目录是否允许写入.';
$_LANG['sitemaps_note'] = 'Sitemaps 服务旨在使用 Feed 文件 sitemap.xml 通知 Google、Yahoo! 以及 Microsoft 等 Crawler(爬虫)网站上哪些文件需要索引、这些文件的最后修订时间、更改频度、文件位置、相对优先索引权，这些信息将帮助他们建立索引范围和索引的行为习惯。详细信息请查看 <a href="http://www.sitemaps.org/" target="_blank">sitemaps.org</a> 网站上的说明。';
?>