<?php

/**
 * ECSHOP Vote management
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
 * $Id: goods_export.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['user_guide'] =
        '<br/>Help:' .
        '<ol>' .
          '<li>Use the search conditions permit only english eligible merchandise 50.</li>' .
          '<li>If the user required to export certain categories of merchandise all under the conditions choose to triage, no click search, the direct choice of data formats and encoding can be derived.</li>' .
        '</ol>';
$_LANG['export_taobao'] = 'Export Taobao Assistant Supporting data formats';
$_LANG['export_taobao_v43'] = 'Export Taobao AssistantV4.3 upporting data formats';
$_LANG['export_taobao_v46'] = 'Export Taobao AssistantV4.6 upporting data formats';
$_LANG['good_cat'] = 'Merchandise categories';
$_LANG['select_please'] = 'Please select Export Classification';
$_LANG['select_charset'] = 'Please choose to export charset';

$_LANG['goods_class'] = 'Precious columnsID';
$_LANG['tabobao_shipping'] = 'Taobao distribution';
$_LANG['post_express'] = 'Ordinary price';
$_LANG['express'] = 'Express Price';
$_LANG['ems'] = 'EMS Price';
$_LANG['notice_goods_class'] = 'ID for the baby section Taobao classification ID, if uncertain, please fill 0';

$_LANG['post_express_not_null'] = 'Ordinary price must be greater than 0';
$_LANG['express_not_null'] = 'Express the price must be greater than 0';
$_LANG['ems_not_null'] = 'EMS price must be greater than 0';


/* 淘宝 */
$_LANG['taobao']['goods_name'] = '宝贝名称';
$_LANG['taobao']['goods_class'] = '宝贝类目';
$_LANG['taobao']['shop_class'] = '店铺类目';
$_LANG['taobao']['new_level'] = '新旧程度';
$_LANG['taobao']['province'] = '省';
$_LANG['taobao']['city'] = '城市';
$_LANG['taobao']['sell_type'] = '出售方式';
$_LANG['taobao']['shop_price'] = '宝贝价格';
$_LANG['taobao']['add_price'] = '加价幅度';
$_LANG['taobao']['goods_number'] = '宝贝数量';
$_LANG['taobao']['die_day'] = '有效期';
$_LANG['taobao']['load_type'] = '运费承担';
$_LANG['taobao']['post_express'] = '平邮';
$_LANG['taobao']['ems'] = 'EMS';
$_LANG['taobao']['express'] = '快递';
$_LANG['taobao']['pay_type'] = '付款方式';
$_LANG['taobao']['allow_alipay'] = '支付宝';
$_LANG['taobao']['invoice'] = '发票';
$_LANG['taobao']['repair'] = '保修';
$_LANG['taobao']['resend'] = '自动重发';
$_LANG['taobao']['is_store'] = '放入仓库';
$_LANG['taobao']['window'] = '橱窗推荐';
$_LANG['taobao']['add_time'] = '发布时间';
$_LANG['taobao']['story'] = '心情故事';
$_LANG['taobao']['goods_desc'] = '宝贝描述';
$_LANG['taobao']['goods_img'] = '宝贝图片';
$_LANG['taobao']['goods_attr'] = '宝贝属性';
$_LANG['taobao']['group_buy'] = '团购价';
$_LANG['taobao']['group_buy_num'] = '最小团购件数';
$_LANG['taobao']['template'] = '邮费模版ID';
$_LANG['taobao']['discount'] = '会员打折';
$_LANG['taobao']['modify_time'] = '修改时间';
$_LANG['taobao']['upload_status'] = '上传状态';
$_LANG['taobao']['img_status'] = '图片状态';
/* 淘宝 */
$_LANG['taobao46']['goods_name'] = '宝贝名称';
$_LANG['taobao46']['goods_class'] = '宝贝类目';
$_LANG['taobao46']['shop_class'] = '店铺类目';
$_LANG['taobao46']['new_level'] = '新旧程度';
$_LANG['taobao46']['province'] = '省';
$_LANG['taobao46']['city'] = '城市';
$_LANG['taobao46']['sell_type'] = '出售方式';
$_LANG['taobao46']['shop_price'] = '宝贝价格';
$_LANG['taobao46']['add_price'] = '加价幅度';
$_LANG['taobao46']['goods_number'] = '宝贝数量';
$_LANG['taobao46']['die_day'] = '有效期';
$_LANG['taobao46']['load_type'] = '运费承担';
$_LANG['taobao46']['post_express'] = '平邮';
$_LANG['taobao46']['ems'] = 'EMS';
$_LANG['taobao46']['express'] = '快递';
$_LANG['taobao46']['pay_type'] = '付款方式';
$_LANG['taobao46']['allow_alipay'] = '支付宝';
$_LANG['taobao46']['invoice'] = '发票';
$_LANG['taobao46']['repair'] = '保修';
$_LANG['taobao46']['resend'] = '自动重发';
$_LANG['taobao46']['is_store'] = '放入仓库';
$_LANG['taobao46']['window'] = '橱窗推荐';
$_LANG['taobao46']['add_time'] = '发布时间';
$_LANG['taobao46']['story'] = '心情故事';
$_LANG['taobao46']['goods_desc'] = '宝贝描述';
$_LANG['taobao46']['goods_img'] = '宝贝图片';
$_LANG['taobao46']['goods_attr'] = '宝贝属性';
$_LANG['taobao46']['group_buy'] = '团购价';
$_LANG['taobao46']['group_buy_num'] = '最小团购件数';
$_LANG['taobao46']['template'] = '邮费模版ID';
$_LANG['taobao46']['discount'] = '会员打折';
$_LANG['taobao46']['modify_time'] = '修改时间';
$_LANG['taobao46']['upload_status'] = '上传状态';
$_LANG['taobao46']['img_status'] = '图片状态';

