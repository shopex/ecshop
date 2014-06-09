<?php

/**
 * ECSHOP 管理中心模版管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * @author:     Weber Liu <weberliu@hotmail.com>
 * @version:    v2.1
 * ---------------------------------------------
 * $Author: liubo $
 * $Id: template.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once('includes/lib_template.php');

/*------------------------------------------------------ */
//-- 模版列表
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'list')
{
    admin_priv('template_select');

    /* 获得当前的模版的信息 */
    $curr_template = $_CFG['template'];
    $curr_style = $_CFG['stylename'];

    /* 获得可用的模版 */
    $available_templates = array();
    $template_dir        = @opendir(ROOT_PATH . 'themes/');
    while ($file = readdir($template_dir))
    {
        if ($file != '.' && $file != '..' && is_dir(ROOT_PATH. 'themes/' . $file) && $file != '.svn' && $file != 'index.htm')
        {
            $available_templates[] = get_template_info($file);
        }
    }
    @closedir($template_dir);

    /* 获得可用的模版的可选风格数组 */
    $templates_style = array();
    if (count($available_templates) > 0)
    {
        foreach ($available_templates as $value)
        {
            $templates_style[$value['code']] = read_tpl_style($value['code'], 2);
        }
    }

    /* 清除不需要的模板设置 */
    $available_code = array();
    $sql = "DELETE FROM ".$ecs->table('template')." WHERE 1 ";
    foreach ($available_templates AS $tmp)
    {
        $sql .= " AND theme <> '".$tmp['code']."' ";
        $available_code[] = $tmp['code'];
    }
    $tmp_bak_dir = @opendir(ROOT_PATH . 'temp/backup/library/');
    while ($file = readdir($tmp_bak_dir))
    {
        if ($file != '.' && $file != '..' && $file != '.svn' && $file != 'index.htm' && is_file(ROOT_PATH .'temp/backup/library/' . $file) == true)
        {
            $code = substr($file, 0, strpos($file, '-'));
            if (!in_array($code, $available_code))
            {
                @unlink(ROOT_PATH . 'temp/backup/library/' . $file);
            }
        }
    }

    $db->query($sql);

    assign_query_info();

    $smarty->assign('ur_here',             $_LANG['template_manage']);
    $smarty->assign('curr_tpl_style', $curr_style);
    $smarty->assign('template_style', $templates_style);
    $smarty->assign('curr_template',       get_template_info($curr_template, $curr_style));
    $smarty->assign('available_templates', $available_templates);
    $smarty->display('templates_list.htm');
}

