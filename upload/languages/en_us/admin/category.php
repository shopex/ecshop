<?php

/**
 * ECSHOP Commodity category management language file
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
 * $Id: category.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* Commodity category field information */
$_LANG['cat_id'] = 'ID';
$_LANG['cat_name'] = 'Name';
$_LANG['isleaf'] = 'Disallow';
$_LANG['noleaf'] = 'Allow';
$_LANG['keywords'] = 'Keywords';
$_LANG['cat_desc'] = 'Description';
$_LANG['parent_id'] = 'Parent';
$_LANG['sort_order'] = 'Sort';
$_LANG['measure_unit'] = 'Quantity unit';
$_LANG['delete_info'] = 'Delete checked';
$_LANG['category_edit'] = 'Edit category';
$_LANG['move_goods'] = 'Move product';
$_LANG['cat_top'] = 'Root';
$_LANG['show_in_nav'] = 'Display in navigation';
$_LANG['cat_style'] = 'Style sheet document classification';
$_LANG['is_show'] = 'Does it show that the';
$_LANG['show_in_index'] = 'Is set to recommend home';
$_LANG['notice_show_in_index'] = 'This setting can be the latest in the home, hot, Department recommend that the classification of merchandise under Recommend';
$_LANG['goods_number'] = 'Quantity of goods';
$_LANG['grade'] = 'Price range of the number of';
$_LANG['notice_grade'] = 'This option indicates that the classification of merchandise under the lowest and the highest price level of the division between the number of express no grading fill 0';
$_LANG['short_grade'] = 'Price classification';

$_LANG['nav'] = 'Navigation';
$_LANG['index_new'] = 'Latest';
$_LANG['index_best'] = 'Boutique';
$_LANG['index_hot'] = 'Top';

$_LANG['back_list'] = 'Return to category list.';
$_LANG['continue_add'] = 'Continue add category.';

$_LANG['notice_style'] = 'You can for each classification of merchandise to specify a style sheet document. For example, documents stored in the themes directory then enter:themes/style.css';

/* Prompting message */
$_LANG['catname_empty'] = 'Please enter a category name!';
$_LANG['catname_exist'] = 'The category name already exists.';
$_LANG["parent_isleaf"] = 'The category can\'t be the bottom class category!';
$_LANG["cat_isleaf"] = 'The category can\'t be deleted, because it isn\'t the bottom class category or some product already exists.';
$_LANG["cat_noleaf"] ='There are still subcategories, so you can\'t modify category for the bottom class!';
$_LANG["is_leaf_error"] ='The selected higher category can\'t be lower category of current category!';
$_LANG['grade_error'] = 'Quantity price classification can only be an integer within 0-10';

$_LANG['catadd_succed'] = 'Add new category successfully!';
$_LANG['catedit_succed'] = 'Edit category successfully!';
$_LANG['catdrop_succed'] = 'Delete category successfully!';
$_LANG['catremove_succed'] = 'Move category successfully!';
$_LANG['move_cat_success'] = 'Move category has finished!';

$_LANG['cat_move_desc'] = 'What is move category?';
$_LANG['select_source_cat'] = 'Please select category that you want to move.';
$_LANG['select_target_cat'] = 'Please select category that the target.';
$_LANG['source_cat'] = 'From that category';
$_LANG['target_cat'] = 'Move to';
$_LANG['start_move_cat'] = 'Submit';
$_LANG['cat_move_notic'] = 'In add product or pruduct management, if you want to change products category, you can manage the products category by the function.';

$_LANG['cat_move_empty'] = 'Please select category rightly!';

$_LANG['sel_goods_type'] = 'Please choose the type of merchandise';
$_LANG['sel_filter_attr'] = 'Please select filter property';
$_LANG['filter_attr'] = 'Filter property';
$_LANG['filter_attr_notic'] = 'Filter property page to the previous classification of merchandise selection';
$_LANG['filter_attr_not_repeated'] = 'Filter property can`t be repeated';

/*JS language item*/
$_LANG['js_languages']['catname_empty'] = 'Category name can\'t be blank!';
$_LANG['js_languages']['unit_empyt'] = 'Unit of quantity can\'t be blank!';
$_LANG['js_languages']['is_leafcat'] ="You selected category is a bottom class category. \\nThe higher category of new category can\'t be a bottom class category.";
$_LANG['js_languages']['not_leafcat'] =" You selected category isn\'t a bottom class category. \\nThe category of product transfer can just be operated between the bottom class categories.";
$_LANG['js_languages']['filter_attr_not_repeated'] = 'Filter property can`t be repeated';
$_LANG['js_languages']['filter_attr_not_selected'] = 'Please select a filter property';

?>