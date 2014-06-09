<?php

/**
 * ECSHOP WAP首页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: index.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);
define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');

$best_goods = get_recommend_goods('best');
$best_num = count($best_goods);
$smarty->assign('best_num' , $best_num);
if ($best_num > 0)
{
    $i = 0;
    foreach  ($best_goods as $key => $best_data)
    {
        $best_goods[$key]['shop_price'] = encode_output($best_data['shop_price']);
        $best_goods[$key]['name'] = encode_output($best_data['name']);
        /*if ($i > 2)
        {
            break;
        }*/
        $i++;
    }
    $smarty->assign('best_goods' , $best_goods);
}

$promote_goods = get_promote_goods();
$promote_num = count($promote_goods);
$smarty->assign('promote_num' , $promote_num);
if ($promote_num > 0)
{
    $i = 0;
    foreach ($promote_goods as $key => $promote_data)
    {
        $promote_goods[$key]['shop_price'] = encode_output($promote_data['shop_price']);
        $promote_goods[$key]['name'] = encode_output($promote_data['name']);
        /*if ($i > 2)
        {
            break;
        }*/
        $i++;
    }
    $smarty->assign('promote_goods' , $promote_goods);
}

$pcat_array = get_categories_tree();
foreach ($pcat_array as $key => $pcat_data)
{
    $pcat_array[$key]['name'] = encode_output($pcat_data['name']);
}
$smarty->assign('pcat_array' , $pcat_array);
$brands_array = get_brands();
if (!empty($brands_array))
{
    foreach ($brands_array as $key => $brands_data)
    {
        $brands_array[$key]['brand_name'] = encode_output($brands_data['brand_name']);
    }
    $smarty->assign('brand_array', $brands_array);
}

$article_array = $db->GetALLCached("SELECT article_id, title FROM " . $ecs->table("article") . " WHERE cat_id != 0 AND is_open = 1 AND open_type = 0 ORDER BY article_id DESC LIMIT 0,4");
if (!empty($article_array))
{
    foreach ($article_array as $key => $article_data)
    {
        $article_array[$key]['title'] = encode_output($article_data['title']);
    }
    $smarty->assign('article_array', $article_array);
}
if ($_SESSION['user_id'] > 0)
{
    $smarty->assign('user_name', $_SESSION['user_name']);
}

$smarty->assign('wap_logo', $_CFG['wap_logo']);
$smarty->assign('footer', get_footer());
$smarty->display("index.wml");

?>