/*------------------------------------------------------ */
//-- 设置模板的内容
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'setup')
{
    admin_priv('template_setup');

    $template_theme = $_CFG['template'];
    $curr_template  = empty($_REQUEST['template_file']) ? 'index' : $_REQUEST['template_file'];

    $temp_options   = array();
    $temp_regions   = get_template_region($template_theme, $curr_template.'.dwt', false);
    $temp_libs      = get_template_region($template_theme, $curr_template.'.dwt', true);

    $editable_libs      = get_editable_libs($curr_template, $page_libs[$curr_template]);

    if (empty($editable_libs))
    {
        /* 获取数据库中数据，并跟模板中数据核对,并设置动态内容 */
        /* 固定内容 */
        foreach ($page_libs[$curr_template] AS $val => $number_enabled)
        {
            $lib = basename(strtolower(substr($val, 0, strpos($val, '.'))));
            if (!in_array($lib, $GLOBALS['dyna_libs']))
            {
                /* 先排除动态内容 */
                $temp_options[$lib]            = get_setted($val, $temp_libs);
                $temp_options[$lib]['desc']    = $_LANG['template_libs'][$lib];
                $temp_options[$lib]['library'] = $val;
                $temp_options[$lib]['number_enabled'] = $number_enabled > 0 ? 1 : 0;
                $temp_options[$lib]['number'] = $number_enabled;
            }
        }
    }
    else
    {
        /* 获取数据库中数据，并跟模板中数据核对,并设置动态内容 */
        /* 固定内容 */
        foreach ($page_libs[$curr_template] AS $val => $number_enabled)
        {
            $lib = basename(strtolower(substr($val, 0, strpos($val, '.'))));
            if (!in_array($lib, $GLOBALS['dyna_libs']))
            {
                /* 先排除动态内容 */
                $temp_options[$lib]            = get_setted($val, $temp_libs);
                $temp_options[$lib]['desc']    = $_LANG['template_libs'][$lib];
                $temp_options[$lib]['library'] = $val;
                $temp_options[$lib]['number_enabled'] = $number_enabled > 0 ? 1 : 0;
                $temp_options[$lib]['number'] = $number_enabled;

                if (!in_array($lib, $editable_libs))
                {
                    $temp_options[$lib]['editable'] = 1;
                }
            }
        }
    }

    /* 动态内容 */
    $cate_goods   = array();
    $brand_goods  = array();
    $cat_articles = array();
    $ad_positions = array();

    $sql = "SELECT region, library, sort_order, id, number, type FROM ".$ecs->table('template') ." ".
           "WHERE theme='$template_theme' AND filename='$curr_template' AND remarks='' ".
           "ORDER BY region, sort_order ASC ";

    $rc = $db->query($sql);
    $db_dyna_libs = array();
    while ($row= $db->FetchRow($rc))
    {
        if ($row['type'] > 0)
        {
            /* 动态内容 */
            $db_dyna_libs[$row['region']][$row['library']][] = array('id' => $row['id'], 'number' => $row['number'], 'type' => $row['type']);
        }
        else
        {
            /* 固定内容 */
            $lib = basename(strtolower(substr($row['library'], 0, strpos($row['library'], '.'))));
            if (isset($lib))
            {
                $temp_options[$lib]['number'] = $row['number'];
            }
        }
    }

    foreach ($temp_libs AS $val)
    {
        /* 对动态内容赋值 */
        if ($val['lib'] == 'cat_goods')
        {
            /* 分类下的商品 */
            if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']])))
            {
                $cate_goods[] = array('region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'cats'=>cat_list(0, $row['id']));
            }
            else
            {
                $cate_goods[] = array('region' => $val['region'], 'sort_order' => $val['sort_order'], 'number'=>0, 'cats'=>cat_list(0));
            }
        }

        elseif ($val['lib'] == 'brand_goods')
        {
            /* 品牌下的商品 */
            if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']])))
            {
                $brand_goods[] = array('region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'brand' => $row['id']);
            }
            else
            {
                $brand_goods[] = array('region' => $val['region'], 'sort_order' => $val['sort_order'], 'number'=>0, 'brand'=>0);
            }
        }

        /* 文章列表 */
        elseif ($val['lib'] == 'cat_articles')
        {
            if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']])))
            {
                $cat_articles[] = array('region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'cat' => article_cat_list(0, $row['id']));
            }
            else
            {
                $cat_articles[] = array('region' => $val['region'], 'sort_order' => $val['sort_order'], 'number'=>0, 'cat'=>article_cat_list(0));
            }
        }

        /* 广告位 */
        elseif ($val['lib'] == 'ad_position')
        {
            if (isset($db_dyna_libs[$val['region']][$val['library']]) && ($row = array_shift($db_dyna_libs[$val['region']][$val['library']])))
            {
                 $ad_positions[] = array('region' => $val['region'], 'sort_order' => $val['sort_order'], 'number' => $row['number'], 'ad_pos' => $row['id']);
            }
            else
            {
                 $ad_positions[] = array('region' => $val['region'], 'sort_order' => $val['sort_order'], 'number'=>0, 'ad_pos'=>0);
            }
        }
    }

    assign_query_info();
    $smarty->assign('ur_here',            $_LANG['03_template_setup']);
    $smarty->assign('curr_template_file', $curr_template);
    $smarty->assign('temp_options',       $temp_options);
    $smarty->assign('temp_regions',       $temp_regions);
    $smarty->assign('cate_goods',         $cate_goods);
    $smarty->assign('brand_goods',        $brand_goods);
    $smarty->assign('cat_articles',       $cat_articles);
    $smarty->assign('ad_positions',       $ad_positions);
    $smarty->assign('arr_cates',          cat_list(0, 0, true));
    $smarty->assign('arr_brands',         get_brand_list());
    $smarty->assign('arr_article_cats',   article_cat_list(0, 0, true));
    $smarty->assign('arr_ad_positions',   get_position_list());
    $smarty->display('template_setup.htm');
}

/*------------------------------------------------------ */
//-- 提交模板内容设置
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'setting')
{
    admin_priv('template_setup');

    $curr_template = $_CFG['template'];
    $db->query("DELETE FROM " .$ecs->table('template'). " WHERE remarks = '' AND filename = '$_POST[template_file]' AND theme = '$curr_template'");

    /* 先处理固定内容 */
    foreach ($_POST['regions'] AS $key => $val)
    {
        $number = isset($_POST['number'][$key]) ? intval($_POST['number'][$key]) : 0;
        if (!in_array($key, $GLOBALS['dyna_libs']) AND (isset($_POST['display'][$key]) AND $_POST['display'][$key] == 1 OR $number > 0))
        {
            $sql = "INSERT INTO " .$ecs->table('template').
                        "(theme, filename, region, library, sort_order, number)".
                    " VALUES ".
                        "('$curr_template', '$_POST[template_file]', '$val', '".$_POST['map'][$key]."', '" . @$_POST['sort_order'][$key] . "', '$number')";
            $db->query($sql);
        }
    }

    /* 分类的商品 */
    if (isset($_POST['regions']['cat_goods']))
    {
        foreach ($_POST['regions']['cat_goods'] AS $key => $val)
        {
            if ($_POST['categories']['cat_goods'][$key] != '' && intval($_POST['categories']['cat_goods'][$key]) > 0)
            {
                $sql = "INSERT INTO " .$ecs->table('template'). " (".
                            "theme, filename, region, library, sort_order, type, id, number".
                        ") VALUES (".
                            "'$curr_template', ".
                            "'$_POST[template_file]', '" .$val. "', '/library/cat_goods.lbi', ".
                            "'" .$_POST['sort_order']['cat_goods'][$key]. "', 1, '" .$_POST['categories']['cat_goods'][$key].
                            "', '" .$_POST['number']['cat_goods'][$key]. "'".
                        ")";
                $db->query($sql);
            }
        }
    }

    /* 品牌的商品 */
    if (isset($_POST['regions']['brand_goods']))
    {
        foreach ($_POST['regions']['brand_goods'] AS $key => $val)
        {
            if ($_POST['brands']['brand_goods'][$key] != '' && intval($_POST['brands']['brand_goods'][$key]) > 0)
            {
                $sql = "INSERT INTO " .$ecs->table('template'). " (".
                            "theme, filename, region, library, sort_order, type, id, number".
                        ") VALUES (".
                            "'$curr_template', ".
                            "'$_POST[template_file]', '" .$val. "', '/library/brand_goods.lbi', ".
                            "'" .$_POST['sort_order']['brand_goods'][$key]. "', 2, '" .$_POST['brands']['brand_goods'][$key].
                            "', '" .$_POST['number']['brand_goods'][$key]. "'".
                        ")";
                $db->query($sql);
            }
        }
    }

    /* 文章列表 */
    if (isset($_POST['regions']['cat_articles']))
    {
        foreach ($_POST['regions']['cat_articles'] AS $key => $val)
        {
            if ($_POST['article_cat']['cat_articles'][$key] != '' && intval($_POST['article_cat']['cat_articles'][$key]) > 0)
            {
                $sql = "INSERT INTO " .$ecs->table('template'). " (".
                            "theme, filename, region, library, sort_order, type, id, number".
                        ") VALUES (".
                            "'$curr_template', ".
                            "'$_POST[template_file]', '" .$val. "', '/library/cat_articles.lbi', ".
                            "'" .$_POST['sort_order']['cat_articles'][$key]. "', 3, '" .$_POST['article_cat']['cat_articles'][$key].
                            "', '" .$_POST['number']['cat_articles'][$key]. "'".
                        ")";
                $db->query($sql);
            }
        }
    }

    /* 广告位 */
    if (isset($_POST['regions']['ad_position']))
    {
        foreach ($_POST['regions']['ad_position'] AS $key => $val)
        {
            if ($_POST['ad_position'][$key] != '' && intval($_POST['ad_position'][$key]) > 0)
            {
                $sql = "INSERT INTO " .$ecs->table('template'). " (".
                            "theme, filename, region, library, sort_order, type, id, number".
                        ") VALUES (".
                            "'$curr_template', ".
                            "'$_POST[template_file]', '" .$val. "', '/library/ad_position.lbi', ".
                            "'" .$_POST['sort_order']['ad_position'][$key]. "', 4, '" .$_POST['ad_position'][$key].
                            "', '" .$_POST['number']['ad_position'][$key]. "'".
                        ")";
                $db->query($sql);
            }
        }
    }

    /* 对提交内容进行处理 */
    $post_regions = array();
    foreach ($_POST['regions'] AS $key => $val)
    {
        switch ($key)
        {
            case 'cat_goods':
                foreach ($val AS $k => $v)
                {
                    if (intval($_POST['categories']['cat_goods'][$k]) > 0)
                    {
                        $post_regions[] = array('region'     => $v,
                                                'type'       => 1,
                                                'number'     => $_POST['number']['cat_goods'][$k],
                                                'library'    => '/library/' .$key. '.lbi',
                                                'sort_order' => $_POST['sort_order']['cat_goods'][$k],
                                                'id'         => $_POST['categories']['cat_goods'][$k]);
                    }
                }
                break;
            case 'brand_goods':
                foreach ($val AS $k => $v)
                {
                    if (intval($_POST['brands']['brand_goods'][$k]) > 0)
                    {
                        $post_regions[] = array('region'     => $v,
                                                'type'       => 2,
                                                'number'     => $_POST['number']['brand_goods'][$k],
                                                'library'    => '/library/' .$key. '.lbi',
                                                'sort_order' => $_POST['sort_order']['brand_goods'][$k],
                                                'id'         => $_POST['brands']['brand_goods'][$k]);
                    }
                }
                break;
            case 'cat_articles':
                foreach ($val AS $k => $v)
                {
                    if (intval($_POST['article_cat']['cat_articles'][$k]) > 0)
                    {
                        $post_regions[] = array('region'     => $v,
                                                'type'       => 3,
                                                'number'     => $_POST['number']['cat_articles'][$k],
                                                'library'    => '/library/' .$key. '.lbi',
                                                'sort_order' => $_POST['sort_order']['cat_articles'][$k],
                                                'id'         => $_POST['article_cat']['cat_articles'][$k]);
                    }
                }
                break;
            case 'ad_position':
                foreach ($val AS $k => $v)
                {
                    if (intval($_POST['ad_position'][$k]) > 0)
                    {
                        $post_regions[] = array('region'     => $v,
                                                'type'       => 4,
                                                'number'     => $_POST['number']['ad_position'][$k],
                                                'library'    => '/library/' .$key. '.lbi',
                                                'sort_order' => $_POST['sort_order']['ad_position'][$k],
                                                'id'         => $_POST['ad_position'][$k]);
                    }
                }
                break;
            default:
                if (!empty($_POST['display'][$key]))
                {
                    $post_regions[] = array('region'     => $val,
                                            'type'       => 0,
                                            'number'     => 0,
                                            'library'    => $_POST['map'][$key],
                                            'sort_order' => $_POST['sort_order'][$key],
                                            'id'         => 0);
                }

        }
    }

    /* 排序 */
    usort($post_regions, "array_sort");

    /* 修改模板文件 */
    $template_file    = '../themes/' . $curr_template . '/' . $_POST['template_file'] . '.dwt';
    $template_content = file_get_contents($template_file);
    $template_content = str_replace("\xEF\xBB\xBF", '', $template_content);
    $org_regions      = get_template_region($curr_template, $_POST['template_file'].'.dwt', false);

    $region_content   = '';
    $pattern          = '/(<!--\\s*TemplateBeginEditable\\sname="%s"\\s*-->)(.*?)(<!--\\s*TemplateEndEditable\\s*-->)/s';
    $replacement      = "\\1\n%s\\3";
    $lib_template     = "<!-- #BeginLibraryItem \"%s\" -->\n%s\n <!-- #EndLibraryItem -->\n";

    foreach ($org_regions AS $region)
    {
        $region_content = ''; // 获取当前区域内容
        foreach ($post_regions AS $lib)
        {
            if ($lib['region'] == $region)
            {
                if (!file_exists('../themes/' . $curr_template . $lib['library']))
                {
                    continue;
                }
                $lib_content     = file_get_contents('../themes/' . $curr_template . $lib['library']);
                $lib_content     = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\/html;\\scharset=.*["|\']>/i', '', $lib_content);
                $lib_content     = str_replace("\xEF\xBB\xBF", '', $lib_content);
                $region_content .= sprintf($lib_template, $lib['library'], $lib_content);
            }
        }

        /* 替换原来区域内容 */
        $template_content = preg_replace(sprintf($pattern, $region), sprintf($replacement , $region_content), $template_content);
    }

    if (file_put_contents($template_file, $template_content))
    {
        //clear_tpl_files(false, '.dwt.php'); // 清除对应的编译文件
        clear_cache_files();
        $lnk[] = array('text' => $_LANG['go_back'], 'href'=>'template.php?act=setup&template_file=' .$_POST['template_file']);
        sys_msg($_LANG['setup_success'], 0, $lnk);
    }
    else
    {
        sys_msg(sprintf($_LANG['modify_dwt_failed'], 'themes/' . $curr_template. '/' . $_POST['template_file'] . '.dwt'), 1, null, false);
    }
}

/*------------------------------------------------------ */
//-- 管理库项目
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'library')
{
    admin_priv('library_manage');

    /* 包含插件语言项 */
    $sql = "SELECT code FROM ".$ecs->table('plugins');
    $rs = $db->query($sql);
    while ($row = $db->FetchRow($rs))
    {
        /* 取得语言项 */
        if (file_exists(ROOT_PATH . 'plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php'))
        {
            include_once(ROOT_PATH . 'plugins/'.$row['code'].'/languages/common_'.$_CFG['lang'].'.php');
        }
    }
    $curr_template = $_CFG['template'];
    $arr_library   = array();
    $library_path  = '../themes/' . $curr_template . '/library';
    $library_dir   = @opendir($library_path);
    $curr_library  = '';

    while ($file = @readdir($library_dir))
    {
        if (substr($file, -3) == "lbi")
        {
            $filename               = substr($file, 0, -4);
            $arr_library[$filename] = $file. ' - ' . @$_LANG['template_libs'][$filename];

            if ($curr_library == '')
            {
                $curr_library = $filename;
            }
        }
    }

    ksort($arr_library);

    @closedir($library_dir);

    $lib = load_library($curr_template, $curr_library);

    assign_query_info();
    $smarty->assign('ur_here',      $_LANG['04_template_library']);
    $smarty->assign('curr_library', $curr_library);
    $smarty->assign('libraries',    $arr_library);
    $smarty->assign('library_html', $lib['html']);
    $smarty->display('template_library.htm');
}

/*------------------------------------------------------ */
//-- 安装模版
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'install')
{
    check_authz_json('backup_setting');

    $tpl_name = trim($_GET['tpl_name']);
    $tpl_fg=0;
    $tpl_fg = trim($_GET['tpl_fg']);

    $sql = "UPDATE " .$GLOBALS['ecs']->table('shop_config'). " SET value = '$tpl_name' WHERE code = 'template'";
    $step_one = $db->query($sql, 'SILENT');
    $sql = "UPDATE " .$GLOBALS['ecs']->table('shop_config'). " SET value = '$tpl_fg' WHERE code = 'stylename'";
    $step_two = $db->query($sql, 'SILENT');

    if ($step_one && $step_two)
    {
        clear_all_files(); //清除模板编译文件

        $error_msg = '';
        if (move_plugin_library($tpl_name, $error_msg))
        {
            make_json_error($error_msg);
        }
        else
        {
            make_json_result(read_style_and_tpl($tpl_name, $tpl_fg), $_LANG['install_template_success']);
        }
    }
    else
    {
        make_json_error($db->error());
    }
}

/*------------------------------------------------------ */
//-- 备份模版
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'backup')
{
    check_authz_json('backup_setting');
    include_once('includes/cls_phpzip.php');
    $tpl= $_CFG['template'];
    //$tpl = trim($_REQUEST['tpl_name']);

    $filename = '../temp/backup/' . $tpl . '_' . date('Ymd') . '.zip';

    $zip = new PHPZip;
    $done = $zip->zip('../themes/' . $tpl . '/', $filename);

    if ($done)
    {
        make_json_result($filename);
    }
    else
    {
        make_json_error($_LANG['backup_failed']);
    }
}

