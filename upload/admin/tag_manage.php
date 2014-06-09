<?php

/**
 * ECSHOP 鍚庡彴鏍囩?绠＄悊
 * ============================================================================
 * * 鐗堟潈鎵€鏈 2005-2012 涓婃捣鍟嗘淳缃戠粶绉戞妧鏈夐檺鍏?徃锛屽苟淇濈暀鎵€鏈夋潈鍒┿€
 * 缃戠珯鍦板潃: http://www.ecshop.com锛
 * ----------------------------------------------------------------------------
 * 杩欎笉鏄?竴涓?嚜鐢辫蒋浠讹紒鎮ㄥ彧鑳藉湪涓嶇敤浜庡晢涓氱洰鐨勭殑鍓嶆彁涓嬪?绋嬪簭浠ｇ爜杩涜?淇?敼鍜
 * 浣跨敤锛涗笉鍏佽?瀵圭▼搴忎唬鐮佷互浠讳綍褰㈠紡浠讳綍鐩?殑鐨勫啀鍙戝竷銆
 * ============================================================================
 * $Author: liubo $
 * $Id: tag_manage.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* act鎿嶄綔椤圭殑鍒濆?鍖 */
$_REQUEST['act'] = trim($_REQUEST['act']);
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'list';
}

/*------------------------------------------------------ */
//-- 鑾峰彇鏍囩?鏁版嵁鍒楄〃
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    /* 鏉冮檺鍒ゆ柇 */
    admin_priv('tag_manage');

    /* 妯℃澘璧嬪€ */
    $smarty->assign('ur_here',      $_LANG['tag_list']);
    $smarty->assign('action_link', array('href' => 'tag_manage.php?act=add', 'text' => $_LANG['add_tag']));
    $smarty->assign('full_page',    1);

    $tag_list = get_tag_list();
    $smarty->assign('tag_list',     $tag_list['tags']);
    $smarty->assign('filter',       $tag_list['filter']);
    $smarty->assign('record_count', $tag_list['record_count']);
    $smarty->assign('page_count',   $tag_list['page_count']);

    $sort_flag  = sort_flag($tag_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    /* 椤甸潰鏄剧ず */
    assign_query_info();
    $smarty->display('tag_manage.htm');
}

/*------------------------------------------------------ */
//-- 娣诲姞 ,缂栬緫
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == 'add' || $_REQUEST['act'] == 'edit')
{
    admin_priv('tag_manage');

    $is_add = $_REQUEST['act'] == 'add';
    $smarty->assign('insert_or_update', $is_add ? 'insert' : 'update');

    if($is_add)
    {
        $tag = array(
            'tag_id' => 0,
            'tag_words' => '',
            'goods_id' => 0,
            'goods_name' => $_LANG['pls_select_goods']
        );
        $smarty->assign('ur_here',      $_LANG['add_tag']);
    }
    else
    {
        $tag_id = $_GET['id'];
        $tag = get_tag_info($tag_id);
        $tag['tag_words']=htmlspecialchars($tag['tag_words']);
        $smarty->assign('ur_here',      $_LANG['tag_edit']);
    }
    $smarty->assign('tag', $tag);
    $smarty->assign('action_link', array('href' => 'tag_manage.php?act=list', 'text' => $_LANG['tag_list']));

    assign_query_info();
    $smarty->display('tag_edit.htm');
}

