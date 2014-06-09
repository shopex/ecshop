<?php

/**
 * ECSHOP 管理中心模板管理语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: template.php 17217 2011-01-19 06:29:08Z liubo $
*/

$_LANG['template_manage'] = '模板管理';
$_LANG['current_template'] = '当前模板';
$_LANG['available_templates'] = '可用模板';
$_LANG['select_template'] = '请选择一个模板：';
$_LANG['select_library'] = '请选择一个库项目：';
$_LANG['library_name'] = '库项目';
$_LANG['region_name'] = '区域';
$_LANG['sort_order'] = '序号';
$_LANG['contents'] = '内容';
$_LANG['number'] = '数量';
$_LANG['display'] = '显示';
$_LANG['select_plz'] = '请选择...';
$_LANG['button_restore'] = '还原到上一修改';

/* 提示信息 */
$_LANG['library_not_written'] = '库文件 %s 没有修改权限，该库文件将无法修改';
$_LANG['install_template_success'] = '启用模板成功。';
$_LANG['setup_success'] = '设置模板内容成功。';
$_LANG['modify_dwt_failed'] = '模板文件 %s 无法修改';
$_LANG['update_lib_success'] = '库项目内容已经更新成功。';
$_LANG['update_lib_failed'] = '编辑库项目内容失败。请检查 %s 目录是否可以写入。';
$_LANG['backup_success'] = "所有模板文件已备份到templates/backup目录下。\n您现在要下载备份文件吗？。";
$_LANG['backup_failed'] = '备份模板文件失败，请检查templates/backup 目录是否可以写入。';
$_LANG['not_editable'] = '非可编辑区库文件无选择项';

/* 每一个模板文件对应的语言 */
$_LANG['template_files']['article'] = '文章内容模板';
$_LANG['template_files']['article_cat'] = '文章分类模板';
$_LANG['template_files']['brand'] = '品牌专区';
//$_LANG['template_files']['catalog'] = '所有分类页';
$_LANG['template_files']['category'] = '商品分类页模板';
$_LANG['template_files']['flow'] = '购物流程模板';
$_LANG['template_files']['goods'] = '商品详情模板';
$_LANG['template_files']['group_buy_goods'] = '团购商品详情模板';
$_LANG['template_files']['group_buy_list'] = '团购商品列表模板';
$_LANG['template_files']['index'] = '首页模板';
$_LANG['template_files']['search'] = '商品搜索模板';
$_LANG['template_files']['compare'] = '商品比较模板';
$_LANG['template_files']['snatch'] = '夺宝奇兵';
$_LANG['template_files']['tag_cloud'] = '标签云模板';
$_LANG['template_files']['brand'] = '商品品牌页';
$_LANG['template_files']['auction_list'] = '拍卖活动列表';
$_LANG['template_files']['auction'] = '拍卖活动详情';
$_LANG['template_files']['message_board'] = '留言板';
//$_LANG['template_files']['quotation'] = '报价单';
$_LANG['template_files']['exchange_list'] = '积分商城列表';

