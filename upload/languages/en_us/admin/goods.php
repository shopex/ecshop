<?php

/**
 * ECSHOP Manage a center start page language file
 * ============================================================================
 * All right reserved (C) 2005-2011 Beijing Yi Shang Interactive Technology
 * Development Ltd.
 * Web site: http://www.ecshop.com
 * ----------------------------------------------------------------------------
 * This is a free/open source software；it mean that you can modify, use and
 * republish the program code, on the premise of that your behavior is not for
 * commercial purposes.
 * ============================================================================
 * $Author: liubo $
 * $Id: goods.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['edit_goods'] ='Edit';
$_LANG['copy_goods'] ='Copy';
$_LANG['continue_add_goods'] ='Continue add new product';
$_LANG['back_goods_list'] ='Return product list';
$_LANG['add_goods_ok'] ='Add successfully';
$_LANG['edit_goods_ok'] ='Edit successfully';
$_LANG['trash_goods_ok'] ='Move to recycle bin successfully.';
$_LANG['restore_goods_ok'] ='Restore successfully.';
$_LANG['drop_goods_ok'] ='Delete successfully.';
$_LANG['batch_handle_ok']       = 'Batch operation successfully.';
$_LANG['drop_goods_confirm']    = 'Are you sure delete the product?';
$_LANG['batch_drop_confirm']    = 'All related products will be deleted if you thorough delete the pruduct!';
$_LANG['trash_goods_confirm']   = 'Are you sure move the product to recycle bin?';
$_LANG['batch_trash_confirm']   = 'Are you sure move the checked product to recycle bin?';
$_LANG['trash_product_confirm'] = 'Are you sure you take the goods removed?';
$_LANG['restore_goods_confirm'] = 'Are you sure restore the product?';
$_LANG['batch_restore_confirm'] = 'Are you sure restore the checked product?';
$_LANG['batch_on_sale_confirm'] = 'Are you sure set the checked product as on sale?';
$_LANG['batch_not_on_sale_confirm'] = 'Are you sure cancel the checked on sale product?';
$_LANG['batch_best_confirm']    = 'Are you sure set the checked product as best?';
$_LANG['batch_not_best_confirm']    = 'Are you sure cancel the checked best product?';
$_LANG['batch_new_confirm']     = 'Are you sure set the checked product as new?';
$_LANG['batch_not_new_confirm'] = 'Are you sure cancel the checked new product?';
$_LANG['batch_hot_confirm']     = 'Are you sure set the checked product as hot?';;
$_LANG['batch_not_hot_confirm']='Are you surecancel the checked hot product?';
$_LANG['cannot_found_goods'] = 'Don\'t find appointed product.';
$_LANG['sel_goods_type'] = 'Please choose the type of merchandise';
$_LANG['sel_goods_suppliers'] = 'Please select the suppliers';

/*------------------------------------------------------ */
//-- The picture processing is related to hint an information
/*------------------------------------------------------ */
$_LANG['no_gd'] ='Your server nonsupport GD or didn\'t install to operate the picture type to expand a database perhaps.';
$_LANG['img_not_exists'] ='Don\'t find out an original picture, create thumbnail failure.';
$_LANG['img_invalid'] ='Create thumbnail failure, because you upload an invalid picture file.';
$_LANG['create_dir_failed'] ='The images file clip and can\'t write, create thumbnail failure.';
$_LANG['safe_mode_warning'] ='Your server circulate under the safe mode, and %s directory nonentity. Your needing probably to establish a directory in advance then can upload a picture.';
$_LANG['not_writable_warning']='The %s directory can\'t be wrote, you need to config the directory as writable then can upload a picture.';

/*------------------------------------------------------ */
//-- Product list
/*------------------------------------------------------ */
$_LANG['goods_cat'] ='All Categories';
$_LANG['goods_brand'] ='All Brands';
$_LANG['intro_type'] =' All';
$_LANG['keyword'] ='Keywords';
$_LANG['is_best'] ='Best';
$_LANG['is_new'] ='New';
$_LANG['is_hot'] ='Hot';
$_LANG['is_promote'] ='Sales promotion';
$_LANG['all_type'] = 'All recommend';
$_LANG['sort_order'] = 'Recommend to sort';

