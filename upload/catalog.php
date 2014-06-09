<?php

/**
 * ECSHOP 列出所有分类及品牌
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: catalog.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

if (!$smarty->is_cached('catalog.dwt'))
{
    /* 取出所有分类 */
    $cat_list = cat_list(0, 0, false);

    foreach ($cat_list AS $key=>$val)
    {
        if ($val['is_show'] == 0)
        {
            unset($cat_list[$key]);
        }
    }


    assign_template();
    assign_dynamic('catalog');
    $position = assign_ur_here(0, $_LANG['catalog']);
    $smarty->assign('page_title', $position['title']);   // 页面标题
    $smarty->assign('ur_here',    $position['ur_here']); // 当前位置

    $smarty->assign('helps',      get_shop_help()); // 网店帮助
    $smarty->assign('cat_list',   $cat_list);       // 分类列表
    $smarty->assign('brand_list', get_brands());    // 所以品牌赋值
    $smarty->assign('promotion_info', get_promotion_info());
}

$smarty->display('catalog.dwt');

/**
 * 计算指定分类的商品数量
 *
 * @access public
 * @param   integer     $cat_id
 *
 * @return void
 */
function calculate_goods_num($cat_list, $cat_id)
{
    $goods_num = 0;

    foreach ($cat_list AS $cat)
    {
        if ($cat['parent_id'] == $cat_id && !empty($cat['goods_num']))
        {
            $goods_num += $cat['goods_num'];
        }
    }

    return $goods_num;
}

?>