<?php

/**
 * ECSHOP 商品批量上传、修改语言文件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: goods_batch.php 17217 2011-01-19 06:29:08Z liubo $
 */

$_LANG['select_method'] = '选择商品的方式：';
$_LANG['by_cat'] = '根据商品分类、品牌';
$_LANG['by_sn'] = '根据商品货号';
$_LANG['select_cat'] = '选择商品分类：';
$_LANG['select_brand'] = '选择商品品牌：';
$_LANG['goods_list'] = '商品列表：';
$_LANG['src_list'] = '待选列表：';
$_LANG['dest_list'] = '选定列表：';
$_LANG['input_sn'] = '输入商品货号：<br />（每行一个）';
$_LANG['edit_method'] = '编辑方式：';
$_LANG['edit_each'] = '逐个编辑';
$_LANG['edit_all'] = '统一编辑';
$_LANG['go_edit'] = '进入编辑';

$_LANG['notice_edit'] = '会员价格为-1表示会员价格将根据会员等级折扣比例计算';

$_LANG['goods_class'] = '商品类别';
$_LANG['g_class'][G_REAL] = '实体商品';
$_LANG['g_class'][G_CARD] = '虚拟卡';

$_LANG['goods_sn'] = '货号';
$_LANG['goods_name'] = '商品名称';
$_LANG['market_price'] = '市场价格';
$_LANG['shop_price'] = '本店价格';
$_LANG['integral'] = '积分购买';
$_LANG['give_integral'] = '赠送积分';
$_LANG['goods_number'] = '库存';
$_LANG['brand'] = '品牌';

$_LANG['batch_edit_ok'] = '批量修改成功';

$_LANG['export_format'] = '数据格式';
$_LANG['export_ecshop'] = 'ecshop支持数据格式';
$_LANG['export_taobao'] = '淘宝助理支持数据格式';
$_LANG['export_taobao46'] = '淘宝助理4.6支持数据格式';
$_LANG['export_paipai'] = '拍拍助理支持数据格式';
$_LANG['export_paipai3'] = '拍拍助理3.0支持数据格式';
$_LANG['goods_cat'] = '所属分类：';
$_LANG['csv_file'] = '上传批量csv文件：';
$_LANG['notice_file'] = '（CSV文件中一次上传商品数量最好不要超过1000，CSV文件大小最好不要超过500K.）';
$_LANG['file_charset'] = '文件编码：';
$_LANG['download_file'] = '下载批量CSV文件（%s）';
$_LANG['use_help'] = '使用说明：' .
        '<ol>' .
          '<li>根据使用习惯，下载相应语言的csv文件，例如中国内地用户下载简体中文语言的文件，港台用户下载繁体语言的文件；</li>' .
          '<li>填写csv文件，可以使用excel或文本编辑器打开csv文件；<br />' .
              '碰到“是否精品”之类，填写数字0或者1，0代表“否”，1代表“是”；<br />' .
              '商品图片和商品缩略图请填写带路径的图片文件名，其中路径是相对于 [根目录]/images/ 的路径，例如图片路径为[根目录]/images/200610/abc.jpg，只要填写 200610/abc.jpg 即可；<br />' .
               '<font style="color:#FE596A;">如果是淘宝助理格式请确保cvs编码为在网站的编码，如编码不正确，可以用编辑软件转换编码。</font></li>' .
          '<li>将填写的商品图片和商品缩略图上传到相应目录，例如：[根目录]/images/200610/；<br />'.
              '<font style="color:#FE596A;">请首先上传商品图片和商品缩略图再上传csv文件，否则图片无法处理。</font></li>' .
          '<li>选择所上传商品的分类以及文件编码，上传csv文件</li>' .
        '</ol>';

$_LANG['js_languages']['please_select_goods'] = '请您选择商品';
$_LANG['js_languages']['please_input_sn'] = '请您输入商品货号';
$_LANG['js_languages']['goods_cat_not_leaf'] = '请选择底级分类';
$_LANG['js_languages']['please_select_cat'] = '请您选择所属分类';
$_LANG['js_languages']['please_upload_file'] = '请您上传批量csv文件';

// 批量上传商品的字段
$_LANG['upload_goods']['goods_name'] = '商品名称';
$_LANG['upload_goods']['goods_sn'] = '商品货号';
$_LANG['upload_goods']['brand_name'] = '商品品牌';   // 需要转换成brand_id
$_LANG['upload_goods']['market_price'] = '市场售价';
$_LANG['upload_goods']['shop_price'] = '本店售价';
$_LANG['upload_goods']['integral'] = '积分购买额度';
$_LANG['upload_goods']['original_img'] = '商品原始图';
$_LANG['upload_goods']['goods_img'] = '商品图片';
$_LANG['upload_goods']['goods_thumb'] = '商品缩略图';
$_LANG['upload_goods']['keywords'] = '商品关键词';
$_LANG['upload_goods']['goods_brief'] = '简单描述';
$_LANG['upload_goods']['goods_desc'] = '详细描述';
$_LANG['upload_goods']['goods_weight'] = '商品重量（kg）';
$_LANG['upload_goods']['goods_number'] = '库存数量';
$_LANG['upload_goods']['warn_number'] = '库存警告数量';
$_LANG['upload_goods']['is_best'] = '是否精品';
$_LANG['upload_goods']['is_new'] = '是否新品';
$_LANG['upload_goods']['is_hot'] = '是否热销';
$_LANG['upload_goods']['is_on_sale'] = '是否上架';
$_LANG['upload_goods']['is_alone_sale'] = '能否作为普通商品销售';
$_LANG['upload_goods']['is_real'] = '是否实体商品';

$_LANG['batch_upload_ok'] = '批量上传成功';
$_LANG['goods_upload_confirm'] = '批量上传确认';
?>