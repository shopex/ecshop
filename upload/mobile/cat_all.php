<?php

/**
 * ECSHOP 分类聚合页
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: index.php 15013 2010-03-25 09:31:42Z liuhui $
*/

define('IN_ECS', true);
define('ECS_ADMIN', true);

require(dirname(__FILE__) . '/includes/init.php');



$pcat_array = get_categories_tree();
foreach ($pcat_array as $key => $pcat_data)
{
    $pcat_array[$key]['name'] = encode_output($pcat_data['name']);
    if ($pcat_data['cat_id'])
    {
        foreach ($pcat_data['cat_id'] as $k => $v)
        {
            $pcat_array[$key]['cat_id'][$k]['name'] = encode_output($v['name']);
        }
    }
}
$smarty->assign('pcat_array' , $pcat_array);

$smarty->assign('wap_logo', $_CFG['wap_logo']);
$smarty->assign('footer', get_footer());
$smarty->display("cat_all.html");

?>
