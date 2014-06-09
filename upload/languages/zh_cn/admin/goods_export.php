<?php

/**
 * ECSHOP
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 *
 * $Author: liubo $
 * $Id: goods_export.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['user_guide'] =
        '<br/>使用说明：' .
        '<ol>' .
          '<li>使用搜索条件每次只允许搜索符合条件的50个商品。</li>' .
          '<li>如果用户需要导出某分类下的所有的商品，在条件中选择分类后，不需点击搜索，直接选择数据格式和编码导出即可。</li>' .
        '</ol>';
$_LANG['export_taobao'] = '导出淘宝助理支持数据格式';
$_LANG['export_taobao_v43'] = '导出淘宝助理V4.3支持数据格式';
$_LANG['export_taobao_v46'] = '导出淘宝助理V4.6支持数据格式';
$_LANG['good_cat'] = '商品分类';
$_LANG['select_please'] = '请选择要导出的分类';
$_LANG['select_charset'] = '请选择要导出的编码';

$_LANG['goods_class'] = '宝贝栏目ID';
$_LANG['tabobao_shipping'] = '淘宝配送';
$_LANG['post_express'] = '平邮价格';
$_LANG['express'] = '快递价格';
$_LANG['ems'] = 'EMS价格';
$_LANG['notice_goods_class'] = '宝贝栏目ID为淘宝分类的ID，如若不确定，请填写0';

$_LANG['post_express_not_null'] = '平邮价格必须大于0';
$_LANG['express_not_null'] = '快递价格必须大于0';
$_LANG['ems_not_null'] = 'EMS价格必须大于0';

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

/*淘宝4.6*/
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

$_LANG['taobao46']['rebate_proportion'] = '返点比例';
$_LANG['taobao46']['new_picture'] = '新图片';
$_LANG['taobao46']['video'] = '视频';
$_LANG['taobao46']['marketing_property_mix'] = '销售属性组合';
$_LANG['taobao46']['user_input_ID_numbers'] = '用户输入ID串';
$_LANG['taobao46']['input_user_name_value'] = '用户输入名-值对';
$_LANG['taobao46']['sellers_code'] = '商家编码';
$_LANG['taobao46']['another_of_marketing_property'] = '销售属性别名';
$_LANG['taobao46']['charge_type'] = '代充类型';
$_LANG['taobao46']['treasure_number'] = '宝贝编号';
$_LANG['taobao46']['ID_number'] = '数字ID';


$_LANG['export_paipai'] = '导出到拍拍助理支持数据格式';
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

$_LANG['export_paipai4'] = '导出到拍拍助理3.0支持数据格式';
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
$_LANG['export_ecshop'] = '导出到ECShop数据格式';
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
$_LANG['export_custom'] = '导出到自定义数据格式';
$_LANG['custom']['goods_name'] = '商品名称';
$_LANG['custom']['goods_sn'] = '商品货号';
$_LANG['custom']['brand_name'] = '商品品牌';
$_LANG['custom']['market_price'] = '市场售价';
$_LANG['custom']['shop_price'] = '本店售价';
$_LANG['custom']['integral'] = '积分购买额度';
$_LANG['custom']['original_img'] = '商品原始图';
$_LANG['custom']['goods_img'] = '商品图片';
$_LANG['custom']['goods_thumb'] = '商品缩略图';
$_LANG['custom']['keywords'] = '商品关键词';
$_LANG['custom']['goods_brief'] = '简单描述';
$_LANG['custom']['goods_desc'] = '详细描述';
$_LANG['custom']['goods_weight'] = '商品重量（kg）';
$_LANG['custom']['goods_number'] = '库存数量';
$_LANG['custom']['warn_number'] = '库存警告数量';
$_LANG['custom']['is_best'] = '是否精品';
$_LANG['custom']['is_new'] = '是否新品';
$_LANG['custom']['is_hot'] = '是否热销';
$_LANG['custom']['is_on_sale'] = '是否上架';
$_LANG['custom']['is_alone_sale'] = '能否作为普通商品销售';
$_LANG['custom']['is_real'] = '是否实体商品';

$_LANG['custom_keyword'] = '关键字';
$_LANG['custom_goods_cat'] = '所有分类';
$_LANG['custom_goods_brand'] = '所有品牌';
$_LANG['custom_goods_list'] = '选择商品数据列';
$_LANG['custom_goods_type'] = '所有商品类型';
$_LANG['custom_export_list'] = '输出商品数据列';
$_LANG['custom_up'] = '上';
$_LANG['custom_down'] = '下';
$_LANG['custom_goods_search'] = '导出条件';
$_LANG['custom_goods_field_not_null'] = '输出的商品数据列不能为空';

// 导出条件
$_LANG['export_condition'] = '商品数据批量导出';
$_LANG['export_condition_search'] = '搜 索';
$_LANG['export_format'] = '数据格式';

?>
