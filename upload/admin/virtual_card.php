<?php

/**
 * ECSHOP 虚拟卡商品管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: virtual_card.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);

/* 包含文件 */
require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_code.php');

/*------------------------------------------------------ */
//-- 补货处理
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'replenish')
{
    assign_query_info();

    /* 检查权限 */
    admin_priv('virualcard');
    /* 验证goods_id是否合法 */
    if (empty($_REQUEST['goods_id']))
    {
        $link[] = array('text'=>$_LANG['go_back'],'href'=>'virtual_card.php?act=list');
        sys_msg($_LANG['replenish_no_goods_id'], 1, $link);
    }
    else
    {
        $goods_name = $db->GetOne("SELECT goods_name From ".$ecs->table('goods')." WHERE goods_id='".$_REQUEST['goods_id']."' AND is_real = 0 AND extension_code='virtual_card' ");
        if (empty($goods_name))
        {
            $link[] = array('text'=>$_LANG['go_back'],'href'=>'virtual_card.php?act=list');
            sys_msg($_LANG['replenish_no_get_goods_name'],1, $link);
        }
    }

    $card = array('goods_id'=>$_REQUEST['goods_id'],'goods_name'=>$goods_name, 'end_date'=>date('Y-m-d', strtotime('+1 year')));
    $smarty->assign('card', $card);

    $smarty->assign('ur_here', $_LANG['replenish']);
    $smarty->assign('action_link', array('text'=>$_LANG['go_list'], 'href'=>'virtual_card.php?act=card&goods_id='.$card['goods_id']));
    $smarty->display('replenish_info.htm');
}

/*------------------------------------------------------ */
//-- 编辑补货信息
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'edit_replenish')
{
    /* 检查权限 */
    admin_priv('virualcard');
    /* 获取卡片信息 */
    $sql = "SELECT T1.card_id, T1.goods_id, T2.goods_name,T1.card_sn, T1.card_password, T1.end_date, T1.crc32 FROM ".
            $ecs->table('virtual_card')." AS T1, ".$ecs->table('goods')." AS T2 ".
            "WHERE T1.goods_id = T2.goods_id AND T1.card_id = '$_REQUEST[card_id]'";
    $card = $db->GetRow($sql);
    if ($card['crc32'] == 0 || $card['crc32'] == crc32(AUTH_KEY))
    {
        $card['card_sn']       = decrypt($card['card_sn']);
        $card['card_password'] = decrypt($card['card_password']);
    }
    elseif ($card['crc32'] == crc32(OLD_AUTH_KEY))
    {
        $card['card_sn']       = decrypt($card['card_sn'], OLD_AUTH_KEY);
        $card['card_password'] = decrypt($card['card_password'], OLD_AUTH_KEY);
    }
    else
    {
        $card['card_sn']       = '***';
        $card['card_password'] = '***';
    }

    $smarty->assign('ur_here',     $_LANG['replenish']);
    $smarty->assign('action_link', array('text'=>$_LANG['go_list'], 'href'=>'virtual_card.php?act=card&goods_id='.$card['goods_id']));
    $smarty->assign('card',        $card);
    $smarty->display('replenish_info.htm');
}