/*------------------------------------------------------ */
//-- 载入指定库项目的内容
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'load_library')
{
    $library = load_library($_CFG['template'], trim($_GET['lib']));
    $message = ($library['mark'] & 7) ? '' : $_LANG['library_not_written'];

    make_json_result($library['html'], $message);
}

/*------------------------------------------------------ */
//-- 更新库项目内容
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'update_library')
{
    check_authz_json('library_manage');

    $html = stripslashes(json_str_iconv($_POST['html']));
    $lib_file = '../themes/' . $_CFG['template'] . '/library/' . $_POST['lib'] . '.lbi';
    $lib_file = str_replace("0xa", '', $lib_file); // 过滤 0xa 非法字符

    $org_html = str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_file));

    if (@file_exists($lib_file) === true && @file_put_contents($lib_file, $html))
    {
        @file_put_contents('../temp/backup/library/' . $_CFG['template'] . '-' . $_POST['lib'] . '.lbi', $org_html);

        make_json_result('', $_LANG['update_lib_success']);
    }
    else
    {
        make_json_error(sprintf($_LANG['update_lib_failed'], 'themes/' . $_CFG['template'] . '/library'));
    }
}

/*------------------------------------------------------ */
//-- 还原库项目
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'restore_library')
{
    admin_priv('backup_setting');
    $lib_name   = trim($_GET['lib']);
    $lib_file   = '../themes/' . $_CFG['template'] . '/library/' . $lib_name . '.lbi';
    $lib_file   = str_replace("0xa", '', $lib_file); // 过滤 0xa 非法字符
    $lib_backup = '../temp/backup/library/' . $_CFG['template'] . '-' . $lib_name . '.lbi';
    $lib_backup = str_replace("0xa", '', $lib_backup); // 过滤 0xa 非法字符

    if (file_exists($lib_backup) && filemtime($lib_backup) >= filemtime($lib_file))
    {
        make_json_result(str_replace("\xEF\xBB\xBF", '',file_get_contents($lib_backup)));
    }
    else
    {
        make_json_result(str_replace("\xEF\xBB\xBF", '',file_get_contents($lib_file)));
    }
}


