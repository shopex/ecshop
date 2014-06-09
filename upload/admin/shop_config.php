<?php

/**
 * ECSHOP 管理中心商店设置
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: shop_config.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);

/* 代码 */
require(dirname(__FILE__) . '/includes/init.php');

if($GLOBALS['_CFG']['certificate_id']  == '')
{
    $certi_id='error';
}
else
{
    $certi_id=$GLOBALS['_CFG']['certificate_id'];
}

$sess_id = $GLOBALS['sess']->get_session_id();

$auth = mktime();
$ac = md5($certi_id.'SHOPEX_SMS'.$auth);
$url = 'http://service.shopex.cn/sms/index.php?certificate_id='.$certi_id.'&sess_id='.$sess_id.'&auth='.$auth.'&ac='.$ac;

/*------------------------------------------------------ */
//-- 列表编辑 ?act=list_edit
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list_edit')
{
    /* 检查权限 */
    admin_priv('shop_config');

    /* 可选语言 */
    $dir = opendir('../languages');
    $lang_list = array();
    while (@$file = readdir($dir))
    {
        if ($file != '.' && $file != '..' &&  $file != '.svn' && $file != '_svn' && is_dir('../languages/' .$file))
        {
            $lang_list[] = $file;
        }
    }
    @closedir($dir);

    $smarty->assign('lang_list',    $lang_list);
    $smarty->assign('ur_here',      $_LANG['01_shop_config']);
    $smarty->assign('group_list',   get_settings(null, array('5')));
    $smarty->assign('countries',    get_regions());

    if (strpos(strtolower($_SERVER['SERVER_SOFTWARE']), 'iis') !== false)
    {
        $rewrite_confirm = $_LANG['rewrite_confirm_iis'];
    }
    else
    {
        $rewrite_confirm = $_LANG['rewrite_confirm_apache'];
    }
    $smarty->assign('rewrite_confirm', $rewrite_confirm);

    if ($_CFG['shop_country'] > 0)
    {
        $smarty->assign('provinces', get_regions(1, $_CFG['shop_country']));
        if ($_CFG['shop_province'])
        {
            $smarty->assign('cities', get_regions(2, $_CFG['shop_province']));
        }
    }
    $smarty->assign('cfg', $_CFG);

    assign_query_info();
    $smarty->display('shop_config.htm');
}

/*------------------------------------------------------ */
//-- 邮件服务器设置
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'mail_settings')
{
    /* 检查权限 */
    admin_priv('shop_config');

    $arr = get_settings(array(5));

    assign_query_info();

    $smarty->assign('ur_here',      $_LANG['mail_settings']);
    $smarty->assign('cfg', $arr[5]['vars']);
    $smarty->display('shop_config_mail_settings.htm');
}

