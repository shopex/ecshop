<?php

/**
 * ECSHOP 专题前台
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author:     webboy <laupeng@163.com>
 * @version:    v2.1
 * ---------------------------------------------
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}
$topic_id  = empty($_REQUEST['topic_id']) ? 0 : intval($_REQUEST['topic_id']);

$sql = "SELECT template FROM " . $ecs->table('topic') .
        "WHERE topic_id = '$topic_id' and  " . gmtime() . " >= start_time and " . gmtime() . "<= end_time";

$topic = $db->getRow($sql);

if(empty($topic))
{
    /* 如果没有找到任何记录则跳回到首页 */
    ecs_header("Location: ./\n");
    exit;
}

$templates = empty($topic['template']) ? 'topic.dwt' : $topic['template'];

$cache_id = sprintf('%X', crc32($_SESSION['user_rank'] . '-' . $_CFG['lang'] . '-' . $topic_id));

if (!$smarty->is_cached($templates, $cache_id))
{
    $sql = "SELECT * FROM " . $ecs->table('topic') . " WHERE topic_id = '$topic_id'";

    $topic = $db->getRow($sql);
    $topic['data'] = addcslashes($topic['data'], "'");
    $tmp = @unserialize($topic["data"]);
    $arr = (array)$tmp;

    $goods_id = array();

    foreach ($arr AS $key=>$value)
    {
        foreach($value AS $k => $val)
        {
            $opt = explode('|', $val);
            $arr[$key][$k] = $opt[1];
            $goods_id[] = $opt[1];
        }
    }

    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, " .
                'g.promote_start_date, g.promote_end_date, g.goods_brief, g.goods_thumb , g.goods_img ' .
                'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
                'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
                "WHERE " . db_create_in($goods_id, 'g.goods_id');

    $res = $GLOBALS['db']->query($sql);

    $sort_goods_arr = array();

    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['promote_price'] > 0)
        {
            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            $row['promote_price'] = $promote_price > 0 ? price_format($promote_price) : '';
        }
        else
        {
            $row['promote_price'] = '';
        }

        if ($row['shop_price'] > 0)
        {
            $row['shop_price'] =  price_format($row['shop_price']);
        }
        else
        {
            $row['shop_price'] = '';
        }

        $row['url']              = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);
        $row['goods_style_name'] = add_style($row['goods_name'], $row['goods_name_style']);
        $row['short_name']       = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
                                    sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        $row['goods_thumb']      = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $row['short_style_name'] = add_style($row['short_name'], $row['goods_name_style']);

        foreach ($arr AS $key => $value)
        {
            foreach ($value AS $val)
            {
                if ($val == $row['goods_id'])
                {
                    $key = $key == 'default' ? $_LANG['all_goods'] : $key;
                    $sort_goods_arr[$key][] = $row;
                }
            }
        }
    }

    /* 模板赋值 */
    assign_template();
    $position = assign_ur_here();
    $smarty->assign('page_title',       $position['title']);       // 页面标题
    $smarty->assign('ur_here',          $position['ur_here'] . '> ' . $topic['title']);     // 当前位置
    $smarty->assign('show_marketprice', $_CFG['show_marketprice']);
    $smarty->assign('sort_goods_arr',   $sort_goods_arr);          // 商品列表
    $smarty->assign('topic',            $topic);                   // 专题信息
    $smarty->assign('keywords',         $topic['keywords']);       // 专题信息
    $smarty->assign('description',      $topic['description']);    // 专题信息
    $smarty->assign('title_pic',        $topic['title_pic']);      // 分类标题图片地址
    $smarty->assign('base_style',       '#' . $topic['base_style']);     // 基本风格样式颜色

    $template_file = empty($topic['template']) ? 'topic.dwt' : $topic['template'];
}
/* 显示模板 */
$smarty->display($templates, $cache_id);

?>