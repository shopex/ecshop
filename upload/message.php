<?php

/**
 * ECSHOP 留言板
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: message.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if (empty($_CFG['message_board']))
{
    show_message($_LANG['message_board_close']);
}
$action  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'default';
if ($action == 'act_add_message')
{
    include_once(ROOT_PATH . 'includes/lib_clips.php');

    /* 验证码防止灌水刷屏 */
    if ((intval($_CFG['captcha']) & CAPTCHA_MESSAGE) && gd_version() > 0)
    {
        include_once('includes/cls_captcha.php');
        $validator = new captcha();
        if (!$validator->check_word($_POST['captcha']))
        {
            show_message($_LANG['invalid_captcha']);
        }
    }
    else
    {
        /* 没有验证码时，用时间来限制机器人发帖或恶意发评论 */
        if (!isset($_SESSION['send_time']))
        {
            $_SESSION['send_time'] = 0;
        }

        $cur_time = gmtime();
        if (($cur_time - $_SESSION['send_time']) < 30) // 小于30秒禁止发评论
        {
            show_message($_LANG['cmt_spam_warning']);
        }
    }
    $user_name = '';
    if (empty($_POST['anonymous']) && !empty($_SESSION['user_name']))
    {
        $user_name = $_SESSION['user_name'];
    }
    elseif (!empty($_POST['anonymous']) && !isset($_POST['user_name']))
    {
        $user_name = $_LANG['anonymous'];
    }
    elseif (empty($_POST['user_name']))
    {
        $user_name = $_LANG['anonymous'];
    }
    else
    {
        $user_name = htmlspecialchars(trim($_POST['user_name']));
    }

    $user_id = !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    $message = array(
        'user_id'     => $user_id,
        'user_name'   => $user_name,
        'user_email'  => isset($_POST['user_email']) ? htmlspecialchars(trim($_POST['user_email']))     : '',
        'msg_type'    => isset($_POST['msg_type']) ? intval($_POST['msg_type'])     : 0,
        'msg_title'   => isset($_POST['msg_title']) ? trim($_POST['msg_title'])     : '',
        'msg_content' => isset($_POST['msg_content']) ? trim($_POST['msg_content']) : '',
        'order_id'    => 0,
        'msg_area'    => 1,
        'upload'      => array()
     );

    if (add_message($message))
    {
        if (intval($_CFG['captcha']) & CAPTCHA_MESSAGE)
        {
            unset($_SESSION[$validator->session_word]);
        }
        else
        {
            $_SESSION['send_time'] = $cur_time;
        }
        $msg_info = $_CFG['message_check'] ? $_LANG['message_submit_wait'] : $_LANG['message_submit_done'];
        show_message($msg_info, $_LANG['message_list_lnk'], 'message.php');
    }
    else
    {
        $err->show($_LANG['message_list_lnk'], 'message.php');
    }
}

if ($action == 'default')
{
    assign_template();
    $position = assign_ur_here(0, $_LANG['message_board']);
    $smarty->assign('page_title', $position['title']);    // 页面标题
    $smarty->assign('ur_here',    $position['ur_here']);  // 当前位置
    $smarty->assign('helps',      get_shop_help());       // 网店帮助

    $smarty->assign('categories', get_categories_tree()); // 分类树
    $smarty->assign('top_goods',  get_top10());           // 销售排行
    $smarty->assign('cat_list',   cat_list(0, 0, true, 2, false));
    $smarty->assign('brand_list', get_brand_list());
    $smarty->assign('promotion_info', get_promotion_info());

    $smarty->assign('enabled_mes_captcha', (intval($_CFG['captcha']) & CAPTCHA_MESSAGE));

    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('comment')." WHERE STATUS =1 AND comment_type =0 ";
    $record_count = $db->getOne($sql);
    $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('feedback')." WHERE `msg_area`='1' AND `msg_status` = '1' ";
    $record_count += $db->getOne($sql);

    /* 获取留言的数量 */
    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
    $pagesize = get_library_number('message_list', 'message_board');
    $pager = get_pager('message.php', array(), $record_count, $page, $pagesize);
    $msg_lists = get_msg_list($pagesize, $pager['start']);
    assign_dynamic('message_board');
    $smarty->assign('rand',      mt_rand());
    $smarty->assign('msg_lists', $msg_lists);
    $smarty->assign('pager', $pager);
    $smarty->display('message_board.dwt');
}

/**
 * 获取留言的详细信息
 *
 * @param   integer $num
 * @param   integer $start
 *
 * @return  array
 */
