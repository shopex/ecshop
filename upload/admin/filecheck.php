<?php

/**
 * ECSHOP 文件校验
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: sunxiaodong $
 * $Id: filecheck.php 15457 2008-12-16 10:42:26Z sunxiaodong $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

/* 检查权限 */
admin_priv('file_check');

if (!$ecshopfiles = @file('./ecshopfiles.md5'))
{
    sys_msg($_LANG['filecheck_nofound_md5file'], 1);
}

$step = empty($_REQUEST['step']) ? 1 : max(1, intval($_REQUEST['step']));

if ($step == 1 || $step == 2)
{
    $smarty->assign('step', $step );
    if ($step == 1)
    {
        $smarty->assign('ur_here', $_LANG['file_check']);
    }
    if ($step == 2)
    {
        $smarty->assign('ur_here', $_LANG['fileperms_verify']);
    }
    assign_query_info();
    $smarty->display('filecheck.htm');

}
elseif ($step == 3)
{
    @set_time_limit(0);

    $md5data = array();
    checkfiles('./', '\.php', 0);
    checkfiles(ADMIN_PATH . '/', '\.php|\.htm|\.js|\.css|\xml');
    checkfiles('api/', '\.php');
    checkfiles('includes/', '\.php|\.html|\.js', 1 ,'fckeditor');
    checkfiles('js/', '\.js|\.css');
    checkfiles('languages/', '\.php');
    checkfiles('plugins/', '\.php');
    checkfiles('wap/', '\.php|\.wml');
    checkfiles('mobile/', '\.php');
    /*
    checkfiles('themes/default/', '\.dwt|\.lbi|\.css');
    checkfiles('uc_client/', '\.php', 0);
    checkfiles('uc_client/control/', '\.php');
    checkfiles('uc_client/model/', '\.php');
    checkfiles('uc_client/lib/', '\.php');
    */

    foreach ($ecshopfiles as $line)
    {
        $file = trim(substr($line, 34));
        $md5datanew[$file] = substr($line, 0, 32);
        if ($md5datanew[$file] != $md5data[$file])
        {
            $modifylist[$file] = $md5data[$file];
        }
        $md5datanew[$file] = $md5data[$file];
    }

    $weekbefore = time() - 604800;  //一周前的时间
    $addlist = @array_diff_assoc($md5data, $md5datanew);
    $dellist = @array_diff_assoc($md5datanew, $md5data);
    $modifylist = @array_diff_assoc($modifylist, $dellist);
    $showlist = @array_merge($md5data, $md5datanew);

    $result = $dirlog = array();
    foreach ($showlist as $file => $md5)
    {
        $dir = dirname($file);
        $statusf = $statust = 1;
        if (@array_key_exists($file, $modifylist))
        {
            $status = '<em class="edited">'.$_LANG['filecheck_modify'] . '</em>';
            if (!isset($dirlog[$dir]['modify']))
            {
                $dirlog[$dir]['modify'] = '';
            }
            $dirlog[$dir]['modify']++;  //统计“被修改”的文件
            $dirlog[$dir]['marker'] = substr(md5($dir),0,3);
        }
        elseif (@array_key_exists($file, $dellist))
        {
            $status = '<em class="del">'.$_LANG['filecheck_delete'] . '</em>';
            if (!isset($dirlog[$dir]['del']))
            {
                $dirlog[$dir]['del'] = '';
            }
            $dirlog[$dir]['del']++;     //统计“被删除”的文件
            $dirlog[$dir]['marker'] = substr(md5($dir),0,3);
        }
        elseif (@array_key_exists($file, $addlist))
        {
            $status = '<em class="unknown">'.$_LANG['filecheck_unknown'] . '</em>';
            if (!isset($dirlog[$dir]['add']))
            {
                $dirlog[$dir]['add'] = '';
            }
            $dirlog[$dir]['add']++;     //统计“未知”的文件
            $dirlog[$dir]['marker'] = substr(md5($dir),0,3);
        }
        else
        {
            $status = '<em class="correct">'.$_LANG['filecheck_check_ok'] . '</em>';
            $statusf = 0;
        }

        //对一周之内发生修改的文件日期加粗显示
        $filemtime = @filemtime(ROOT_PATH.$file);
        if ($filemtime > $weekbefore)
        {
            $filemtime = '<b>'.date("Y-m-d H:i:s", $filemtime).'</b>';
        }
        else
        {
            $filemtime = date("Y-m-d H:i:s", $filemtime);
            $statust = 0;
        }

        if ($statusf)
        {
            $filelist[$dir][] = array('file' => basename($file), 'size' => file_exists(ROOT_PATH.$file) ? number_format(filesize(ROOT_PATH.$file)).' Bytes' : '', 'filemtime' => $filemtime, 'status' => $status);
        }
    }

        $result[$_LANG['result_modify']] = count($modifylist);
        $result[$_LANG['result_delete']] = count($dellist);
        $result[$_LANG['result_unknown']] = count($addlist);

        $smarty->assign('result',     $result);
        $smarty->assign('dirlog',     $dirlog);
        $smarty->assign('filelist',   $filelist);
        $smarty->assign('step', $step );
        $smarty->assign('ur_here', $_LANG['filecheck_completed']);
        $smarty->assign('action_link', array('text' => $_LANG['filecheck_return'], 'href' => 'filecheck.php?step=1'));

        assign_query_info();
        $smarty->display('filecheck.htm');
}


/**检查文件
* @param  string $currentdir    //待检查目录
* @param  string $ext           //待检查的文件类型
* @param  int    $sub           //是否检查子目录
* @param  string $skip          //不检查的目录或文件
*/
function checkfiles($currentdir, $ext = '', $sub = 1, $skip = '')
{
    global $md5data;

    $currentdir = ROOT_PATH.str_replace(ROOT_PATH, '', $currentdir);
    $dir = @opendir($currentdir);
    $exts = '/('.$ext.')$/i';
    $skips = explode(',', $skip);

    while ($entry = @readdir($dir))
    {
        $file = $currentdir.$entry;

        if ($entry != '.' && $entry != '..' && $entry != '.svn' && (preg_match($exts, $entry) || ($sub && is_dir($file))) && !in_array($entry, $skips))
        {
            if ($sub && is_dir($file))
            {
                checkfiles($file.'/', $ext, $sub, $skip);
            }
            else
            {
                if(str_replace(ROOT_PATH, '', $file) != './md5.php')
                {
                    $md5data[str_replace(ROOT_PATH, '', $file)] = md5_file($file);
                }
            }
        }
    }
}
?>