/*------------------------------------------------------ */
//-- 布局备份
/*------------------------------------------------------ */
if ($_REQUEST['act'] == 'backup_setting')
{
    admin_priv('backup_setting');

    $sql = "SELECT DISTINCT(remarks) FROM " . $ecs->table('template') . " WHERE theme = '" . $_CFG['template'] . "' AND remarks > ''";
    $col = $db->getCol($sql);
    $remarks = array();
    foreach ($col as $val)
    {
        $remarks[] = array('content'=>$val, 'url'=>urlencode($val));
    }

    $sql = "SELECT DISTINCT(filename) FROM " . $ecs->table('template') . " WHERE theme = '" . $_CFG['template'] . "' AND remarks = ''";
    $col = $db->getCol($sql);
    $files = array();
    foreach ($col as $val)
    {
        $files[$val] = $_LANG['template_files'][$val];
    }

    assign_query_info();
    $smarty->assign('ur_here',      $_LANG['backup_setting']);
    $smarty->assign('list',  $remarks);
    $smarty->assign('files', $files);
    $smarty->display('templates_backup.htm');
}

if ($_REQUEST['act'] == 'act_backup_setting')
{
    $remarks = empty($_POST['remarks']) ? local_date($_CFG['time_format']) : trim($_POST['remarks']);

    if (empty($_POST['files']))
    {
        $files = array();
    }
    else
    {
        $files = $_POST['files'];
    }

    $sql = "SELECT COUNT(*) FROM " . $ecs->table('template') . " WHERE remarks='$remarks' AND theme = '" . $_CFG['template'] . "'";
    if ($db->getOne($sql) > 0)
    {
        sys_msg(sprintf($_LANG['remarks_exist'], $remarks), 1);
    }

    $sql = "INSERT INTO " . $ecs->table('template') .
           " (filename, region, library, sort_order, id, number, type, theme, remarks)".
           " SELECT filename, region, library, sort_order, id, number, type, theme, '$remarks'".
           " FROM " . $ecs->table('template') .
           " WHERE remarks = '' AND theme = '" . $_CFG['template'] . "'".
           " AND " . db_create_in($files, 'filename');

    $db->query($sql);
    sys_msg($_LANG['backup_template_ok'],0,array(array('text'=>$_LANG['backup_setting'], 'href'=>'template.php?act=backup_setting')));
}