elseif ($_REQUEST['act'] == 'action')
{
    /* 检查权限 */
    admin_priv('virualcard');

    $_POST['card_sn'] = trim($_POST['card_sn']);

    /* 加密后的 */
    $coded_card_sn       = encrypt($_POST['card_sn']);
    $coded_old_card_sn   = encrypt($_POST['old_card_sn']);
    $coded_card_password = encrypt($_POST['card_password']);

    /* 在前后两次card_sn不一致时，检查是否有重复记录,一致时直接更新数据 */
    if ($_POST['card_sn'] != $_POST['old_card_sn'])
    {
        $sql = "SELECT count(*) FROM ".$ecs->table('virtual_card')." WHERE goods_id='".$_POST['goods_id']."' AND card_sn='$coded_card_sn'";

        if ($db->GetOne($sql) > 0)
        {
             $link[] = array('text'=>$_LANG['go_back'], 'href'=>'virtual_card.php?act=replenish&goods_id='.$_POST['goods_id']);
             sys_msg(sprintf($_LANG['card_sn_exist'],$_POST['card_sn']),1,$link);
        }
    }

    /* 如果old_card_sn不存在则新加一条记录 */
    if (empty($_POST['old_card_sn']))
    {
        /* 插入一条新记录 */
        $end_date = strtotime($_POST['end_dateYear'] . "-" . $_POST['end_dateMonth'] . "-" . $_POST['end_dateDay']);
        $add_date = gmtime();
        $sql = "INSERT INTO ".$ecs->table('virtual_card')." (goods_id, card_sn, card_password, end_date, add_date, crc32) ".
               "VALUES ('$_POST[goods_id]', '$coded_card_sn', '$coded_card_password', '$end_date', '$add_date', '" . crc32(AUTH_KEY) . "')";
        $db->query($sql);

        /* 如果添加成功且原卡号为空时商品库存加1 */
        if (empty($_POST['old_card_sn']))
        {
            $sql = "UPDATE ".$ecs->table('goods')." SET goods_number= goods_number+1 WHERE goods_id='$_POST[goods_id]'";
            $db->query($sql);
        }

        $link[] = array('text'=>$_LANG['go_list'], 'href'=>'virtual_card.php?act=card&goods_id='.$_POST['goods_id']);
        $link[] = array('text'=>$_LANG['continue_add'], 'href'=>'virtual_card.php?act=replenish&goods_id='.$_POST['goods_id']);
        sys_msg($_LANG['action_success'], 0, $link);
    }
    else
    {
        /* 更新数据 */
        $end_date = strtotime($_POST['end_dateYear'] . "-" . $_POST['end_dateMonth'] . "-" . $_POST['end_dateDay']);
        $sql = "UPDATE ".$ecs->table('virtual_card')." SET card_sn='$coded_card_sn', card_password='$coded_card_password', end_date='$end_date' ".
               "WHERE card_id='$_POST[card_id]'";
        $db->query($sql);

        $link[] = array('text'=>$_LANG['go_list'], 'href'=>'virtual_card.php?act=card&goods_id='.$_POST['goods_id']);
        $link[] = array('text'=>$_LANG['continue_add'], 'href'=>'virtual_card.php?act=replenish&goods_id='.$_POST['goods_id']);
        sys_msg($_LANG['action_success'], 0, $link);
    }

}
/*------------------------------------------------------ */
//-- 补货列表
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'card')
{
    /* 检查权限 */
    admin_priv('virualcard');

    /* 验证goods_id是否合法 */
    if (empty($_REQUEST['goods_id']))
    {
        $link[] = array('text'=>$_LANG['go_back'],'href'=>'virtual_card.php?act=list');
        sys_msg($_LANG['replenish_no_goods_id'], 1, $link);
    }
    else
    {
        $goods_name = $db->GetOne("SELECT goods_name From ".$ecs->table('goods')." WHERE goods_id='".$_REQUEST['goods_id']."' AND is_real = 0 AND extension_code='virtual_card' ");
        if (empty($goods_name))
        {
            $link[] = array('text'=>$_LANG['go_back'],'href'=>'virtual_card.php?act=list');
            sys_msg($_LANG['replenish_no_get_goods_name'],1, $link);
        }
    }

    if (empty($_REQUEST['order_sn']))
    {
        $_REQUEST['order_sn'] = '';
    }

    $smarty->assign('goods_id',     $_REQUEST['goods_id']);
    $smarty->assign('full_page',    1);
    $smarty->assign('lang',         $_LANG);
    $smarty->assign('ur_here',      $goods_name);
    $smarty->assign('action_link',  array('text'    => $_LANG['replenish'],
                                            'href'  => 'virtual_card.php?act=replenish&goods_id='.$_REQUEST['goods_id']));
    $smarty->assign('goods_id',      $_REQUEST['goods_id']);

    $list = get_replenish_list();

    $smarty->assign('card_list',    $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $sort_flag = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    assign_query_info();
    $smarty->display('replenish_list.htm');
}

/*------------------------------------------------------ */
//-- 虚拟卡列表，用于排序、翻页
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'query_card')
{
    $list = get_replenish_list();

    $smarty->assign('card_list',    $list['item']);
    $smarty->assign('filter',       $list['filter']);
    $smarty->assign('record_count', $list['record_count']);
    $smarty->assign('page_count',   $list['page_count']);

    $sort_flag = sort_flag($list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    make_json_result($smarty->fetch('replenish_list.htm'), '',
        array('filter'=>$list['filter'], 'page_count'=>$list['page_count']));
}

/* 批量删除card */
elseif ($_REQUEST['act'] == 'batch_drop_card')
{
    /* 检查权限 */
    admin_priv('virualcard');

    $num = count($_POST['checkboxes']);
    $sql = "DELETE FROM ".$ecs->table('virtual_card')." WHERE card_id ".db_create_in(implode(',',$_POST['checkboxes']));
    if ($db->query($sql))
    {
        /* 商品数量减$num */
        update_goods_number(intval($_REQUEST['goods_id']));
        $link[] = array('text'=>$_LANG['go_list'], 'href'=>'virtual_card.php?act=card&goods_id='.$_REQUEST['goods_id']);
        sys_msg($_LANG['action_success'], 0, $link);
    }
}

/* 批量上传页面 */

elseif ($_REQUEST['act'] == 'batch_card_add')
{
    /* 检查权限 */
    admin_priv('virualcard');

    $smarty->assign('ur_here',          $_LANG['batch_card_add']);
    $smarty->assign('action_link',      array('text'=>$_LANG['virtual_card_list'], 'href'=>'goods.php?act=list&extension_code=virtual_card'));
    $smarty->assign('goods_id',           $_REQUEST['goods_id']);
    $smarty->display('batch_card_info.htm');
}

elseif ($_REQUEST['act'] == 'batch_confirm')
{
    /* 检查上传是否成功 */
    if ($_FILES['uploadfile']['tmp_name'] == '' || $_FILES['uploadfile']['tmp_name'] == 'none')
    {
        sys_msg($_LANG['uploadfile_fail'], 1);
    }

    $data = file($_FILES['uploadfile']['tmp_name']);
    $rec = array(); //数据数组
    $i = 0;
    $separator = trim($_POST['separator']);
    foreach ($data as $line)
    {
        $row = explode($separator, $line);
        switch(count($row))
        {
            case '3':
                $rec[$i]['end_date'] = $row[2];
            case '2':
                $rec[$i]['card_password'] = $row[1];
            case '1':
                $rec[$i]['card_sn']  = $row[0];
                break;
            default:
                $rec[$i]['card_sn']  = $row[0];
                $rec[$i]['card_password'] = $row[1];
                $rec[$i]['end_date'] = $row[2];
                break;
        }
        $i++;
    }

    $smarty->assign('ur_here',          $_LANG['batch_card_add']);
    $smarty->assign('action_link',      array('text'=>$_LANG['batch_card_add'], 'href'=>'virtual_card.php?act=batch_card_add&goods_id='.$_REQUEST['goods_id']));
    $smarty->assign('list',               $rec);
    $smarty->display('batch_card_confirm.htm');

}
/* 批量上传处理 */
elseif ($_REQUEST['act'] == 'batch_insert')
{
    /* 检查权限 */
    admin_priv('virualcard');

    $add_time = gmtime();
    $i = 0;
    foreach ($_POST['checked'] as $key)
    {
        $rec['card_sn']  = encrypt($_POST['card_sn'][$key]);
        $rec['card_password'] = encrypt($_POST['card_password'][$key]);
        $rec['crc32']    = crc32(AUTH_KEY);
        $rec['end_date'] = empty($_POST['end_date'][$key]) ? 0 : strtotime($_POST['end_date'][$key]);
        $rec['goods_id'] = $_POST['goods_id'];
        $rec['add_date'] = $add_time;
        $db->AutoExecute($ecs->table('virtual_card'), $rec, 'INSERT');
        $i++;
    }

    /* 更新商品库存 */
    update_goods_number(intval($_REQUEST['goods_id']));
    $link[] = array('text' => $_LANG['card'] , 'href' => 'virtual_card.php?act=card&goods_id='.$_POST['goods_id']);
    sys_msg(sprintf($_LANG['batch_card_add_ok'], $i) , 0, $link);
}

/*------------------------------------------------------ */
//-- 更改加密串
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'change')
{
    /* 检查权限 */
    admin_priv('virualcard');

    $smarty->assign('ur_here', $_LANG['virtual_card_change']);

    assign_query_info();
    $smarty->display('virtual_card_change.htm');
}

