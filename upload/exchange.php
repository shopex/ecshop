<?php

/**
 * ECSHOP 积分商城
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: exchange.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

/*------------------------------------------------------ */
//-- act 操作项的初始化
/*------------------------------------------------------ */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}

/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

/*------------------------------------------------------ */
//-- 积分兑换商品列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 初始化分页信息 */
    $page         = isset($_REQUEST['page'])   && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
    $size         = isset($_CFG['page_size'])  && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;
    $cat_id       = isset($_REQUEST['cat_id']) && intval($_REQUEST['cat_id']) > 0 ? intval($_REQUEST['cat_id']) : 0;
    $integral_max = isset($_REQUEST['integral_max']) && intval($_REQUEST['integral_max']) > 0 ? intval($_REQUEST['integral_max']) : 0;
    $integral_min = isset($_REQUEST['integral_min']) && intval($_REQUEST['integral_min']) > 0 ? intval($_REQUEST['integral_min']) : 0;

    /* 排序、显示方式以及类型 */
    $default_display_type      = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');
    $default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
    $default_sort_order_type   = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'exchange_integral' : 'last_update');

    $sort    = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'exchange_integral', 'last_update'))) ? trim($_REQUEST['sort'])  : $default_sort_order_type;
    $order   = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC')))                              ? trim($_REQUEST['order']) : $default_sort_order_method;
    $display = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display'])  : (isset($_COOKIE['ECS']['display']) ? $_COOKIE['ECS']['display'] : $default_display_type);
    $display  = in_array($display, array('list', 'grid', 'text')) ? $display : 'text';
    setcookie('ECS[display]', $display, gmtime() + 86400 * 7);

    /* 页面的缓存ID */
    $cache_id = sprintf('%X', crc32($cat_id . '-' . $display . '-' . $sort  .'-' . $order  .'-' . $page . '-' . $size . '-' . $_SESSION['user_rank'] . '-' .
        $_CFG['lang'] . '-' . $integral_max . '-' .$integral_min));

    if (!$smarty->is_cached('exchange.dwt', $cache_id))
    {
        /* 如果页面没有被缓存则重新获取页面的内容 */

        $children = get_children($cat_id);

        $cat = get_cat_info($cat_id);   // 获得分类的相关信息

        if (!empty($cat))
        {
            $smarty->assign('keywords',    htmlspecialchars($cat['keywords']));
            $smarty->assign('description', htmlspecialchars($cat['cat_desc']));
        }

        assign_template();

        $position = assign_ur_here('exchange');
        $smarty->assign('page_title',       $position['title']);    // 页面标题
        $smarty->assign('ur_here',          $position['ur_here']);  // 当前位置

        $smarty->assign('categories',       get_categories_tree());        // 分类树
        $smarty->assign('helps',            get_shop_help());              // 网店帮助
        $smarty->assign('top_goods',        get_top10());                  // 销售排行
        $smarty->assign('promotion_info',   get_promotion_info());         // 促销活动信息

        /* 调查 */
        $vote = get_vote();
        if (!empty($vote))
        {
            $smarty->assign('vote_id',     $vote['id']);
            $smarty->assign('vote',        $vote['content']);
        }

        $ext = ''; //商品查询条件扩展

        //$smarty->assign('best_goods',      get_exchange_recommend_goods('best', $children, $integral_min, $integral_max));
        //$smarty->assign('new_goods',       get_exchange_recommend_goods('new',  $children, $integral_min, $integral_max));
        $smarty->assign('hot_goods',       get_exchange_recommend_goods('hot',  $children, $integral_min, $integral_max));


        $count = get_exchange_goods_count($children, $integral_min, $integral_max);
        $max_page = ($count> 0) ? ceil($count / $size) : 1;
        if ($page > $max_page)
        {
            $page = $max_page;
        }
        $goodslist = exchange_get_goods($children, $integral_min, $integral_max, $ext, $size, $page, $sort, $order);
        if($display == 'grid')
        {
            if(count($goodslist) % 2 != 0)
            {
                $goodslist[] = array();
            }
        }
        $smarty->assign('goods_list',       $goodslist);
        $smarty->assign('category',         $cat_id);
        $smarty->assign('integral_max',     $integral_max);
        $smarty->assign('integral_min',     $integral_min);


        assign_pager('exchange',            $cat_id, $count, $size, $sort, $order, $page, '', '', $integral_min, $integral_max, $display); // 分页
        assign_dynamic('exchange_list'); // 动态内容
    }

    $smarty->assign('feed_url',         ($_CFG['rewrite'] == 1) ? "feed-typeexchange.xml" : 'feed.php?type=exchange'); // RSS URL
    $smarty->display('exchange_list.dwt', $cache_id);
}

