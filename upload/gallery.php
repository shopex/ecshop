<?php

/**
 * ECSHOP 商品相册
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: gallery.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* 参数 */
$_REQUEST['id']  = isset($_REQUEST['id'])  ? intval($_REQUEST['id'])  : 0; // 商品编号
$_REQUEST['img'] = isset($_REQUEST['img']) ? intval($_REQUEST['img']) : 0; // 图片编号

/* 获得商品名称 */
$sql = 'SELECT goods_name FROM ' . $ecs->table('goods') . "WHERE goods_id = '$_REQUEST[id]'";
$goods_name = $db->getOne($sql);

/* 如果该商品不存在，返回首页 */
if ($goods_name === false)
{
    ecs_header("Location: ./\n");

    exit;
}

/* 获得所有的图片 */
$sql = 'SELECT img_id, img_desc, thumb_url, img_url'.
       ' FROM ' .$ecs->table('goods_gallery').
       " WHERE goods_id = '$_REQUEST[id]' ORDER BY img_id";
$img_list = $db->getAll($sql);

$img_count = count($img_list);

$gallery = array('goods_name' => htmlspecialchars($goods_name, ENT_QUOTES), 'list' => array());
if ($img_count == 0)
{
    /* 如果没有图片，返回商品详情页 */
    ecs_header('Location: goods.php?id=' . $_REQUEST['id'] . "\n");
    exit;
}
else
{
    foreach ($img_list AS $key => $img)
    {
        $gallery['list'][] = array(
            'gallery_thumb' => get_image_path($_REQUEST['id'], $img_list[$key]['thumb_url'], true, 'gallery'),
            'gallery' => get_image_path($_REQUEST['id'], $img_list[$key]['img_url'], false, 'gallery'),
            'img_desc' => $img_list[$key]['img_desc']
        );
    }
}

$smarty->assign('shop_name',  $_CFG['shop_name']);
$smarty->assign('watermark', str_replace('../', './', $_CFG['watermark']));
$smarty->assign('gallery',  $gallery);
$smarty->display('gallery.dwt');

?>