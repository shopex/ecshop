<?php

/**
 * ECSHOP 订单管理语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: order.php 17217 2011-01-19 06:29:08Z liubo $
 */

/* 订单搜索 */
$_LANG['order_sn'] = '订单号';
$_LANG['consignee'] = '收货人';
$_LANG['all_status'] = '订单状态';

$_LANG['cs'][OS_UNCONFIRMED] = '待确认';
$_LANG['cs'][CS_AWAIT_PAY] = '待付款';
$_LANG['cs'][CS_AWAIT_SHIP] = '待发货';
$_LANG['cs'][CS_FINISHED] = '已完成';
$_LANG['cs'][PS_PAYING] = '付款中';
$_LANG['cs'][OS_CANCELED] = '取消';
$_LANG['cs'][OS_INVALID] = '无效';
$_LANG['cs'][OS_RETURNED] = '退货';
$_LANG['cs'][OS_SHIPPED_PART] = '部分发货';

/* 订单状态 */
$_LANG['os'][OS_UNCONFIRMED] = '未确认';
$_LANG['os'][OS_CONFIRMED] = '已确认';
$_LANG['os'][OS_CANCELED] = '<font color="red"> 取消</font>';
$_LANG['os'][OS_INVALID] = '<font color="red">无效</font>';
$_LANG['os'][OS_RETURNED] = '<font color="red">退货</font>';
$_LANG['os'][OS_SPLITED] = '已分单';
$_LANG['os'][OS_SPLITING_PART] = '部分分单';

$_LANG['ss'][SS_UNSHIPPED] = '未发货';
$_LANG['ss'][SS_PREPARING] = '配货中';
$_LANG['ss'][SS_SHIPPED] = '已发货';
$_LANG['ss'][SS_RECEIVED] = '收货确认';
$_LANG['ss'][SS_SHIPPED_PART] = '已发货(部分商品)';
$_LANG['ss'][SS_SHIPPED_ING] = '发货中';

$_LANG['ps'][PS_UNPAYED] = '未付款';
$_LANG['ps'][PS_PAYING] = '付款中';
$_LANG['ps'][PS_PAYED] = '已付款';

$_LANG['ss_admin'][SS_SHIPPED_ING] = '发货中（前台状态：未发货）';
/* 订单操作 */
$_LANG['label_operable_act'] = '当前可执行操作：';
$_LANG['label_action_note'] = '操作备注：';
$_LANG['label_invoice_note'] = '发货备注：';
$_LANG['label_invoice_no'] = '发货单号：';
$_LANG['label_cancel_note'] = '取消原因：';
$_LANG['notice_cancel_note'] = '（会记录在商家给客户的留言中）';
$_LANG['op_confirm'] = '确认';
$_LANG['op_pay'] = '付款';
$_LANG['op_prepare'] = '配货';
$_LANG['op_ship'] = '发货';
$_LANG['op_cancel'] = '取消';
$_LANG['op_invalid'] = '无效';
$_LANG['op_return'] = '退货';
$_LANG['op_unpay'] = '设为未付款';
$_LANG['op_unship'] = '未发货';
$_LANG['op_cancel_ship'] = '取消发货';
$_LANG['op_receive'] = '已收货';
$_LANG['op_assign'] = '指派给';
$_LANG['op_after_service'] = '售后';
$_LANG['act_ok'] = '操作成功';
$_LANG['act_false'] = '操作失败';
$_LANG['act_ship_num'] = '此单发货数量不能超出订单商品数量';
$_LANG['act_good_vacancy'] = '商品已缺货';
$_LANG['act_good_delivery'] = '货已发完';
$_LANG['notice_gb_ship'] = '备注：团购活动未处理为成功前，不能发货';
$_LANG['back_list'] = '返回订单列表';
$_LANG['op_remove'] = '删除';
$_LANG['op_you_can'] = '您可进行的操作';
$_LANG['op_split'] = '生成发货单';
$_LANG['op_to_delivery'] = '去发货';

/* 订单列表 */
$_LANG['order_amount'] = '应付金额';
$_LANG['total_fee'] = '总金额';
$_LANG['shipping_name'] = '配送方式';
$_LANG['pay_name'] = '支付方式';
$_LANG['address'] = '地址';
$_LANG['order_time'] = '下单时间';
$_LANG['detail'] = '查看';
$_LANG['phone'] = '电话';
$_LANG['group_buy'] = '（团购）';
$_LANG['error_get_goods_info'] = '获取订单商品信息错误';
$_LANG['exchange_goods'] = '（积分兑换）';