if ($_REQUEST['act'] == 'del_backup')
{
    $remarks = empty($_GET['remarks']) ? '' : trim($_GET['remarks']);
    if ($remarks)
    {
        $sql = "DELETE FROM " . $ecs->table('template') . " WHERE remarks='$remarks' AND theme = '" . $_CFG['template'] . "'";
        $db->query($sql);
    }
    sys_msg($_LANG['del_backup_ok'],0,array(array('text'=>$_LANG['backup_setting'], 'href'=>'template.php?act=backup_setting')));
}

if ($_REQUEST['act'] == 'restore_backup')
{
    $remarks = empty($_GET['remarks']) ? '' : trim($_GET['remarks']);
    if ($remarks)
    {
        $sql = "SELECT filename, region, library, sort_order ".
               " FROM " . $ecs->table('template').
               " WHERE remarks='$remarks' AND theme = '" . $_CFG['template'] . "'".
               " ORDER BY filename, region, sort_order";
        $arr = $db->getAll($sql);
        if ($arr)
        {
            $data = array();
            foreach ($arr as $val)
            {
                $lib_content     = file_get_contents(ROOT_PATH . 'themes/' . $_CFG['template'] . $val['library']);
                //去除lib头部
                $lib_content     = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\/html;\\scharset=utf-8"|\']>/i', '', $lib_content);
                //去除utf bom
                $lib_content     = str_replace("\xEF\xBB\xBF", '', $lib_content);
                //加入dw 标识
                $lib_content     = '<!-- #BeginLibraryItem "' . $val['library'] . "\" -->\r\n" . $lib_content . "\r\n" . '<!-- #EndLibraryItem -->';
                if (isset($data[$val['filename']][$val['region']]))
                {
                    $data[$val['filename']][$val['region']] .= $lib_content;
                }
                else
                {
                    $data[$val['filename']][$val['region']] = $lib_content;
                }
            }

            foreach ($data as $file => $regions)
            {
                $pattern = '/(?:<!--\\s*TemplateBeginEditable\\sname="('. implode('|',array_keys($regions)) .')"\\s*-->)(?:.*?)(?:<!--\\s*TemplateEndEditable\\s*-->)/se';
                $temple_file = ROOT_PATH . 'themes/' . $_CFG['template'] . '/' . $file . '.dwt';
                $template_content = file_get_contents($temple_file);
                $match = array();
                $template_content = preg_replace($pattern, "'<!-- TemplateBeginEditable name=\"\\1\" -->\r\n' . \$regions['\\1'] . '\r\n<!-- TemplateEndEditable -->';", $template_content);
                file_put_contents($temple_file, $template_content);
            }

            /* 文件修改成功后，恢复数据库 */
            $sql = "DELETE FROM " .$ecs->table('template').
                   " WHERE remarks = '' AND  theme = '" . $_CFG['template'] . "'".
                   " AND " . db_create_in(array_keys($data), 'filename');
            $db->query($sql);
            $sql = "INSERT INTO " . $ecs->table('template') .
                   " (filename, region, library, sort_order, id, number, type, theme, remarks)".
                   " SELECT filename, region, library, sort_order, id, number, type, theme, ''".
                   " FROM " . $ecs->table('template') .
                   " WHERE remarks = '$remarks' AND theme = '" . $_CFG['template'] . "'";
            $db->query($sql);
        }
    }
    sys_msg($_LANG['restore_backup_ok'],0,array(array('text'=>$_LANG['backup_setting'], 'href'=>'template.php?act=backup_setting')));
}

