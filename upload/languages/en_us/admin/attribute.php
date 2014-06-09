<?php
/**
 * ECSHOP
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
 * $Id: attribute.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* List */
$_LANG['by_goods_type'] = 'Display by product type:';
$_LANG['all_goods_type'] = 'All products type';

$_LANG['attr_id'] = 'ID';
$_LANG['cat_id'] = 'Product type';
$_LANG['attr_name'] = 'Attribute name';
$_LANG['attr_input_type'] = 'Enter mode';
$_LANG['attr_values'] = 'Choice value list';
$_LANG['attr_type'] = 'Whether need select the value of attribute for shopping?';

$_LANG['value_attr_input_type'][ATTR_TEXT] = 'Manual enter';
$_LANG['value_attr_input_type'][ATTR_OPTIONAL] = 'Select from list';
$_LANG['value_attr_input_type'][ATTR_TEXTAREA] = 'Multirow textbox';

$_LANG['drop_confirm'] = 'Are you sure delete the attribute?';

/* Add/Edit */
$_LANG['label_attr_name'] = 'Attribute name:';
$_LANG['label_cat_id'] = 'Category:';
$_LANG['label_attr_index'] = 'Index:';
$_LANG['label_is_linked'] = 'Relational products with same attribute?';
$_LANG['no_index'] = 'Needn\'t index';
$_LANG['keywords_index'] = 'Keywords index';
$_LANG['range_index'] = 'Range index';
$_LANG['note_attr_index'] = 'Please select have no use for search if have no use for the attribute become a situation of search product condition. Please select keywords search if have use for the attribute carry through keywords search. Please select range search if the attribute search need to appoint a certain range.';
$_LANG['label_attr_input_type'] = 'Attribute value enter mode:';
$_LANG['text'] = 'Manual enter';
$_LANG['select'] = 'Select from below (one line stand for one value)';
$_LANG['text_area'] = 'Multirow textbox';
$_LANG['label_attr_values'] = 'Choice value list:';
$_LANG['label_attr_group'] = 'Property division:';
$_LANG['label_attr_type'] = 'Property is optional';
$_LANG['note_attr_type'] = 'Select "Yes" when the merchandise can set up a number of property value, while property values specified for different different price increases, users need to purchase merchandise at selected specific property value. Choose "No" when the property value of the merchandise can only set a value, the user can only view the value.';
$_LANG['attr_type_values'][0] = 'The only property';
$_LANG['attr_type_values'][1] = 'Radio property';
$_LANG['attr_type_values'][2] = 'Check property';


$_LANG['add_next'] = 'Add next attribute';
$_LANG['back_list'] = 'Return to attribute list';

$_LANG['add_ok'] = 'Add attribute [%s] successfully.';
$_LANG['edit_ok'] = 'Edit attribute [%s] successfully.';

/* Prompting message */
$_LANG['name_exist'] = 'The attribute name already exists, please change another one.';
$_LANG['drop_confirm'] = 'Are you sure delete the attribute?';
$_LANG['notice_drop_confirm'] = 'Already has %s the use of the property of merchandise, you sure you want to delete the right property?';
$_LANG['name_not_null'] = 'Attribute name can\'t be blank.';

$_LANG['no_select_arrt'] = 'You have no choice need to remove the attribute name.';
$_LANG['drop_ok'] = 'Delete %d products attribute successfully.';

$_LANG['js_languages']['name_not_null'] = 'Please enter attribute name.';
$_LANG['js_languages']['values_not_null'] = 'Please enter the attribute\'s value.';
$_LANG['js_languages']['cat_id_not_null'] = 'Please select the attribute of product type.';

?>