$_LANG['goods_name'] ='Name';
$_LANG['goods_sn'] ='NO.';
$_LANG['shop_price'] ='Price';
$_LANG['is_on_sale'] ='On sale';
$_LANG['goods_number'] ='Stock';

$_LANG['copy'] ='Copy';
$_LANG['item_list'] = 'Item List';

$_LANG['integral'] ='Points limit';
$_LANG['on_sale'] ='On sale';
$_LANG['not_on_sale'] ='Not on sale';
$_LANG['best'] ='Best product';
$_LANG['not_best'] ='Cancel best product';
$_LANG['new'] ='New product';
$_LANG['not_new'] ='Cancel new product';
$_LANG['hot'] ='Hot product';
$_LANG['not_hot'] ='Cancel hot product';
$_LANG['move_to'] ='Move to category';

// ajax
$_LANG['goods_name_null'] ='Please enter product name.';
$_LANG['goods_sn_null'] ='Please enter product NO..';
$_LANG['shop_price_not_number']='Price must be a figure.';
$_LANG['shop_price_invalid'] = 'You have entered an illegal market price.';
$_LANG['goods_sn_exists'] ='The product NO. already exist, please change a number.';

/*------------------------------------------------------ */
//-- Add /edit a product information
/*------------------------------------------------------ */
$_LANG['tab_general'] ='Brief';
$_LANG['tab_detail'] ='Details';
$_LANG['tab_mix'] ='Others';
$_LANG['tab_properties'] ='Attribute';
$_LANG['tab_gallery'] ='Gallery';
$_LANG['tab_linkgoods'] ='Relational products';
$_LANG['tab_groupgoods'] ='Accessories';
$_LANG['tab_article'] ='Relational articles';

$_LANG['lab_goods_name'] ='Name:';
$_LANG['lab_goods_sn'] ='NO.:';
$_LANG['lab_goods_cat'] ='Category:';
$_LANG['lab_other_cat'] ='Extend category:';
$_LANG['lab_goods_brand'] ='Brand:';
$_LANG['lab_shop_price'] ='Shop price:';
$_LANG['lab_market_price'] ='Market price:';
$_LANG['lab_user_price'] ='Member price:';
$_LANG['lab_promote_price'] ='Promotion price:';
$_LANG['lab_promote_date'] ='Promotion date:';
$_LANG['lab_picture'] ='Upload picture:';
$_LANG['lab_thumb'] ='Upload thumbnail:';
$_LANG['auto_thumb'] ='Create thumbnail automatically';
$_LANG['lab_keywords'] ='Keywords:';
$_LANG['lab_goods_brief'] ='Brief:';
$_LANG['lab_seller_note'] ='Shop notice:';
$_LANG['lab_goods_type'] = 'Goods type：';
$_LANG['lab_picture_url'] = 'Merchandise picture external URL';
$_LANG['lab_thumb_url'] = 'External merchandise Thumbnail URL';

$_LANG['lab_goods_weight'] ='Weight:';
$_LANG['unit_g'] ='Gram';
$_LANG['unit_kg'] ='Kilogram';
$_LANG['lab_goods_number'] ='Stock quantity:';
$_LANG['lab_warn_number'] ='Stock warning quantity:';
$_LANG['lab_integral'] ='Integral purchase amount:';
$_LANG['lab_give_integral'] = 'Consumption presented a few points:';
$_LANG['lab_rank_integral'] = 'Presented a number of grade points:';
$_LANG['lab_intro'] ='Recommend:';
$_LANG['lab_is_on_sale'] ='On sale:';
$_LANG['lab_is_alone_sale'] ='Common product:';
$_LANG['lab_is_free_shipping'] = 'Free shipping：';


$_LANG['compute_by_mp'] ='Calculate';

$_LANG['notice_goods_sn'] ='If you don\'t enter product NO., the system will create unique NO. automatically.';
$_LANG['notice_integral'] ='（This required amount）Buy the goods can use points.';
$_LANG['notice_give_integral'] = 'Purchase the merchandise when presented fraction of consumption, express -1 presented by commodity prices';
$_LANG['notice_rank_integral'] = 'Purchase the merchandise when presented fraction grading, express -1 presented by commodity prices';
$_LANG['notice_seller_note'] ='Only provide information for shop owner.';
$_LANG['notice_storage'] = 'Inventories of goods for the virtual goods or commodities when there is non-editable state of goods, inventory value depends on its quantity or volume of goods virtual goods';
$_LANG['notice_keywords'] ='Divided by blank character.';
$_LANG['notice_user_price'] = 'Member price is -1, said member prices Member grade discount rate. You can also specify a hierarchy for each fixed-price';
$_LANG['notice_goods_type'] = 'Please select the type of the goods, then complete the attributes of the goods';