$_LANG['taobao46']['img_status'] = '返点比例';
$_LANG['taobao46']['img_status'] = '新图片';
$_LANG['taobao46']['img_status'] = '视频';
$_LANG['taobao46']['img_status'] = '销售属性组合';
$_LANG['taobao46']['img_status'] = '用户输入ID串';
$_LANG['taobao46']['img_status'] = '用户输入名-值对';
$_LANG['taobao46']['img_status'] = '商家编码';
$_LANG['taobao46']['img_status'] = '销售属性别名';
$_LANG['taobao46']['img_status'] = '代充类型';
$_LANG['taobao46']['img_status'] = '宝贝编号';
$_LANG['taobao46']['img_status'] = '数字ID';

$_LANG['export_paipai'] = 'Export to patted Assistant Supporting data formats';
$_LANG['paipai']['id'] = 'id';
$_LANG['paipai']['tree_node_id'] = 'tree_node_id';
$_LANG['paipai']['old_tree_node_id'] = 'old_tree_node_id';
$_LANG['paipai']['title'] = 'title';
$_LANG['paipai']['id_in_web'] = 'id_in_web';
$_LANG['paipai']['auctionType'] = 'auctionType';
$_LANG['paipai']['category'] = 'category';
$_LANG['paipai']['shopCategoryId'] = 'shopCategoryId';
$_LANG['paipai']['pictURL'] = 'pictURL';
$_LANG['paipai']['quantity'] = 'quantity';
$_LANG['paipai']['duration'] = 'duration';
$_LANG['paipai']['startDate'] = 'startDate';
$_LANG['paipai']['stuffStatus'] = 'stuffStatus';
$_LANG['paipai']['price'] = 'price';
$_LANG['paipai']['increment'] = 'increment';
$_LANG['paipai']['prov'] = 'prov';
$_LANG['paipai']['city'] = 'city';
$_LANG['paipai']['shippingOption'] = 'shippingOption';
$_LANG['paipai']['ordinaryPostFee'] = 'ordinaryPostFee';
$_LANG['paipai']['fastPostFee'] = 'fastPostFee';
$_LANG['paipai']['paymentOption'] = 'paymentOption';
$_LANG['paipai']['haveInvoice'] = 'haveInvoice';
$_LANG['paipai']['haveGuarantee'] = 'haveGuarantee';
$_LANG['paipai']['secureTradeAgree'] = 'secureTradeAgree';
$_LANG['paipai']['autoRepost'] = 'autoRepost';
$_LANG['paipai']['shopWindow'] = 'shopWindow';
$_LANG['paipai']['failed_reason'] = 'failed_reason';
$_LANG['paipai']['pic_size'] = 'pic_size';
$_LANG['paipai']['pic_filename'] = 'pic_filename';
$_LANG['paipai']['pic'] = 'pic';
$_LANG['paipai']['description'] = 'description';
$_LANG['paipai']['story'] = 'story';
$_LANG['paipai']['putStore'] = 'putStore';
$_LANG['paipai']['pic_width'] = 'pic_width';
$_LANG['paipai']['pic_height'] = 'pic_height';
$_LANG['paipai']['skin'] = 'skin';
$_LANG['paipai']['prop'] = 'prop';


