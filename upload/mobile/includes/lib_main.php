<?php

/**
 * ECSHOP mobile前台公共函数
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: testyang $
 * $Id: lib_main.php 15013 2008-10-23 09:31:42Z testyang $
*/

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

/**
 * 对输出编码
 *
 * @access  public
 * @param   string   $str
 * @return  string
 */
function encode_output($str)
{
//    if (EC_CHARSET != 'utf-8')
//    {
//        $str = ecs_iconv(EC_CHARSET, 'utf-8', $str);
//    }
    return htmlspecialchars($str);
}

/**
 * wap分页函数
 *
 * @access      public
 * @param       int     $num        总记录数
 * @param       int     $perpage    每页记录数
 * @param       int     $curr_page  当前页数
 * @param       string  $mpurl      传入的连接地址
 * @param       string  $pvar       分页变量
 */
function get_wap_pager($num, $perpage, $curr_page, $mpurl,$pvar)
{
    $multipage = '';
    if($num > $perpage)
    {
        $page = 2;
        $offset = 1;
        $pages = ceil($num / $perpage);
        $all_pages = $pages;
        $tmp_page = $curr_page;
        $setp = strpos($mpurl, '?') === false ? "?" : '&amp;';
        if($curr_page > 1)
        {
            $multipage .= "<a href=\"$mpurl${setp}${pvar}=".($curr_page-1)."\">上一页</a>";
        }
        $multipage .= $curr_page."/".$pages;
        if(($curr_page++) < $pages)
        {
            $multipage .= "<a href=\"$mpurl${setp}${pvar}=".$curr_page++."\">下一页</a><br/>";
        }
        //$multipage .= $pages > $page ? " ... <a href=\"$mpurl&amp;$pvar=$pages\"> [$pages] &gt;&gt;</a>" : " 页/".$all_pages."页";
        //$url_array = explode("?" , $mpurl);
       // $field_str = "";
       // if (isset($url_array[1]))
       // {
          //  $filed_array = explode("&amp;" , $url_array[1]);
           // if (count($filed_array) > 0)
            //{
             //   foreach ($filed_array AS $data)
              //  {
               //     $value_array = explode("=" , $data);
                //    $field_str .= "<postfield name='".$value_array[0]."' value='".encode_output($value_array[1])."'/>\n";
               // }
           // }
      //  }
        //$multipage .= "跳转到第<input type='text' name='pageno' format='*N' size='4' value='' maxlength='2' emptyok='true' />页<anchor>[GO]<go href='{$url_array[0]}' method='get'>{$field_str}<postfield name='".$pvar."' value='$(pageno)'/></go></anchor>";
        //<postfield name='snid' value='".session_id()."'/>
    }
    return $multipage;
}

/**
 * 返回尾文件
 *
 * @return  string
 */
function get_footer()
{
    if ($_SESSION['user_id'] > 0)
    {
        $footer = "<br/><a href='user.php?act=user_center'>用户中心</a>|<a href='user.php?act=logout'>退出</a>|<a href='javascript:scroll(0,0)' hidefocus='true'>回到顶部</a><br/>Copyright 2009<br/>Powered by ECShop v2.7.2";
    }
    else
    {
        $footer = "<br/><a href='user.php?act=login'>登陆</a>|<a href='user.php?act=register'>免费注册</a>|<a href='javascript:scroll(0,0)' hidefocus='true'>回到顶部</a><br/>Copyright 2009<br/>Powered by ECShop v2.7.2";
    }

    return $footer;
}

?>