/*------------------------------------------------------ */
//-- 提交   ?act=post
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'post')
{
    $type = empty($_POST['type']) ? '' : $_POST['type'];

    /* 检查权限 */
    admin_priv('shop_config');

    /* 允许上传的文件类型 */
    $allow_file_types = '|GIF|JPG|PNG|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|CERT|';

    /* 保存变量值 */
    $count = count($_POST['value']);

    $arr = array();
    $sql = 'SELECT id, value FROM ' . $ecs->table('shop_config');
    $res= $db->query($sql);
    while($row = $db->fetchRow($res))
    {
        $arr[$row['id']] = $row['value'];
    }
    foreach ($_POST['value'] AS $key => $val)
    {
        if($arr[$key] != $val)
        {
            $sql = "UPDATE " . $ecs->table('shop_config') . " SET value = '" . trim($val) . "' WHERE id = '" . $key . "'";
            $db->query($sql);
        }
    }

    /* 处理上传文件 */
    $file_var_list = array();
    $sql = "SELECT * FROM " . $ecs->table('shop_config') . " WHERE parent_id > 0 AND type = 'file'";
    $res = $db->query($sql);
    while ($row = $db->fetchRow($res))
    {
        $file_var_list[$row['code']] = $row;
    }

    foreach ($_FILES AS $code => $file)
    {
        /* 判断用户是否选择了文件 */
        if ((isset($file['error']) && $file['error'] == 0) || (!isset($file['error']) && $file['tmp_name'] != 'none'))
        {
            /* 检查上传的文件类型是否合法 */
            if (!check_file_type($file['tmp_name'], $file['name'], $allow_file_types))
            {
                sys_msg(sprintf($_LANG['msg_invalid_file'], $file['name']));
            }
            else
            {
                if ($code == 'shop_logo')
                {
                    include_once('includes/lib_template.php');
                    $info = get_template_info($_CFG['template']);

                    $file_name = str_replace('{$template}', $_CFG['template'], $file_var_list[$code]['store_dir']) . $info['logo'];
                }
                elseif ($code == 'watermark')
                {
                    $ext = array_pop(explode('.', $file['name']));
                    $file_name = $file_var_list[$code]['store_dir'] . 'watermark.' . $ext;
                    if (file_exists($file_var_list[$code]['value']))
                    {
                        @unlink($file_var_list[$code]['value']);
                    }
                }
                elseif($code == 'wap_logo')
                {
                    $ext = array_pop(explode('.', $file['name']));
                    $file_name = $file_var_list[$code]['store_dir'] . 'wap_logo.' . $ext;
                    if (file_exists($file_var_list[$code]['value']))
                    {
                        @unlink($file_var_list[$code]['value']);
                    }
                }
                else
                {
                    $file_name = $file_var_list[$code]['store_dir'] . $file['name'];
                }

                /* 判断是否上传成功 */
                if (move_upload_file($file['tmp_name'], $file_name))
                {
                    $sql = "UPDATE " . $ecs->table('shop_config') . " SET value = '$file_name' WHERE code = '$code'";
                    $db->query($sql);
                }
                else
                {
                    sys_msg(sprintf($_LANG['msg_upload_failed'], $file['name'], $file_var_list[$code]['store_dir']));
                }
            }
        }
    }

    /* 处理发票类型及税率 */
    if (!empty($_POST['invoice_rate']))
    {
        foreach ($_POST['invoice_rate'] as $key => $rate)
        {
            $rate = round(floatval($rate), 2);
            if ($rate < 0)
            {
                $rate = 0;
            }
            $_POST['invoice_rate'][$key] = $rate;
        }
        $invoice = array(
            'type' => $_POST['invoice_type'],
            'rate' => $_POST['invoice_rate']
        );
        $sql = "UPDATE " . $ecs->table('shop_config') . " SET value = '" . serialize($invoice) . "' WHERE code = 'invoice_type'";
        $db->query($sql);
    }

    /* 记录日志 */
    admin_log('', 'edit', 'shop_config');

    /* 清除缓存 */
    clear_all_files();

    $_CFG = load_config();

    $shop_country   = $db->getOne("SELECT region_name FROM ".$ecs->table('region')." WHERE region_id='$_CFG[shop_country]'");
    $shop_province  = $db->getOne("SELECT region_name FROM ".$ecs->table('region')." WHERE region_id='$_CFG[shop_province]'");
    $shop_city      = $db->getOne("SELECT region_name FROM ".$ecs->table('region')." WHERE region_id='$_CFG[shop_city]'");

    $spt = '<script type="text/javascript" src="http://api.ecshop.com/record.php?';
    $spt .= "url=" .urlencode($ecs->url());
    $spt .= "&shop_name=" .urlencode($_CFG['shop_name']);
    $spt .= "&shop_title=".urlencode($_CFG['shop_title']);
    $spt .= "&shop_desc=" .urlencode($_CFG['shop_desc']);
    $spt .= "&shop_keywords=" .urlencode($_CFG['shop_keywords']);
    $spt .= "&country=".urlencode($shop_country)."&province=".urlencode($shop_province)."&city=".urlencode($shop_city);
    $spt .= "&address=" .urlencode($_CFG['shop_address']);
    $spt .= "&qq=$_CFG[qq]&ww=$_CFG[ww]&ym=$_CFG[ym]&msn=$_CFG[msn]";
    $spt .= "&email=$_CFG[service_email]&phone=$_CFG[service_phone]&icp=".urlencode($_CFG['icp_number']);
    $spt .= "&version=".VERSION."&language=$_CFG[lang]&php_ver=" .PHP_VERSION. "&mysql_ver=" .$db->version();
    $spt .= "&charset=".EC_CHARSET;
    $spt .= '"></script>';

    if ($type == 'mail_setting')
    {
        $links[] = array('text' => $_LANG['back_mail_settings'], 'href' => 'shop_config.php?act=mail_settings');
        sys_msg($_LANG['mail_save_success'].$spt, 0, $links);
    }
    else
    {
        $links[] = array('text' => $_LANG['back_shop_config'], 'href' => 'shop_config.php?act=list_edit');
        sys_msg($_LANG['save_success'].$spt, 0, $links);
    }
}

