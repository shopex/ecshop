<?php

/**
 * ECSHOP 首页文件
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

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);

$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";

if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap'))
{
    $Loaction = 'mobile/';

    if (!empty($Loaction))
    {
        ecs_header("Location: $Loaction\n");

        exit;
    }

}
/*------------------------------------------------------ */
//-- Shopex系统地址转换
/*------------------------------------------------------ */
if (!empty($_GET['gOo']))
{
    if (!empty($_GET['gcat']))
    {
        /* 商品分类。*/
        $Loaction = 'category.php?id=' . $_GET['gcat'];
    }
    elseif (!empty($_GET['acat']))
    {
        /* 文章分类。*/
        $Loaction = 'article_cat.php?id=' . $_GET['acat'];
    }
    elseif (!empty($_GET['goodsid']))
    {
        /* 商品详情。*/
        $Loaction = 'goods.php?id=' . $_GET['goodsid'];
    }
    elseif (!empty($_GET['articleid']))
    {
        /* 文章详情。*/
        $Loaction = 'article.php?id=' . $_GET['articleid'];
    }

    if (!empty($Loaction))
    {
        ecs_header("Location: $Loaction\n");

        exit;
    }
}

//判断是否有ajax请求
$act = !empty($_GET['act']) ? $_GET['act'] : '';
if ($act == 'cat_rec')
{
    $rec_array = array(1 => 'best', 2 => 'new', 3 => 'hot');
    $rec_type = !empty($_REQUEST['rec_type']) ? intval($_REQUEST['rec_type']) : '1';
    $cat_id = !empty($_REQUEST['cid']) ? intval($_REQUEST['cid']) : '0';
    include_once('includes/cls_json.php');
    $json = new JSON;
    $result   = array('error' => 0, 'content' => '', 'type' => $rec_type, 'cat_id' => $cat_id);

    $children = get_children($cat_id);
    $smarty->assign($rec_array[$rec_type] . '_goods',      get_category_recommend_goods($rec_array[$rec_type], $children));    // 推荐商品
    $smarty->assign('cat_rec_sign', 1);
    $result['content'] = $smarty->fetch('library/recommend_' . $rec_array[$rec_type] . '.lbi');
    die($json->encode($result));
}

/*------------------------------------------------------ */
//-- 判断是否存在缓存，如果存在则调用缓存，反之读取相应内容
/*------------------------------------------------------ */
/* 缓存编号 */
$cache_id = sprintf('%X', crc32($_SESSION['user_rank'] . '-' . $_CFG['lang']));

if (!$smarty->is_cached('index.dwt', $cache_id))
{
    assign_template();

    $position = assign_ur_here();
    $smarty->assign('page_title',      $position['title']);    // 页面标题
    $smarty->assign('ur_here',         $position['ur_here']);  // 当前位置

    /* meta information */
    $smarty->assign('keywords',        htmlspecialchars($_CFG['shop_keywords']));
    $smarty->assign('description',     htmlspecialchars($_CFG['shop_desc']));
    $smarty->assign('flash_theme',     $_CFG['flash_theme']);  // Flash轮播图片模板

    $smarty->assign('feed_url',        ($_CFG['rewrite'] == 1) ? 'feed.xml' : 'feed.php'); // RSS URL

    $smarty->assign('categories',      get_categories_tree()); // 分类树
    $smarty->assign('helps',           get_shop_help());       // 网店帮助
    $smarty->assign('top_goods',       get_top10());           // 销售排行

    $smarty->assign('best_goods',      get_recommend_goods('best'));    // 推荐商品
    $smarty->assign('new_goods',       get_recommend_goods('new'));     // 最新商品
    $smarty->assign('hot_goods',       get_recommend_goods('hot'));     // 热点文章
    $smarty->assign('promotion_goods', get_promote_goods()); // 特价商品
    $smarty->assign('brand_list',      get_brands());
    $smarty->assign('promotion_info',  get_promotion_info()); // 增加一个动态显示所有促销信息的标签栏

    $smarty->assign('invoice_list',    index_get_invoice_query());  // 发货查询
    $smarty->assign('new_articles',    index_get_new_articles());   // 最新文章
    $smarty->assign('group_buy_goods', index_get_group_buy());      // 团购商品
    $smarty->assign('auction_list',    index_get_auction());        // 拍卖活动
    $smarty->assign('shop_notice',     $_CFG['shop_notice']);       // 商店公告

    /* 首页主广告设置 */
    $smarty->assign('index_ad',     $_CFG['index_ad']);
    if ($_CFG['index_ad'] == 'cus')
    {
        $sql = 'SELECT ad_type, content, url FROM ' . $ecs->table("ad_custom") . ' WHERE ad_status = 1';
        $ad = $db->getRow($sql, true);
        $smarty->assign('ad', $ad);
    }

    /* links */
    $links = index_get_links();
    $smarty->assign('img_links',       $links['img']);
    $smarty->assign('txt_links',       $links['txt']);
    $smarty->assign('data_dir',        DATA_DIR);       // 数据目录

    /* 首页推荐分类 */
    $cat_recommend_res = $db->getAll("SELECT c.cat_id, c.cat_name, cr.recommend_type FROM " . $ecs->table("cat_recommend") . " AS cr INNER JOIN " . $ecs->table("category") . " AS c ON cr.cat_id=c.cat_id");
    if (!empty($cat_recommend_res))
    {
        $cat_rec_array = array();
        foreach($cat_recommend_res as $cat_recommend_data)
        {
            $cat_rec[$cat_recommend_data['recommend_type']][] = array('cat_id' => $cat_recommend_data['cat_id'], 'cat_name' => $cat_recommend_data['cat_name']);
        }
        $smarty->assign('cat_rec', $cat_rec);
    }

    /* 页面中的动态内容 */
    assign_dynamic('index');
}