/* 每一个库项目的描述 */
$_LANG['template_libs']['ad_position'] = '广告位';
$_LANG['template_libs']['index_ad'] = '首页主广告位';
$_LANG['template_libs']['cat_articles'] = '文章列表';
$_LANG['template_libs']['articles'] = '文章列表';
$_LANG['template_libs']['goods_attrlinked'] = '属性关联的商品';
$_LANG['template_libs']['recommend_best'] = '精品推荐';
$_LANG['template_libs']['recommend_promotion'] = '促销商品';
$_LANG['template_libs']['recommend_hot'] = '热卖商品';
$_LANG['template_libs']['recommend_new'] = '新品上架';
$_LANG['template_libs']['bought_goods'] = '购买过此商品的人还买过的商品';
$_LANG['template_libs']['bought_note_guide'] = '购买记录';
$_LANG['template_libs']['brand_goods'] = '品牌的商品';
$_LANG['template_libs']['brands'] = '品牌专区';
$_LANG['template_libs']['cart'] = '购物车';
$_LANG['template_libs']['cat_goods'] = '分类下的商品';
$_LANG['template_libs']['category_tree'] = '商品分类树';
$_LANG['template_libs']['comments'] = '用户评论列表';
$_LANG['template_libs']['consignee'] = '收货地址表单';
$_LANG['template_libs']['goods_fittings'] = '相关配件';
$_LANG['template_libs']['page_footer'] = '页脚';
$_LANG['template_libs']['goods_gallery'] = '商品相册';
$_LANG['template_libs']['goods_article'] = '相关文章';
$_LANG['template_libs']['goods_list'] = '商品列表';
$_LANG['template_libs']['goods_tags'] = '商品标记';
$_LANG['template_libs']['group_buy'] = '团购商品';
$_LANG['template_libs']['group_buy_fee'] = '团购商品费用总计';
$_LANG['template_libs']['help'] = '帮助内容';
$_LANG['template_libs']['history'] = '商品浏览历史';
$_LANG['template_libs']['comments_list'] = '评论内容';
$_LANG['template_libs']['invoice_query'] = '发货单查询';
$_LANG['template_libs']['member'] = '会员区';
$_LANG['template_libs']['member_info'] = '会员信息';
$_LANG['template_libs']['new_articles'] = '最新文章';
$_LANG['template_libs']['order_total'] = '订单费用总计';
$_LANG['template_libs']['page_header'] = '页面顶部';
$_LANG['template_libs']['pages'] = '列表分页';
$_LANG['template_libs']['goods_related'] = '相关商品';
$_LANG['template_libs']['search_form'] = '搜索表单';
$_LANG['template_libs']['signin'] = '登录表单';
$_LANG['template_libs']['snatch'] = '夺宝奇兵出价';
$_LANG['template_libs']['snatch_price'] = '夺宝奇兵最新出价';
$_LANG['template_libs']['top10'] = '销售排行';
$_LANG['template_libs']['ur_here'] = '当前位置';
$_LANG['template_libs']['user_menu'] = '用户中心菜单';
$_LANG['template_libs']['vote'] = '调查';
$_LANG['template_libs']['auction'] = '拍卖商品';
$_LANG['template_libs']['article_category_tree'] = '文章分类树';
$_LANG['template_libs']['order_query'] = '前台订单状态查询';
$_LANG['template_libs']['email_list'] = '前台邮件订阅';
$_LANG['template_libs']['vote_list'] = '在线调查';
$_LANG['template_libs']['price_grade'] = '价格范围';
$_LANG['template_libs']['filter_attr'] = '属性筛选';
$_LANG['template_libs']['promotion_info'] = '促销信息';
$_LANG['template_libs']['categorys'] = '商品分类';
$_LANG['template_libs']['myship'] = '配送方式';
$_LANG['template_libs']['online'] = '统计在线人数';
$_LANG['template_libs']['relatetag'] = '其他应用关联标签数据';
$_LANG['template_libs']['message_list'] = '留言列表';
$_LANG['template_libs']['exchange_hot'] = '积分商城热卖商品';
$_LANG['template_libs']['exchange_list'] = '积分商城列表商品';

/* 模板布局备份 */
$_LANG['backup_setting'] = '备份模板设置';
$_LANG['cur_setting_template'] = '当前可备份的模板设置';
$_LANG['no_setting_template'] = '没有可备份的模板设置';
$_LANG['cur_backup'] = '可使用的模板设置备份';
$_LANG['no_backup'] = '没有模板设置备份';
$_LANG['remarks'] = '备份注释';
$_LANG['backup_setting'] = '备份模板设置';
$_LANG['select_all'] = '全选';
$_LANG['remarks_exist'] = '备份注释 %s 已经用过，请换个注释名称';
$_LANG['backup_template_ok'] = '备份设置成功';
$_LANG['del_backup_ok'] = '备份删除成功';
$_LANG['restore_backup_ok'] = '恢复备份成功';

/* JS 语言项 */
$_LANG['js_languages']['setupConfirm'] = '启用新的模板将覆盖原来的模板。\n您确定要启用选定的模板吗？';
$_LANG['js_languages']['reinstall'] = '重新安装当前模板';
$_LANG['backup'] = '备份当前模板';
$_LANG['js_languages']['selectPlease'] = '请选择...';
$_LANG['js_languages']['removeConfirm'] = '您确定要删除选定的内容吗？';
$_LANG['js_languages']['empty_content'] = '对不起，库项目的内容不能为空。';
$_LANG['js_languages']['save_confirm'] = '您已经修改了模板内容，您确定不保存么？';

?>