function array_sort($a, $b)
{
    $cmp = strcmp($a['region'], $b['region']);

    if ($cmp == 0)
    {
        return ($a['sort_order'] < $b['sort_order']) ? -1 : 1;
    }
    else
    {
        return ($cmp > 0) ? -1 : 1;
    }
}

/**
 * 载入库项目内容
 *
 * @access  public
 * @param   string  $curr_template  模版名称
 * @param   string  $lib_name       库项目名称
 * @return  array
 */
function load_library($curr_template, $lib_name)
{
    $lib_name = str_replace("0xa", '', $lib_name); // 过滤 0xa 非法字符

    $lib_file    = '../themes/' . $curr_template . '/library/' . $lib_name . '.lbi';
    $arr['mark'] = file_mode_info($lib_file);
    $arr['html'] = str_replace("\xEF\xBB\xBF", '', file_get_contents($lib_file));

    return $arr;
}

/**
 * 读取模板风格列表
 *
 * @access  public
 * @param   string  $tpl_name       模版名称
 * @param   int     $flag           1，AJAX数据；2，Array
 * @return
 */
function read_tpl_style($tpl_name, $flag=1)
{
    if (empty($tpl_name) && $flag == 1)
    {
        return 0;
    }

    /* 获得可用的模版 */
    $temp = '';
    $start = 0;
    $available_templates = array();
    $dir = ROOT_PATH . 'themes/' . $tpl_name . '/';
    $tpl_style_dir = @opendir($dir);
    while ($file = readdir($tpl_style_dir))
    {
        if ($file != '.' && $file != '..' && is_file($dir . $file) && $file != '.svn' && $file != 'index.htm')
        {
            if (preg_match("/^(style|style_)(.*)*/i", $file)) // 取模板风格缩略图
            {
                $start = strpos($file, '.');
                $temp = substr($file, 0, $start);
                $temp = explode('_', $temp);
                if (count($temp) == 2)
                {
                    $available_templates[] = $temp[1];
                }
            }
        }
    }
    @closedir($tpl_style_dir);

    if ($flag == 1)
    {
        $ec = '<table border="0" width="100%" cellpadding="0" cellspacing="0" class="colortable" onMouseOver="javascript:onSOver(0, this);" onMouseOut="onSOut(this);" onclick="javascript:setupTemplateFG(0);"  bgcolor="#FFFFFF"><tr><td>&nbsp;</td></tr></table>';
        if (count($available_templates) > 0)
        {
            foreach ($available_templates as $value)
            {
                $tpl_info = get_template_info($tpl_name, $value);

                $ec .= '<table border="0" width="100%" cellpadding="0" cellspacing="0" class="colortable" onMouseOver="javascript:onSOver(\'' . $value . '\', this);" onMouseOut="onSOut(this);" onclick="javascript:setupTemplateFG(\'' . $value . '\');"  bgcolor="' . $tpl_info['type'] . '"><tr><td>&nbsp;</td></tr></table>';

                unset($tpl_info);
            }
        }
        else
        {
            $ec = '0';
        }

        return $ec;
    }
    elseif ($flag == 2)
    {
        $templates_temp = array('');
        if (count($available_templates) > 0)
        {
            foreach ($available_templates as $value)
            {
                $templates_temp[] = $value;
            }
        }

        return $templates_temp;
    }
}