$_LANG['export_paipai4'] = 'Export to patted Assistant Supporting 3.0 data formats';
$_LANG['paipai4']['id'] = 'id';
$_LANG['paipai4']['goods_name'] = '商品名称';
$_LANG['paipai4']['auctionType'] = '出售方式';
$_LANG['paipai4']['category'] = '商品类目';
$_LANG['paipai4']['shopCategoryId'] = '店铺类目';
$_LANG['paipai4']['quantity'] = '商品数量';
$_LANG['paipai4']['duration'] = '有效期';
$_LANG['paipai4']['startDate'] = '定时上架';
$_LANG['paipai4']['stuffStatus'] = '新旧程度';
$_LANG['paipai4']['price'] = '价格';
$_LANG['paipai4']['increment'] = '加价幅度';
$_LANG['paipai4']['prov'] = '省';
$_LANG['paipai4']['city'] = '市';
$_LANG['paipai4']['shippingOption'] = '运费承担';
$_LANG['paipai4']['ordinaryPostFee'] = '平邮';
$_LANG['paipai4']['fastPostFee'] = '快递';
$_LANG['paipai4']['buyLimit'] = '购买限制';
$_LANG['paipai4']['paymentOption'] = '付款方式';
$_LANG['paipai4']['haveInvoice'] = '有发票';
$_LANG['paipai4']['haveGuarantee'] = '有保修';
$_LANG['paipai4']['secureTradeAgree'] = '支持财付通';
$_LANG['paipai4']['autoRepost'] = '自动重发';
$_LANG['paipai4']['failed_reason'] = '错误原因';
$_LANG['paipai4']['pic_filename'] = '图片';
$_LANG['paipai4']['description'] = '商品详情';
$_LANG['paipai4']['shelfOption'] = '上架选项';
$_LANG['paipai4']['skin'] = '皮肤风格';
$_LANG['paipai4']['attr'] = '属性';
$_LANG['paipai4']['chengBao'] = '诚保';
$_LANG['paipai4']['shopWindow'] = '橱窗';

// 批量上传商品的字段
$_LANG['export_ecshop'] = 'Export to ECShop data format';
$_LANG['ecshop']['goods_name'] = '商品名称';
$_LANG['ecshop']['goods_sn'] = '商品货号';
$_LANG['ecshop']['brand_name'] = '商品品牌';   // 需要转换成brand_id
$_LANG['ecshop']['market_price'] = '市场售价';
$_LANG['ecshop']['shop_price'] = '本店售价';
$_LANG['ecshop']['integral'] = '积分购买额度';
$_LANG['ecshop']['original_img'] = '商品原始图';
$_LANG['ecshop']['goods_img'] = '商品图片';
$_LANG['ecshop']['goods_thumb'] = '商品缩略图';
$_LANG['ecshop']['keywords'] = '商品关键词';
$_LANG['ecshop']['goods_brief'] = '简单描述';
$_LANG['ecshop']['goods_desc'] = '详细描述';
$_LANG['ecshop']['goods_weight'] = '商品重量（kg）';
$_LANG['ecshop']['goods_number'] = '库存数量';
$_LANG['ecshop']['warn_number'] = '库存警告数量';
$_LANG['ecshop']['is_best'] = '是否精品';
$_LANG['ecshop']['is_new'] = '是否新品';
$_LANG['ecshop']['is_hot'] = '是否热销';
$_LANG['ecshop']['is_on_sale'] = '是否上架';
$_LANG['ecshop']['is_alone_sale'] = '能否作为普通商品销售';
$_LANG['ecshop']['is_real'] = '是否实体商品';

//自定义导出数据格式
$_LANG['export_custom'] = 'Export to a custom data format';
$_LANG['custom']['goods_name'] = 'goods_name';
$_LANG['custom']['goods_sn'] = 'goods_sn';
$_LANG['custom']['brand_name'] = 'brand_name';
$_LANG['custom']['market_price'] = 'market_price';
$_LANG['custom']['shop_price'] = 'shop_price';
$_LANG['custom']['integral'] = 'integral';
$_LANG['custom']['original_img'] = 'riginal_img';
$_LANG['custom']['goods_img'] = 'goods_img';
$_LANG['custom']['goods_thumb'] = 'goods_thumb';
$_LANG['custom']['keywords'] = 'keywords';
$_LANG['custom']['goods_brief'] = 'goods_brief';
$_LANG['custom']['goods_desc'] = 'goods_desc';
$_LANG['custom']['goods_weight'] = 'goods_weight(kg)';
$_LANG['custom']['goods_number'] = 'goods_number';
$_LANG['custom']['warn_number'] = 'warn_number';
$_LANG['custom']['is_best'] = 'is_best';
$_LANG['custom']['is_new'] = 'is_new';
$_LANG['custom']['is_hot'] = 'is_hot';
$_LANG['custom']['is_on_sale'] = 'is_on_sale';
$_LANG['custom']['is_alone_sale'] = 'is_alone_sale';
$_LANG['custom']['is_real'] = 'is_real';

$_LANG['custom_keyword'] = 'Keyword';
$_LANG['custom_goods_cat'] = 'All Categories';
$_LANG['custom_goods_brand'] = 'All brands';
$_LANG['custom_goods_list'] = 'Select merchandise data columns';
$_LANG['custom_goods_type'] = 'All types of merchandise';
$_LANG['custom_export_list'] = 'Merchandise export data columns';
$_LANG['custom_up'] = 'On';
$_LANG['custom_down'] = 'Under';
$_LANG['custom_goods_search'] = 'Export conditions';
$_LANG['custom_goods_field_not_null'] = 'Output data out of the merchandise should not be empty';

// 导出条件
$_LANG['export_condition'] = 'Export volume of goods data';
$_LANG['export_condition_search'] = 'Search';
$_LANG['export_format'] = 'Data Format';

?>
