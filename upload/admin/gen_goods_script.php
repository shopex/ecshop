<?php
/**
 * ECSHOP 生成显示商品的js代码
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: gen_goods_script.php 17217 2011-01-19 06:29:08Z liubo $
 */

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/*------------------------------------------------------ */
//-- 生成代码
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'setup')
{
    /* 检查权限 */
    admin_priv('gen_goods_script');

    /* 编码 */
    $lang_list = array(
        'UTF8'   => $_LANG['charset']['utf8'],
        'GB2312' => $_LANG['charset']['zh_cn'],
        'BIG5'   => $_LANG['charset']['zh_tw'],
    );

    /* 参数赋值 */
    $ur_here = $_LANG['16_goods_script'];
    $smarty->assign('ur_here',    $ur_here);
    $smarty->assign('cat_list',   cat_list());
    $smarty->assign('brand_list', get_brand_list());
    $smarty->assign('intro_list', $_LANG['intro']);
    $smarty->assign('url',        $ecs->url());
    $smarty->assign('lang_list',  $lang_list);

    /* 显示模板 */
    assign_query_info();
    $smarty->display('gen_goods_script.htm');
}

?>