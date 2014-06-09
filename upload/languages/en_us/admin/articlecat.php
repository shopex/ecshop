<?php

/**
 * ECSHOP Article\'s Category management program language file
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
 * $Id: articlecat.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['cat_name'] = 'Name';
$_LANG['type'] = 'Category Type';
$_LANG['type_name'][COMMON_CAT] = 'General classification';
$_LANG['type_name'][SYSTEM_CAT] = 'Taxonomy';
$_LANG['type_name'][INFO_CAT]   = 'Shop information';
$_LANG['type_name'][UPHELP_CAT] = 'Help Category';
$_LANG['type_name'][HELP_CAT]   = 'Shop help';

$_LANG['cat_keywords'] = 'Keywords';
$_LANG['cat_desc'] = 'Description';
$_LANG['parent_cat'] = 'Superior Categories';
$_LANG['cat_top'] = 'Top Categories';
$_LANG['not_allow_add'] = 'Classification does not allow you to add the selected sub-classification';
$_LANG['not_allow_remove'] = 'System to retain the classification does not allow delete';
$_LANG['is_fullcat'] = 'There are sub-classified under the classification, first delete its sub-classification';
$_LANG['show_in_nav'] = 'Display in navigation';

$_LANG['isopen'] = 'Yes';
$_LANG['isclose'] = 'No';
$_LANG['add_article'] = 'Add new article';

$_LANG['articlecat_edit'] = 'Edit article category';


/* Prompting message */
$_LANG['catname_exist'] = '%s already exists.';
$_LANG['parent_id_err'] = 'Category name %s parent classification should not set itself or its own sub-classification';
$_LANG['back_list'] = 'Return to category list';
$_LANG['continue_add'] = 'Continue add new category';
$_LANG['catadd_succed'] = 'Add successfully!';
$_LANG['catedit_succed'] = 'Edit category %s successfully!';
$_LANG['back_list'] = 'Return to category list';
$_LANG['continue_add'] = 'Continue add new category';
$_LANG['no_catname'] = 'Please enter a category name.';
$_LANG['edit_fail'] = 'Edit failed.';
$_LANG['enter_int'] = 'Please enter an integer';
$_LANG['not_emptycat'] = 'Wrong, there are articles in the category.';

/* Help */
$_LANG['notice_keywords'] ='The keywords is optional, for search conveniently.';
$_LANG['notice_isopen'] ='Whether display the category in navigation.';

/* JS language item */
$_LANG['js_languages']['no_catname'] = 'Please enter article category name.';
$_LANG['js_languages']['sys_hold'] = 'Retain the classification system is not permitted to add sub-categories';
$_LANG['js_languages']['remove_confirm'] = 'Are you sure delete the selected category?';

?>