$_LANG['on_sale_desc'] ='Checked means it can be allowed to sale, otherwise can be disallowed to sale.';
$_LANG['alone_sale'] ='Checked means it can be sold as common product, otherwise can be sold as accessories or gifts.';
$_LANG['free_shipping'] = 'Checked means it can shipped free, otherwise as regular.';

$_LANG['invalid_goods_img'] ='Product picture format inaccuracy!';
$_LANG['invalid_goods_thumb']='Product thumbnail format inaccuracy!';
$_LANG['invalid_img_url'] ='Product gallery the %s picture format inaccuracy!';

$_LANG['goods_img_too_big'] ='Product picture file is too big(the biggest value: %s), can\'t upload.';
$_LANG['goods_thumb_too_big']='Product thumbnail file is too big(the biggest value: %s), can\'t upload.';
$_LANG['img_url_too_big'] ='Product gallery in the %s picture file is too big(the biggest value: %s), can\'t upload.';

$_LANG['integral_market_price']='Take integral';
$_LANG['upload_images'] ='Upload a picture';
$_LANG['spec_price'] = 'Attribute price';
$_LANG['drop_img_confirm'] = 'Are you sure delete the picture?';

$_LANG['select_font'] = 'Font Style';
$_LANG['font_styles'] = array('strong' => 'Bold', 'em' => 'Italic', 'u' => 'Underline', 'strike' => 'Strike Through');

$_LANG['rapid_add_cat'] = 'Add category';
$_LANG['rapid_add_brand'] = 'Rapid add brand';
$_LANG['category_manage'] = 'Category manage';
$_LANG['brand_manage'] = 'Brand manage';
$_LANG['hide'] = 'Hide';

$_LANG['lab_volume_price'] = 'Goods favourable price：';
$_LANG['volume_number'] = 'volume number';
$_LANG['volume_price'] = 'Favourable price';
$_LANG['notice_volume_price'] = 'Purchase quantity discount when the total number of preferential prices';
$_LANG['volume_number_continuous'] = 'Repeat quantity discount!';

$_LANG['label_suppliers']          = 'Choice of supplier:';
$_LANG['suppliers_no']             = 'Do not specify a supplier of goods belonging to our';
$_LANG['suppliers_move_to']        = 'Transferred to the supplier';
$_LANG['lab_to_shopex']         = 'Transferred to the Shop';

/*------------------------------------------------------ */
//-- Connection product
/*------------------------------------------------------ */

$_LANG['all_goods'] ='Choose product';
$_LANG['link_goods'] ='Relational products';
$_LANG['single'] ='Single';
$_LANG['double'] ='Double';
$_LANG['all_article'] ='Choose product';
$_LANG['goods_article'] ='Relational articles';
$_LANG['top_cat'] = 'Top Categories';

/*------------------------------------------------------ */
//-- Combine a product
/*------------------------------------------------------ */

$_LANG['group_goods'] ='Accessories';
$_LANG['price'] ='Price';

/*------------------------------------------------------ */
//-- Product gallery
/*------------------------------------------------------ */

$_LANG['img_desc'] ='Description';
$_LANG['img_url'] ='Upload a file';
$_LANG['img_file'] = 'or input the url of the image';

/*------------------------------------------------------ */
//-- Connection article
/*------------------------------------------------------ */
$_LANG['article_title'] ='Article title';

$_LANG['goods_not_exist'] = 'The product doesn\'t exist. ';
$_LANG['goods_not_in_recycle_bin'] = 'The product can\'t be deleted until it is removed to recycle bin.';