/*------------------------------------------------------ */
//-- 提交更改
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'submit_change')
{
    /* 检查权限 */
    admin_priv('virualcard');

    if (isset($_POST['old_string']) && isset($_POST['new_string']))
    {
        // 检查原加密串是否正确
        if ($_POST['old_string'] != OLD_AUTH_KEY)
        {
            sys_msg($_LANG['invalid_old_string'], 1);
        }

        // 检查新加密串是否正确
        if ($_POST['new_string'] != AUTH_KEY)
        {
            sys_msg($_LANG['invalid_new_string'], 1);
        }

        // 检查原加密串和新加密串是否相同
        if ($_POST['old_string'] == $_POST['new_string'] || crc32($_POST['old_string']) == crc32($_POST['new_string']))
        {
            sys_msg($_LANG['same_string'], 1);
        }



        // 重新加密卡号和密码
        $old_crc32 = crc32($_POST['old_string']);
        $new_crc32 = crc32($_POST['new_string']);
        $sql = "SELECT card_id, card_sn, card_password FROM " . $ecs->table('virtual_card') . " WHERE crc32 = '$old_crc32'";
        $res = $db->query($sql);
        while ($row = $db->fetchRow($res))
        {
            $row['card_sn'] = encrypt(decrypt($row['card_sn'], $_POST['old_string']), $_POST['new_string']);
            $row['card_password'] = encrypt(decrypt($row['card_password'], $_POST['old_string']), $_POST['new_string']);
            $row['crc32'] = $new_crc32;
            $db->autoExecute($ecs->table('virtual_card'), $row, 'UPDATE', 'card_id = ' . $row['card_id']);
        }

        // 记录日志
        //admin_log();

        // 返回
        sys_msg($_LANG['change_key_ok'], 0, array(array('href' => 'virtual_card.php?act=list', 'text' => $_LANG['virtual_card_list'])));
    }
}

