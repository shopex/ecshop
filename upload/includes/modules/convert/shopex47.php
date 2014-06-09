<?php

/**
 * shopex4.7转换程序插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: shopex47.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * 模块信息
 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code'] = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc'] = 'shopex47_desc';

    /* 作者 */
    $modules[$i]['author'] = 'ECSHOP R&D TEAM';

    return;
}

/* 类 */
class shopex47
{
    /* 数据库连接 ADOConnection 对象 */
    var $sdb;

    /* 表前缀 */
    var $sprefix;

    /* 原系统根目录 */
    var $sroot;

    /* 新系统根目录 */
    var $troot;

    /* 新系统网站根目录 */
    var $tdocroot;

    /* 原系统字符集 */
    var $scharset;

    /* 新系统字符集 */
    var $tcharset;

    /* 构造函数 */
    function shopex47(&$sdb, $sprefix, $sroot, $scharset = 'UTF8')
    {
        $this->sdb = $sdb;
        $this->sprefix = $sprefix;
        $this->sroot = $sroot;
        $this->troot = str_replace('/includes/modules/convert', '', str_replace('\\', '/', dirname(__FILE__)));
        $this->tdocroot = str_replace('/' . ADMIN_PATH, '', dirname(PHP_SELF));
        $this->scharset = $scharset;
        if (EC_CHARSET == 'utf-8')
        {
            $tcharset = 'UTF8';
        }
        elseif (EC_CHARSET == 'gbk')
        {
            $tcharset = 'GB2312';
        }
        $this->tcharset = $tcharset;
    }

    /**
     * 需要转换的表（用于检查数据库是否完整）
     * @return  array
     */
    function required_tables()
    {
        return array(
            $this->sprefix.'mall_offer_pcat',$this->sprefix.'mall_brand',$this->sprefix.'mall_goods',$this->sprefix.'mall_offer_linkgoods', $this->sprefix.'mall_member_level',$this->sprefix.'mall_member',$this->sprefix.'mall_offer_p',$this->sprefix.'mall_offer_deliverarea',$this->sprefix.'mall_offer_t',$this->sprefix.'mall_offer_ncat',$this->sprefix.'mall_offer_ncon',$this->sprefix.'mall_offer_link',
            $this->sprefix.'mall_orders',$this->sprefix.'mall_items',$this->sprefix.'mall_offer',
        );
    }

    /**
     * 必需的目录
     * @return  array
     */
    function required_dirs()
    {
        return array(
            '/syssite/home/shop/1/pictures/brandimg/',
            '/syssite/home/shop/1/pictures/newsimg/',
            '/syssite/home/shop/1/pictures/productsimg/big/',
            '/syssite/home/shop/1/pictures/productsimg/small/',
            '/syssite/home/shop/1/pictures/linkimg/',
            '/cert/',
        );
    }

    /**
     * 下一步操作：空表示结束
     * @param   string  $step  当前操作：空表示开始
     * @return  string
     */
    function next_step($step)
    {
        /* 所有操作 */
        $steps = array(
            ''              => 'step_file',
            'step_file'     => 'step_cat',
            'step_cat'      => 'step_brand',
            'step_brand'    => 'step_goods',
            'step_goods'    => 'step_users',
            'step_users'    => 'step_article',
            'step_article'  => 'step_order',
            'step_order'    => 'step_config',
            'step_config'   => '',
        );

        return $steps[$step];
    }

    /**
     * 执行某个步骤
     * @param   string  $step
     */
    function process($step)
    {
        $func = str_replace('step', 'process', $step);
        return $this->$func();
    }

    /**
     * 复制文件
     * @return  成功返回true，失败返回错误信息
     */
    function process_file()
    {
        /* 复制品牌图片 */
        $from = $this->sroot . '/syssite/home/shop/1/pictures/brandimg/';
        $to   = $this->troot . '/data/brandlogo/';
        copy_files($from, $to);

        /* 复制 html 编辑器的图片 */
        $from = $this->sroot . '/syssite/home/shop/1/pictures/newsimg/';
        $to   = $this->troot . '/images/upload/Image/';
        copy_files($from, $to);

        /* 复制商品图片 */
        $to   = $this->troot . '/images/' . date('Ym') . '/';

        $from = $this->sroot . '/syssite/home/shop/1/pictures/productsimg/big/';
        copy_files($from, $to, 'big_');

        $from = $this->sroot . '/syssite/home/shop/1/pictures/productsimg/small/';
        copy_files($from, $to, 'small_');

        $from = $this->sroot . '/syssite/home/shop/1/pictures/productsimg/big/';
        copy_files($from, $to, 'original_');

        /* 复制友情链接图片 */
        $from = $this->sroot . '/syssite/home/shop/1/pictures/linkimg/';
        $to   = $this->troot . '/data/afficheimg/';
        copy_files($from, $to);

        /* 复制证书 */
        $from = $this->sroot . '/cert/';
        $to   = $this->troot . '/cert/';
        copy_files($from, $to);

        return TRUE;
    }