$_LANG['js_languages']['remove_confirm'] = '删除订单将清除该订单的所有信息。您确定要这么做吗？';

/* 订单搜索 */
$_LANG['label_order_sn'] = '订单号：';
$_LANG['label_all_status'] = '订单状态：';
$_LANG['label_user_name'] = '购货人：';
$_LANG['label_consignee'] = '收货人：';
$_LANG['label_email'] = '电子邮件：';
$_LANG['label_address'] = '地址：';
$_LANG['label_zipcode'] = '邮编：';
$_LANG['label_tel'] = '电话：';
$_LANG['label_mobile'] = '手机：';
$_LANG['label_shipping'] = '配送方式：';
$_LANG['label_payment'] = '支付方式：';
$_LANG['label_order_status'] = '订单状态：';
$_LANG['label_pay_status'] = '付款状态：';
$_LANG['label_shipping_status'] = '发货状态：';
$_LANG['label_area'] = '所在地区：';
$_LANG['label_time'] = '下单时间：';

/* 订单详情 */
$_LANG['prev'] = '前一个订单';
$_LANG['next'] = '后一个订单';
$_LANG['print_order'] = '打印订单';
$_LANG['print_shipping'] = '打印快递单';
$_LANG['print_order_sn'] = '订单编号：';
$_LANG['print_buy_name'] = '购 货 人：';
$_LANG['label_consignee_address'] = '收货地址：';
$_LANG['no_print_shipping'] = '很抱歉,目前您还没有设置打印快递单模板.不能进行打印';
$_LANG['suppliers_no'] = '不指定供货商本店自行处理';
$_LANG['restaurant'] = '本店';

$_LANG['order_info'] = '订单信息';
$_LANG['base_info'] = '基本信息';
$_LANG['other_info'] = '其他信息';
$_LANG['consignee_info'] = '收货人信息';
$_LANG['fee_info'] = '费用信息';
$_LANG['action_info'] = '操作信息';
$_LANG['shipping_info'] = '配送信息';

$_LANG['label_how_oos'] = '缺货处理：';
$_LANG['label_how_surplus'] = '余额处理：';
$_LANG['label_pack'] = '包装：';
$_LANG['label_card'] = '贺卡：';
$_LANG['label_card_message'] = '贺卡祝福语：';
$_LANG['label_order_time'] = '下单时间：';
$_LANG['label_pay_time'] = '付款时间：';
$_LANG['label_shipping_time'] = '发货时间：';
$_LANG['label_sign_building'] = '标志性建筑：';
$_LANG['label_best_time'] = '最佳送货时间：';
$_LANG['label_inv_type'] = '发票类型：';
$_LANG['label_inv_payee'] = '发票抬头：';
$_LANG['label_inv_content'] = '发票内容：';
$_LANG['label_postscript'] = '客户给商家的留言：';
$_LANG['label_region'] = '所在地区：';

$_LANG['label_shop_url'] = '网址：';
$_LANG['label_shop_address'] = '地址：';
$_LANG['label_service_phone'] = '电话：';
$_LANG['label_print_time'] = '打印时间：';

$_LANG['label_suppliers'] = '选择供货商：';
$_LANG['label_agency'] = '办事处：';
$_LANG['suppliers_name'] = '供货商';

$_LANG['product_sn'] = '货品号';
$_LANG['goods_info'] = '商品信息';
$_LANG['goods_name'] = '商品名称';
$_LANG['goods_name_brand'] = '商品名称 [ 品牌 ]';
$_LANG['goods_sn'] = '货号';
$_LANG['goods_price'] = '价格';
$_LANG['goods_number'] = '数量';
$_LANG['goods_attr'] = '属性';
$_LANG['goods_delivery'] = '已发货数量';
$_LANG['goods_delivery_curr'] = '此单发货数量';
$_LANG['storage'] = '库存';
$_LANG['subtotal'] = '小计';
$_LANG['label_total'] = '合计：';
$_LANG['label_total_weight'] = '商品总重量：';