function get_msg_list($num, $start)
{
    /* 获取留言数据 */
    $msg = array();
        
    $mysql_ver = $GLOBALS['db']->version();
    
    if($mysql_ver > '3.2.3')
    {
        $sql = "(SELECT 'comment' AS tablename,   comment_id AS ID, content AS msg_content, null AS msg_title, add_time AS msg_time, id_value AS id_value, comment_rank AS comment_rank, null AS message_img, user_name AS user_name, '6' AS msg_type ";
        $sql .= " FROM " .$GLOBALS['ecs']->table('comment');
        $sql .= "WHERE STATUS =1 AND comment_type =0) ";
        $sql .= " UNION ";
        $sql .= "(SELECT 'feedback' AS tablename, msg_id AS ID, msg_content AS msg_content, msg_title AS msg_title, msg_time AS msg_time, null AS id_value, null AS comment_rank, message_img AS message_img, user_name AS user_name, msg_type AS msg_type ";
        $sql .= " FROM " .$GLOBALS['ecs']->table('feedback');
        $sql .= " WHERE `msg_area`='1' AND `msg_status` = '1') ";
        $sql .= " ORDER BY msg_time DESC ";
    }
    else 
    {
        $con_sql = "SELECT 'comment' AS tablename,   comment_id AS ID, content AS msg_content, null AS msg_title, add_time AS msg_time, id_value AS id_value, comment_rank AS comment_rank, null AS message_img, user_name AS user_name, '6' AS msg_type ";
        $con_sql .= " FROM " .$GLOBALS['ecs']->table('comment');
        $con_sql .= "WHERE STATUS =1 AND comment_type =0 ";
    
        $fee_sql = "SELECT 'feedback' AS tablename, msg_id AS ID, msg_content AS msg_content, msg_title AS msg_title, msg_time AS msg_time, null AS id_value, null AS comment_rank, message_img AS message_img, user_name AS user_name, msg_type AS msg_type ";
        $fee_sql .= " FROM " .$GLOBALS['ecs']->table('feedback');
        $fee_sql .= " WHERE `msg_area`='1' AND `msg_status` = '1' ";
    
        
        $cre_con = "CREATE TEMPORARY TABLE tmp_table ".$con_sql;
        $GLOBALS['db']->query($cre_con);
    
        $cre_con = "INSERT INTO tmp_table ".$fee_sql;
        $GLOBALS['db']->query($cre_con);
    
        $sql = "SELECT * FROM  " .$GLOBALS['ecs']->table('tmp_table') . " ORDER BY msg_time DESC ";
    }

    $res = $GLOBALS['db']->SelectLimit($sql, $num, $start);

    while ($rows = $GLOBALS['db']->fetchRow($res))
    {
        for($i = 0; $i < count($rows); $i++)
        {
        $msg[$rows['msg_time']]['user_name'] = htmlspecialchars($rows['user_name']);
        $msg[$rows['msg_time']]['msg_content'] = str_replace('\r\n', '<br />', htmlspecialchars($rows['msg_content']));
        $msg[$rows['msg_time']]['msg_content'] = str_replace('\n', '<br />', $msg[$rows['msg_time']]['msg_content']);
        $msg[$rows['msg_time']]['msg_time']    = local_date($GLOBALS['_CFG']['time_format'], $rows['msg_time']);
        $msg[$rows['msg_time']]['msg_type']    = $GLOBALS['_LANG']['message_type'][$rows['msg_type']];
        $msg[$rows['msg_time']]['msg_title']   = nl2br(htmlspecialchars($rows['msg_title']));
        $msg[$rows['msg_time']]['message_img'] = $rows['message_img'];
        $msg[$rows['msg_time']]['tablename'] = $rows['tablename'];

            if(isset($rows['order_id']))
            {
                 $msg[$rows['msg_time']]['order_id'] = $rows['order_id'];
            }
            $msg[$rows['msg_time']]['comment_rank'] = $rows['comment_rank'];
            $msg[$rows['msg_time']]['id_value'] = $rows['id_value'];

            /*如果id_value为true为商品评论,根据商品id取出商品名称*/
            if($rows['id_value'])
            {
                $sql_goods = "SELECT goods_name FROM ".$GLOBALS['ecs']->table('goods');
                $sql_goods .= "WHERE goods_id= ".$rows['id_value'];
                $goods_res = $GLOBALS['db']->getRow($sql_goods);
                $msg[$rows['msg_time']]['goods_name'] = $goods_res['goods_name'];
                $msg[$rows['msg_time']]['goods_url'] = build_uri('goods', array('gid' => $rows['id_value']), $goods_res['goods_name']);
            }
        }

        $msg[$rows['msg_time']]['tablename'] = $rows['tablename'];
        $id = $rows['ID'];
        $reply = array();
        if(isset($msg[$rows['msg_time']]['tablename']))
        {
            $table_name = $msg[$rows['msg_time']]['tablename'];

            if ($table_name == 'feedback')
            {
                $sql = "SELECT user_name AS re_name, user_email AS re_email, msg_time AS re_time, msg_content AS re_content ,parent_id".
                 " FROM " .$GLOBALS['ecs']->table('feedback') .
                 " WHERE parent_id = '" . $id. "'";
            }
            else
            {
                $sql = 'SELECT user_name AS re_name, email AS re_email, add_time AS re_time, content AS re_content ,parent_id
                FROM ' . $GLOBALS['ecs']->table('comment') .
                " WHERE parent_id = $id ";

            }
            $reply = $GLOBALS['db']->getRow($sql);
            if ($reply)
            {
                $msg[$rows['msg_time']]['re_name']   = $reply['re_name'];
                $msg[$rows['msg_time']]['re_email']  = $reply['re_email'];
                $msg[$rows['msg_time']]['re_time']    = local_date($GLOBALS['_CFG']['time_format'], $reply['re_time']);
                $msg[$rows['msg_time']]['re_content'] = nl2br(htmlspecialchars($reply['re_content']));
            }
        }

    }

    return $msg;
}

?>
