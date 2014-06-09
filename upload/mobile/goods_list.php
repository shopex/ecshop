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
 * $Author: testyang $
 * $Id: goods_list.php 15013 2008-10-23 09:31:42Z testyang $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$type = !empty($_GET['type']) ? $_GET['type'] : 'best';

$temp_type=array();
$temp_type=array('best','promote','hot','new');
if(!in_array($type,$temp_type))
{
    $type='best';
}
$_GET['type']=$type;



if ($type != 'best' && $type != 'promote' && $type != 'hot' && $type != 'new')
{
    $type = 'best';
}
$smarty->assign('type', $type);

if (empty($_GET['order_price']))
{
    $order_rule = 'ORDER BY shop_price ASC, g.last_update DESC';
}
else
{
    $order_rule = 'ORDER BY shop_price DESC, g.last_update DESC';
}

if ($type == 'promote')
{
    $goods = wap_get_promote_goods($order_rule);
}
else
{
    $goods = wap_get_recommend_goods($type, $order_rule);
}

$num = count($goods);
if ($num > 0)
{
    foreach ($goods as $key => $data)
    {
        $sort_array[$data['id']] = $key;
    }
    $page_num = '10';
    $page = !empty($_GET['page']) ? intval($_GET['page']) : 1;
    $_GET['page']=$page;
    $pages = ceil($num / $page_num);
    if ($page <= 0)
    {
        $page = 1;
    }
    if ($pages == 0)
    {
        $pages = 1;
    }
    if ($page > $pages)
    {
        $page = $pages;
    }
    $i = 1;
    foreach ($sort_array as $goods_key)
    {
        if (($i > ($page_num * ($page - 1 ))) && ($i <= ($page_num * $page)))
        {
            $price = empty($goods[$goods_key]['promote_price_org']) ? $goods[$goods_key]['shop_price'] : $goods[$goods_key]['promote_price'];
            //$wml_data .= "<a href='goods.php?id={}'>".encode_output($goods[$goods_key]['name'])."</a>[".encode_output($price)."]<br/>";
            $goods_data[] = array('i' => $i , 'price' => encode_output($price) , 'id' => $goods[$goods_key]['id'] , 'name' => encode_output($goods[$goods_key]['name']));
        }
        $i++;
    }
    $smarty->assign('goods_data', $goods_data);
    $pagebar = get_wap_pager($num, $page_num, $page, 'goods_list.php?type='.$type.'&order_price='.(empty($order_price)?0:$order_price), 'page');
    $smarty->assign('pagebar' , $pagebar);
}

$smarty->assign('footer', get_footer());
$smarty->display('goods_list.html');



/**
 * 获得推荐商品
 *
 * @access  public
 * @param   string      $type       推荐类型，可以是 best, new, hot
 * @param   string      $order_rule     指定商品排序规则
 * @return  array
 */
