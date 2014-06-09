<?php

/**
 * shopex4.8转换程序插件
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: shopex48.php 17217 2011-01-19 06:29:08Z liubo $
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
    $modules[$i]['desc'] = 'shopex48_desc';

    /* 作者 */
    $modules[$i]['author'] = 'ECSHOP R&D TEAM';

    return;
}

/* 类 */
class shopex48
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
    function shopex48(&$sdb, $sprefix, $sroot, $scharset = 'UTF8')
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
         $this->sprefix.'goods',
       );
    }

    /**
     * 必需的目录
     * @return  array
     */
    function required_dirs()
    {
        return array(
            '/images/goods/',
            '/images/brand/',
            '/images/link/',
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
            'step_config'    => '',
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
        $from = $this->sroot . '/images/brand/';
        $to   = $this->troot . '/data/brandlogo/';
        copy_dirs($from, $to);

        /* 复制商品图片 */
        $to   = $this->troot . '/images/goods/';

        $from = $this->sroot . '/images/goods/';
        copy_dirs($from, $to);

        /* 复制友情链接图片 */
        $from = $this->sroot . '/images/link/';
        $to   = $this->troot . '/data/afficheimg/';
        copy_dirs($from, $to);

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
        //truncate_table('attribute');

        /* 查询分类并循环处理 */
        $sql = "SELECT * FROM ".$this->sprefix."goods_cat";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $cat = array();
            $cat['cat_id']      = $row['cat_id'];
            $cat['cat_name']    = $row['cat_name'];
            $cat['parent_id']   = $row['parent_id'];
            $cat['sort_order']  = $row['p_order'];

            /* 插入分类 */
            if (!$db->autoExecute($ecs->table('category'), $cat, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 查询商品类型并循环处理 */
        $sql = "SELECT * FROM ".$this->sprefix."goods_type";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $type = array();
            $type['cat_id']     = $row['prop_cat_id'];
            $type['cat_name']   = $row['name'];
            $type['enabled']    = '1';
            if (!$db->autoExecute($ecs->table('goods_type'), $type, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 查询属性值并循环处理 */


        /* 返回成功 */
        return true;
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
        $sql = "SELECT * FROM ".$this->sprefix."brand";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $brand_logo = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['brand_logo']));
            $logoarr = explode('|',$brand_logo);
            if(strpos($logoarr[0],'http') === 0){
                $brand_url = $logoarr[0];

            }else{
                $logourl = explode('/',$logoarr[0],3);
                $brand_url = $logourl[2];
            }

            $brand = array(
                'brand_name' => $row['brand_name'],
                'brand_desc' => '',
                'site_url' => ecs_iconv($this->scharset, $this->tcharset, addslashes($row['brand_url'])),
                'brand_logo' => $brand_url
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


        /* 取得商品分类对应的商品类型 */
        $cat_type_list = array();
        $sql = "SELECT cat_id, supplier_cat_id FROM ".$this->sprefix."goods_cat";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $cat_type_list[$row['cat_id']] = $row['supplier_cat_id'];
        }

        /* 查询商品并处理 */
        $sql = "SELECT * FROM ".$this->sprefix."goods";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $goods = array();


            $goods['goods_id']      = $row['goods_id'];
            $goods['cat_id']        = $row['cat_id'];
            $goods['goods_sn']    = $row['bn'];
            $goods['goods_name']    = $row['name'];
            $goods['brand_id']      = trim($row['brand']) == '' ? '0' : $brand_list[ecs_iconv($this->scharset, $this->tcharset, addslashes($row['brand']))];
            $goods['goods_number']  = $row['store'];
            $goods['goods_weight']  = $row['weight'];
            $goods['market_price']  = $row['mktprice'];
            $goods['shop_price']    = $row['price'];
            $goods['promote_price']    = $row['name'];
            $goods['goods_brief']    = $row['brief'];
            $goods['goods_desc']    = $row['intro'];
            //$goods['is_on_sale']    = $row['shop_iffb'];
            //$goods['is_alone_sale'] = $row['onsale'];
            $goods['add_time']      = $row['uptime'];
            //$goods['sort_order']    = $row['offer_ord'];
            //$goods['is_delete']     = '0';
            //$goods['is_best']       = $row['recommand2'];
            //$goods['is_new']        = $row['new2'];
            //$goods['is_hot']        = $row['hot2'];
            //$goods['is_promote']    = $row['tejia2'];
            //$goods['goods_type']    = isset($cat_type_list[$row['cat_id']]) ? $cat_type_list[$row['cat_id']] : 0;
            $big_pic = $row['big_pic'];
            $big_pic_arr = explode('|',$big_pic);
            $small_pic = $row['small_pic'];
            $small_pic_arr = explode('|',$small_pic);
            $goods['goods_img']     = $small_pic_arr[0];
            $goods['goods_thumb']   = $small_pic_arr[0];
            $goods['original_img']  = $small_pic_arr[0];
            $goods['last_update'] = gmtime();

            /* 插入 */
            if (!$db->autoExecute($ecs->table('goods'), $goods, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }

            /* 商品相册 */
            $sql2 = "SELECT * FROM ".$this->sprefix."gimages";
            $result = $this->sdb->query($sql2);
            while ($row2 = $this->sdb->fetchRow($result))
            {
                $goods_gallery = array();
                $goods_gallery['goods_id'] = $row2['goods_id'];
                $big_pic = $row2['big'];
                $big_pic_arr = explode('|',$big_pic);
                $goods_gallery['img_original']     = $big_pic_arr[0];
                $small_pic = $row2['small'];
                $small_pic_arr = explode('|',$small_pic);
                $goods_gallery['thumb_url']   = $small_pic_arr[0];
                $goods_gallery['img_url'] = $goods_gallery['thumb_url'];
                //$goods['original_img']  = $big_pic;

                 if (!$db->autoExecute($ecs->table('goods_gallery'), $goods_gallery, 'INSERT', '', 'SILENT'))
                 {
                        //return $db->error();
                 }
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
        $sql = "SELECT * FROM ".$this->sprefix."member_lv order by point desc";
        $res = $this->sdb->query($sql);
        $max_points = 50000;
        while ($row = $this->sdb->fetchRow($res))
        {
            $user_rank = array();
            $user_rank['rank_id']       = $row['member'];
            $user_rank['rank_name']     = $row['name'];
            $user_rank['min_points']    = $row['point'];
            $user_rank['max_points']    = $max_points;
            $user_rank['discount']      = round($row['dis_count'] * 100);
            $user_rank['show_price']    = '1';
            $user_rank['special_rank']  = '0';

            if (!$db->autoExecute($ecs->table('user_rank'), $user_rank, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }

            $max_points = $row['point'] - 1;
        }

        /* 查询并插入会员 */
        $sql = "SELECT * FROM ".$this->sprefix."members";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $user = array();
            $user['user_id']        = $row['member_id'];
            $user['email']          = $row['email'];
            $user['user_name']      = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['uname']));
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
            $user['last_ip']        = $row['reg_ip'];
            $user['visit_count']    = '1';
            $user['user_rank']      = '0';

            if (!$db->autoExecute($ecs->table('users'), $user, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
            //uc_call('uc_user_register', array($user['user_name'], $user['password'], $user['email']));
        }

        /* 收货人地址 */
        $sql = "SELECT * FROM ".$this->sprefix."member_addrs";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $address = array();
            $address['address_id']      = $row['addr_id'];
            $address['address_name']    = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['name']));
            $address['user_id']         = $row['member_id'];
            $address['consignee']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['name']));
            //$address['email']           = $row['email'];
            $address['address']         = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['addr']));
            $address['zipcode']         = $row['zip'];
            $address['tel']             = $row['tel'];
            $address['mobile']          = $row['mobile'];
            $address['country']         = $row['country'];
            $address['province']        = $row['province'];
            $address['city']            = $row['city'];

            if (!$db->autoExecute($ecs->table('user_address'), $address, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }
        }

        /* 会员价格 */
        $temp_arr = array();
        $sql = "SELECT * FROM ".$this->sprefix."goods_lv_price";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            if ($row['goods_id'] > 0 && $row['level_id'] > 0 && !isset($temp_arr[$row['goods_id']][$row['level_id']]))
            {
                $temp_arr[$row['goods_id']][$row['level_id']] = true;

                $member_price = array();
                $member_price['goods_id']   = $row['goods_id'];
                $member_price['user_rank']  = $row['level_id'];
                $member_price['user_price'] = $row['price'];

                if (!$db->autoExecute($ecs->table('member_price'), $member_price, 'INSERT', '', 'SILENT'))
                {
                    //return $db->error();
                }
            }
        }
        unset($temp_arr);

        /* 帐户明细 */
        $sql = "SELECT * FROM ".$this->sprefix."advance_logs";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $user_account = array();
            $user_account['user_id']        = $row['member_id'];
            $user_account['admin_user']     = $row['memo'];
            $user_account['amount']         = $row['money'];
            $user_account['add_time']       = $row['mtime'];
            $user_account['paid_time']      = $row['mtime'];
            $user_account['admin_note']     = $row['message'];
            $user_account['payment']        = $row['paymethod'];
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
        //truncate_table('article_cat');
        //truncate_table('article');
        truncate_table('friend_link');

        /* 文章 */
        $sql = "SELECT * FROM ".$this->sprefix."articles";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $article = array();
            $article['article_id']  = $row['article_id'];
            $article['cat_id']      = $row['node_id'];
            $article['title']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['title']));
            $article['content']     = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['content']));
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
        $sql = "SELECT * FROM ".$this->sprefix."link";
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $link = array();
            $link['link_id']     = $row['link_id'];
            $link['link_name']   = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['link_name']));
            $link['link_url']    = $row['href'];
            $link['show_order']  = '0';
            $link_logo           = $row['image_url'];
            $logoarr             = explode('|',$link_logo);
            $logourl             = explode('/',$logoarr[0],3);
            $link['link_logo']   = 'data/afficheimg/'.$logourl[2];
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
        $sql = "SELECT o.* FROM ".$this->sprefix."orders AS o " ;
        $res = $this->sdb->query($sql);
        while ($row = $this->sdb->fetchRow($res))
        {
            $order = array();
            $order['order_sn']          = $row['order_id'];
            $order['user_id']           = $row['member_id'];
            $order['add_time']          = $row['createtime'];
            $order['consignee']         = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['consignee']));
            $order['address']           = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['ship_addr']));
            $order['zipcode']           = $row['ship_zip'];
            $order['tel']               = $row['ship_tel'];
            $order['mobile']            = $row['ship_mobile'];
            $order['email']             = $row['ship_email'];
            $order['postscript']        = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['memo']));
            $order['shipping_name']     = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['ship_name']));
            $order['pay_name']          = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['shipping']));
            $order['inv_payee']         = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['tax_company']));
            $order['goods_amount']      = $row['total_amount'];
            $order['shipping_fee']      = $row['cost_freight'];
            $order['order_amount']      = $row['final_amount'];
            $order['pay_time']          = $row['paytime'];
            $order['shipping_time']     = $row['acttime'];

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

            if ($row['pay_status'] == '1')
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

            if (!$db->autoExecute($ecs->table('order_info'), $order, 'INSERT', '', 'SILENT'))
            {
                //return $db->error();
            }

            /* 订单商品 */

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
        $sql = "SELECT * FROM ".$this->sprefix."settings";
        $row = $this->sdb->getRow($sql);
        $store = $row['store'];
        $store_arr = unserialize($store);
        $config = array();
        //$config['shop_name']        = ecs_iconv($this->scharset, $this->tcharset, addslashes($store_arr[0]);
        //$config['shop_title']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($store_arr[0]));
        //$config['shop_desc']        = ecs_iconv($this->scharset, $this->tcharset, addslashes($store_arr[1]));
        //$config['shop_address']     = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['store']));
        $config['shop_address'] = $row['store'];
        //$config['service_email']    = $row['offer_email'];
        $config['service_phone']    = $store_arr[2];
        //$config['icp_number']       = ecs_iconv($this->scharset, $this->tcharset, addslashes($row['offer_certtext']));
        //$config['integral_scale']   = $row['offer_pointtype'] == '0' ? '0' : $row['offer_pointnum'] * 100;
        //$config['thumb_width']      = $row['offer_smallsize_w'];
        //$config['thumb_height']     = $row['offer_smallsize_h'];
        //$config['image_width']      = $row['offer_bigsize_w'];
        //$config['image_height']     = $row['offer_bigsize_h'];
        //$config['promote_number']   = $row['offer_tejianums'];
        //$config['best_number']      = $row['offer_tjnums'];
        //$config['new_number']       = $row['offer_newgoodsnums'];
        //$config['hot_number']       = $row['offer_hotnums'];
        //$config['smtp_host']        = $row['offer_smtp_server'];
        //$config['smtp_port']        = $row['offer_smtp_port'];
        //$config['smtp_user']        = $row['offer_smtp_user'];
        //$config['smtp_pass']        = $row['offer_smtp_password'];
        //$config['smtp_mail']        = $row['offer_smtp_email'];

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