$_LANG['label_goods_amount'] = '商品总金额：';
$_LANG['label_discount'] = '折扣：';
$_LANG['label_tax'] = '发票税额：';
$_LANG['label_shipping_fee'] = '配送费用：';
$_LANG['label_insure_fee'] = '保价费用：';
$_LANG['label_insure_yn'] = '是否保价：';
$_LANG['label_pay_fee'] = '支付费用：';
$_LANG['label_pack_fee'] = '包装费用：';
$_LANG['label_card_fee'] = '贺卡费用：';
$_LANG['label_money_paid'] = '已付款金额：';
$_LANG['label_surplus'] = '使用余额：';
$_LANG['label_integral'] = '使用积分：';
$_LANG['label_bonus'] = '使用红包：';
$_LANG['label_order_amount'] = '订单总金额：';
$_LANG['label_money_dues'] = '应付款金额：';
$_LANG['label_money_refund'] = '应退款金额：';
$_LANG['label_to_buyer'] = '商家给客户的留言：';
$_LANG['save_order'] = '保存订单';
$_LANG['notice_gb_order_amount'] = '（备注：团购如果有保证金，第一次只需支付保证金和相应的支付费用）';

$_LANG['action_user'] = '操作者：';
$_LANG['action_time'] = '操作时间';
$_LANG['order_status'] = '订单状态';
$_LANG['pay_status'] = '付款状态';
$_LANG['shipping_status'] = '发货状态';
$_LANG['action_note'] = '备注';
$_LANG['pay_note'] = '支付备注：';

$_LANG['sms_time_format'] = 'm月j日G时';
$_LANG['order_shipped_sms'] = '您的订单%s已于%s发货 [%s]';
$_LANG['order_splited_sms'] = '您的订单%s,%s正在%s [%s]';
$_LANG['order_removed'] = '订单删除成功。';
$_LANG['return_list'] = '返回订单列表';

/* 订单处理提示 */
$_LANG['surplus_not_enough'] = '该订单使用 %s 余额支付，现在用户余额不足';
$_LANG['integral_not_enough'] = '该订单使用 %s 积分支付，现在用户积分不足';
$_LANG['bonus_not_available'] = '该订单使用红包支付，现在红包不可用';

/* 购货人信息 */
$_LANG['display_buyer'] = '显示购货人信息';
$_LANG['buyer_info'] = '购货人信息';
$_LANG['pay_points'] = '消费积分';
$_LANG['rank_points'] = '等级积分';
$_LANG['user_money'] = '账户余额';
$_LANG['email'] = '电子邮件';
$_LANG['rank_name'] = '会员等级';
$_LANG['bonus_count'] = '红包数量';
$_LANG['zipcode'] = '邮编';
$_LANG['tel'] = '电话';
$_LANG['mobile'] = '备用电话';

/* 合并订单 */
$_LANG['order_sn_not_null'] = '请填写要合并的订单号';
$_LANG['two_order_sn_same'] = '要合并的两个订单号不能相同';
$_LANG['order_not_exist'] = '定单 %s 不存在';
$_LANG['os_not_unconfirmed_or_confirmed'] = '%s 的订单状态不是“未确认”或“已确认”';
$_LANG['ps_not_unpayed'] = '订单 %s 的付款状态不是“未付款”';
$_LANG['ss_not_unshipped'] = '订单 %s 的发货状态不是“未发货”';
$_LANG['order_user_not_same'] = '要合并的两个订单不是同一个用户下的';
$_LANG['merge_invalid_order'] = '对不起，您选择合并的订单不允许进行合并的操作。';

$_LANG['from_order_sn'] = '从订单：';
$_LANG['to_order_sn'] = '主订单：';
$_LANG['merge'] = '合并';
$_LANG['notice_order_sn'] = '当两个订单不一致时，合并后的订单信息（如：支付方式、配送方式、包装、贺卡、红包等）以主订单为准。';
$_LANG['js_languages']['confirm_merge'] = '您确实要合并这两个订单吗？';

/* 批处理 */
$_LANG['pls_select_order'] = '请选择您要操作的订单';
$_LANG['no_fulfilled_order'] = '没有满足操作条件的订单。';
$_LANG['updated_order'] = '更新的订单：';
$_LANG['order'] = '订单：';
$_LANG['confirm_order'] = '以下订单无法设置为确认状态';
$_LANG['invalid_order'] = '以下订单无法设置为无效';
$_LANG['cancel_order'] = '以下订单无法取消';
$_LANG['remove_order'] = '以下订单无法被移除';

