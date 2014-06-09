<?php

/**
 * ECSHOP 综合流量统计
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: flow_stats.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/statistic.php');
$smarty->assign('lang', $_LANG);

/* act操作项的初始化 */
if (empty($_REQUEST['act']))
{
    $_REQUEST['act'] = 'view';
}
else
{
    $_REQUEST['act'] = trim($_REQUEST['act']);
}

if ($_REQUEST['act'] == 'view')
{
    if ($_CFG['visit_stats'] == 'off')
    {
        sys_msg($_LANG['stats_off']);
        exit();
    }
    admin_priv('client_flow_stats');
    $is_multi = empty($_POST['is_multi']) ? false : true;

    /* 时间参数 */
    if (isset($_POST['start_date']) && !empty($_POST['end_date']))
    {
        $start_date = local_strtotime($_POST['start_date']);
        $end_date = local_strtotime($_POST['end_date']);
    }
    else
    {
        $today  = local_strtotime(local_date('Y-m-d'));
        $start_date = $today - 86400 * 7;
        $end_date   = $today;
    }

    $start_date_arr = array();
    $end_date_arr = array();
    if(!empty($_POST['year_month']))
    {
        $tmp = $_POST['year_month'];

        for ($i = 0; $i < count($tmp); $i++)
        {
            if (!empty($tmp[$i]))
            {
                $tmp_time = local_strtotime($tmp[$i] . '-1');
                $start_date_arr[] = $tmp_time;
                $end_date_arr[]   = local_strtotime($tmp[$i] . '-' . date('t', $tmp_time));
            }
        }
    }
    else
    {
        $tmp_time = local_strtotime(local_date('Y-m-d'));
        $start_date_arr[] = local_strtotime(local_date('Y-m') . '-1');
        $end_date_arr[]   = local_strtotime(local_date('Y-m') . '-31');;
    }

    /* ------------------------------------- */
    /* --综合流量
    /* ------------------------------------- */
    $max = 0;

    if(!$is_multi)
    {
        $general_xml = "<graph caption='$_LANG[general_stats]' shownames='1' showvalues='1' decimalPrecision='0' yaxisminvalue='0' yaxismaxvalue='%d' animation='1' outCnvBaseFontSize='12' baseFontSize='12' xaxisname='$_LANG[date]' yaxisname='$_LANG[access_count]' >";

        $sql = "SELECT FLOOR((access_time - $start_date) / (24 * 3600)) AS sn, access_time, COUNT(*) AS access_count".
                " FROM " .$ecs->table('stats').
                " WHERE access_time >= '$start_date' AND access_time <= " .($end_date + 86400).
                " GROUP BY sn";
        $res = $db->query($sql);

        $key = 0;

        while ($val = $db->fetchRow($res))
        {
            $val['access_date'] = gmdate('m-d',$val['access_time'] +  $timezone * 3600);
            $general_xml .= "<set name='$val[access_date]' value='$val[access_count]' color='" .chart_color($key). "' />";
            if ($val['access_count'] > $max)
            {
                $max = $val['access_count'];
            }
            $key++;
        }

        $general_xml .= '</graph>';
        $general_xml  = sprintf($general_xml, $max);
    }
    else
    {
        $general_xml = "<graph caption='$_LANG[general_stats]' lineThickness='1' showValues='0' formatNumberScale='0' anchorRadius='2'   divLineAlpha='20' divLineColor='CC3300' divLineIsDashed='1' showAlternateHGridColor='1' alternateHGridAlpha='5' alternateHGridColor='CC3300' shadowAlpha='40' labelStep='2' numvdivlines='5' chartRightMargin='35' bgColor='FFFFFF,CC3300' bgAngle='270' bgAlpha='10,10' outCnvBaseFontSize='12' baseFontSize='12' >";
        foreach($start_date_arr AS $k => $val)
        {

            $seriesName = local_date('Y-m', $start_date_arr[$k]);
            $general_xml .= "<dataset seriesName='$seriesName' color='" . chart_color($k) . "' anchorBorderColor='" . chart_color($k) . "' anchorBgColor='" . chart_color($k) . "'>";
            $sql = "SELECT FLOOR((access_time - $start_date_arr[$k]) / (24 * 3600)) AS sn, access_time, COUNT(*) AS access_count".
                " FROM " .$ecs->table('stats').
                " WHERE access_time >= '$start_date_arr[$k]' AND access_time <= " .($end_date_arr[$k] + 86400).
                " GROUP BY sn";
            $res = $db->query($sql);

            $lastDay = 0;

            while ($val = $db->fetchRow($res))
            {
                $day = gmdate('d',$val['access_time'] +  $timezone * 3600);

                if ($lastDay == 0)
                {
                    $time_span = (($day-1) - $lastDay);
                    $lastDay++;
                    for (; $lastDay < $day; $lastDay++)
                    {
                        $general_xml .= "<set value='0' />";
                    }
                }
                $general_xml .= "<set value='$val[access_count]' />";
                $lastDay = $day;
            }

            $general_xml .= "</dataset>";
        }

        $general_xml .= "<categories>";

        for ($i = 1;$i<=31;$i++)
        {
            $general_xml .= "<category label='$i' />";
        }
        $general_xml .= "</categories>";
        $general_xml .= "</graph>";
    }
    /* ------------------------------------- */
    /* --地域分布
    /* ------------------------------------- */
    $area_xml  = '';

    if(!$is_multi)
    {
        $area_xml .= "<graph caption='".$_LANG['area_stats']."' shownames='1' showvalues='1' decimalPrecision='2' outCnvBaseFontSize='13' baseFontSize='13' pieYScale='45'  pieBorderAlpha='40' pieFillAlpha='70' pieSliceDepth='15' pieRadius='100' bgAngle='460'>";

        $sql = "SELECT COUNT(*) AS access_count, area FROM " . $ecs->table('stats') .
                " WHERE access_time >= '$start_date' AND access_time < " .($end_date + 86400).
                " GROUP BY area ORDER BY access_count DESC LIMIT 20";
        $res = $db->query($sql);

        $key = 0;
        while ($val = $db->fetchRow($res))
        {
            $area = empty($val['area']) ? 'unknow' : $val['area'];

            $area_xml .= "<set name='$area' value='$val[access_count]' color='" .chart_color($key). "' />";
            $key++;
        }
        $area_xml .= '</graph>';
    }
    else
    {
        $where = '';
        foreach($start_date_arr AS $k => $val)
        {
            if ($where != '')
            {
                $where .= ' or ';
            }
            $where .= "(access_time >= '$start_date_arr[$k]' AND access_time <= " .($end_date_arr[$k] + 86400).")";
        }
        $sql = "SELECT access_time, area FROM " . $ecs->table('stats') .
                " WHERE $where";
        $res = $db->query($sql);
        $area_arr = array();
        while ($val = $db->fetchRow($res))
        {
           $date = local_date('Y-m', $val['access_time']);
           $area_arr[$val['area']] = null;
           if (isset($category[$date][$val['area']]))
           {
               $category[$date][$val['area']]++;
           }
           else
           {
               $category[$date][$val['area']] = 1;
           }
        }
        $area_xml = "<chart palette='2' caption='$_LANG[area_stats]' shownames='1' showvalues='0' numberPrefix='' useRoundEdges='1' legendBorderAlpha='0' outCnvBaseFontSize='13' baseFontSize='13'>";
        $area_xml .= "<categories>";
        foreach ($area_arr AS $k => $v)
        {
            $area_xml .= "<category label='$k'/>";
        }
        $area_xml .= "</categories>";
        $key = 0;
        foreach($start_date_arr AS $val)
        {
            $key++;
            $date = local_date('Y-m', $val);
            $area_xml .= "<dataset seriesName='$date' color='" .chart_color($key). "' showValues='0'>";

            foreach ($area_arr AS $k => $v)
            {
                if (isset($category[$date][$k]))
                {
                    $area_xml .= "<set value='" .$category[$date][$k]. "'/>";
                }
                else
                {
                    $area_xml .= "<set value='0'/>";
                }
            }
            $area_xml .= "</dataset>";
        }
        $area_xml .= "</chart>";
    }

    /* ------------------------------------- */
    /* --来源网站
    /* ------------------------------------- */
    if(!$is_multi)
    {
        $from_xml = "<graph caption='$_LANG[from_stats]' shownames='1' showvalues='1' decimalPrecision='2' outCnvBaseFontSize='12' baseFontSize='12' pieYScale='45' pieBorderAlpha='40' pieFillAlpha='70' pieSliceDepth='15' pieRadius='100' bgAngle='460'>";

        $sql = "SELECT COUNT(*) AS access_count, referer_domain FROM " . $ecs->table('stats') .
                " WHERE access_time >= '$start_date' AND access_time <= " .($end_date + 86400).
                " GROUP BY referer_domain ORDER BY access_count DESC LIMIT 20";
        $res = $db->query($sql);

        $key = 0;

        while ($val = $db->fetchRow($res))
        {
            $from = empty($val['referer_domain']) ? $_LANG['input_url'] : $val['referer_domain'];

            $from_xml .= "<set name='".str_replace(array('http://', 'https://'), array('', ''), $from) . "' value='$val[access_count]' color='" . chart_color($key). "' />";

            $key++;
        }

        $from_xml .= '</graph>';
    }
    else
    {
        $where = '';
        foreach($start_date_arr AS $k => $val)
        {
            if ($where != '')
            {
                $where .= ' or ';
            }
            $where .= "(access_time >= '$start_date_arr[$k]' AND access_time <= " .($end_date_arr[$k] + 86400).")";
        }

        $sql = "SELECT access_time, referer_domain FROM " . $ecs->table('stats') .
                " WHERE $where";

        $res = $db->query($sql);
        $domain_arr = array();
        while ($val = $db->fetchRow($res))
        {
           $date = local_date('Y-m', $val['access_time']);
           $domain_arr[$val['referer_domain']] = null;
           if (isset($category[$date][$val['referer_domain']]))
           {
               $category[$date][$val['referer_domain']]++;
           }
           else
           {
               $category[$date][$val['referer_domain']] = 1;
           }
        }
        $from_xml = "<chart palette='2' caption='$_LANG[from_stats]' shownames='1' showvalues='0' numberPrefix='' useRoundEdges='1' legendBorderAlpha='0' outCnvBaseFontSize='13' baseFontSize='13'>";
        $from_xml .= "<categories>";
        foreach ($domain_arr AS $k => $v)
        {
            $from = $k == '' ? $_LANG['input_url'] : $k;
            $from_xml .= "<category label='$from'/>";
        }
        $from_xml .= "</categories>";
        $key = 0;
        foreach($start_date_arr AS $val)
        {
            $key++;
            $date = local_date('Y-m', $val);
            $from_xml .= "<dataset seriesName='$date' color='" .chart_color($key). "' showValues='0'>";

            foreach ($domain_arr AS $k => $v)
            {
                if (isset($category[$date][$k]))
                {
                    $from_xml .= "<set value='" .$category[$date][$k]. "'/>";
                }
                else
                {
                    $from_xml .= "<set value='0'/>";
                }
            }
            $from_xml .= "</dataset>";
        }
        $from_xml .= "</chart>";
    }

    /* 模板赋值 */
    $smarty->assign('ur_here',      $_LANG['flow_stats']);
    $smarty->assign('general_data', $general_xml);
    $smarty->assign('area_data',    $area_xml);
    $smarty->assign('is_multi',     $is_multi);
    $smarty->assign('from_data',    $from_xml);
    /* 显示日期 */

    $smarty->assign('start_date',   local_date('Y-m-d', $start_date));
    $smarty->assign('end_date',     local_date('Y-m-d', $end_date));

    for ($i = 0; $i < 5; $i++)
    {
        if (isset($start_date_arr[$i]))
        {
            $start_date_arr[$i] = local_date('Y-m', $start_date_arr[$i]);
        }
        else
        {
            $start_date_arr[$i] = null;
        }
    }
    $smarty->assign('start_date_arr', $start_date_arr);

    if (!$is_multi)
    {
        $filename = gmdate($_CFG['date_format'], $start_date + $timezone * 3600) . '_' .
                    gmdate($_CFG['date_format'], $end_date + $timezone * 3600);

        $smarty->assign('action_link',  array('text' => $_LANG['down_flow_stats'],
          'href'=>'flow_stats.php?act=download&filename=' . $filename .
            '&start_date=' . $start_date . '&end_date=' . $end_date));
    }

    /* 显示页面 */
    assign_query_info();
    $smarty->display('flow_stats.htm');
}
/* 报表下载 */
elseif ($act = 'download')
{
    $filename = !empty($_REQUEST['filename']) ? trim($_REQUEST['filename']) : '';

    header("Content-type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=$filename.xls");
    $start_date = empty($_GET['start_date']) ? strtotime('-20 day') : intval($_GET['start_date']);
    $end_date   = empty($_GET['end_date']) ? time() : intval($_GET['end_date']);
    $sql = "SELECT FLOOR((access_time - $start_date) / (24 * 3600)) AS sn, access_time, COUNT(*) AS access_count".
                " FROM " . $GLOBALS['ecs']->table('stats') .
                " WHERE access_time >= '$start_date' AND access_time <= " .($end_date + 86400).
                " GROUP BY sn";
    $res = $GLOBALS['db']->query($sql);

    $data  = $_LANG['general_stats'] . "\t\n";
    $data .= $_LANG['date'] . "\t";
    $data .= $_LANG['access_count'] . "\t\n";

    while ($val = $GLOBALS['db']->fetchRow($res))
    {
        $val['access_date'] = gmdate('m-d',$val['access_time'] +  $timezone * 3600);
        $data .= $val['access_date'] . "\t";
        $data .= $val['access_count'] . "\t\n";
    }

    $sql = "SELECT COUNT(*) AS access_count, area FROM " . $GLOBALS['ecs']->table('stats') .
            " WHERE access_time >= '$start_date' AND access_time <= " .($end_date + 86400).
            " GROUP BY area ORDER BY access_count DESC LIMIT 20";

    $res = $GLOBALS['db']->query($sql);

    $data .= $_LANG['area_stats'] . "\t\n";
    $data .= $_LANG['area'] . "\t";
    $data .= $_LANG['access_count'] . "\t\n";

    while ($val = $GLOBALS['db']->fetchRow($res))
    {
        $data .= $val['area'] . "\t";
        $data .= $val['access_count'] . "\t\n";
    }

    $sql = "SELECT COUNT(*) AS access_count, referer_domain FROM " . $GLOBALS['ecs']->table('stats') .
            " WHERE access_time >= '$start_date' AND access_time <= " .($end_date + 86400).
            " GROUP BY referer_domain ORDER BY access_count DESC LIMIT 20";

    $res  = $GLOBALS['db']->query($sql);

    $data .= "\n" . $_LANG['from_stats'] . "\t\n";

    $data .= $_LANG['url'] . "\t";
    $data .= $_LANG['access_count'] . "\t\n";

    while ($val = $GLOBALS['db']->fetchRow($res))
    {
        $data .= ($val['referer_domain'] == "" ? $_LANG['input_url'] : $val['referer_domain']) . "\t";
        $data .= $val['access_count'] . "\t\n";
    }
    if (EC_CHARSET != 'gbk')
    {
        echo ecs_iconv(EC_CHARSET, 'gbk', $data) . "\t";
    }
    else
    {
        echo $data. "\t";
    }
}

?>
