<?php

/**
 * ECSHOP 管理中心起始页语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: sxc_shop $
 * $Id: goods.php 16154 2009-06-03 06:29:03Z sxc_shop $
*/

$_LANG['edit_goods'] = '编辑商品信息';
$_LANG['copy_goods'] = '复制商品信息';
$_LANG['continue_add_goods'] = '继续添加新商品';
$_LANG['back_goods_list'] = '返回商品列表';
$_LANG['add_goods_ok'] = '添加商品成功。';
$_LANG['edit_goods_ok'] = '编辑商品成功。';
$_LANG['trash_goods_ok'] = '把商品放入回收站成功。';
$_LANG['restore_goods_ok'] = '还原商品成功。';
$_LANG['drop_goods_ok'] = '删除商品成功。';
$_LANG['batch_handle_ok'] = '批量操作成功。';
$_LANG['drop_goods_confirm'] = '您确实要删除该商品吗？';
$_LANG['batch_drop_confirm'] = '彻底删除商品将删除与该商品有关的所有信息。\n您确实要删除选中的商品吗？';
$_LANG['trash_goods_confirm'] = '您确实要把该商品放入回收站吗？';
$_LANG['batch_trash_confirm'] = '您确实要把选中的商品放入回收站吗？';
$_LANG['restore_goods_confirm'] = '您确实要把该商品还原吗？';
$_LANG['batch_restore_confirm'] = '您确实要把选中的商品还原吗？';
$_LANG['batch_on_sale_confirm'] = '您确实要把选中的商品上架吗？';
$_LANG['batch_not_on_sale_confirm'] = '您确实要把选中的商品下架吗？';
$_LANG['batch_best_confirm'] = '您确实要把选中的商品设为精品吗？';
$_LANG['batch_not_best_confirm'] = '您确实要把选中的商品取消精品吗？';
$_LANG['batch_new_confirm'] = '您确实要把选中的商品设为新品吗？';
$_LANG['batch_not_new_confirm'] = '您确实要把选中的商品取消新品吗？';
$_LANG['batch_hot_confirm'] = '您确实要把选中的商品设为热销吗？';
$_LANG['batch_not_hot_confirm'] = '您确实要把选中的商品取消热销吗？';
$_LANG['cannot_found_goods'] = '找不到指定的商品。';
$_LANG['sel_goods_type'] = '请选择商品类型';

/*------------------------------------------------------ */
//-- 图片处理相关提示信息
/*------------------------------------------------------ */
$_LANG['no_gd'] = '您的服务器不支持 GD 或者没有安装处理该图片类型的扩展库。';
$_LANG['img_not_exists'] = '没有找到原始图片，创建缩略图失败。';
$_LANG['img_invalid'] = '创建缩略图失败，因为您上传了一个无效的图片文件。';
$_LANG['create_dir_failed'] = 'images 文件夹不可写，创建缩略图失败。';
$_LANG['safe_mode_warning'] = '您的服务器运行在安全模式下，而且 %s 目录不存在。您可能需要先行创建该目录才能上传图片。';
$_LANG['not_writable_warning'] = '目录 %s 不可写，您需要把该目录设为可写才能上传图片。';

/*------------------------------------------------------ */
//-- 商品列表
/*------------------------------------------------------ */
$_LANG['goods_cat'] = '所有分类';
$_LANG['goods_brand'] = '所有品牌';
$_LANG['intro_type'] = '全部';
$_LANG['keyword'] = '关键字';
$_LANG['is_best'] = '精品';
$_LANG['is_new'] = '新品';
$_LANG['is_hot'] = '热销';
$_LANG['is_promote'] = '特价';
$_LANG['all_type'] = '全部推荐';
$_LANG['sort_order'] = '推荐排序';

$_LANG['goods_name'] = '商品名称';
$_LANG['goods_sn'] = '货号';
$_LANG['shop_price'] = '价格';
$_LANG['is_on_sale'] = '上架';
$_LANG['goods_number'] = '库存';

$_LANG['copy'] = '复制';

$_LANG['integral'] = '积分额度';
$_LANG['on_sale'] = '上架';
$_LANG['not_on_sale'] = '下架';
$_LANG['best'] = '精品';
$_LANG['not_best'] = '取消精品';
$_LANG['new'] = '新品';
$_LANG['not_new'] = '取消新品';
$_LANG['hot'] = '热销';
$_LANG['not_hot'] = '取消热销';
$_LANG['move_to'] = '转移到分类';