/*------------------------------------------------------ */
//-- 切换是否已出售状态
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'toggle_sold')
{
    check_authz_json('virualcard');

    $id = intval($_POST['id']);
    $val = intval($_POST['val']);

    $sql = "UPDATE ".$ecs->table('virtual_card')." SET is_saled= '$val' WHERE card_id='$id'";

    if ($db->query($sql, 'SILENT'))
    {
        /* 修改商品库存 */
        $sql = "SELECT goods_id FROM " . $ecs->table('virtual_card') . " WHERE card_id = '$id' LIMIT 1";
        $goods_id = $db->getOne($sql);

        update_goods_number($goods_id);
        make_json_result($val);
    }
    else
    {
        make_json_error($_LANG['action_fail'] . "\n" .$db->error());
    }
}

/*------------------------------------------------------ */
//-- 删除卡片
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'remove_card')
{
    check_authz_json('virualcard');

    $id = intval($_GET['id']);

    $row = $db->GetRow('SELECT card_sn, goods_id FROM ' . $ecs->table('virtual_card') . " WHERE card_id = '$id'");

    $sql = 'DELETE FROM ' . $ecs->table('virtual_card') . " WHERE card_id = '$id'";
    if ($db->query($sql, 'SILENT'))
    {
        /* 修改商品数量 */
        update_goods_number($row['goods_id']);

        $url = 'virtual_card.php?act=query_card&' . str_replace('act=remove', '', $_SERVER['QUERY_STRING']);

        ecs_header("Location: $url\n");
        exit;
    }
    else
    {
        make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
//-- 开始更改加密串：先检查原串和新串
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'start_change')
{
    $old_key = json_str_iconv(trim($_GET['old_key']));
    $new_key = json_str_iconv(trim($_GET['new_key']));
    // 检查原加密串和新加密串是否相同
    if ($old_key == $new_key || crc32($old_key) == crc32($new_key))
    {
        make_json_error($GLOBALS['_LANG']['same_string']);
    }
    if ($old_key != AUTH_KEY)
    {
        make_json_error($GLOBALS['_LANG']['invalid_old_string']);
    }
    else
    {
        $f=ROOT_PATH . 'data/config.php';
        file_put_contents($f,str_replace("'AUTH_KEY', '".AUTH_KEY."'","'AUTH_KEY', '".$new_key."'",file_get_contents($f)));
        file_put_contents($f,str_replace("'OLD_AUTH_KEY', '".OLD_AUTH_KEY."'","'OLD_AUTH_KEY', '".$old_key."'",file_get_contents($f)));
        @fclose($fp);
    }



    // 查询统计信息：总记录，使用原串的记录，使用新串的记录，使用未知串的记录
    $stat = array('all' => 0, 'new' => 0, 'old' => 0, 'unknown' => 0);
    $sql = "SELECT crc32, count(*) AS cnt FROM " . $ecs->table('virtual_card') . " GROUP BY crc32";
    $res = $GLOBALS['db']->query($sql);
    while ($row = $db->fetchRow($res))
    {
        $stat['all'] += $row['cnt'];
        if (crc32($new_key) == $row['crc32'])
        {
            $stat['new'] += $row['cnt'];
        }
        elseif (crc32($old_key) == $row['crc32'])
        {
            $stat['old'] += $row['cnt'];
        }
        else
        {
            $stat['unknown'] += $row['cnt'];
        }
    }

    make_json_result(sprintf($GLOBALS['_LANG']['old_stat'], $stat['all'], $stat['new'], $stat['old'], $stat['unknown']));
}

/*------------------------------------------------------ */
//-- 更新加密串
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'on_change')
{
   // 重新加密卡号和密码
   $each_num    = 1;
   $old_crc32   = crc32(OLD_AUTH_KEY);
   $new_crc32   = crc32(AUTH_KEY);
   $updated     = intval($_GET['updated']);

   $sql = "SELECT card_id, card_sn, card_password ".
            " FROM " . $ecs->table('virtual_card') .
            " WHERE crc32 = '$old_crc32' LIMIT $each_num";
   $res = $db->query($sql);

   while ($row = $db->fetchRow($res))
   {
       $row['card_sn']       = encrypt(decrypt($row['card_sn'], OLD_AUTH_KEY));
       $row['card_password'] = encrypt(decrypt($row['card_password'], OLD_AUTH_KEY));
       $row['crc32']         = $new_crc32;

       if (!$db->autoExecute($ecs->table('virtual_card'), $row, 'UPDATE', 'card_id = ' . $row['card_id']))
       {
           make_json_error($updated, 0, $_LANG['update_error'] ."\n". $db->error());
       }

       $updated++;
    }

    // 查询是否还有未更新的
    $sql      = "SELECT COUNT(*) FROM " . $ecs->table('virtual_card') . " WHERE crc32 = '$old_crc32' ";
    $left_num = $db->getOne($sql);

    if ($left_num > 0)
    {
        make_json_result($updated);
    }
    else
    {
        // 查询统计信息
        $stat = array('new' => 0, 'unknown' => 0);
        $sql = "SELECT crc32, count(*) AS cnt FROM " . $GLOBALS['ecs']->table('virtual_card') . " GROUP BY crc32";
        $res = $GLOBALS['db']->query($sql);
        while ($row = $db->fetchRow($res))
        {
            if ($new_crc32 == $row['crc32'])
            {
                $stat['new'] += $row['cnt'];
            }
            else
            {
                $stat['unknown'] += $row['cnt'];
            }
        }

        make_json_result($updated, sprintf($_LANG['new_stat'], $stat['new'], $stat['unknown']));
    }
}