/*------------------------------------------------------ */
//-- 鏇存柊
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == 'insert' || $_REQUEST['act'] == 'update')
{
    admin_priv('tag_manage');

    $is_insert = $_REQUEST['act'] == 'insert';

    $tag_words = empty($_POST['tag_name']) ? '' : trim($_POST['tag_name']);
    $id = intval($_POST['id']);
    $goods_id = intval($_POST['goods_id']);
    if ($goods_id <= 0)
    {
        sys_msg($_LANG['pls_select_goods']);
    }

    if (!tag_is_only($tag_words, $id, $goods_id))
    {
        sys_msg(sprintf($_LANG['tagword_exist'], $tag_words));
    }

    if($is_insert)
    {
        $sql = 'INSERT INTO ' . $ecs->table('tag') . '(tag_id, goods_id, tag_words)' .
               " VALUES('$id', '$goods_id', '$tag_words')";
        $db->query($sql);

        admin_log($tag_words, 'add', 'tag');

         /* 娓呴櫎缂撳瓨 */
        clear_cache_files();

        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'tag_manage.php?act=list';

        sys_msg($_LANG['tag_add_success'], 0, $link);
    }
    else
    {

        edit_tag($tag_words, $id, $goods_id);

        /* 娓呴櫎缂撳瓨 */
        clear_cache_files();

        $link[0]['text'] = $_LANG['back_list'];
        $link[0]['href'] = 'tag_manage.php?act=list';

        sys_msg($_LANG['tag_edit_success'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 缈婚〉锛屾帓搴
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'query')
{
    check_authz_json('tag_manage');

    $tag_list = get_tag_list();
    $smarty->assign('tag_list',     $tag_list['tags']);
    $smarty->assign('filter',       $tag_list['filter']);
    $smarty->assign('record_count', $tag_list['record_count']);
    $smarty->assign('page_count',   $tag_list['page_count']);

    $sort_flag  = sort_flag($tag_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('tag_manage.htm'), '',
        array('filter' => $tag_list['filter'], 'page_count' => $tag_list['page_count']));
}

/*------------------------------------------------------ */
//-- 鎼滅储
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'search_goods')
{
    check_authz_json('tag_manage');

    include_once(ROOT_PATH . 'includes/cls_json.php');

    $json   = new JSON;
    $filter = $json->decode($_GET['JSON']);
    $arr    = get_goods_list($filter);
    if (empty($arr))
    {
        $arr[0] = array(
            'goods_id'   => 0,
            'goods_name' => ''
        );
    }

    make_json_result($arr);
}

/*------------------------------------------------------ */
//-- 鎵归噺鍒犻櫎鏍囩?
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'batch_drop')
{
    admin_priv('tag_manage');

    if (isset($_POST['checkboxes']))
    {
        $count = 0;
        foreach ($_POST['checkboxes'] AS $key => $id)
        {
            $sql = "DELETE FROM " .$ecs->table('tag'). " WHERE tag_id='$id'";
            $db->query($sql);

            $count++;
        }

        admin_log($count, 'remove', 'tag_manage');
        clear_cache_files();

        $link[] = array('text' => $_LANG['back_list'], 'href'=>'tag_manage.php?act=list');
        sys_msg(sprintf($_LANG['drop_success'], $count), 0, $link);
    }
    else
    {
        $link[] = array('text' => $_LANG['back_list'], 'href'=>'tag_manage.php?act=list');
        sys_msg($_LANG['no_select_tag'], 0, $link);
    }
}

/*------------------------------------------------------ */
//-- 鍒犻櫎鏍囩?
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'remove')
{
    check_authz_json('tag_manage');

    include_once(ROOT_PATH . 'includes/cls_json.php');
    $json = new JSON;

    $id = intval($_GET['id']);

    /* 鑾峰彇鍒犻櫎鐨勬爣绛剧殑鍚嶇О */
    $tag_name = $db->getOne("SELECT tag_words FROM " .$ecs->table('tag'). " WHERE tag_id = '$id'");

    $sql = "DELETE FROM " .$ecs->table('tag'). " WHERE tag_id = '$id'";
    $result = $GLOBALS['db']->query($sql);
    if ($result)
    {
        /* 绠＄悊鍛樻棩蹇 */
        admin_log(addslashes($tag_name), 'remove', 'tag_manage');

        $url = 'tag_manage.php?act=query&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);
        ecs_header("Location: $url\n");
        exit;
    }
    else
    {
       make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
//-- 缂栬緫鏍囩?鍚嶇О
/*------------------------------------------------------ */

elseif($_REQUEST['act'] == "edit_tag_name")
{
    check_authz_json('tag_manage');

    $name = json_str_iconv(trim($_POST['val']));
    $id = intval($_POST['id']);

    if (!tag_is_only($name, $id))
    {
        make_json_error(sprintf($_LANG['tagword_exist'], $name));
    }
    else
    {
        edit_tag($name, $id);
        make_json_result(stripslashes($name));
    }
}

/**
 * 鍒ゆ柇鍚屼竴鍟嗗搧鐨勬爣绛炬槸鍚﹀敮涓€
 *
 * @param $name  鏍囩?鍚
 * @param $id  鏍囩?id
 * @return bool
 */
function tag_is_only($name, $tag_id, $goods_id = '')
{
    if(empty($goods_id))
    {
        $db = $GLOBALS['db'];
        $sql = 'SELECT goods_id FROM ' . $GLOBALS['ecs']->table('tag') . " WHERE tag_id = '$tag_id'";
        $row = $GLOBALS['db']->getRow($sql);
        $goods_id = $row['goods_id'];
    }

    $sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('tag') . " WHERE tag_words = '$name'" .
           " AND goods_id = '$goods_id' AND tag_id != '$tag_id'";

    if($GLOBALS['db']->getOne($sql) > 0)
    {
        return false;
    }
    else
    {
        return true;
    }
}

/**
 * 鏇存柊鏍囩?
 *
 * @param  $name
 * @param  $id
 * @return void
 */
function edit_tag($name, $id, $goods_id = '')
{
    $db = $GLOBALS['db'];
    $sql = 'UPDATE ' . $GLOBALS['ecs']->table('tag') . " SET tag_words = '$name'";
    if(!empty($goods_id))
    {
        $sql .= ", goods_id = '$goods_id'";
    }
    $sql .= " WHERE tag_id = '$id'";
    $GLOBALS['db']->query($sql);

    admin_log($name, 'edit', 'tag');
}

/**
 * 鑾峰彇鏍囩?鏁版嵁鍒楄〃
 * @access  public
 * @return  array
 */
function get_tag_list()
{
    $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 't.tag_id' : trim($_REQUEST['sort_by']);
    $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

    $sql = "SELECT COUNT(*) FROM ".$GLOBALS['ecs']->table('tag');
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    $filter = page_and_size($filter);

    $sql = "SELECT t.tag_id, u.user_name, t.goods_id, g.goods_name, t.tag_words ".
            "FROM " .$GLOBALS['ecs']->table('tag'). " AS t ".
            "LEFT JOIN " .$GLOBALS['ecs']->table('users'). " AS u ON u.user_id=t.user_id ".
            "LEFT JOIN " .$GLOBALS['ecs']->table('goods'). " AS g ON g.goods_id=t.goods_id ".
            "ORDER by $filter[sort_by] $filter[sort_order] LIMIT ". $filter['start'] .", ". $filter['page_size'];
    $row = $GLOBALS['db']->getAll($sql);
    foreach($row as $k=>$v)
    {
        $row[$k]['tag_words'] = htmlspecialchars($v['tag_words']);
    }

    $arr = array('tags' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}

/**
 * 鍙栧緱鏍囩?鐨勪俊鎭
 * return array
 */

function get_tag_info($tag_id)
{
    $sql = 'SELECT t.tag_id, t.tag_words, t.goods_id, g.goods_name FROM ' . $GLOBALS['ecs']->table('tag') . ' AS t' .
           ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON t.goods_id=g.goods_id' .
           " WHERE tag_id = '$tag_id'";
    $row = $GLOBALS['db']->getRow($sql);

    return $row;
}

?>