// ajax
$_LANG['goods_name_null'] = '请输入商品名称';
$_LANG['goods_sn_null'] = '请输入货号';
$_LANG['shop_price_not_number'] = '价格不是数字';
$_LANG['shop_price_invalid'] = '您输入了一个非法的市场价格。';
$_LANG['goods_sn_exists'] = '您输入的货号已存在，请换一个';

/*------------------------------------------------------ */
//-- 添加/编辑商品信息
/*------------------------------------------------------ */
$_LANG['tab_general'] = '通用信息';
$_LANG['tab_detail'] = '详细描述';
$_LANG['tab_mix'] = '其他信息';
$_LANG['tab_properties'] = '商品属性';
$_LANG['tab_gallery'] = '商品相册';
$_LANG['tab_linkgoods'] = '关联商品';
$_LANG['tab_groupgoods'] = '配件';
$_LANG['tab_article'] = '关联文章';

$_LANG['lab_goods_name'] = '商品名称：';
$_LANG['lab_goods_sn'] = '商品货号：';
$_LANG['lab_goods_cat'] = '商品分类：';
$_LANG['lab_other_cat'] = '扩展分类：';
$_LANG['lab_goods_brand'] = '商品品牌：';
$_LANG['lab_shop_price'] = '本店售价：';
$_LANG['lab_market_price'] = '市场售价：';
$_LANG['lab_user_price'] = '会员价格：';
$_LANG['lab_promote_price'] = '促销价：';
$_LANG['lab_promote_date'] = '促销日期：';
$_LANG['lab_picture'] = '上传商品图片：';
$_LANG['lab_thumb'] = '上传商品缩略图：';
$_LANG['auto_thumb'] = '自动生成商品缩略图';
$_LANG['lab_keywords'] = '商品关键词：';
$_LANG['lab_goods_brief'] = '商品简单描述：';
$_LANG['lab_seller_note'] = '商家备注：';
$_LANG['lab_goods_type'] = '商品类型：';
$_LANG['lab_picture_url'] = '商品图片外部URL';
$_LANG['lab_thumb_url'] = '商品缩略图外部URL';

$_LANG['lab_goods_weight'] = '商品重量：';
$_LANG['unit_g'] = '克';
$_LANG['unit_kg'] = '千克';
$_LANG['lab_goods_number'] = '商品库存数量：';
$_LANG['lab_warn_number'] = '库存警告数量：';
$_LANG['lab_integral'] = '积分购买额度：';
$_LANG['lab_give_integral'] = '赠送消费积分数：';
$_LANG['lab_rank_integral'] = '赠送等级积分数：';
$_LANG['lab_intro'] = '加入推荐：';
$_LANG['lab_is_on_sale'] = '上架：';
$_LANG['lab_is_alone_sale'] = '能作为普通商品销售：';

$_LANG['compute_by_mp'] = '按市场价计算';

$_LANG['notice_goods_sn'] = '如果您不输入商品货号，系统将自动生成一个唯一的货号。';
$_LANG['notice_integral'] = '购买该商品时最多可以使用多少钱的积分';
$_LANG['notice_give_integral'] = '购买该商品时赠送消费积分数,-1表示按商品价格赠送';
$_LANG['notice_rank_integral'] = '购买该商品时赠送等级积分数,-1表示按商品价格赠送';
$_LANG['notice_seller_note'] = '仅供商家自己看的信息';
$_LANG['notice_keywords'] = '用空格分隔';
$_LANG['notice_user_price'] = '会员价格为-1时表示会员价格按会员等级折扣率计算。你也可以为每个等级指定一个固定价格';
$_LANG['notice_goods_type'] = '请选择商品的所属类型，进而完善此商品的属性';

$_LANG['on_sale_desc'] = '打勾表示允许销售，否则不允许销售。';
$_LANG['alone_sale'] = '打勾表示能作为普通商品销售，否则只能作为配件或赠品销售。';

$_LANG['invalid_goods_img'] = '商品图片格式不正确！';
$_LANG['invalid_goods_thumb'] = '商品缩略图格式不正确！';
$_LANG['invalid_img_url'] = '商品相册中第%s个图片格式不正确!';

$_LANG['goods_img_too_big'] = '商品图片文件太大了（最大值：%s），无法上传。';
$_LANG['goods_thumb_too_big'] = '商品缩略图文件太大了（最大值：%s），无法上传。';
$_LANG['img_url_too_big'] = '商品相册中第%s个图片文件太大了（最大值：%s），无法上传。';

$_LANG['integral_market_price'] = '取整数';
$_LANG['upload_images'] = '上传图片';
$_LANG['spec_price'] = '属性价格';
$_LANG['drop_img_confirm'] = '您确实要删除该图片吗？';