/**
 * 返回补货列表
 *
 * @return array
 */
function get_replenish_list()
{
    /* 查询条件 */
    $filter['goods_id']    = empty($_REQUEST['goods_id'])    ? 0 : intval($_REQUEST['goods_id']);
    $filter['search_type'] = empty($_REQUEST['search_type']) ? 0 : trim($_REQUEST['search_type']);
    $filter['order_sn']    = empty($_REQUEST['order_sn'])    ? 0 : trim($_REQUEST['order_sn']);
    $filter['keyword']     = empty($_REQUEST['keyword'])     ? 0 : trim($_REQUEST['keyword']);
    if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1)
    {
        $filter['keyword'] = json_str_iconv($filter['keyword']);
    }
    $filter['sort_by']     = empty($_REQUEST['sort_by'])     ? 'card_id' : trim($_REQUEST['sort_by']);
    $filter['sort_order']  = empty($_REQUEST['sort_order'])  ? 'DESC' : trim($_REQUEST['sort_order']);

    $where  = (!empty($filter['goods_id'])) ? " AND goods_id = '" . $filter['goods_id'] . "' " : '';
    $where .= (!empty($filter['order_sn'])) ? " AND order_sn LIKE '%" . mysql_like_quote($filter['order_sn']) . "%' " : '';

    if (!empty($filter['keyword']))
    {
        if ($filter['search_type'] == 'card_sn')
        {
            $where .= " AND card_sn = '" .encrypt($filter['keyword']). "'";
        }
        else
        {
            $where .= " AND order_sn LIKE '%" . mysql_like_quote($filter['keyword']). "%' ";
        }
    }

    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('virtual_card') . " WHERE 1 $where";
    $filter['record_count'] = $GLOBALS['db']->getOne($sql);

    /* 分页大小 */
    $filter = page_and_size($filter);
    $start  = ($filter['page'] - 1) * $filter['page_size'];

    /* 查询 */
    $sql = "SELECT card_id, goods_id, card_sn, card_password, end_date, is_saled, order_sn, crc32 ".
            " FROM ".$GLOBALS['ecs']->table('virtual_card').
            " WHERE 1 ".$where.
            " ORDER BY $filter[sort_by] $filter[sort_order] ".
            " LIMIT $start, $filter[page_size]";
    $all = $GLOBALS['db']->getAll($sql);

    $arr = array();
    foreach ($all AS $key => $row)
    {
        if ($row['crc32'] == 0 || $row['crc32'] == crc32(AUTH_KEY))
        {
            $row['card_sn']       = decrypt($row['card_sn']);
            $row['card_password'] = decrypt($row['card_password']);
        }
        elseif ($row['crc32'] == crc32(OLD_AUTH_KEY))
        {
            $row['card_sn']       = decrypt($row['card_sn'], OLD_AUTH_KEY);
            $row['card_password'] = decrypt($row['card_password'], OLD_AUTH_KEY);
        }
        else
        {
            $row['card_sn']       = '***';
            $row['card_password'] = '***';
        }

        $row['end_date'] = $row['end_date'] == 0 ? '' : date($GLOBALS['_CFG']['date_format'], $row['end_date']);

        $arr[] = $row;
    }

    return array('item' => $arr, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}

/**
 * 更新虚拟商品的商品数量
 *
 * @access  public
 * @param   int     $goods_id
 *
 * @return bool
 */
function update_goods_number($goods_id)
{
    $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('virtual_card') . " WHERE goods_id = '$goods_id' AND is_saled = 0";
    $goods_number = $GLOBALS['db']->getOne($sql);

    $sql = "UPDATE " . $GLOBALS['ecs']->table('goods') . " SET goods_number = '$goods_number' WHERE goods_id = '$goods_id' AND extension_code='virtual_card'";

    return $GLOBALS['db']->query($sql);
}


?>