<?php

/**
 * ECSHOP 管理中心模版管理程序
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: mail_template.php 17063 2010-03-25 06:35:46Z liuhui $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

admin_priv('mail_template');

/*------------------------------------------------------ */
//-- 模版列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    include_once(ROOT_PATH . 'includes/fckeditor/fckeditor.php'); // 包含 html editor 类文件

    /* 包含插件语言项 */
    $sql = "SELECT code FROM ".$ecs->table('plugins');
    $rs = $db->query($sql);
    while ($row = $db->FetchRow($rs))
    {
        /* 取得语言项 */
        if (file_exists('../plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php'))
        {
            include_once(ROOT_PATH.'plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php');
        }

    }

    /* 获得所有邮件模板 */
    $sql = "SELECT template_id, template_code FROM " .$ecs->table('mail_templates') . " WHERE  type = 'template'";
    $res = $db->query($sql);
    $cur = null;

    while ($row = $db->FetchRow($res))
    {
        if ($cur == null)
        {
            $cur = $row['template_id'];
        }

        $len = strlen($_LANG[$row['template_code']]);
        $templates[$row['template_id']] = $len < 18 ?
            $_LANG[$row['template_code']].str_repeat('&nbsp;', (18-$len)/2) ." [$row[template_code]]" :
            $_LANG[$row['template_code']] . " [$row[template_code]]";
    }

    assign_query_info();

    $content = load_template($cur);

    /* 创建 html editor */
    $editor = new FCKeditor('content');
    $editor->BasePath   = '../includes/fckeditor/';
    $editor->ToolbarSet = 'Normal';
    $editor->Width      = '100%';
    $editor->Height     = '320';
    $editor->Value      = $content['template_content'];
    $FCKeditor = $editor->CreateHtml();
    $smarty->assign('FCKeditor', $FCKeditor);
    $smarty->assign('tpl', $cur);
    $smarty->assign('cur',          $cur);
    $smarty->assign('ur_here',      $_LANG['mail_template_manage']);
    $smarty->assign('templates',    $templates);
    $smarty->assign('template',     $content);
    $smarty->assign('full_page',    1);
    $smarty->display('mail_template.htm');
}

/*------------------------------------------------------ */
//-- 载入指定模版
/*------------------------------------------------------ */
elseif ($_REQUEST['act'] == 'loat_template')
{
    include_once(ROOT_PATH . 'includes/fckeditor/fckeditor.php'); // 包含 html editor 类文件

    $tpl = intval($_GET['tpl']);
    $mail_type = isset($_GET['mail_type']) ? $_GET['mail_type'] : -1;

    /* 包含插件语言项 */
    $sql = "SELECT code FROM ".$ecs->table('plugins');
    $rs = $db->query($sql);
    while ($row = $db->FetchRow($rs))
    {
        /* 取得语言项 */
        if (file_exists('../plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php'))
        {
            include_once(ROOT_PATH.'plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php');
        }

    }

    /* 获得所有邮件模板 */
    $sql = "SELECT template_id, template_code FROM " .$ecs->table('mail_templates') . " WHERE  type = 'template'";
    $res = $db->query($sql);

    while ($row = $db->FetchRow($res))
    {
        $len = strlen($_LANG[$row['template_code']]);
        $templates[$row['template_id']] = $len < 18 ?
            $_LANG[$row['template_code']].str_repeat('&nbsp;', (18-$len)/2) ." [$row[template_code]]" :
            $_LANG[$row['template_code']] . " [$row[template_code]]";
    }

    $content = load_template($tpl);

    if (($mail_type == -1 && $content['is_html'] == 1) || $mail_type == 1)
    {
        /* 创建 html editor */
        $editor = new FCKeditor('content');
        $editor->BasePath   = '../includes/fckeditor/';
        $editor->ToolbarSet = 'Normal';
        $editor->Width      = '100%';
        $editor->Height     = '320';
        $editor->Value      = $content['template_content'];
        $FCKeditor = $editor->CreateHtml();
        $smarty->assign('FCKeditor', $FCKeditor);

        $content['is_html'] = 1;
    }
    elseif ($mail_type == 0)
    {
        $content['is_html'] = 0;
    }

    $smarty->assign('tpl', $tpl);
    $smarty->assign('cur',          $tpl);
    $smarty->assign('templates',    $templates);
    $smarty->assign('template',     $content);

    make_json_result($smarty->fetch('mail_template.htm'));
}

/*------------------------------------------------------ */
//-- 保存模板内容
/*------------------------------------------------------ */

elseif ($_REQUEST['act'] == 'save_template')
{

    if (empty($_POST['subject']))
    {
       sys_msg($_LANG['subject_empty'], 1, array(), false);
    }
    else
    {
        $subject = trim($_POST['subject']);
    }

    if (empty($_POST['content']))
    {
       sys_msg($_LANG['content_empty'], 1, array(), false);
    }
    else
    {
        $content = trim($_POST['content']);
    }

    $type   = intval($_POST['is_html']);
    $tpl_id = intval($_POST['tpl']);


    $sql = "UPDATE " .$ecs->table('mail_templates'). " SET ".
                "template_subject = '" .str_replace('\\\'\\\'', '\\\'', $subject). "', ".
                "template_content = '" .str_replace('\\\'\\\'', '\\\'', $content).  "', ".
                "is_html = '$type', ".
                "last_modify = '" .gmtime(). "' ".
            "WHERE template_id='$tpl_id'";

    if ($db->query($sql, "SILENT"))
    {
        $link[0]=array('href' => 'mail_template.php?act=list', 'text' => $_LANG['update_success']);
        sys_msg($_LANG['update_success'], 0, $link);
    }
    else
    {
         sys_msg($_LANG['update_failed'], 1, array(), false);
    }
}

/**
 * 加载指定的模板内容
 *
 * @access  public
 * @param   string  $temp   邮件模板的ID
 * @return  array
 */
function load_template($temp_id)
{
    $sql = "SELECT template_subject, template_content, is_html ".
            "FROM " .$GLOBALS['ecs']->table('mail_templates'). " WHERE template_id='$temp_id'";
    $row = $GLOBALS['db']->GetRow($sql);

    return $row;
}

?>