$smarty->display('index.dwt', $cache_id);

/*------------------------------------------------------ */
//-- PRIVATE FUNCTIONS
/*------------------------------------------------------ */

/**
 * 调用发货单查询
 *
 * @access  private
 * @return  array
 */
function index_get_invoice_query()
{
    $sql = 'SELECT o.order_sn, o.invoice_no, s.shipping_code FROM ' . $GLOBALS['ecs']->table('order_info') . ' AS o' .
            ' LEFT JOIN ' . $GLOBALS['ecs']->table('shipping') . ' AS s ON s.shipping_id = o.shipping_id' .
            " WHERE invoice_no > '' AND shipping_status = " . SS_SHIPPED .
            ' ORDER BY shipping_time DESC LIMIT 10';
    $all = $GLOBALS['db']->getAll($sql);

    foreach ($all AS $key => $row)
    {
        $plugin = ROOT_PATH . 'includes/modules/shipping/' . $row['shipping_code'] . '.php';

        if (file_exists($plugin))
        {
            include_once($plugin);

            $shipping = new $row['shipping_code'];
            $all[$key]['invoice_no'] = $shipping->query((string)$row['invoice_no']);
        }
    }

    clearstatcache();

    return $all;
}

/**
 * 获得最新的文章列表。
 *
 * @access  private
 * @return  array
 */
function index_get_new_articles()
{
    $sql = 'SELECT a.article_id, a.title, ac.cat_name, a.add_time, a.file_url, a.open_type, ac.cat_id, ac.cat_name ' .
            ' FROM ' . $GLOBALS['ecs']->table('article') . ' AS a, ' .
                $GLOBALS['ecs']->table('article_cat') . ' AS ac' .
            ' WHERE a.is_open = 1 AND a.cat_id = ac.cat_id AND ac.cat_type = 1' .
            ' ORDER BY a.article_type DESC, a.add_time DESC LIMIT ' . $GLOBALS['_CFG']['article_number'];
    $res = $GLOBALS['db']->getAll($sql);

    $arr = array();
    foreach ($res AS $idx => $row)
    {
        $arr[$idx]['id']          = $row['article_id'];
        $arr[$idx]['title']       = $row['title'];
        $arr[$idx]['short_title'] = $GLOBALS['_CFG']['article_title_length'] > 0 ?
                                        sub_str($row['title'], $GLOBALS['_CFG']['article_title_length']) : $row['title'];
        $arr[$idx]['cat_name']    = $row['cat_name'];
        $arr[$idx]['add_time']    = local_date($GLOBALS['_CFG']['date_format'], $row['add_time']);
        $arr[$idx]['url']         = $row['open_type'] != 1 ?
                                        build_uri('article', array('aid' => $row['article_id']), $row['title']) : trim($row['file_url']);
        $arr[$idx]['cat_url']     = build_uri('article_cat', array('acid' => $row['cat_id']), $row['cat_name']);
    }

    return $arr;
}