function wap_get_recommend_goods($type = '', $order_rule = '')
{
    if (!in_array($type, array('best', 'new', 'hot')))
    {
        return array();
    }

    //取不同推荐对应的商品
    static $type_goods = array();
    if (empty($type_goods[$type]))
    {
        //初始化数据
        $type_goods['best'] = array();
        $type_goods['new'] = array();
        $type_goods['hot'] = array();
        $data = read_static_cache('recommend_goods');
        if ($data === false)
        {
            $sql = 'SELECT g.goods_id, g.is_best, g.is_new, g.is_hot, g.is_promote, b.brand_name,g.sort_order ' .
               ' FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
               ' LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ON b.brand_id = g.brand_id ' .
               ' WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND (g.is_best = 1 OR g.is_new =1 OR g.is_hot = 1)'.
               ' ORDER BY g.sort_order, g.last_update DESC';
            $goods_res = $GLOBALS['db']->getAll($sql);
            //定义推荐,最新，热门，促销商品
            $goods_data['best'] = array();
            $goods_data['new'] = array();
            $goods_data['hot'] = array();
            $goods_data['brand'] = array();
            if (!empty($goods_res))
            {
                foreach($goods_res as $data)
                {
                    if ($data['is_best'] == 1)
                    {
                        $goods_data['best'][] = array('goods_id' => $data['goods_id'], 'sort_order' => $data['sort_order']);
                    }
                    if ($data['is_new'] == 1)
                    {
                        $goods_data['new'][] = array('goods_id' => $data['goods_id'], 'sort_order' => $data['sort_order']);
                    }
                    if ($data['is_hot'] == 1)
                    {
                        $goods_data['hot'][] = array('goods_id' => $data['goods_id'], 'sort_order' => $data['sort_order']);
                    }
                    if ($data['brand_name'] != '')
                    {
                        $goods_data['brand'][$data['goods_id']] = $data['brand_name'];
                    }
                }
            }
            write_static_cache('recommend_goods', $goods_data);
        }
        else
        {
            $goods_data = $data;
        }

        $time = gmtime();
        $order_type = $GLOBALS['_CFG']['recommend_order'];

        //按推荐数量及排序取每一项推荐显示的商品 order_type可以根据后台设定进行各种条件显示
        static $type_array = array();
        $type2lib = array('best'=>'recommend_best', 'new'=>'recommend_new', 'hot'=>'recommend_hot');
        if (empty($type_array))
        {
            foreach($type2lib as $key => $data)
            {
                if (!empty($goods_data[$key]))
                {
                    $data_count = count($goods_data[$key]);
                    $num = $data_count ;
                    if ($order_type == 0)
                    {
                        //usort($goods_data[$key], 'goods_sort');
                        $rand_key = array_slice($goods_data[$key], 0, $num);
                        foreach($rand_key as $key_data)
                        {
                            $type_array[$key][] = $key_data['goods_id'];
                        }
                    }
                    else
                    {
                        $rand_key = array_rand($goods_data[$key], $num);
                        if ($num == 1)
                        {
                            $type_array[$key][] = $goods_data[$key][$rand_key]['goods_id'];
                        }
                        else
                        {
                            foreach($rand_key as $key_data)
                            {
                                $type_array[$key][] = $goods_data[$key][$key_data]['goods_id'];
                            }
                        }
                    }
                }
                else
                {
                    $type_array[$key] = array();
                }
            }
        }

        //取出所有符合条件的商品数据，并将结果存入对应的推荐类型数组中
        $sql = 'SELECT g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.shop_price AS org_price, g.promote_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                "promote_start_date, promote_end_date, g.goods_brief, g.goods_thumb, g.goods_img, RAND() AS rnd " .
                'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
                "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ";
        $type_merge = array_merge($type_array['new'], $type_array['best'], $type_array['hot']);
        $type_merge = array_unique($type_merge);
        $sql .= ' WHERE g.goods_id ' . db_create_in($type_merge);
        $order_rule = empty($order_rule) ? ' ORDER BY g.sort_order, g.last_update DESC' : $order_rule;
        $sql .= $order_rule;

        $result = $GLOBALS['db']->getAll($sql);
        foreach ($result AS $idx => $row)
        {
            if ($row['promote_price'] > 0)
            {
                $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $goods[$idx]['promote_price'] = $promote_price > 0 ? price_format($promote_price) : '';
            }
            else
            {
                $goods[$idx]['promote_price'] = '';
            }

            $goods[$idx]['id']           = $row['goods_id'];
            $goods[$idx]['name']         = $row['goods_name'];
            $goods[$idx]['brief']        = $row['goods_brief'];
            $goods[$idx]['brand_name']   = isset($goods_data['brand'][$row['goods_id']]) ? $goods_data['brand'][$row['goods_id']] : '';
            $goods[$idx]['goods_style_name']   = add_style($row['goods_name'],$row['goods_name_style']);

            $goods[$idx]['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
                                               sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
            $goods[$idx]['short_style_name']   = add_style($goods[$idx]['short_name'],$row['goods_name_style']);
            $goods[$idx]['market_price'] = price_format($row['market_price']);
            $goods[$idx]['shop_price']   = price_format($row['shop_price']);
            $goods[$idx]['thumb']        = get_image_path($row['goods_id'], $row['goods_thumb'], true);
            $goods[$idx]['goods_img']    = get_image_path($row['goods_id'], $row['goods_img']);
            $goods[$idx]['url']          = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
            if (in_array($row['goods_id'], $type_array['best']))
            {
                $type_goods['best'][] = $goods[$idx];
            }
            if (in_array($row['goods_id'], $type_array['new']))
            {
                $type_goods['new'][] = $goods[$idx];
            }
            if (in_array($row['goods_id'], $type_array['hot']))
            {
                $type_goods['hot'][] = $goods[$idx];
            }
        }
    }
    return $type_goods[$type];
}

/**
 * 获得促销商品
 *
 * @access  public
 * @param   string      $order_rule     指定商品排序规则
 * @return  array
 */
function wap_get_promote_goods($order_rule = '')
{
    $time = gmtime();

    /* 取得促销lbi的数量限制 */
    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.shop_price AS org_price, g.promote_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                "promote_start_date, promote_end_date, g.goods_brief, g.goods_thumb, goods_img, b.brand_name, " .
                "g.is_best, g.is_new, g.is_hot, g.is_promote, RAND() AS rnd " .
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ON b.brand_id = g.brand_id ' .
            "LEFT JOIN " . $GLOBALS['ecs']->table('member_price') . " AS mp ".
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
            'WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ' .
            " AND g.is_promote = 1 AND promote_start_date <= '$time' AND promote_end_date >= '$time' ";
    $order_rule = empty($order_rule) ? 'ORDER BY g.sort_order, g.last_update DESC' : $order_rule;
    $sql .= $order_rule;
    $result = $GLOBALS['db']->getAll($sql);

    $goods = array();
    foreach ($result AS $idx => $row)
    {
        if ($row['promote_price'] > 0)
        {
            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
            $goods[$idx]['promote_price'] = $promote_price > 0 ? price_format($promote_price) : '';
        }
        else
        {
            $goods[$idx]['promote_price'] = '';
        }

        $goods[$idx]['id']           = $row['goods_id'];
        $goods[$idx]['name']         = $row['goods_name'];
        $goods[$idx]['brief']        = $row['goods_brief'];
        $goods[$idx]['brand_name']   = $row['brand_name'];
        $goods[$idx]['goods_style_name']   = add_style($row['goods_name'],$row['goods_name_style']);
        $goods[$idx]['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        $goods[$idx]['short_style_name']   = add_style($goods[$idx]['short_name'],$row['goods_name_style']);
        $goods[$idx]['market_price'] = price_format($row['market_price']);
        $goods[$idx]['shop_price']   = price_format($row['shop_price']);
        $goods[$idx]['thumb']        = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $goods[$idx]['goods_img']    = get_image_path($row['goods_id'], $row['goods_img']);
        $goods[$idx]['url']          = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
    }

    return $goods;
}
?>