/**
 * 读取当前风格信息与当前模板风格列表
 *
 * @access  public
 * @param   string  $tpl_name       模版名称
 * @param   string  $tpl_style 模版风格名
 * @return
 */
function read_style_and_tpl($tpl_name, $tpl_style)
{
    $style_info = array();
    $style_info = get_template_info($tpl_name, $tpl_style);

    $tpl_style_info = array();
    $tpl_style_info = read_tpl_style($tpl_name, 2);
    $tpl_style_list = '';
    if (count($tpl_style_info) > 1)
    {
        foreach ($tpl_style_info as $value)
        {
            $tpl_style_list .= '<span style="cursor:pointer;" onMouseOver="javascript:onSOver(\'screenshot\', \'' . $value . '\', this);" onMouseOut="onSOut(\'screenshot\', this, \'' . $style_info['screenshot'] . '\');" onclick="javascript:setupTemplateFG(\'' . $tpl_name . '\', \'' . $value . '\', \'\');" id="templateType_' . $value . '"><img src="../themes/' . $tpl_name . '/images/type' . $value . '_';

            if ($value == $tpl_style)
            {
                $tpl_style_list .= '1';
            }
            else
            {
                $tpl_style_list .= '0';
            }
            $tpl_style_list .= '.gif" border="0"></span>&nbsp;';
        }
    }
    $style_info['tpl_style'] = $tpl_style_list;

    return $style_info;
}
?>