/*------------------------------------------------------ */
//-- 积分兑换商品详情
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'view')
{
    $goods_id = isset($_REQUEST['id'])  ? intval($_REQUEST['id']) : 0;

    $cache_id = $goods_id . '-' . $_SESSION['user_rank'] . '-' . $_CFG['lang'] . '-exchange';
    $cache_id = sprintf('%X', crc32($cache_id));

    if (!$smarty->is_cached('exchange_goods.dwt', $cache_id))
    {
        $smarty->assign('image_width',  $_CFG['image_width']);
        $smarty->assign('image_height', $_CFG['image_height']);
        $smarty->assign('helps',        get_shop_help()); // 网店帮助
        $smarty->assign('id',           $goods_id);
        $smarty->assign('type',         0);
        $smarty->assign('cfg',          $_CFG);

        /* 获得商品的信息 */
        $goods = get_exchange_goods_info($goods_id);

        if ($goods === false)
        {
            /* 如果没有找到任何记录则跳回到首页 */
            ecs_header("Location: ./\n");
            exit;
        }
        else
        {
            if ($goods['brand_id'] > 0)
            {
                $goods['goods_brand_url'] = build_uri('brand', array('bid'=>$goods['brand_id']), $goods['goods_brand']);
            }

            $goods['goods_style_name'] = add_style($goods['goods_name'], $goods['goods_name_style']);

            $smarty->assign('goods',              $goods);
            $smarty->assign('goods_id',           $goods['goods_id']);
            $smarty->assign('categories',         get_categories_tree());  // 分类树

            /* meta */
            $smarty->assign('keywords',           htmlspecialchars($goods['keywords']));
            $smarty->assign('description',        htmlspecialchars($goods['goods_brief']));

            assign_template();

            /* 上一个商品下一个商品 */
            $sql = "SELECT eg.goods_id FROM " .$ecs->table('exchange_goods'). " AS eg," . $GLOBALS['ecs']->table('goods') . " AS g WHERE eg.goods_id = g.goods_id AND eg.goods_id > " . $goods['goods_id'] . " AND eg.is_exchange = 1 AND g.is_delete = 0 LIMIT 1";
            $prev_gid = $db->getOne($sql);
            if (!empty($prev_gid))
            {
                $prev_good['url'] = build_uri('exchange_goods', array('gid' => $prev_gid), $goods['goods_name']);
                $smarty->assign('prev_good', $prev_good);//上一个商品
            }

            $sql = "SELECT max(eg.goods_id) FROM " . $ecs->table('exchange_goods') . " AS eg," . $GLOBALS['ecs']->table('goods') . " AS g WHERE eg.goods_id = g.goods_id AND eg.goods_id < ".$goods['goods_id'] . " AND eg.is_exchange = 1 AND g.is_delete = 0";
            $next_gid = $db->getOne($sql);
            if (!empty($next_gid))
            {
                $next_good['url'] = build_uri('exchange_goods', array('gid' => $next_gid), $goods['goods_name']);
                $smarty->assign('next_good', $next_good);//下一个商品
            }

            /* current position */
            $position = assign_ur_here('exchange', $goods['goods_name']);
            $smarty->assign('page_title',          $position['title']);                    // 页面标题
            $smarty->assign('ur_here',             $position['ur_here']);                  // 当前位置

            $properties = get_goods_properties($goods_id);  // 获得商品的规格和属性
            $smarty->assign('properties',          $properties['pro']);                              // 商品属性
            $smarty->assign('specification',       $properties['spe']);                              // 商品规格

            $smarty->assign('pictures',            get_goods_gallery($goods_id));                    // 商品相册

            assign_dynamic('exchange_goods');
        }
    }

    $smarty->display('exchange_goods.dwt',      $cache_id);
}