/*------------------------------------------------------ */
//-- 发送测试邮件
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'send_test_email')
{
    /* 检查权限 */
    check_authz_json('shop_config');

    /* 取得参数 */
    $email          = trim($_POST['email']);

    /* 更新配置 */
    $_CFG['mail_service'] = intval($_POST['mail_service']);
    $_CFG['smtp_host']    = trim($_POST['smtp_host']);
    $_CFG['smtp_port']    = trim($_POST['smtp_port']);
    $_CFG['smtp_user']    = json_str_iconv(trim($_POST['smtp_user']));
    $_CFG['smtp_pass']    = trim($_POST['smtp_pass']);
    $_CFG['smtp_mail']    = trim($_POST['reply_email']);
    $_CFG['mail_charset'] = trim($_POST['mail_charset']);

    if (send_mail('', $email, $_LANG['test_mail_title'], $_LANG['cfg_name']['email_content'], 0))
    {
        make_json_result('', $_LANG['sendemail_success'] . $email);
    }
    else
    {
        make_json_error(join("\n", $err->_message));
    }
}

/*------------------------------------------------------ */
//-- 删除上传文件
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'del')
{
    /* 检查权限 */
    check_authz_json('shop_config');

    /* 取得参数 */
    $code          = trim($_GET['code']);

    $filename = $_CFG[$code];

    //删除文件
    @unlink($filename);

    //更新设置
    update_configure($code, '');

    /* 记录日志 */
    admin_log('', 'edit', 'shop_config');

    /* 清除缓存 */
    clear_all_files();

    sys_msg($_LANG['save_success'], 0);

}

/**
 * 设置系统设置
 *
 * @param   string  $key
 * @param   string  $val
 *
 * @return  boolean
 */
function update_configure($key, $val='')
{
    if (!empty($key))
    {
        $sql = "UPDATE " . $GLOBALS['ecs']->table('shop_config') . " SET value='$val' WHERE code='$key'";

        return $GLOBALS['db']->query($sql);
    }

    return true;
}

/**
 * 获得设置信息
 *
 * @param   array   $groups     需要获得的设置组
 * @param   array   $excludes   不需要获得的设置组
 *
 * @return  array
 */
function get_settings($groups=null, $excludes=null)
{
    global $db, $ecs, $_LANG;

    $config_groups = '';
    $excludes_groups = '';

    if (!empty($groups))
    {
        foreach ($groups AS $key=>$val)
        {
            $config_groups .= " AND (id='$val' OR parent_id='$val')";
        }
    }

    if (!empty($excludes))
    {
        foreach ($excludes AS $key=>$val)
        {
            $excludes_groups .= " AND (parent_id<>'$val' AND id<>'$val')";
        }
    }

    /* 取出全部数据：分组和变量 */
    $sql = "SELECT * FROM " . $ecs->table('shop_config') .
            " WHERE type<>'hidden' $config_groups $excludes_groups ORDER BY parent_id, sort_order, id";
    $item_list = $db->getAll($sql);

    /* 整理数据 */
    $group_list = array();
    foreach ($item_list AS $key => $item)
    {
        $pid = $item['parent_id'];
        $item['name'] = isset($_LANG['cfg_name'][$item['code']]) ? $_LANG['cfg_name'][$item['code']] : $item['code'];
        $item['desc'] = isset($_LANG['cfg_desc'][$item['code']]) ? $_LANG['cfg_desc'][$item['code']] : '';

        if ($item['code'] == 'sms_shop_mobile')
        {
            $item['url'] = 1;
        }
        if ($pid == 0)
        {
            /* 分组 */
            if ($item['type'] == 'group')
            {
                $group_list[$item['id']] = $item;
            }
        }
        else
        {
            /* 变量 */
            if (isset($group_list[$pid]))
            {
                if ($item['store_range'])
                {
                    $item['store_options'] = explode(',', $item['store_range']);

                    foreach ($item['store_options'] AS $k => $v)
                    {
                        $item['display_options'][$k] = isset($_LANG['cfg_range'][$item['code']][$v]) ?
                                $_LANG['cfg_range'][$item['code']][$v] : $v;
                    }
                }
                $group_list[$pid]['vars'][] = $item;
            }
        }

    }

    return $group_list;
}

?>