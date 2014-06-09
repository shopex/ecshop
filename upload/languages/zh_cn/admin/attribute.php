<?php
/**
 * ECSHOP 商品类型管理语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: attribute.php 17217 2011-01-19 06:29:08Z liubo $
*/

/* 列表 */
$_LANG['by_goods_type'] = '按商品类型显示：';
$_LANG['all_goods_type'] = '所有商品类型';

$_LANG['attr_id'] = '编号';
$_LANG['cat_id'] = '商品类型';
$_LANG['attr_name'] = '属性名称';
$_LANG['attr_input_type'] = '属性值的录入方式';
$_LANG['attr_values'] = '可选值列表';
$_LANG['attr_type'] = '购买商品时是否需要选择该属性的值';

$_LANG['value_attr_input_type'][ATTR_TEXT] = '手工录入';
$_LANG['value_attr_input_type'][ATTR_OPTIONAL] = '从列表中选择';
$_LANG['value_attr_input_type'][ATTR_TEXTAREA] = '多行文本框';

$_LANG['drop_confirm'] = '您确实要删除该属性吗？';

/* 添加/编辑 */
$_LANG['label_attr_name'] = '属性名称：';
$_LANG['label_cat_id'] = '所属商品类型：';
$_LANG['label_attr_index'] = '能否进行检索：';
$_LANG['label_is_linked'] = '相同属性值的商品是否关联？';
$_LANG['no_index'] = '不需要检索';
$_LANG['keywords_index'] = '关键字检索';
$_LANG['range_index'] = '范围检索';
$_LANG['note_attr_index'] = '不需要该属性成为检索商品条件的情况请选择不需要检索，需要该属性进行关键字检索商品时选择关键字检索，如果该属性检索时希望是指定某个范围时，选择范围检索。';
$_LANG['label_attr_input_type'] = '该属性值的录入方式：';
$_LANG['text'] = '手工录入';
$_LANG['select'] = '从下面的列表中选择（一行代表一个可选值）';
$_LANG['text_area'] = '多行文本框';
$_LANG['label_attr_values'] = '可选值列表：';
$_LANG['label_attr_group'] = '属性分组：';
$_LANG['label_attr_type'] = '属性是否可选';
$_LANG['note_attr_type'] = '选择"单选/复选属性"时，可以对商品该属性设置多个值，同时还能对不同属性值指定不同的价格加价，用户购买商品时需要选定具体的属性值。选择"唯一属性"时，商品的该属性值只能设置一个值，用户只能查看该值。';
$_LANG['attr_type_values'][0] = '唯一属性';
$_LANG['attr_type_values'][1] = '单选属性';
$_LANG['attr_type_values'][2] = '复选属性';


$_LANG['add_next'] = '添加下一个属性';
$_LANG['back_list'] = '返回属性列表';

$_LANG['add_ok'] = '添加属性 [%s] 成功。';
$_LANG['edit_ok'] = '编辑属性 [%s] 成功。';

/* 提示信息 */
$_LANG['name_exist'] = '该属性名称已存在，请您换一个名称。';
$_LANG['drop_confirm'] = '您确实要删除该属性吗？';
$_LANG['notice_drop_confirm'] = '已经有%s个商品使用该属性，您确实要删除该属性吗？';
$_LANG['name_not_null'] = '属性名称不能为空。';

$_LANG['no_select_arrt'] = '您没有选择需要删除的属性名';
$_LANG['drop_ok'] = '成功删除了 %d 条商品属性';

$_LANG['js_languages']['name_not_null'] = '请您输入属性名称。';
$_LANG['js_languages']['values_not_null'] = '请您输入该属性的可选值。';
$_LANG['js_languages']['cat_id_not_null'] = '请您选择该属性所属的商品类型。';

?>