<?php

/**
 * ECSHOP  云服务接口
 * ============================================================================
 * 版权所有 2005-2010 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: cloud.php 17063 2011-07-25 06:35:46Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/cls_transport.php');
require(ROOT_PATH . 'includes/cls_json.php');

require(ROOT_PATH . 'includes/shopex_json.php');

$data['api_ver'] = '1.0';
$data['version'] = VERSION;
$data['patch'] = file_get_contents(ROOT_PATH.ADMIN_PATH."/patch_num");
$data['ecs_lang'] = $_CFG['lang'];
$data['release'] = RELEASE;
$data['charset'] = strtoupper(EC_CHARSET);
$data['certificate_id'] = $_CFG['certificate_id'];
$data['token'] = md5($_CFG['token']);
$data['certi'] = $_CFG['certi'];
$data['php_ver'] = PHP_VERSION;
$data['mysql_ver'] = $db->version();
$data['shop_url'] = urlencode($ecs->url());
$data['admin_url'] = urlencode($ecs->url().ADMIN_PATH);
$data['sess_id'] = $GLOBALS['sess']->get_session_id();
$data['stamp'] = mktime();
$data['ent_id'] = $_CFG['ent_id'];
$data['ent_ac'] = $_CFG['ent_ac'];
$data['ent_sign'] = $_CFG['ent_sign'];
$data['ent_email'] = $_CFG['ent_email'];

$act = !empty($_REQUEST['act']) ? $_REQUEST['act'] :  'index';

$must = array('version','ecs_lang','charset','patch','stamp','api_ver');
if($act =='menu_api')
{

    if (!admin_priv('all','',false))
    {
        make_json_result('0');
    }
    $api_data = read_static_cache('menu_api');

    if($api_data === false || (isset($api_data['api_time']) && $api_data['api_time']<date('Ymd')))
    {
        $t = new transport;
       $apiget = "ver= $data[version] &ecs_lang= $data[ecs_lang] &charset= $data[charset]&ent_id=$data[ent_id]& certificate_id=$data[certificate_id]";
        $api_comment = $t->request('http://cloud.ecshop.com/menu_api.php', $apiget);
        $api_str = $api_comment["body"];
        if (!empty($api_str))
        {
            $json = new Services_JSON;
            $api_arr = @$json->decode($api_str,1);
            if (!empty($api_arr) && $api_arr['error'] == 0 && md5($api_arr['content']) == $api_arr['hash'])
            {
                $api_arr['content'] = urldecode($api_arr['content']);
                if ($data['charset'] != 'UTF-8')
                {
                    $api_arr['content'] = ecs_iconv('UTF-8',$data['charset'],$api_arr['content']);
                }
                $api_arr['api_time'] = date('Ymd');
                write_static_cache('menu_api', $api_arr);
                make_json_result($api_arr['content']);
            }
            else
            {
                make_json_result('0');
            }
        }
        else
        {
            make_json_result('0');
        }
    }
    else 
    {
        make_json_result($api_data['content']);
    }
}
elseif($act == 'cloud_remind')
{
    $api_data = read_static_cache('cloud_remind');
    
    if($api_data === false || (isset($api_data['api_time']) && $api_data['api_time']<date('Ymd')) )
    {
        $t = new transport('-1',5);
        $apiget = "ver=$data[version]&ecs_lang=$data[ecs_lang]&charset=$data[charset]&certificate_id=$data[certificate_id]&ent_id=$data[ent_id]";
        $api_comment = $t->request('http://cloud.ecshop.com/cloud_remind.php', $apiget);
        $api_str=    $api_comment["body"];
        $json = new Services_JSON;
        $api_arr = @$json->decode($api_str,1);
        if(!empty($api_str))
        {
            if (!empty($api_arr) && $api_arr['error'] == 0 && md5($api_arr['content']) == $api_arr['hash'])
            {
                $api_arr['content'] = urldecode($api_arr['content']);
                $message =explode('|',$api_arr['content']);
                $api_arr['content']='<li  class="cloud_close">'.$message['0'].'<img onclick="cloud_close('.$message['1'].')" src="images/no.gif"></li>';
                if ($data['charset'] != 'UTF-8')
                {
                    $api_arr['content'] = ecs_iconv('UTF-8',$data['charset'],$api_arr['content']);
                }
                $api_arr['api_time'] = date('Ymd');
                write_static_cache('cloud_remind', $api_arr);
                make_json_result($api_arr['content']);
            }
            else
            {
                make_json_result('0');
            }
       }
       else
      {
          make_json_result('0');
      }
    }
    else
    {
        make_json_result($api_data['content']);
    }
}
elseif($act == 'close_remind')
{

    $remind_id=$_REQUEST['remind_id'];
    $t = new transport('-1',5);
    $apiget = "ver= $data[version] &ecs_lang= $data[ecs_lang] &charset= $data[charset] &certificate_id=$data[certificate_id]&ent_id=$data[ent_id]&remind_id=$remind_id";
    $api_comment = $t->request('http://cloud.ecshop.com/cloud_remind.php', $apiget);

    $api_str = $api_comment["body"];
    $json = new Services_JSON;
    $api_arr = array();
    $api_arr = @$json->decode($api_str,1);
    if(!empty($api_str))
    {
        if (!empty($api_arr) && $api_arr['error'] == 0 && md5($api_arr['content']) == $api_arr['hash'])
        {
            $api_arr['content'] = urldecode($api_arr['content']);
            if($data['charset'] != 'UTF-8')
            {
                $api_arr['content'] = ecs_iconv('UTF-8',$data['charset'],$api_arr['content']);
            }
            if (admin_priv('all','',false))
            {
                $apiget.="&act=close_remind&ent_ac=$data[ent_ac]";
                $result=$t->request('http://cloud.ecshop.com/cloud_remind.php', $apiget);
                $api_str = $result["body"];
                //var_dump($api_str);
                $api_arr = array();
                $api_arr = @$json->decode($api_str,1);
                $api_arr['content'] = urldecode($api_arr['content']);
                if ($data['charset'] != 'UTF-8')
                {
                    $api_arr['content'] = ecs_iconv('UTF-8',$data['charset'],$api_arr['content']);
                }
                if($api_arr['error'] == 1)
                {
                    $message =explode('|',$api_arr['content']);
                    $api_arr['content']='<li  class="cloud_close">'.$message['0'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$message['2'].'</li>';
                   make_json_result($api_arr['content']);
                }
                else
                {
                    clear_all_files();
                    make_json_result('0');
                }
            }
            else
            {
                $message =explode('|',$api_arr['content']);

                $api_arr['content']='<li  class="cloud_close">'.$message['0'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$_LANG['cloud_no_priv'].'<img onclick="cloud_close( '.$message['1'].')" src="images/no.gif"></li>';

                make_json_result($api_arr['content']);
            }
        }
        else
        {
                make_json_result('0');
        }
    }
}
else
{
    admin_priv('all');
    if (empty($_GET['act']))
    {
        $act = 'index';
    }
    else
    {
        $query = '';
        $act = trim($_GET['act']);
        foreach ($_GET as $k=>$v)
        {
            if (array_key_exists($k, $data))
            {
                $query .= '&'.$k.'='.$data[$k];
            }
        }
    }
    if (!empty($_GET['link']))
    {
        $url = parse_url($_GET['link']);
        if (!empty($url['host']))
        {
            ecs_header("Location: ".$url['scheme']."://".$url['host'].$url['path']."?".$url['query'].$query."\n");
            exit();
        }
    }

    foreach ($must as $v)
    {
        $query .= '&'.$v.'='.$data[$v];
    }
    ecs_header("Location: http://cloud.ecshop.com/api.php?act=".$act.$query."\n");
    exit();
}

?>