$_LANG['js_languages']['goods_name_not_null']='Product name can\'t be blank.';
$_LANG['js_languages']['goods_cat_not_null'] ='Please select product category.';
$_LANG['js_languages']['category_cat_not_null'] = 'Category name can not null';
$_LANG['js_languages']['brand_cat_not_null'] = 'Brand name can not null';
$_LANG['js_languages']['goods_cat_not_leaf'] ='You selected product category isn\'t a bottom class category, please select a bottom class category.';
$_LANG['js_languages']['shop_price_not_null']='The shop selling price can\'t be blank.';
$_LANG['js_languages']['shop_price_not_number']='The shop selling price isn\'t a figure.';

$_LANG['js_languages']['select_please'] ='Please select...';
$_LANG['js_languages']['button_add'] ='Add';
$_LANG['js_languages']['button_del'] ='Delete';
$_LANG['js_languages']['spec_value_not_null'] ='The specification can\'t be blank.';
$_LANG['js_languages']['spec_price_not_number'] ='The price markup isn\'t a fugure.';
$_LANG['js_languages']['market_price_not_number']='The market price isn\'t a figure.';
$_LANG['js_languages']['goods_number_not_int'] ='The product stock isn\'t an integer.';
$_LANG['js_languages']['warn_number_not_int'] ='The stock warning isn\'t an integer.';
$_LANG['js_languages']['promote_not_lt'] = 'Sales start date can not be greater than the end date';
$_LANG['js_languages']['promote_start_not_null'] = 'Promotions start time should not be empty';
$_LANG['js_languages']['promote_end_not_null'] = 'Ending time promotions should not be empty';

$_LANG['js_languages']['drop_img_confirm'] = 'Are you sure delete the picture?';
$_LANG['js_languages']['batch_no_on_sale'] = 'Are you sure stop sale the checked product?';
$_LANG['js_languages']['batch_trash_confirm'] = 'Are you sure move the checked product to recycle bin?';
$_LANG['js_languages']['go_category_page'] = "This page's data will lost, are you sure to go to adding category page？";
$_LANG['js_languages']['go_brand_page'] = "This page's data will lost, are you sure to go to adding brand page？";

$_LANG['js_languages']['volume_num_not_null'] = 'Please enter a quantity discount';
$_LANG['js_languages']['volume_num_not_number'] = 'Quantity discount is not a figure';
$_LANG['js_languages']['volume_price_not_null'] = 'Please enter at preferential prices';
$_LANG['js_languages']['volume_price_not_number'] = 'Discounted prices not figure';

$_LANG['js_languages']['cancel_color'] = 'no font';

/* 虚拟卡 */
$_LANG['card'] = 'See the virtual card information';
$_LANG['replenish'] = 'Replenishment';
$_LANG['batch_card_add'] = 'Batch Replenishment';
$_LANG['add_replenish'] = 'Add virtual Kaka Micronesia';

$_LANG['goods_number_error'] = 'Merchandise inventory quantity errors';

/*------------------------------------------------------ */
//-- 货品
/*------------------------------------------------------ */
$_LANG['product'] = 'Goods';
$_LANG['product_info'] = 'Item Information';
$_LANG['specifications'] = 'Specifications';
$_LANG['total'] = 'Total:';
$_LANG['add_products'] = 'Add Item';
$_LANG['save_products'] = 'Save the success of goods';
$_LANG['product_id_null'] = 'Goods id is empty';
$_LANG['cannot_found_products'] = 'Specified items not found';
$_LANG['product_batch_del_success'] = 'Remove the success of bulk goods';
$_LANG['product_batch_del_failure'] = 'Goods bulk delete failed';
$_LANG['batch_product_add'] = 'Bulk Add';
$_LANG['batch_product_edit'] = 'Batch Edit';
$_LANG['products_title'] = 'Product Name:%s';
$_LANG['products_title_2'] = 'Item:%s';
$_LANG['good_shop_price'] = '(Price:%d)';
$_LANG['good_goods_sn'] = '(Product Code:%s)';
$_LANG['exist_same_goods_sn'] = 'Item No. Item and products are not allowed to repeat';
$_LANG['exist_same_product_sn'] = 'No duplication of goods';
$_LANG['cannot_add_products'] = 'Add a failure of goods';
$_LANG['exist_same_goods_attr'] = 'Item Specifications Property repeat';
$_LANG['cannot_goods_number'] = 'Item Specifications Property repeat';
$_LANG['not_exist_goods_attr'] = 'This product does not exist specifications, please add the size of their';
$_LANG['goods_sn_exists'] = 'The goods_sn you entered already exists';

?>