/**
 * 获得最新的团购活动
 *
 * @access  private
 * @return  array
 */
function index_get_group_buy()
{
    $time = gmtime();
    $limit = get_library_number('group_buy', 'index');

    $group_buy_list = array();
    if ($limit > 0)
    {
        $sql = 'SELECT gb.act_id AS group_buy_id, gb.goods_id, gb.ext_info, gb.goods_name, g.goods_thumb, g.goods_img ' .
                'FROM ' . $GLOBALS['ecs']->table('goods_activity') . ' AS gb, ' .
                    $GLOBALS['ecs']->table('goods') . ' AS g ' .
                "WHERE gb.act_type = '" . GAT_GROUP_BUY . "' " .
                "AND g.goods_id = gb.goods_id " .
                "AND gb.start_time <= '" . $time . "' " .
                "AND gb.end_time >= '" . $time . "' " .
                "AND g.is_delete = 0 " .
                "ORDER BY gb.act_id DESC " .
                "LIMIT $limit" ;
        $res = $GLOBALS['db']->query($sql);

        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            /* 如果缩略图为空，使用默认图片 */
            $row['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
            $row['thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);

            /* 根据价格阶梯，计算最低价 */
            $ext_info = unserialize($row['ext_info']);
            $price_ladder = $ext_info['price_ladder'];
            if (!is_array($price_ladder) || empty($price_ladder))
            {
                $row['last_price'] = price_format(0);
            }
            else
            {
                foreach ($price_ladder AS $amount_price)
                {
                    $price_ladder[$amount_price['amount']] = $amount_price['price'];
                }
            }
            ksort($price_ladder);
            $row['last_price'] = price_format(end($price_ladder));
            $row['url'] = build_uri('group_buy', array('gbid' => $row['group_buy_id']));
            $row['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
                                           sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
            $row['short_style_name']   = add_style($row['short_name'],'');
            $group_buy_list[] = $row;
        }
    }

    return $group_buy_list;
}

/**
 * 取得拍卖活动列表
 * @return  array
 */
function index_get_auction()
{
    $now = gmtime();
    $limit = get_library_number('auction', 'index');
    $sql = "SELECT a.act_id, a.goods_id, a.goods_name, a.ext_info, g.goods_thumb ".
            "FROM " . $GLOBALS['ecs']->table('goods_activity') . " AS a," .
                      $GLOBALS['ecs']->table('goods') . " AS g" .
            " WHERE a.goods_id = g.goods_id" .
            " AND a.act_type = '" . GAT_AUCTION . "'" .
            " AND a.is_finished = 0" .
            " AND a.start_time <= '$now'" .
            " AND a.end_time >= '$now'" .
            " AND g.is_delete = 0" .
            " ORDER BY a.start_time DESC" .
            " LIMIT $limit";
    $res = $GLOBALS['db']->query($sql);

    $list = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $ext_info = unserialize($row['ext_info']);
        $arr = array_merge($row, $ext_info);
        $arr['formated_start_price'] = price_format($arr['start_price']);
        $arr['formated_end_price'] = price_format($arr['end_price']);
        $arr['thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr['url'] = build_uri('auction', array('auid' => $arr['act_id']));
        $arr['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
                                           sub_str($arr['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $arr['goods_name'];
        $arr['short_style_name']   = add_style($arr['short_name'],'');
        $list[] = $arr;
    }

    return $list;
}

/**
 * 获得所有的友情链接
 *
 * @access  private
 * @return  array
 */
function index_get_links()
{
    $sql = 'SELECT link_logo, link_name, link_url FROM ' . $GLOBALS['ecs']->table('friend_link') . ' ORDER BY show_order';
    $res = $GLOBALS['db']->getAll($sql);

    $links['img'] = $links['txt'] = array();

    foreach ($res AS $row)
    {
        if (!empty($row['link_logo']))
        {
            $links['img'][] = array('name' => $row['link_name'],
                                    'url'  => $row['link_url'],
                                    'logo' => $row['link_logo']);
        }
        else
        {
            $links['txt'][] = array('name' => $row['link_name'],
                                    'url'  => $row['link_url']);
        }
    }

    return $links;
}

?>