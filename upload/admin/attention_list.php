<?php

/**
 * ECSHOP 程序说明
 * ===========================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ==========================================================
 * $Author: liubo $
 * $Id: attention_list.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
admin_priv('attention_list');
if ($_REQUEST['act'] == 'list')
{
    $goodsdb = get_attention();
    $smarty->assign('full_page',    1);
    $smarty->assign('ur_here',      $_LANG['attention_list']);
    $smarty->assign('goodsdb',      $goodsdb['goodsdb']);
    $smarty->assign('filter',       $goodsdb['filter']);
    $smarty->assign('cfg_lang',     $_CFG['lang']);
    $smarty->assign('record_count', $goodsdb['record_count']);
    $smarty->assign('page_count',   $goodsdb['page_count']);
    assign_query_info();
    $smarty->display('attention_list.htm');
}
elseif ($_REQUEST['act'] == 'query')
{
    $goodsdb = get_attention();
    $smarty->assign('goodsdb',      $goodsdb['goodsdb']);
    $smarty->assign('filter',       $goodsdb['filter']);
    $smarty->assign('record_count', $goodsdb['record_count']);
    $smarty->assign('page_count',   $goodsdb['page_count']);
    make_json_result($smarty->fetch('attention_list.htm'), '',
        array('filter' => $goodsdb['filter'], 'page_count' => $goodsdb['page_count']));
}
elseif ($_REQUEST['act'] == 'addtolist')
{
    $id = intval($_REQUEST['id']);
    $pri = (intval($_REQUEST['pri']) == 1) ? 1 : 0;
    $start = empty($_GET['start']) ? 0 : (int)$_GET['start'];

    $sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('goods') . " g"
        ." LEFT JOIN " . $GLOBALS['ecs']->table('collect_goods') . " c"
            . " ON g.goods_id = c.goods_id"
        ." LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u"
            . " ON c.user_id = u.user_id" .
         " WHERE c.is_attention = 1 AND g.is_delete = 0 AND c.goods_id = '$id'";

    $count = $db->getOne($sql);

    if ($count > $start)
    {
        $sql = "SELECT u.user_name, u.email, g.goods_name, g.goods_id FROM " . $GLOBALS['ecs']->table('goods') . " g LEFT JOIN " . $GLOBALS['ecs']->table('collect_goods') . " c ON g.goods_id = c.goods_id LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u ON c.user_id = u.user_id" .
               " WHERE c.is_attention = 1 AND g.is_delete = 0 AND c.goods_id = '$id' LIMIT $start,100";
        $query = $db->query($sql);
        $add = '';        
        $template = $db->getRow("SELECT * FROM " . $ecs->table('mail_templates') . " WHERE  template_code = 'attention_list' AND type = 'template'");

        $i = 0;
        while ($rt = $db->fetch_array($query))
        {
            $time = time();
            $goods_url = $ecs->url() . build_uri('goods', array('gid' => $id), $rt['goods_name']);
            $smarty->assign(array('user_name'=>$rt['user_name'],'goods_name'=>$rt['goods_name'],'goods_url'=>$goods_url,'shop_name'=>$_CFG['shop_title'], 'send_date'=>local_date($_CFG['date_format'])));
            $content = $smarty->fetch("str:$template[template_content]");
            $add .= $add ? ",('$rt[email]','$template[template_id]','$content','$pri','$time')" : "('$rt[email]','$template[template_id]','$content','$pri','$time')";
            $i++;
        }
        if ($add)
        {
            $sql = "INSERT INTO "  . $ecs->table('email_sendlist') . " (email,template_id,email_content,pri,last_send) VALUES " . $add;
            $db->query($sql);
        }
        if($i == 100)
        {
            $start = $start + 100;
        }
        else
        {
            $start = $start + $i;
        }
        $links[] = array('text' => sprintf($_LANG['finish_list'],$start), 'href' => "attention_list.php?act=addtolist&id=$id&pri=$pri&start=$start");
        sys_msg($_LANG['finishing'], 0, $links);
    }
    else
    {
        $links[] = array('text' => $_LANG['attention_list'], 'href' => 'attention_list.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}
elseif ($_REQUEST['act'] == 'batch_addtolist')
{
    $olddate = $_REQUEST['date'];
    $date = local_strtotime(trim($_REQUEST['date']));
    $pri = (intval($_REQUEST['pri']) == 1) ? 1 : 0;
    $start = empty($_GET['start']) ? 0 : (int)$_GET['start'];

    $sql = "SELECT count(*) FROM " . $GLOBALS['ecs']->table('goods') . " g"
        ." LEFT JOIN " . $GLOBALS['ecs']->table('collect_goods') . " c"
            . " ON g.goods_id = c.goods_id"
        ." LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u"
            . " ON c.user_id = u.user_id" .
         " WHERE c.is_attention = 1 AND g.is_delete = 0 AND g.last_update >= '$date'";

    $count = $db->getOne($sql);

    if ($count > $start)
    {
        $sql = "SELECT u.user_name, u.email, g.goods_name, g.goods_id FROM " . $GLOBALS['ecs']->table('goods') . " g LEFT JOIN " . $GLOBALS['ecs']->table('collect_goods') . " c ON g.goods_id = c.goods_id LEFT JOIN " . $GLOBALS['ecs']->table('users') . " u ON c.user_id = u.user_id" .
               " WHERE c.is_attention = 1 AND g.is_delete = 0 AND g.last_update >= '$date' LIMIT $start,100";
        $query = $db->query($sql);
        $add = '';

        $template = $db->getRow("SELECT * FROM " . $ecs->table('mail_templates') . " WHERE  template_code = 'attention_list' AND type = 'template'");

        $i = 0;
        while ($rt = $db->fetch_array($query))
        {
            $time = time();

            $goods_url = $ecs->url() . build_uri('goods', array('gid' => $rt['goods_id']), $rt['user_name']);

            $smarty->assign(array('user_name'=>$rt['user_name'],'goods_name'=>$rt['goods_name'],'goods_url'=>$goods_url));
            $content = $smarty->fetch("str:$template[template_content]");
            $add .= $add ? ",('$rt[email]','$template[template_id]','$content','$pri','$time')" : "('$rt[email]','$template[template_id]','$content','$pri','$time')";
            $i++;
        }
        if ($add)
        {
            $sql = "INSERT INTO "  . $ecs->table('email_sendlist') . " (email,template_id,email_content,pri,last_send) VALUES " . $add;
            $db->query($sql);
        }
        if($i == 100)
        {
            $start = $start + 100;
        }
        else
        {
            $start = $start + $i;
        }
        $links[] = array('text' => sprintf($_LANG['finish_list'],$start), 'href' => "attention_list.php?act=batch_addtolist&date=$olddate&pri=$pri&start=$start");
        sys_msg($_LANG['finishing'], 0, $links);
    }
    else
    {
        $links[] = array('text' => $_LANG['attention_list'], 'href' => 'attention_list.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}
function get_attention()
{

    $result = get_filter();

    if ($result === false)
    {
         $where = 'WHERE c.is_attention = 1 AND g.is_delete = 0 ';
        if (!empty($_POST['goods_name']))
        {
            $goods_name = trim($_POST['goods_name']);
            $where .= " AND g.goods_name LIKE '%$goods_name%'";
            $filter['goods_name'] = $goods_name;
        }

        $filter['sort_by']      = empty($_REQUEST['sort_by']) ? 'last_update' : trim($_REQUEST['sort_by']);
        $filter['sort_order']   = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $sql = "SELECT COUNT(DISTINCT c.goods_id) FROM " . $GLOBALS['ecs']->table('collect_goods') . " c " .
                "LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " g ON c.goods_id = g.goods_id " .
                    $where;

        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        /* 分页大小 */
        $filter = page_and_size($filter);

        /* 查询 */
        $sql = "SELECT DISTINCT c.goods_id, g.goods_name, g.last_update FROM " . $GLOBALS['ecs']->table('collect_goods') . " c " .
                "LEFT JOIN " . $GLOBALS['ecs']->table('goods') . " g ON c.goods_id = g.goods_id " .
                    $where .
               " ORDER BY " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
               " LIMIT " . $filter['start'] . ",$filter[page_size]";
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $goodsdb = $GLOBALS['db']->getAll($sql);
    foreach($goodsdb as $k=>$v)
    {
        $goodsdb[$k]['last_update'] = local_date('Y-m-d', $v['last_update']);
    }

    $arr = array('goodsdb' => $goodsdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    return $arr;
}
?>