    /**
     * 商品分类
     * @return  成功返回true，失败返回错误信息
     */
    function process_cat()
    {
        global $db, $ecs;

        /* 清空分类、商品类型、属性 */
        truncate_table('category');
        truncate_table('goods_type');
        truncate_table('attribute');

        /* 查询分类并循环处理 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_offer_pcat";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $cat = array();
            $cat['cat_id']      = $row['catid'];
            $cat['cat_name']    = $row['cat'];
            $cat['parent_id']   = $row['pid'];
            $cat['sort_order']  = $row['catord'];

            /* 插入分类 */
            if (!$db->autoExecute($ecs->table('category'), $cat, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 查询商品类型并循环处理 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_prop_category";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $type = array();
            $type['cat_id']     = $row['prop_cat_id'];
            $type['cat_name']   = $row['cat_name'];
            $type['enabled']    = '1';
            if (!$db->autoExecute($ecs->table('goods_type'), $type, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 查询属性值并循环处理 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_prop WHERE prop_type = 'propvalue'";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $attr = array();
            $attr['attr_id']         = $row['prop_id'];
            $attr['attr_name']       = $row['prop_name'];
            $attr['cat_id']          = $row['prop_cat_id'];
            $attr['sort_order']      = $row['ordnum'];
            $attr['attr_input_type'] = '1';
            $attr['attr_type']       = '1';

            $sql = "SELECT DISTINCT prop_value FROM ".$this->sprefix."mall_prop_value WHERE prop_id = '$row[prop_id]'";
            $attr['attr_values']= join("\n", $this->sdb->getCol($sql));
            if (!$db->autoExecute($ecs->table('attribute'), $attr, 'INSERT', '', 'SILENT'))
            {
                 //return $db->error();
            }
        }

        /* 返回成功 */
        return TRUE;
    }

    /**
     * 品牌
     * @return  成功返回true，失败返回错误信息
     */
    function process_brand()
    {
        global $db, $ecs;

        /* 清空品牌 */
        truncate_table('brand');

        /* 查询品牌并插入 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_brand";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $brand = array(
                'brand_name' => $row['brand_name'],
                'brand_desc' => '',
                'site_url' => ecs_iconv($this->scharset, $this->tcharset, addslashes($row['brand_site_url'])),
                'brand_logo' => ecs_iconv($this->scharset, $this->tcharset, addslashes($row['brand_logo']))
            );
            if (!$db->autoExecute($ecs->table('brand'), $brand, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 返回成功 */
        return TRUE;
    }

    /**
     * 商品
     * @return  成功返回true，失败返回错误信息
     */
    function process_goods()
    {
        global $db, $ecs;

        /* 清空商品、商品扩展分类、商品属性、商品相册、关联商品、组合商品、赠品 */
        truncate_table('goods');
        truncate_table('goods_cat');
        truncate_table('goods_attr');
        truncate_table('goods_gallery');
        truncate_table('link_goods');
        truncate_table('group_goods');

        /* 查询品牌列表 name => id */
        $brand_list = array();
        $sql = "SELECT brand_id, brand_name FROM " . $ecs->table('brand');
        $res = $db->query($sql);
        while ($row = $db->fetchRow($res))
        {
            $brand_list[$row['brand_name']] = $row['brand_id'];
        }

        /* 取得商店设置 */
        $sql = "SELECT offer_pointtype, offer_pointnum FROM ".$this->sprefix."mall_offer WHERE offerid = '1'";
        $config = $this->sdb->getRow($sql);

        /* 取得商品分类对应的商品类型 */
        $cat_type_list = array();
        $sql = "SELECT catid, prop_cat_id FROM ".$this->sprefix."mall_offer_pcat";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $cat_type_list[$row['catid']] = $row['prop_cat_id'];
        }

        /* 查询商品并处理 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_goods";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $goods = array();

            if ($row['ifobject'] == '0')
            {
                /* 虚拟商品 */
                $goods['is_real'] = '0';
            }
            elseif ($row['ifobject'] == '1')
            {
                /* 实体商品 */
                $goods['is_real'] = '1';
            }
            elseif ($row['ifobject'] == '2')
            {
                /* 数字文件，暂时无法转换 */
                continue;
            }
            elseif ($row['ifobject'] == '3')
            {
                /* 捆绑销售，暂时无法转换 */
                continue;
            }
            else
            {
                /* 未知，无法转换 */
                continue;
            }
            $goods['goods_id']      = $row['gid'];
            $goods['cat_id']        = $row['catid'];
            $goods['goods_sn']      = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['bn']));
            $goods['goods_name']    = $row['goods'];
            $goods['brand_id']      = trim($row['brand']) == '' ? '0' : $brand_list[ecs_iconv($this->scharset, $this->tcharset, addslashes($row['brand']))];
            $goods['goods_number']  = $row['storage'];
            $goods['goods_weight']  = $row['weight'];
            $goods['market_price']  = $row['priceintro'];
            $goods['shop_price']    = $row['ifdiscreteness'] == '1' ? $row['basicprice'] : $row['price'];
            if ($row['tejia2'] == '1')
            {
                $goods['promote_price']         = $goods['shop_price'];
                $goods['promote_start_date']    = gmtime();
                $goods['promote_end_date']      = gmstr2time('+1 weeks');
            }
            $goods['warn_number']   = $row['ifalarm'] == '1' ? $row['alarmnum'] : '0';
            $goods['goods_brief']   = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['intro']));
            $goods['goods_desc']    = str_replace('pictures/newsimg/', $this->tdocroot . '/images/upload/Image/', ecs_iconv($this->scharset, $this->tcharset, addslashes($row['memo'])));
            $goods['is_on_sale']    = $row['shop_iffb'];
            $goods['is_alone_sale'] = $row['onsale'];
            $goods['add_time']      = $row['uptime'];
            $goods['sort_order']    = $row['offer_ord'];
            $goods['is_delete']     = '0';
            $goods['is_best']       = $row['recommand2'];
            $goods['is_new']        = $row['new2'];
            $goods['is_hot']        = $row['hot2'];
            $goods['is_promote']    = $row['tejia2'];
            $goods['goods_type']    = isset($cat_type_list[$row['catid']]) ? $cat_type_list[$row['catid']] : 0;
            $goods['last_update'] = gmtime();

            /* 图片：如果没有本地文件，取远程图片 */
            $file = $this->troot . '/images/' . date('Ym') . '/small_' . $row['gid'];
            if (file_exists($file. '.jpg'))
            {
                $goods['goods_thumb'] = 'images/' . date('Ym') . '/small_' . $row['gid'] . '.jpg';
            }
            elseif (file_exists($file. '.jpeg'))
            {
                $goods['goods_thumb'] = 'images/' . date('Ym') . '/small_' . $row['gid'] . '.jpeg';
            }
            elseif (file_exists($file. '.gif'))
            {
                $goods['goods_thumb'] = 'images/' . date('Ym') . '/small_' . $row['gid'] . '.gif';
            }
            elseif (file_exists($file. '.png'))
            {
                $goods['goods_thumb'] = 'images/' . date('Ym') . '/small_' . $row['gid'] . '.png';
            }
            else
            {
                $goods['goods_thumb'] = $row['smallimgremote'];
            }

            $file = $this->troot . '/images/' . date('Ym') . '/big_' . $row['gid'];
            if (file_exists($file. '.jpg'))
            {
                $goods['goods_img'] = 'images/' . date('Ym') . '/big_' . $row['gid'] . '.jpg';
                $goods['original_img'] = 'images/' . date('Ym') . '/original_' . $row['gid'] . '.jpg';
            }
            elseif (file_exists($file. '.jpeg'))
            {
                $goods['goods_img'] = 'images/' . date('Ym') . '/big_' . $row['gid'] . '.jpeg';
                $goods['original_img'] = 'images/' . date('Ym') . '/original_' . $row['gid'] . '.jpeg';
            }
            elseif (file_exists($file. '.gif'))
            {
                $goods['goods_img'] = 'images/' . date('Ym') . '/big_' . $row['gid'] . '.gif';
                $goods['original_img'] = 'images/' . date('Ym') . '/original_' . $row['gid'] . '.gif';
            }
            elseif (file_exists($file. '.png'))
            {
                $goods['goods_img'] = 'images/' . date('Ym') . '/big_' . $row['gid'] . '.png';
                $goods['orinigal_img'] = 'images/' . date('Ym') . '/original_' . $row['gid'] . '.png';
            }
            else
            {
                $goods['goods_img'] = $row['bigimgremote'];
            }

            /* 积分：根据商店设置 */
            if ($config['offer_pointtype'] == '0')
            {
                /* 不使用积分 */
                $goods['integral'] = '0';
            }
            elseif ($config['offer_pointtype'] == '1')
            {
                /* 按比例 */
                $goods['integral'] = round($goods['shop_price'] * $config['offer_pointnum']);
            }
            else
            {
                /* 自定义 */
                $goods['integral'] = $row['point'];
            }

            /* 插入 */
            if (!$db->autoExecute($ecs->table('goods'), $goods, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }

            /* 扩展分类 */
            if ($row['linkclass'] != '')
            {
                $goods_cat = array();
                $goods_cat['goods_id'] = $row['gid'];
                $cat_id_list = explode(',', trim($row['linkclass'], ','));
                foreach ($cat_id_list as $cat_id)
                {
                    $goods_cat['cat_id'] = $cat_id;
                    if (!$db->autoExecute($ecs->table('goods_cat'), $goods_cat, 'INSERT', '', 'SILENT'))
                    {
                        //return $db->error();
                    }
                }
            }

            /* 取得该分类的所有属性 */
            $sql = "SELECT DISTINCT pv.prop_id, pv.prop_value " .
                    "FROM ".$this->sprefix."mall_goods_prop_grp_value AS gp, " .
                            $this->sprefix."mall_prop_value AS pv " .
                    "WHERE gp.prop_value_id = pv.prop_value_id " .
                    "AND gp.gid = '$row[gid]'";
            $res1 = $this->sdb->query($sql);
            while ($attr = $this->sdb->fetchRow($res1))
            {
                $goods_attr = array();
                $goods_attr['goods_id']     = $row['gid'];
                $goods_attr['attr_id']      = $attr['prop_id'];
                $goods_attr['attr_value']   = ecs_iconv($this->scharset, $this->tcharset, addslashes($attr['prop_value']));
                $goods_attr['attr_price']   = '0';
                if (!$db->autoExecute($ecs->table('goods_attr'), $goods_attr, 'INSERT', '', 'SILENT'))
                {
                    //return $db->error();
                }
            }

            /* 商品相册 */
            if ($row['multi_image'])
            {
                $goods_gallery = array();
                $goods_gallery['goods_id'] = $row['gid'];
                $img_list = explode('&&&', $row['multi_image']);
                foreach ($img_list as $img)
                {
                    if (substr($img, 0, 7) == 'http://')
                    {
                        $goods_gallery['img_url'] = $img;
                    }
                    else
                    {
                        make_dir('images/' . date('Ym') . '/');
                        $goods_gallery['img_url'] = 'images/' . date('Ym') . '/big_' . $img;
                        $goods_gallery['img_original'] = 'images/' . date('Ym') . '/original_' . $img;
                    }

                    if (!$db->autoExecute($ecs->table('goods_gallery'), $goods_gallery, 'INSERT', '', 'SILENT'))
                    {
                        //return $db->error();
                    }
                }
            }
        }

        /* 关联商品 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_offer_linkgoods";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $link_goods = array();
            $link_goods['goods_id']         = $row['pgid'];
            $link_goods['link_goods_id']    = $row['sgid'];
            $link_goods['is_double']        = $row['type'];

            if (!$db->autoExecute($ecs->table('link_goods'), $link_goods, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }

            if ($row['type'] == '1')
            {
                $link_goods = array();
                $link_goods['goods_id']         = $row['sgid'];
                $link_goods['link_goods_id']    = $row['pgid'];
                $link_goods['is_double']        = $row['type'];

                if (!$db->autoExecute($ecs->table('link_goods'), $link_goods, 'INSERT', '', 'SILENT'))
                {
                    //return $db->error();
                }
            }
        }

        /* 组合商品 */
        $sql = "SELECT DISTINCT gid, prop_goods_id, price FROM ".$this->sprefix."mall_pcat_prop_has_goods";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $group_goods = array();
            $group_goods['parent_id']   = $row['gid'];
            $group_goods['goods_id']    = $row['prop_goods_id'];
            $group_goods['goods_price'] = $row['price'];

            if (!$db->autoExecute($ecs->table('group_goods'), $group_goods, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 返回成功 */
        return TRUE;
    }

    /**
     * 会员等级、会员、会员价格
     */
    function process_users()
    {
        global $db, $ecs;

        /* 清空会员、会员等级、会员价格、用户红包、用户地址、帐户明细 */
        truncate_table('user_rank');
        truncate_table('users');
        truncate_table('user_address');
        truncate_table('user_bonus');
        truncate_table('member_price');
        truncate_table('user_account');

        /* 查询并插入会员等级 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_member_level order by point desc";
        $res = $this->sdb->query($sql);
        $max_points = 50000;
        while ($row = $this->sdb->fetchRow($res))
        {
            $user_rank = array();
            $user_rank['rank_id']       = $row['levelid'];
            $user_rank['rank_name']     = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['name']));
            $user_rank['min_points']    = $row['point'];
            $user_rank['max_points']    = $max_points;
            $user_rank['discount']      = round($row['discount'] * 100);
            $user_rank['show_price']    = '1';
            $user_rank['special_rank']  = '0';

            if (!$db->autoExecute($ecs->table('user_rank'), $user_rank, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }

            $max_points = $row['point'] - 1;
        }

        /* 查询并插入会员 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_member";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $user = array();
            $user['user_id']        = $row['userid'];
            $user['email']          = $row['email'];
            $user['user_name']      = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['user']));
            $user['password']       = $row['password'];
            $user['question']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['pw_question']));
            $user['answer']         = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['pw_answer']));
            $user['sex']            = $row['sex'];
            if (!empty($row['birthday']))
            {
                $birthday           = strtotime($row['birthday']);
                if ($birthday != -1 && $birthday !== false)
                {
                    $user['birthday']   = date('Y-m-d', $birthday);
                }
            }
            $user['user_money']     = $row['advance'];
            $user['pay_points']     = $row['point'];
            $user['rank_points']    = $row['point'];
            $user['reg_time']       = $row['regtime'];
            $user['last_login']     = $row['regtime'];
            $user['last_ip']        = $row['ip'];
            $user['visit_count']    = '1';
            $user['user_rank']      = '0';

            if (!$db->autoExecute($ecs->table('users'), $user, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
          //  uc_call('uc_user_register', array($user['user_name'], $user['password'], $user['email']));
        }

        /* 收货人地址 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_member_receiver";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $address = array();
            $address['address_id']      = $row['receiveid'];
            $address['address_name']    = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['name']));
            $address['user_id']         = $row['memberid'];
            $address['consignee']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['name']));
            $address['email']           = $row['email'];
            $address['address']         = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['address']));
            $address['zipcode']         = $row['zipcode'];
            $address['tel']             = $row['telphone'];
            $address['mobile']          = $row['mobile'];

            if (!$db->autoExecute($ecs->table('user_address'), $address, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 会员价格 */
        $temp_arr = array();
        $sql = "SELECT * FROM ".$this->sprefix."mall_member_price";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            if ($row['gid'] > 0 && $row['levelid'] > 0 && !isset($temp_arr[$row['gid']][$row['levelid']]))
            {
                $temp_arr[$row['gid']][$row['levelid']] = true;

                $member_price = array();
                $member_price['goods_id']   = $row['gid'];
                $member_price['user_rank']  = $row['levelid'];
                $member_price['user_price'] = $row['price'];

                if (!$db->autoExecute($ecs->table('member_price'), $member_price, 'INSERT', '', 'SILENT'))
                {
                    //return $db->error();
                }
            }
        }
        unset($temp_arr);

        /* 帐户明细 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_member_advance";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $user_account = array();
            $user_account['user_id']        = $row['memberid'];
            $user_account['admin_user']     = $row['doman'];
            $user_account['amount']         = $row['money'];
            $user_account['add_time']       = $row['date'];
            $user_account['paid_time']      = $row['date'];
            $user_account['admin_note']     = $row['description'];
            $user_account['process_type']   = $row['money'] >= 0 ? SURPLUS_SAVE : SURPLUS_RETURN;
            $user_account['is_paid']        = '1';

            if (!$db->autoExecute($ecs->table('user_account'), $user_account, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 返回 */
        return TRUE;
    }

    /**
     * 文章
     */
    function process_article()
    {
        global $db, $ecs;

        /* 清空文章类型、文章、友情链接 */
        truncate_table('article_cat');
        truncate_table('article');
        truncate_table('friend_link');

        /* 文章类型 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_offer_ncat";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $cat = array();
            $cat['cat_id']      = $row['catid'];
            $cat['cat_name']    = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['cat']));
            $cat['cat_type']    = '1';
            $cat['sort_order']  = $row['pid'];
            $cat['is_open']     = '1';

            if (!$db->autoExecute($ecs->table('article_cat'), $cat, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 文章 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_offer_ncon";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $article = array();
            $article['article_id']  = $row['newsid'];
            $article['cat_id']      = $row['catid'];
            $article['title']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['title']));
            $article['content']     = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['con']));
            $article['content']     = str_replace('pictures/newsimg/', 'images/upload/Image/', $article['content']);
            $article['article_type']= '0';
            $article['is_open']     = $row['ifpub'];
            $article['add_time']    = $row['uptime'];

            if (!$db->autoExecute($ecs->table('article'), $article, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 友情链接 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_offer_link";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $link = array();
            $link['link_id']     = $row['linkid'];
            $link['link_name']   = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['linktitle']));
            $link['link_url']    = $row['linkurl'];
            $link['show_order']  = '0';

            if ($row['linktype'] == 'image')
            {
                $link['link_logo']   = 'data/afficheimg/'.$row['imgurl'];
            }

            if (!$db->autoExecute($ecs->table('friend_link'), $link, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 返回 */
        return TRUE;
    }

    /**
     * 订单
     */
    function process_order()
    {
        global $db, $ecs;

        /* 清空订单、订单商品 */
        truncate_table('order_info');
        truncate_table('order_goods');
        truncate_table('order_action');

        /* 订单 */
        $sql = "SELECT o.*, t.tmethod, p.payment FROM ".$this->sprefix."mall_orders AS o " .
                "LEFT JOIN ".$this->sprefix."mall_offer_t AS t ON o.ttype = t.id " .
                "LEFT JOIN ".$this->sprefix."mall_offer_p AS p ON o.ptype = p.id";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $order = array();
            $order['order_sn']          = $row['orderid'];
            $order['user_id']           = $row['userid'];
            $order['add_time']          = $row['ordertime'];
            $order['consignee']         = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['name']));
            $order['address']           = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['addr']));
            $order['zipcode']           = $row['zip'];
            $order['tel']               = $row['tel'];
            $order['mobile']            = $row['mobile'];
            $order['email']             = $row['email'];
            $order['postscript']        = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['memo']));
            $order['shipping_name']     = is_null($row['tmethod']) ? ' ' : ecs_iconv($this->scharset, $this->tcharset, addslashes($row['tmethod']));
            $order['pay_name']          = is_null($row['payment']) ? ' ' : ecs_iconv($this->scharset, $this->tcharset, addslashes($row['payment']));
            $order['inv_payee']         = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['invoiceform']));
            $order['goods_amount']      = $row['item_amount'];
            $order['shipping_fee']      = $row['freight'];
            $order['order_amount']      = $row['total_amount'];
            $order['pay_time']          = $row['paytime'];
            $order['shipping_time']     = $row['sendtime'];

            /* 状态 */
            if ($row['ordstate'] == '0')
            {
                $order['order_status']      = OS_UNCONFIRMED;
                $order['shipping_status']   = SS_UNSHIPPED;
            }
            elseif ($row['ordstate'] == '1')
            {
                $order['order_status']      = OS_CONFIRMED;
                $order['shipping_status']   = SS_UNSHIPPED;
            }
            elseif ($row['ordstate'] == '9')
            {
                $order['order_status']      = OS_INVALID;
                $order['shipping_status']   = SS_UNSHIPPED;
            }
            else // 3 发货 4 归档
            {
                $order['order_status']      = OS_CONFIRMED;
                $order['shipping_status']   = SS_SHIPPED;
            }

            if ($row['ifsk'] == '1')
            {
                $order['pay_status']        = PS_PAYED;
            }
            else // 0 未付款 5 退款
            {
                $order['pay_status']        = PS_UNPAYED;
            }

            if ($row['userrecsts'] == '1') // 用户操作了
            {
                if ($row['recsts'] == '1') // 到货
                {
                    if ($order['shipping_status'] == SS_SHIPPED)
                    {
                        $order['shipping_status'] = SS_RECEIVED;
                    }
                }
                elseif ($row['recsts'] == '2') // 取消
                {
                    $order['order_status']      = OS_CANCELED;
                    $order['pay_status']        = PS_UNPAYED;
                    $order['shipping_status']   = SS_UNSHIPPED;
                }
            }

            /* 如果已付款，修改已付款金额为订单总金额，修改订单总金额为0 */
            if ($order['pay_status'] > PS_UNPAYED)
            {
                $order['money_paid']    = $order['order_amount'];
                $order['order_amount']  = 0;
            }

            if (!$db->autoExecute($ecs->table('order_info'), $order, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }

            /* 订单商品 */
            $order_id = $db->insert_id();
            $sql = "SELECT i.*, g.priceintro FROM ".$this->sprefix."mall_items AS i " .
                    "LEFT JOIN ".$this->sprefix."mall_goods AS g ON i.gid = g.gid " .
                    "WHERE orderid = '$row[orderid]'";
            $res1 = $this->sdb->query($sql);
            while ($row = $this->sdb->fetchRow($res1))
            {
                $goods = array();
                $goods['order_id']      = $order_id;
                $goods['goods_id']      = $row['gid'];
                $goods['goods_name']    = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['goods']));
                $goods['goods_sn']      = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['bn']));
                $goods['goods_number']  = $row['nums'];
                $goods['goods_price']   = $row['price'];
                $goods['market_price']  = is_null($row['priceintro']) ? $row['goods_price'] : $row['priceintro'];
                $goods['is_real']       = 1;
                $goods['parent_id']     = 0;
                $goods['is_gift']       = 0;

                if (!$db->autoExecute($ecs->table('order_goods'), $goods, 'INSERT', '', 'SILENT'))
                {
                    //return $db->error();
                }
            }
        }

        /* 返回 */
        return TRUE;
    }

    /**
     * 商店设置
     */
    function process_config()
    {
        global $ecs, $db;

        /* 查询设置 */
        $sql = "SELECT * FROM ".$this->sprefix."mall_offer " .
                "WHERE offerid = '1'";
        $row = $this->sdb->getRow($sql);

        $config = array();
        $config['shop_name']        = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['offer_name']));
        $config['shop_title']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['offer_shoptitle']));
        $config['shop_desc']        = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['offer_metadesc']));
        $config['shop_address']     = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['offer_addr']));
        $config['service_email']    = $row['offer_email'];
        $config['service_phone']    = $row['offer_tel'];
        $config['icp_number']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['offer_certtext']));
        //$config['integral_scale']   = $row['offer_pointtype'] == '0' ? '0' : $row['offer_pointnum'] * 100;
        $config['thumb_width']      = $row['offer_smallsize_w'];
        $config['thumb_height']     = $row['offer_smallsize_h'];
        $config['image_width']      = $row['offer_bigsize_w'];
        $config['image_height']     = $row['offer_bigsize_h'];
        $config['promote_number']   = $row['offer_tejianums'];
        $config['best_number']      = $row['offer_tjnums'];
        $config['new_number']       = $row['offer_newgoodsnums'];
        $config['hot_number']       = $row['offer_hotnums'];
        $config['smtp_host']        = $row['offer_smtp_server'];
        $config['smtp_port']        = $row['offer_smtp_port'];
        $config['smtp_user']        = $row['offer_smtp_user'];
        $config['smtp_pass']        = $row['offer_smtp_password'];
        $config['smtp_mail']        = $row['offer_smtp_email'];

        /* 更新 */
        foreach ($config as $code => $value)
        {
            $sql = "UPDATE " . $ecs->table('shop_config') . " SET " .
                    "value = '$value' " .
                    "WHERE code = '$code' LIMIT 1";
            if (!$db->query($sql, 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 返回 */
        return TRUE;
    }

}

?>