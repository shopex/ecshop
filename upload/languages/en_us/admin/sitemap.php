<?php

/**
 * ECSHOP Creat site map language file
 * ============================================================================
 * All right reserved (C) 2005-2011 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software；it means that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: liubo $
 * $Id: sitemap.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['homepage_changefreq'] = 'Home page update frequency';
$_LANG['category_changefreq'] = 'Category page update frequency';
$_LANG['content_changefreq'] = 'Content page update frequency';

$_LANG['priority']['always'] = 'Always';
$_LANG['priority']['hourly'] = 'Hourly';
$_LANG['priority']['daily'] = 'Daily';
$_LANG['priority']['weekly'] = 'Weekly';
$_LANG['priority']['monthly'] = 'Monthly';
$_LANG['priority']['yearly'] = 'Yearly';
$_LANG['priority']['never'] = 'Never';

$_LANG['generate_success'] = 'Creat sitemap to data directory. <br />Address : %s';
$_LANG['generate_failed'] = 'Creat sitemap faied, please check /data/ directory whether can be wrote.';
$_LANG['sitemaps_note'] = 'Sitemaps service for the Feed file sitemap.xml notice Google,Yahoo! and Microsoft and so on, and the Crawler website which files need to index, the last edit time, update frequency, file position , relative index priority, and this information will help them to establish range and habit of behavior. Details: <a href="http://www.sitemaps.org/" target="_blank">sitemaps.org</a>.';
?>