/* 编辑订单打印模板 */
$_LANG['edit_order_templates'] = '编辑订单打印模板';
$_LANG['template_resetore'] = '还原模板';
$_LANG['edit_template_success'] = '编辑订单打印模板操作成功!';
$_LANG['remark_fittings'] = '（配件）';
$_LANG['remark_gift'] = '（赠品）';
$_LANG['remark_favourable'] = '（特惠品）';
$_LANG['remark_package'] = '（礼包）';

/* 订单来源统计 */
$_LANG['from_order'] = '订单来源：';
$_LANG['from_ad_js'] = '广告：';
$_LANG['from_goods_js'] = '商品站外JS投放';
$_LANG['from_self_site'] = '来自本站';
$_LANG['from'] = '来自站点：';

/* 添加、编辑订单 */
$_LANG['add_order'] = '添加订单';
$_LANG['edit_order'] = '编辑订单';
$_LANG['step']['user'] = '请选择您要为哪个会员下订单';
$_LANG['step']['goods'] = '选择商品';
$_LANG['step']['consignee'] = '设置收货人信息';
$_LANG['step']['shipping'] = '选择配送方式';
$_LANG['step']['payment'] = '选择支付方式';
$_LANG['step']['other'] = '设置其他信息';
$_LANG['step']['money'] = '设置费用';
$_LANG['anonymous'] = '匿名用户';
$_LANG['by_useridname'] = '按会员编号或会员名搜索';
$_LANG['button_prev'] = '上一步';
$_LANG['button_next'] = '下一步';
$_LANG['button_finish'] = '完成';
$_LANG['button_cancel'] = '取消';
$_LANG['name'] = '名称';
$_LANG['desc'] = '描述';
$_LANG['shipping_fee'] = '配送费';
$_LANG['free_money'] = '免费额度';
$_LANG['insure'] = '保价费';
$_LANG['pay_fee'] = '手续费';
$_LANG['pack_fee'] = '包装费';
$_LANG['card_fee'] = '贺卡费';
$_LANG['no_pack'] = '不要包装';
$_LANG['no_card'] = '不要贺卡';
$_LANG['add_to_order'] = '加入订单';
$_LANG['calc_order_amount'] = '计算订单金额';
$_LANG['available_surplus'] = '可用余额：';
$_LANG['available_integral'] = '可用积分：';
$_LANG['available_bonus'] = '可用红包：';
$_LANG['admin'] = '管理员添加';
$_LANG['search_goods'] = '按商品编号或商品名称或商品货号搜索';
$_LANG['category'] = '分类';
$_LANG['brand'] = '品牌';
$_LANG['user_money_not_enough'] = '用户余额不足';
$_LANG['pay_points_not_enough'] = '用户积分不足';
$_LANG['money_paid_enough'] = '已付款金额比商品总金额和各种费用之和还多，请先退款';
$_LANG['price_note'] = '备注：商品价格中已包含属性加价';
$_LANG['select_pack'] = '选择包装';
$_LANG['select_card'] = '选择贺卡';
$_LANG['select_shipping'] = '请先选择配送方式';
$_LANG['want_insure'] = '我要保价';
$_LANG['update_goods'] = '更新商品';
$_LANG['notice_user'] = '<strong>注意：</strong>搜索结果只显示前20条记录，如果没有找到相' .
        '应会员，请更精确地查找。另外，如果该会员是从论坛注册的且没有在商城登录过，' .
        '也无法找到，需要先在商城登录。';