/*------------------------------------------------------ */
//--  兑换
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'buy')
{
    /* 查询：判断是否登录 */
    if (!isset($back_act) && isset($GLOBALS['_SERVER']['HTTP_REFERER']))
    {
        $back_act = strpos($GLOBALS['_SERVER']['HTTP_REFERER'], 'exchange') ? $GLOBALS['_SERVER']['HTTP_REFERER'] : './index.php';
    }

    /* 查询：判断是否登录 */
    if ($_SESSION['user_id'] <= 0)
    {
        show_message($_LANG['eg_error_login'], array($_LANG['back_up_page']), array($back_act), 'error');
    }

    /* 查询：取得参数：商品id */
    $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
    if ($goods_id <= 0)
    {
        ecs_header("Location: ./\n");
        exit;
    }

    /* 查询：取得兑换商品信息 */
    $goods = get_exchange_goods_info($goods_id);
    if (empty($goods))
    {
        ecs_header("Location: ./\n");
        exit;
    }
    /* 查询：检查兑换商品是否有库存 */
    if($goods['goods_number'] == 0 && $_CFG['use_storage'] == 1)
    {
        show_message($_LANG['eg_error_number'], array($_LANG['back_up_page']), array($back_act), 'error');
    }
    /* 查询：检查兑换商品是否是取消 */
    if ($goods['is_exchange'] == 0)
    {
        show_message($_LANG['eg_error_status'], array($_LANG['back_up_page']), array($back_act), 'error');
    }

    $user_info   = get_user_info($_SESSION['user_id']);
    $user_points = $user_info['pay_points']; // 用户的积分总数
    if ($goods['exchange_integral'] > $user_points)
    {
        show_message($_LANG['eg_error_integral'], array($_LANG['back_up_page']), array($back_act), 'error');
    }

    /* 查询：取得规格 */
    $specs = '';
    foreach ($_POST as $key => $value)
    {
        if (strpos($key, 'spec_') !== false)
        {
            $specs .= ',' . intval($value);
        }
    }
    $specs = trim($specs, ',');

    /* 查询：如果商品有规格则取规格商品信息 配件除外 */
    if (!empty($specs))
    {
        $_specs = explode(',', $specs);

        $product_info = get_products_info($goods_id, $_specs);
    }
    if (empty($product_info))
    {
        $product_info = array('product_number' => '', 'product_id' => 0);
    }

    //查询：商品存在规格 是货品 检查该货品库存
    if((!empty($specs)) && ($product_info['product_number'] == 0) && ($_CFG['use_storage'] == 1))
    {
        show_message($_LANG['eg_error_number'], array($_LANG['back_up_page']), array($back_act), 'error');
    }

    /* 查询：查询规格名称和值，不考虑价格 */
    $attr_list = array();
    $sql = "SELECT a.attr_name, g.attr_value " .
            "FROM " . $ecs->table('goods_attr') . " AS g, " .
                $ecs->table('attribute') . " AS a " .
            "WHERE g.attr_id = a.attr_id " .
            "AND g.goods_attr_id " . db_create_in($specs);
    $res = $db->query($sql);
    while ($row = $db->fetchRow($res))
    {
        $attr_list[] = $row['attr_name'] . ': ' . $row['attr_value'];
    }
    $goods_attr = join(chr(13) . chr(10), $attr_list);

    /* 更新：清空购物车中所有团购商品 */
    include_once(ROOT_PATH . 'includes/lib_order.php');
    clear_cart(CART_EXCHANGE_GOODS);

    /* 更新：加入购物车 */
    $number = 1;
    $cart = array(
        'user_id'        => $_SESSION['user_id'],
        'session_id'     => SESS_ID,
        'goods_id'       => $goods['goods_id'],
        'product_id'     => $product_info['product_id'],
        'goods_sn'       => addslashes($goods['goods_sn']),
        'goods_name'     => addslashes($goods['goods_name']),
        'market_price'   => $goods['market_price'],
        'goods_price'    => 0,//$goods['exchange_integral']
        'goods_number'   => $number,
        'goods_attr'     => addslashes($goods_attr),
        'goods_attr_id'  => $specs,
        'is_real'        => $goods['is_real'],
        'extension_code' => addslashes($goods['extension_code']),
        'parent_id'      => 0,
        'rec_type'       => CART_EXCHANGE_GOODS,
        'is_gift'        => 0
    );
    $db->autoExecute($ecs->table('cart'), $cart, 'INSERT');

    /* 记录购物流程类型：团购 */
    $_SESSION['flow_type'] = CART_EXCHANGE_GOODS;
    $_SESSION['extension_code'] = 'exchange_goods';
    $_SESSION['extension_id'] = $goods_id;

    /* 进入收货人页面 */
    ecs_header("Location: ./flow.php?step=consignee\n");
    exit;
}

