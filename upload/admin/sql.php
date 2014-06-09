<?php

/**
 * ECSHOP 会员管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: sql.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$_POST['sql'] = !empty($_POST['sql']) ? trim($_POST['sql']) : '';

if (!$_POST['sql'])
{
    $_REQUEST['act'] = 'main';
}

/*------------------------------------------------------ */
//-- 用户帐号列表
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'main')
{
    admin_priv('sql_query');
    assign_query_info();
    $smarty->assign('type',    -1);
    $smarty->assign('ur_here', $_LANG['04_sql_query']);

    $smarty->display('sql.htm');
}

if ($_REQUEST['act'] == 'query')
{
    admin_priv('sql_query');
    assign_sql($_POST['sql']);
    assign_query_info();
    $smarty->assign('ur_here', $_LANG['04_sql_query']);

    $smarty->display('sql.htm');
}

/**
 *
 *
 * @access  public
 * @param
 *
 * @return void
 */
function assign_sql($sql)
{
    global $db, $smarty, $_LANG;

    $sql = stripslashes($sql);
    $smarty->assign('sql', $sql);

    /* 解析查询项 */
    $sql = str_replace("\r", '', $sql);
    $query_items = explode(";\n", $sql);
    foreach ($query_items as $key=>$value)
    {
        if (empty($value))
        {
            unset($query_items[$key]);
        }
    }
    /* 如果是多条语句，拆开来执行 */
    if (count($query_items) > 1)
    {
        foreach ($query_items as $key=>$value)
        {
            if ($db->query($value, 'SILENT'))
            {
                $smarty->assign('type',  1);
            }
            else
            {
                $smarty->assign('type',  0);
                $smarty->assign('error', $db->error());
                return;
            }
        }
        return; //退出函数
    }

    /* 单独一条sql语句处理 */
    if (preg_match("/^(?:UPDATE|DELETE|TRUNCATE|ALTER|DROP|FLUSH|INSERT|REPLACE|SET|CREATE)\\s+/i", $sql))
    {
        if ($db->query($sql, 'SILENT'))
        {
            $smarty->assign('type',  1);
        }
        else
        {
            $smarty->assign('type',  0);
            $smarty->assign('error', $db->error());
        }
    }
    else
    {
        $data = $db->GetAll($sql);
        if ($data === false)
        {
            $smarty->assign('type',  0);
            $smarty->assign('error', $db->error());
        }
        else
        {
            $result = '';
            if (is_array($data) && isset($data[0]) === true)
            {
                $result = "<table> \n <tr>";
                $keys = array_keys($data[0]);
                for ($i = 0, $num = count($keys); $i < $num; $i++)
                {
                    $result .= "<th>" . $keys[$i] . "</th>\n";
                }
                $result .= "</tr> \n";
                foreach ($data AS $data1)
                {
                    $result .= "<tr>\n";
                    foreach ($data1 AS $value)
                    {
                        $result .= "<td>" . $value . "</td>";
                    }
                    $result .= "</tr>\n";
                }
                $result .= "</table>\n";
            }
            else
            {
                $result ="<center><h3>" . $_LANG['no_data'] . "</h3></center>";
            }

            $smarty->assign('type',   2);
            $smarty->assign('result', $result);
        }
    }
}

?>