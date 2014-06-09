<?php

/**
 * ECSHOP 注册短信
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liuhui $
 * $Id: sms_url.php 16654 2009-09-09 10:29:24Z liuhui $
 */

$url = '';
if(isset($GLOBALS['_CFG']['certificate_id']))
{

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
}

?>