/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 * 获得分类的信息
 *
 * @param   integer $cat_id
 *
 * @return  void
 */
function get_cat_info($cat_id)
{
    return $GLOBALS['db']->getRow('SELECT keywords, cat_desc, style, grade, filter_attr, parent_id FROM ' . $GLOBALS['ecs']->table('category') .
        " WHERE cat_id = '$cat_id'");
}

/**
 * 获得分类下的商品
 *
 * @access  public
 * @param   string  $children
 * @return  array
 */
function exchange_get_goods($children, $min, $max, $ext, $size, $page, $sort, $order)
{
    $display = $GLOBALS['display'];
    $where = "eg.is_exchange = 1 AND g.is_delete = 0 AND ".
             "($children OR " . get_extension_goods($children) . ')';

    if ($min > 0)
    {
        $where .= " AND eg.exchange_integral >= $min ";
    }

    if ($max > 0)
    {
        $where .= " AND eg.exchange_integral <= $max ";
    }

    /* 获得商品列表 */
    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_name_style, eg.exchange_integral, ' .
                'g.goods_type, g.goods_brief, g.goods_thumb , g.goods_img, eg.is_hot ' .
            'FROM ' . $GLOBALS['ecs']->table('exchange_goods') . ' AS eg, ' .$GLOBALS['ecs']->table('goods') . ' AS g ' .
            "WHERE eg.goods_id = g.goods_id AND $where $ext ORDER BY $sort $order";
    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        /* 处理商品水印图片 */
        $watermark_img = '';

//        if ($row['is_new'] != 0)
//        {
//            $watermark_img = "watermark_new_small";
//        }
//        elseif ($row['is_best'] != 0)
//        {
//            $watermark_img = "watermark_best_small";
//        }
//        else
        if ($row['is_hot'] != 0)
        {
            $watermark_img = 'watermark_hot_small';
        }

        if ($watermark_img != '')
        {
            $arr[$row['goods_id']]['watermark_img'] =  $watermark_img;
        }

        $arr[$row['goods_id']]['goods_id']          = $row['goods_id'];
        if($display == 'grid')
        {
            $arr[$row['goods_id']]['goods_name']    = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        }
        else
        {
            $arr[$row['goods_id']]['goods_name']    = $row['goods_name'];
        }
        $arr[$row['goods_id']]['name']              = $row['goods_name'];
        $arr[$row['goods_id']]['goods_brief']       = $row['goods_brief'];
        $arr[$row['goods_id']]['goods_style_name']  = add_style($row['goods_name'],$row['goods_name_style']);
        $arr[$row['goods_id']]['exchange_integral'] = $row['exchange_integral'];
        $arr[$row['goods_id']]['type']              = $row['goods_type'];
        $arr[$row['goods_id']]['goods_thumb']       = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']         = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['url']               = build_uri('exchange_goods', array('gid'=>$row['goods_id']), $row['goods_name']);
    }

    return $arr;
}

/**
 * 获得分类下的商品总数
 *
 * @access  public
 * @param   string     $cat_id
 * @return  integer
 */
function get_exchange_goods_count($children, $min = 0, $max = 0, $ext='')
{
    $where  = "eg.is_exchange = 1 AND g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';


    if ($min > 0)
    {
        $where .= " AND eg.exchange_integral >= $min ";
    }

    if ($max > 0)
    {
        $where .= " AND eg.exchange_integral <= $max ";
    }

    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('exchange_goods') . ' AS eg, ' .
           $GLOBALS['ecs']->table('goods') . " AS g WHERE eg.goods_id = g.goods_id AND $where $ext";

    /* 返回商品总数 */
    return $GLOBALS['db']->getOne($sql);
}

/**
 * 获得指定分类下的推荐商品
 *
 * @access  public
 * @param   string      $type       推荐类型，可以是 best, new, hot, promote
 * @param   string      $cats       分类的ID
 * @param   integer     $min        商品积分下限
 * @param   integer     $max        商品积分上限
 * @param   string      $ext        商品扩展查询
 * @return  array
 */
