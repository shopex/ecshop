<?php

/**
 * ECSHOP google sitemap 文件
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: liubo $
 * $Id: sitemaps.php 17217 2011-01-19 06:29:08Z liubo $
 */

class sitemap
{
    var $head = "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>\n<urlset xmlns=\"http://www.google.com/schemas/sitemap/0.84\">\n";
    var $footer = "</urlset>\n";
    var $item;
    function item($item)
    {
        $this->item .= "<url>\n";
        foreach($item as $key => $val){
            $this->item .=" <$key>".htmlentities($val, ENT_QUOTES)."</$key>\n";
        }
        $this->item .= "</url>\n";
    }
    function generate()
    {
        $all = $this->head;
        $all .= $this->item;
        $all .= $this->footer;

        return $all;
    }
}

define('IN_ECS', true);
define('INIT_NO_USERS', true);
define('INIT_NO_SMARTY', true);
require(dirname(__FILE__) . '/includes/init.php');
if (file_exists(ROOT_PATH . DATA_DIR . '/sitemap.dat') && time() - filemtime(ROOT_PATH . DATA_DIR . '/sitemap.dat') < 86400)
{
    $out = file_get_contents(ROOT_PATH . DATA_DIR . '/sitemap.dat');
}
else
{
    $site_url = rtrim($ecs->url(),'/');
    $sitemap = new sitemap;
    $config = unserialize($_CFG['sitemap']);
    $item = array(
        'loc'        =>  "$site_url/",
        'lastmod'     =>  local_date('Y-m-d'),
        'changefreq' => $config['homepage_changefreq'],
        'priority' => $config['homepage_priority'],
    );
    $sitemap->item($item);
    /* 商品分类 */
    $sql = "SELECT cat_id,cat_name FROM " .$ecs->table('category'). " ORDER BY parent_id";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $item = array(
            'loc'        =>  "$site_url/" . build_uri('category', array('cid' => $row['cat_id']), $row['cat_name']),
            'lastmod'     =>  local_date('Y-m-d'),
            'changefreq' => $config['category_changefreq'],
            'priority' => $config['category_priority'],
        );
        $sitemap->item($item);
    }
    /* 文章分类 */
    $sql = "SELECT cat_id,cat_name FROM " .$ecs->table('article_cat'). " WHERE cat_type=1";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $item = array(
            'loc'        =>  "$site_url/" . build_uri('article_cat', array('acid' => $row['cat_id']), $row['cat_name']),
            'lastmod'     =>  local_date('Y-m-d'),
            'changefreq' => $config['category_changefreq'],
            'priority' => $config['category_priority'],
        );
        $sitemap->item($item);
    }
    /* 商品 */
    $sql = "SELECT goods_id, goods_name, last_update FROM " .$ecs->table('goods'). " WHERE is_delete = 0 LIMIT 300";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $item = array(
            'loc'        =>  "$site_url/" . build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']),
            'lastmod'     =>  local_date('Y-m-d', $row['last_update']),
            'changefreq' => $config['content_changefreq'],
            'priority' => $config['content_priority'],
        );
        $sitemap->item($item);
    }
    /* 文章 */
    $sql = "SELECT article_id,title,file_url,open_type, add_time FROM " .$ecs->table('article'). " WHERE is_open=1";
    $res = $db->query($sql);

    while ($row = $db->fetchRow($res))
    {
        $article_url=$row['open_type'] != 1 ? build_uri('article', array('aid'=>$row['article_id']), $row['title']) : trim($row['file_url']);
        $item = array(
            'loc'        =>  "$site_url/" . $article_url,
            'lastmod'     =>  local_date('Y-m-d', $row['add_time']),
            'changefreq' => $config['content_changefreq'],
            'priority' => $config['content_priority'],
        );
        $sitemap->item($item);
    }
    $out =  $sitemap->generate();
    file_put_contents(ROOT_PATH . DATA_DIR . '/sitemap.dat', $out);
}
if (function_exists('gzencode'))
{
    header('Content-type: application/x-gzip');
    $out = gzencode($out, 9);
}
else
{
    header('Content-type: application/xml; charset=utf-8');
}
die($out);
?>