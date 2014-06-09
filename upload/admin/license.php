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
 * $Author: wangleisvn $
 * $Id: flashplay.php 16131 2009-05-31 08:21:41Z wangleisvn $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 证书编辑页
/*------------------------------------------------------ */
if ($_REQUEST['act']== 'list_edit')
{
    /* 检查权限 */
    admin_priv('shop_authorized');

    include_once(ROOT_PATH . 'includes/lib_license.php');

    $license = get_shop_license();

    $smarty->assign('ur_here', $_LANG['license_here']);
    $smarty->assign('is_download', '0');
    if ($license['certificate_id'] != '' && $license['token'] != '')
    {
        $smarty->assign('is_download', '1');
    }

    $smarty->assign('certificate_id', $license['certificate_id']);
    $smarty->assign('token', $license['token']);

    $smarty->display('license.htm');
}

/*------------------------------------------------------ */
//-- 证书下载
/*------------------------------------------------------ */

elseif ($_REQUEST['act']== 'download')
{
    /* 检查权限 */
    admin_priv('shop_authorized');

    include_once(ROOT_PATH . 'includes/lib_license.php');

    $license = get_shop_license();

    if ($license['certificate_id'] == '' || $license['token'] == '')
    {
        $links[] = array('text' => $_LANG['back'], 'href' => 'license.php?act=list_edit');
        sys_msg($_LANG['no_license_down'], 0, $links);
    }
    /* 文件下载 */
    ecs_header("Content-Type:text/plain");
    ecs_header("Accept-Ranges:bytes");
    ecs_header("Content-Disposition: attachment; filename=CERTIFICATE.CER");
    echo $license['certificate_id'] . '|' . $license['token'];
    exit;
}

/*------------------------------------------------------ */
//-- 证书上传
/*------------------------------------------------------ */

elseif ($_REQUEST['act']== 'upload')
{
    /* 检查权限 */
    admin_priv('shop_authorized');

    /* 接收上传文件 */
    /* 取出证书内容 */
    $license_arr = array();
    if (isset($_FILES['license']['error']) && $_FILES['license']['error'] == 0 && preg_match('/CER$/i' ,$_FILES['license']['name']))
    {
        if (file_exists($_FILES['license']['tmp_name']) && is_readable($_FILES['license']['tmp_name']))
        {
            if ($license_f = fopen($_FILES['license']['tmp_name'], 'r'))
            {
                $license_content = '';
                while (!feof($license_f))
                {
                    $license_content .= fgets($license_f, 4096);
                }
                $license_content = trim($license_content);
                $license_content = addslashes_deep($license_content);
                $license_arr = explode('|', $license_content);
            }
        }
    }

    /* 恢复证书 */
    if (count($license_arr) != 2 || $license_arr[0] == '' || $license_arr[1] == '')
    {
        $links[] = array('text' => $_LANG['back'], 'href' => 'license.php?act=list_edit');
        sys_msg($_LANG['fail_license'], 1, $links);
    }
    else
    {
        include_once(ROOT_PATH . 'includes/cls_transport.php');
        include_once(ROOT_PATH . 'includes/cls_json.php');
        include_once(ROOT_PATH . 'includes/lib_main.php');
        include_once(ROOT_PATH . 'includes/lib_license.php');

        // 证书登录
        $login_result = license_login();
        if ($login_result['flag'] != 'login_succ')
        {
            $links[] = array('text' => $_LANG['back'], 'href' => 'license.php?act=list_edit');
            sys_msg($_LANG['fail_license_login'], 1, $links);
        }

        $sql = "UPDATE " . $ecs->table('shop_config') . "
                SET value = '" . $license_arr[0] . "'
                WHERE code = 'certificate_id'";
        $db->query($sql);
        $sql = "UPDATE " . $ecs->table('shop_config') . "
                SET value = '" . $license_arr[1] . "'
                WHERE code = 'token'";
        $db->query($sql);

        $links[] = array('text' => $_LANG['back'], 'href' => 'license.php?act=list_edit');
        sys_msg($_LANG['recover_license'], 0, $links);
    }

}

/*------------------------------------------------------ */
//-- 证书删除
/*------------------------------------------------------ */

elseif ($_REQUEST['act']== 'del')
{
    /* 检查权限 */
    admin_priv('shop_authorized');

    $sql = "UPDATE " . $ecs->table('shop_config') . "
            SET value = ''
            WHERE code IN('certificate_id', 'token')";
    $db->query($sql);

    $links[] = array('text' => $_LANG['back'], 'href' => 'license.php?act=list_edit');
    sys_msg($_LANG['delete_license'], 0, $links);
}
?>