function get_exchange_recommend_goods($type = '', $cats = '', $min =0,  $max = 0, $ext='')
{
    $price_where = ($min > 0) ? " AND g.shop_price >= $min " : '';
    $price_where .= ($max > 0) ? " AND g.shop_price <= $max " : '';

    $sql =  'SELECT g.goods_id, g.goods_name, g.goods_name_style, eg.exchange_integral, ' .
                'g.goods_brief, g.goods_thumb, goods_img, b.brand_name ' .
            'FROM ' . $GLOBALS['ecs']->table('exchange_goods') . ' AS eg ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON g.goods_id = eg.goods_id ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ON b.brand_id = g.brand_id ' .
            'WHERE eg.is_exchange = 1 AND g.is_delete = 0 ' . $price_where . $ext;
    $num = 0;
    $type2lib = array('best'=>'exchange_best', 'new'=>'exchange_new', 'hot'=>'exchange_hot');
    $num = get_library_number($type2lib[$type], 'exchange_list');

    switch ($type)
    {
        case 'best':
            $sql .= ' AND eg.is_best = 1';
            break;
        case 'new':
            $sql .= ' AND eg.is_new = 1';
            break;
        case 'hot':
            $sql .= ' AND eg.is_hot = 1';
            break;
    }

    if (!empty($cats))
    {
        $sql .= " AND (" . $cats . " OR " . get_extension_goods($cats) .")";
    }
    $order_type = $GLOBALS['_CFG']['recommend_order'];
    $sql .= ($order_type == 0) ? ' ORDER BY g.sort_order, g.last_update DESC' : ' ORDER BY RAND()';
    $res = $GLOBALS['db']->selectLimit($sql, $num);

    $idx = 0;
    $goods = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $goods[$idx]['id']                = $row['goods_id'];
        $goods[$idx]['name']              = $row['goods_name'];
        $goods[$idx]['brief']             = $row['goods_brief'];
        $goods[$idx]['brand_name']        = $row['brand_name'];
        $goods[$idx]['short_name']        = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
                                                sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        $goods[$idx]['exchange_integral'] = $row['exchange_integral'];
        $goods[$idx]['thumb']             = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $goods[$idx]['goods_img']         = get_image_path($row['goods_id'], $row['goods_img']);
        $goods[$idx]['url']               = build_uri('exchange_goods', array('gid' => $row['goods_id']), $row['goods_name']);

        $goods[$idx]['short_style_name']  = add_style($goods[$idx]['short_name'], $row['goods_name_style']);
        $idx++;
    }

    return $goods;
}

/**
 * 获得积分兑换商品的详细信息
 *
 * @access  public
 * @param   integer     $goods_id
 * @return  void
 */
function get_exchange_goods_info($goods_id)
{
    $time = gmtime();
    $sql = 'SELECT g.*, c.measure_unit, b.brand_id, b.brand_name AS goods_brand, eg.exchange_integral, eg.is_exchange ' .
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('exchange_goods') . ' AS eg ON g.goods_id = eg.goods_id ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('category') . ' AS c ON g.cat_id = c.cat_id ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ON g.brand_id = b.brand_id ' .
            "WHERE g.goods_id = '$goods_id' AND g.is_delete = 0 " .
            'GROUP BY g.goods_id';

    $row = $GLOBALS['db']->getRow($sql);

    if ($row !== false)
    {
        /* 处理商品水印图片 */
        $watermark_img = '';

        if ($row['is_new'] != 0)
        {
            $watermark_img = "watermark_new";
        }
        elseif ($row['is_best'] != 0)
        {
            $watermark_img = "watermark_best";
        }
        elseif ($row['is_hot'] != 0)
        {
            $watermark_img = 'watermark_hot';
        }

        if ($watermark_img != '')
        {
            $row['watermark_img'] =  $watermark_img;
        }

        /* 修正重量显示 */
        $row['goods_weight']  = (intval($row['goods_weight']) > 0) ?
            $row['goods_weight'] . $GLOBALS['_LANG']['kilogram'] :
            ($row['goods_weight'] * 1000) . $GLOBALS['_LANG']['gram'];

        /* 修正上架时间显示 */
        $row['add_time']      = local_date($GLOBALS['_CFG']['date_format'], $row['add_time']);

        /* 修正商品图片 */
        $row['goods_img']   = get_image_path($goods_id, $row['goods_img']);
        $row['goods_thumb'] = get_image_path($goods_id, $row['goods_thumb'], true);

        return $row;
    }
    else
    {
        return false;
    }
}


?>