$_LANG['select_font'] = '字体样式';
$_LANG['font_styles'] = array('strong' => '加粗', 'em' => '斜体', 'u' => '下划线', 'strike' => '删除线');

$_LANG['rapid_add_cat'] = '添加分类';
$_LANG['rapid_add_brand'] = '添加品牌';
$_LANG['category_manage'] = '分类管理';
$_LANG['brand_manage'] = '品牌管理';
$_LANG['hide'] = '隐藏';

$_LANG['lab_volume_price']         = '商品优惠价格：';
$_LANG['volume_number']            = '优惠数量';
$_LANG['volume_price']             = '优惠价格';
$_LANG['notice_volume_price']      = '购买数量达到优惠数量时享受的优惠价格';
$_LANG['volume_number_continuous'] = '优惠数量重复！';

/*------------------------------------------------------ */
//-- 关联商品
/*------------------------------------------------------ */

$_LANG['all_goods'] = '可选商品';
$_LANG['link_goods'] = '跟该商品关联的商品';
$_LANG['single'] = '单向关联';
$_LANG['double'] = '双向关联';
$_LANG['all_article'] = '可选文章';
$_LANG['goods_article'] = '跟该商品关联的文章';
$_LANG['top_cat'] = '顶级分类';

/*------------------------------------------------------ */
//-- 组合商品
/*------------------------------------------------------ */

$_LANG['group_goods'] = '该商品的配件';
$_LANG['price'] = '价格';

/*------------------------------------------------------ */
//-- 商品相册
/*------------------------------------------------------ */

$_LANG['img_desc'] = '图片描述';
$_LANG['img_url'] = '上传文件';

/*------------------------------------------------------ */
//-- 关联文章
/*------------------------------------------------------ */
$_LANG['article_title'] = '文章标题';

$_LANG['goods_not_exist'] = '该商品不存在';
$_LANG['goods_not_in_recycle_bin'] = '该商品尚未放入回收站，不能删除';

$_LANG['js_languages']['goods_name_not_null'] = '商品名称不能为空。';
$_LANG['js_languages']['goods_cat_not_null'] = '商品分类必须选择。';
$_LANG['js_languages']['category_cat_not_null'] = '分类名称不能为空';
$_LANG['js_languages']['brand_cat_not_null'] = '品牌名称不能为空';
$_LANG['js_languages']['goods_cat_not_leaf'] = '您选择的商品分类不是底级分类，请选择底级分类。';
$_LANG['js_languages']['shop_price_not_null'] = '本店售价不能为空。';
$_LANG['js_languages']['shop_price_not_number'] = '本店售价不是数值。';

$_LANG['js_languages']['select_please'] = '请选择...';
$_LANG['js_languages']['button_add'] = '添加';
$_LANG['js_languages']['button_del'] = '删除';
$_LANG['js_languages']['spec_value_not_null'] = '规格不能为空';
$_LANG['js_languages']['spec_price_not_number'] = '加价不是数字';
$_LANG['js_languages']['market_price_not_number'] = '市场价格不是数字';
$_LANG['js_languages']['goods_number_not_int'] = '商品库存不是整数';
$_LANG['js_languages']['warn_number_not_int'] = '库存警告不是整数';
$_LANG['js_languages']['promote_not_lt'] = '促销开始日期不能大于结束日期';
$_LANG['js_languages']['promote_start_not_null'] = '促销开始时间不能为空';
$_LANG['js_languages']['promote_end_not_null'] = '促销结束时间不能为空';

$_LANG['js_languages']['drop_img_confirm'] = '您确实要删除该图片吗？';
$_LANG['js_languages']['batch_no_on_sale'] = '您确实要将选定的商品下架吗？';
$_LANG['js_languages']['batch_trash_confirm'] = '您确实要把选中的商品放入回收站吗？';
$_LANG['js_languages']['go_category_page'] = '本页数据将丢失，确认要去商品分类页添加分类吗？';
$_LANG['js_languages']['go_brand_page'] = '本页数据将丢失，确认要去商品品牌页添加品牌吗？';

$_LANG['js_languages']['volume_num_not_null'] = '请输入优惠数量';
$_LANG['js_languages']['volume_num_not_number'] = '优惠数量不是数字';
$_LANG['js_languages']['volume_price_not_null'] = '请输入优惠价格';
$_LANG['js_languages']['volume_price_not_number'] = '优惠价格不是数字';

/* 虚拟卡 */
$_LANG['card'] = '查看虚拟卡信息';
$_LANG['replenish'] = '补货';
$_LANG['batch_card_add'] = '批量补货';
$_LANG['add_replenish'] = '添加虚拟卡卡密';

$_LANG['goods_number_error'] = '商品库存数量错误';

?>
