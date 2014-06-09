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
 * $Id: magazine_list.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
admin_priv('magazine_list');
if ($_REQUEST['act'] == 'list')
{
    $smarty->assign('ur_here', $_LANG['magazine_list']);
    $smarty->assign('action_link', array('text' => $_LANG['add_new'], 'href' => 'magazine_list.php?act=add'));
    $smarty->assign('full_page',  1);

    $magazinedb = get_magazine();

    $smarty->assign('magazinedb',   $magazinedb['magazinedb']);
    $smarty->assign('filter',       $magazinedb['filter']);
    $smarty->assign('record_count', $magazinedb['record_count']);
    $smarty->assign('page_count',   $magazinedb['page_count']);

    $special_ranks = get_rank_list();
    $send_rank[SEND_LIST . '_0'] = $_LANG['email_user'];
    $send_rank[SEND_USER . '_0'] = $_LANG['user_list'];
    foreach($special_ranks as $rank_key => $rank_value)
    {
        $send_rank[SEND_RANK . '_' . $rank_key] = $rank_value;
    }
    $smarty->assign('send_rank',   $send_rank);

    assign_query_info();
    $smarty->display('magazine_list.htm');
}
elseif ($_REQUEST['act'] == 'query')
{
    $magazinedb = get_magazine();
    $smarty->assign('magazinedb',   $magazinedb['magazinedb']);
    $smarty->assign('filter',       $magazinedb['filter']);
    $smarty->assign('record_count', $magazinedb['record_count']);
    $smarty->assign('page_count',   $magazinedb['page_count']);

    $sort_flag  = sort_flag($magazinedb['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('magazine_list.htm'), '', array('filter' => $magazinedb['filter'], 'page_count' => $magazinedb['page_count']));
}
elseif ($_REQUEST['act'] == 'add')
{
    if (empty($_POST['step']))
    {
        include_once(ROOT_PATH.'includes/fckeditor/fckeditor.php'); // 包含 html editor 类文件
        $smarty->assign('action_link', array('text' => $_LANG['go_list'], 'href' => 'magazine_list.php?act=list'));
        $smarty->assign(array('ur_here'=>$_LANG['magazine_list'],'act'=>'add'));
        create_html_editor('magazine_content');
        assign_query_info();
        $smarty->display('magazine_list_add.htm');
    }
    elseif ($_POST['step'] == 2)
    {
        $magazine_name = trim($_POST['magazine_name']);
        $magazine_content = trim($_POST['magazine_content']);
        $magazine_content = str_replace('src=\"','src=\"http://'.$_SERVER['HTTP_HOST'],$magazine_content);
        $time = gmtime();
        $sql = "INSERT INTO " . $ecs->table('mail_templates') . " (template_code, is_html,template_subject, template_content, last_modify, type) VALUES('" . md5($magazine_name.$time) . "',1, '$magazine_name', '$magazine_content', '$time', 'magazine')";
        $db->query($sql);
        $links[] = array('text' => $_LANG['magazine_list'], 'href' => 'magazine_list.php?act=list');
        $links[] = array('text' => $_LANG['add_new'], 'href' => 'magazine_list.php?act=add');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}
elseif ($_REQUEST['act'] == 'edit')
{
    include_once(ROOT_PATH.'includes/fckeditor/fckeditor.php'); // 包含 html editor 类文件
    $id = intval($_REQUEST['id']);
    if (empty($_POST['step']))
    {
        $rt = $db->getRow("SELECT * FROM "  . $ecs->table('mail_templates') . " WHERE type = 'magazine' AND template_id = '$id'");
        $smarty->assign(array('id'=>$id,'act'=>'edit','magazine_name'=>$rt['template_subject'],'magazine_content'=>$rt['template_content']));
        $smarty->assign(array('ur_here'=>$_LANG['magazine_list'],'act'=>'edit'));
        $smarty->assign('action_link', array('text' => $_LANG['go_list'], 'href' => 'magazine_list.php?act=list'));
        create_html_editor('magazine_content', $rt['template_content']);
        assign_query_info();
        $smarty->display('magazine_list_add.htm');
    }
    elseif ($_POST['step'] == 2)
    {
        $magazine_name = trim($_POST['magazine_name']);
        $magazine_content = trim($_POST['magazine_content']);
        $magazine_content = str_replace('src=\"','src=\"http://'.$_SERVER['HTTP_HOST'],$magazine_content);
        $time = gmtime();
        $db->query("UPDATE " . $ecs->table('mail_templates') . " SET is_html = 1, template_subject = '$magazine_name', template_content = '$magazine_content', last_modify = '$time' WHERE type = 'magazine' AND template_id = '$id'");
        $links[] = array('text' => $_LANG['magazine_list'], 'href' => 'magazine_list.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}
elseif ($_REQUEST['act'] == 'del')
{
    $id = intval($_REQUEST['id']);
    $db->query("DELETE  FROM "  . $ecs->table('mail_templates') . " WHERE type = 'magazine' AND template_id = '$id' LIMIT 1");
    $links[] = array('text' => $_LANG['magazine_list'], 'href' => 'magazine_list.php?act=list');
    sys_msg($_LANG['edit_ok'], 0, $links);
}
elseif ($_REQUEST['act'] == 'addtolist')
{
    $id         = intval($_REQUEST['id']);
    $pri        = !empty($_REQUEST['pri']) ? 1 : 0;
    $start      = empty($_GET['start']) ? 0 : (int)$_GET['start'];
    $send_rank  = $_REQUEST['send_rank'];
    $rank_array = explode('_', $send_rank);
    $template_id = $db->getOne("SELECT template_id FROM "  . $ecs->table('mail_templates') . " WHERE type = 'magazine' AND template_id = '$id'");
    if (!empty($template_id))
    {
        if (SEND_LIST == $rank_array['0'])
        {
            $count = $db->getOne("SELECT COUNT(*) FROM " . $ecs->table('email_list') . "WHERE stat = 1");
            if ($count > $start)
            {
                $sql = "SELECT email FROM " . $ecs->table('email_list') . "WHERE stat = 1 LIMIT $start,100";
                $query = $db->query($sql);
                $add = '';

                $i = 0;
                while ($rt = $db->fetch_array($query))
                {
                    $time = time();
                    $add .= $add ? ",('$rt[email]','$id','$pri','$time')" : "('$rt[email]','$id','$pri','$time')";
                    $i ++ ;
                }
                if ($add)
                {
                    $sql = "INSERT INTO "  . $ecs->table('email_sendlist') . " (email,template_id,pri,last_send) VALUES " . $add;
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
                $links[] = array('text' => sprintf($_LANG['finish_list'],$start), 'href' => "magazine_list.php?act=addtolist&id=$id&pri=$pri&start=$start&send_rank=$send_rank");
                sys_msg($_LANG['finishing'], 0, $links);
            }
            else
            {
                $db->query("UPDATE "  . $ecs->table('mail_templates') . " SET last_send = " . time() . " WHERE type = 'magazine' AND template_id = '$id'");
                $links[] = array('text' => $_LANG['magazine_list'], 'href' => 'magazine_list.php?act=list');
                sys_msg($_LANG['edit_ok'], 0, $links);
            }
        }
        else
        {
            $sql = "SELECT special_rank FROM " . $ecs->table('user_rank') . " WHERE rank_id = '" . $rank_array['1'] . "'";
            $row = $db->getRow($sql);
            if (SEND_USER == $rank_array['0'])
            {
                $count_sql = 'SELECT COUNT(*) FROM ' . $ecs->table('users') . 'WHERE is_validated = 1';
                $email_sql = 'SELECT email FROM ' . $ecs->table('users') . "WHERE is_validated = 1 LIMIT $start,100";
            }
            elseif ($row['special_rank'])
            {
                $count_sql = 'SELECT COUNT(*) FROM ' . $ecs->table('users') . 'WHERE is_validated = 1 AND user_rank = ' . $rank_array['1'];
                $email_sql = 'SELECT email FROM ' . $ecs->table('users') . 'WHERE is_validated = 1 AND user_rank = ' . $rank_array['1'] . " LIMIT $start,100";
            }
            else
            {

                $count_sql = 'SELECT COUNT(*) ' .
                             'FROM ' . $ecs->table('users') . ' AS u LEFT JOIN ' . $ecs->table('user_rank') . ' AS ur ' .
                             "  ON ur.special_rank = '0' AND ur.min_points <= u.rank_points AND ur.max_points > u.rank_points" .
                             " WHERE ur.rank_id = '" . $rank_array['1'] . "' AND u.is_validated = 1";
                $email_sql = 'SELECT u.email ' .
                             'FROM ' . $ecs->table('users') . ' AS u LEFT JOIN ' . $ecs->table('user_rank') . ' AS ur ' .
                             "  ON ur.special_rank = '0' AND ur.min_points <= u.rank_points AND ur.max_points > u.rank_points" .
                             " WHERE ur.rank_id = '" . $rank_array['1'] . "' AND u.is_validated = 1 LIMIT $start,100";
            }

            $count = $db->getOne($count_sql);
            if ($count > $start)
            {
                $query = $db->query($email_sql);
                $add = '';

                $i = 0;
                while ($rt = $db->fetch_array($query))
                {
                    $time = time();
                    $add .= $add ? ",('$rt[email]','$id','$pri','$time')" : "('$rt[email]','$id','$pri','$time')";
                    $i ++ ;
                }
                if ($add)
                {
                    $sql = "INSERT INTO "  . $ecs->table('email_sendlist') . " (email,template_id,pri,last_send) VALUES " . $add;
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
                $links[] = array('text' => sprintf($_LANG['finish_list'],$start), 'href' => "magazine_list.php?act=addtolist&id=$id&pri=$pri&start=$start&send_rank=$send_rank");
                sys_msg($_LANG['finishing'], 0, $links);
            }
            else
            {
                $db->query("UPDATE "  . $ecs->table('mail_templates') . " SET last_send = " . time() . " WHERE type = 'magazine' AND template_id = '$id'");
                $links[] = array('text' => $_LANG['magazine_list'], 'href' => 'magazine_list.php?act=list');
                sys_msg($_LANG['edit_ok'], 0, $links);
            }
        }
    }
    else
    {
        $links[] = array('text' => $_LANG['magazine_list'], 'href' => 'magazine_list.php?act=list');
        sys_msg($_LANG['edit_ok'], 0, $links);
    }
}


function get_magazine()
{
    $result = get_filter();

    if ($result === false)
    {
        $filter['sort_by']      = empty($_REQUEST['sort_by']) ? 'template_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order']   = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $sql = "SELECT count(*) FROM " .$GLOBALS['ecs']->table('mail_templates') . " WHERE type = 'magazine'";
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        /* 分页大小 */
        $filter = page_and_size($filter);

        /* 查询 */
        $sql = "SELECT * ".
               " FROM ".$GLOBALS['ecs']->table('mail_templates') .
               " WHERE type = 'magazine'" .
               " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'] .
               " LIMIT " . $filter['start'] . ',' . $filter['page_size'];

        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $magazinedb = $GLOBALS['db']->getAll($sql);

    foreach($magazinedb as $k => $v)
    {
        $magazinedb[$k]['last_modify'] = local_date('Y-m-d', $v['last_modify']);
        $magazinedb[$k]['last_send'] = local_date('Y-m-d', $v['last_send']);
    }

    $arr = array('magazinedb' => $magazinedb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

?>