$_LANG['amount_increase'] = '由于您修改了订单，导致订单总金额增加，需要再次付款';
$_LANG['amount_decrease'] = '由于您修改了订单，导致订单总金额减少，需要退款';
$_LANG['continue_shipping'] = '由于您修改了收货人所在地区，导致原来的配送方式不再可用，请重新选择配送方式';
$_LANG['continue_payment'] = '由于您修改了配送方式，导致原来的支付方式不再可用，请重新选择配送方式';
$_LANG['refund'] = '退款';
$_LANG['cannot_edit_order_shipped'] = '您不能修改已发货的订单';
$_LANG['address_list'] = '从已有收货地址中选择：';
$_LANG['order_amount_change'] = '订单总金额由 %s 变为 %s';
$_LANG['shipping_note'] = '说明：因为订单已发货，修改配送方式将不会改变配送费和保价费。';
$_LANG['change_use_surplus'] = '编辑订单 %s ，改变使用预付款支付的金额';
$_LANG['change_use_integral'] = '编辑订单 %s ，改变使用积分支付的数量';
$_LANG['return_order_surplus'] = '由于取消、无效或退货操作，退回支付订单 %s 时使用的预付款';
$_LANG['return_order_integral'] = '由于取消、无效或退货操作，退回支付订单 %s 时使用的积分';
$_LANG['order_gift_integral'] = '订单 %s 赠送的积分';
$_LANG['return_order_gift_integral'] = '由于退货或未发货操作，退回订单 %s 赠送的积分';
$_LANG['invoice_no_mall'] = '&nbsp;&nbsp;&nbsp;&nbsp;多个发货单号，请用英文逗号（“,”）隔开。';

$_LANG['js_languages']['input_price'] = '自定义价格';
$_LANG['js_languages']['pls_search_user'] = '请搜索并选择会员';
$_LANG['js_languages']['confirm_drop'] = '确认要删除该商品吗？';
$_LANG['js_languages']['invalid_goods_number'] = '商品数量不正确';
$_LANG['js_languages']['pls_search_goods'] = '请搜索并选择商品';
$_LANG['js_languages']['pls_select_area'] = '请完整选择所在地区';
$_LANG['js_languages']['pls_select_shipping'] = '请选择配送方式';
$_LANG['js_languages']['pls_select_payment'] = '请选择支付方式';
$_LANG['js_languages']['pls_select_pack'] = '请选择包装';
$_LANG['js_languages']['pls_select_card'] = '请选择贺卡';
$_LANG['js_languages']['pls_input_note'] = '请您填写备注！';
$_LANG['js_languages']['pls_input_cancel'] = '请您填写取消原因！';
$_LANG['js_languages']['pls_select_refund'] = '请选择退款方式！';
$_LANG['js_languages']['pls_select_agency'] = '请选择办事处！';
$_LANG['js_languages']['pls_select_other_agency'] = '该订单现在就属于这个办事处，请选择其他办事处！';
$_LANG['js_languages']['loading'] = '加载中...';

/* 订单操作 */
$_LANG['order_operate'] = '订单操作：';
$_LANG['label_refund_amount'] = '退款金额：';
$_LANG['label_handle_refund'] = '退款方式：';
$_LANG['label_refund_note'] = '退款说明：';
$_LANG['return_user_money'] = '退回用户余额';
$_LANG['create_user_account'] = '生成退款申请';
$_LANG['not_handle'] = '不处理，误操作时选择此项';

$_LANG['order_refund'] = '订单退款：%s';
$_LANG['order_pay'] = '订单支付：%s';

$_LANG['send_mail_fail'] = '发送邮件失败';

$_LANG['send_message'] = '发送/查看留言';

/* 发货单操作 */
$_LANG['delivery_operate'] = '发货单操作：';
$_LANG['delivery_sn_number'] = '发货单流水号：';
$_LANG['invoice_no_sms'] = '请填写发货单号！';

/* 发货单搜索 */
$_LANG['delivery_sn'] = '发货单';

/* 发货单状态 */
$_LANG['delivery_status'][0] = '已发货';
$_LANG['delivery_status'][1] = '退货';
$_LANG['delivery_status'][2] = '正常';

/* 发货单标签 */
$_LANG['label_delivery_status'] = '发货单状态';
$_LANG['label_suppliers_name'] = '供货商';
$_LANG['label_delivery_time'] = '生成时间';
$_LANG['label_delivery_sn'] = '发货单流水号';
$_LANG['label_add_time'] = '下单时间';
$_LANG['label_update_time'] = '发货时间';
$_LANG['label_send_number'] = '发货数量';

/* 发货单提示 */
$_LANG['tips_delivery_del'] = '发货单删除成功！';

/* 退货单操作 */
$_LANG['back_operate'] = '退货单操作：';

/* 退货单标签 */
$_LANG['return_time'] = '退货时间：';
$_LANG['label_return_time'] = '退货时间';

/* 退货单提示 */
$_LANG['tips_back_del'] = '退货单删除成功！';

$_LANG['goods_num_err'] = '库存不足，请重新选择！';
?>