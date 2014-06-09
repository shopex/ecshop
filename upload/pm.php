<?php

/**
 * ECSHOP 鐭?秷鎭?枃浠
 * ============================================================================
 * * 鐗堟潈鎵€鏈 2005-2012 涓婃捣鍟嗘淳缃戠粶绉戞妧鏈夐檺鍏?徃锛屽苟淇濈暀鎵€鏈夋潈鍒┿€
 * 缃戠珯鍦板潃: http://www.ecshop.com锛
 * ----------------------------------------------------------------------------
 * 杩欎笉鏄?竴涓?嚜鐢辫蒋浠讹紒鎮ㄥ彧鑳藉湪涓嶇敤浜庡晢涓氱洰鐨勭殑鍓嶆彁涓嬪?绋嬪簭浠ｇ爜杩涜?淇?敼鍜
 * 浣跨敤锛涗笉鍏佽?瀵圭▼搴忎唬鐮佷互浠讳綍褰㈠紡浠讳綍鐩?殑鐨勫啀鍙戝竷銆
 * ============================================================================
 * $Author: liubo $
 * $Id: pm.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
if (empty($_SESSION['user_id'])||$_CFG['integrate_code']=='ecshop')
{
    ecs_header('Location:./');
}

uc_call("uc_pm_location", array($_SESSION['user_id']));
//$ucnewpm = uc_pm_checknew($_SESSION['user_id']);
//setcookie('checkpm', '');

?>