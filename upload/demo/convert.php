<?php

/* 鍒濆?鍖栧彉閲忓畾涔 */
$charset = 'utf-8';
$tools_version = "v1.0";
$mysql_version = '';
$ecshop_version = '';
$mysql_charset = '';
$ecshop_charset = '';
$convert_charset = array('utf-8' => 'gbk', 'gbk' => 'utf-8');
$convert_tables_file = 'data/convert_tables.php';
$rpp = 500; // 姣忔?澶勭悊鐨勮?褰曟暟

/* ECShop鐨勭珯鐐圭洰褰 */
define('ROOT_PATH', str_replace('\\', '/', substr(__FILE__, 0, -19)));
define('IN_ECS', true);

require(ROOT_PATH . 'data/config.php');
require(ROOT_PATH . 'includes/cls_ecshop.php');
require(ROOT_PATH . 'includes/cls_mysql.php');
require(ROOT_PATH . 'includes/lib_common.php');
require(ROOT_PATH . 'includes/lib_base.php');

/* 鏈?崌绾у墠锛岃?甯搁噺涓嶅瓨鍦 */
if (defined('EC_CHARSET')) {
    $ec_charset = EC_CHARSET;
} else {
    $ec_charset = '';
}
$ecshop_version = str_replace('v', '', VERSION);
$ecshop_charset = $ec_charset;
$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name, '', 0, 1);
$mysql_version = $db->version;
$mysql_charset = get_mysql_charset();

$step = getgpc('step');
$step = empty($step) ? 1 : $step;

if ($ecshop_version < '2.6.0') {
    $step = 'halt';
}

ob_start();
instheader();
if ($step == 1) {
    if (!empty($ecshop_charset) && !empty($mysql_charset) && $ecshop_charset == $mysql_charset) {
        $ext_msg = '<span style="color:red;font-size:14px;font-weight:bold">鎮ㄧ殑绋嬪簭缂栫爜涓庢暟鎹?簱缂栫爜涓€鑷达紝鏃犻渶杩涜?杞?崲銆侟/span><br /><a href="index.php"><font size="2"><b>&gt;&gt;&nbsp;濡傛灉鎮ㄩ渶瑕佹墽琛屽崌绾х▼搴忥紝璇风偣杩欓噷杩涜?鍗囩骇</b></font></a>';
    } elseif(empty($ecshop_charset) && !empty($mysql_charset)) {
        $ext_msg = '<form name="convert_form" method="post" action="?step=start"><b>鐢变簬鏈?兘纭?畾鎮ㄧ殑绋嬪簭缂栫爜锛岃?缂栫爜鐢辨偍鎵嬪姩纭?畾銆侟/b><br />
                    <b>鎮ㄧ殑鏁版嵁搴撶紪鐮佷负锛欬span style="color:blue">'. $mysql_charset .'</span> 锛岀‘璁ゆ偍鐨勭▼搴忕紪鐮佹槸锛欬span style="color:red">'. $convert_charset[$mysql_charset] .'</span> 鎵嶈兘杩涜?杞?崲</b><br /><br />
        <a href="###" id="runturn"><font size="2"><b>&gt;&gt;&nbsp;濡傛灉鎮ㄥ凡纭??瀹屾垚涓婇潰鐨勮?鏄?璇风偣杩欓噷杩涜?杞?崲</b></font></a><input type="hidden" name="ecshop_charset" value="'. $convert_charset[$mysql_charset] .'" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><font size="2">&gt;&gt;&nbsp;濡傛灉鎮ㄧ‘璁ょ▼搴忎笌鏁版嵁搴撶殑缂栫爜涓€鑷达紝璇风偣杩欓噷杩涜?鍗囩骇</font></a></form>';
        $ecshop_charset = '<span style="color:red">鏈?煡</span>';
    } elseif(empty($mysql_charset) && !empty($ecshop_charset)) {
        $ext_msg = '<form name="convert_form" method="post" action="?step=start"><b>鐢变簬鏈?兘纭?畾鎮ㄧ殑鏁版嵁搴撶紪鐮侊紝璇ョ紪鐮佺敱鎮ㄦ墜鍔ㄧ‘瀹氥€侟/b><br />
                    <b>鎮ㄧ殑绋嬪簭缂栫爜涓猴細<span style="color:blue">'. $ecshop_charset .'</span> 锛岀‘璁ゆ偍鐨勬暟鎹?簱缂栫爜鏄?細<span style="color:red">'. $convert_charset[$ecshop_charset] .'</span> 鎵嶈兘杩涜?杞?崲</b><br /><br />
        <a href="###" id="runturn"><font size="2"><b>&gt;&gt;&nbsp;濡傛灉鎮ㄥ凡纭??瀹屾垚涓婇潰鐨勮?鏄?璇风偣杩欓噷杩涜?杞?崲</b></font></a><input type="hidden" name="mysql_charset" value="'. $convert_charset[$ecshop_charset] .'" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><font size="2">&gt;&gt;&nbsp;濡傛灉鎮ㄧ‘璁ょ▼搴忎笌鏁版嵁搴撶殑缂栫爜涓€鑷达紝璇风偣杩欓噷杩涜?鍗囩骇</font></a></form>';
        $mysql_charset = '<span style="color:red">鏈?煡</span>';
    } elseif(empty($ecshop_charset) && empty($mysql_charset)) {
        $charset_option = '';
        foreach($convert_charset as $c_charset) {
            $charset_option .= '<option value="'.$c_charset.'">'.$c_charset.'</option>';
        }
        $ext_msg = '<form name="convert_form" method="post" action="?step=start"><b>鐢变簬鏈?兘纭?畾鎮ㄧ殑绋嬪簭涓庢暟鎹?簱缂栫爜锛岃?缂栫爜鐢辨偍鎵嬪姩纭?畾銆侟/b><br />
                    <b>鎮ㄧ殑绋嬪簭缂栫爜涓猴細<select name="ecshop_charset" id="ecshop_charset">'. $charset_option .'</select> 锛屾偍鐨勬暟鎹?簱缂栫爜涓猴細<select name="mysql_charset" id="mysql_charset">'. $charset_option .'</select></b><br /><b></b><br /><br />
        <a href="###" id="runturn"><font size="2"><b>&gt;&gt;&nbsp;濡傛灉鎮ㄥ凡纭??瀹屾垚涓婇潰鐨勮?鏄?璇风偣杩欓噷杩涜?杞?崲</b></font></a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php"><font size="2"><b>&gt;&gt;&nbsp;濡傛灉鎮ㄧ‘璁ょ▼搴忎笌鏁版嵁搴撶殑缂栫爜涓€鑷达紝璇风偣杩欓噷杩涜?鍗囩骇</font></a></form>';
        $mysql_charset = '<span style="color:red">鏈?煡</span>';
        $ecshop_charset = '<span style="color:red">鏈?煡</span>';
    }else {
        $ext_msg ='<a href="?step=start"><font size="2"><b>&gt;&gt;&nbsp;濡傛灉鎮ㄥ凡纭??瀹屾垚涓婇潰鐨勮?鏄?璇风偣杩欓噷杩涜?杞?崲</b></font></a>';
    }
    $ext_msg .= '
<script type="text/javascript">
    function _o(id) {
        return document.getElementById(id);
    }
    if (_o("runturn")) {
        _o("runturn").onclick = function() {
            document.forms["convert_form"].submit();
        }
    }
    if (_o("ecshop_charset") && _o("mysql_charset")) {
        if (_o("ecshop_charset").options[0].value == "gbk") {
            _o("mysql_charset").options[1].selected = true;
        } else {
            _o("mysql_charset").options[0].selected = true;
        }
        _o("ecshop_charset").onchange = function() {
            if (this.value == "gbk") {
                _o("mysql_charset").options[1].selected = true;
            } else {
                _o("mysql_charset").options[0].selected = true;
            }
        }
        _o("mysql_charset").onchange = function() {
            if (this.value == "gbk") {
                _o("ecshop_charset").options[1].selected = true;
            } else {
                _o("ecshop_charset").options[0].selected = true;
            }
        }
    }
</script>
';
    echo <<<EOT
<h4>鏈?浆鎹㈢▼搴忓彧鑳介拡ECShop2.6.0鎴栬€呬互涓婄増鏈?▼搴忕殑杞?崲<br /></h4>
杞?崲涔嬪墠<b>鍔″繀澶囦唤鏁版嵁搴撹祫鏂橖/b>锛岄伩鍏嶈浆鎹㈠け璐ョ粰鎮ㄥ甫鏉ユ崯澶变笌涓嶄究<br /><br />

<p>杞?崲绋嬪簭浣跨敤璇存槑锛欬/p>
<ol>
    <li>鍙?敮鎸丒CShop鏁版嵁搴撶殑杞?崲
    <li>鏍规嵁鎮ㄤ笂浼犵▼搴忕殑缂栫爜鑷?姩杞?崲鏁版嵁搴撶紪鐮侊紝鐜板湪鍙?敮鎸 UTF-8 涓 GBK 缂栫爜鐨勪簰鎹?€
    <li>鏈?伐鍏峰湪鎵ц?杩囩▼涓?笉浼氬?鎮ㄧ殑鍘熸暟鎹?簱杩涜?鐮村潖锛屼細灏嗘偍鐨勫師鏁版嵁琛ㄥ懡鍚嶄负澶囦唤鏂囦欢锛岃浆鎹㈠悗鐨勬暟鎹?瓨鍦ㄥ師鏉ョ殑琛ㄦ槑涓?€備緥濡傦細鍘熻〃鍚嶄负members锛堢紪鐮佷负UTF-8锛夐渶瑕佽浆涓篏BK缂栫爜锛屽垯杞?崲鍚庝负members锛堢紪鐮佷负GBK锛夛紝members_bak锛堢紪鐮佷负UTF-8锛屽嵆涓哄師琛ㄧ殑澶囦唤锛夈€
    <li>濡傛灉涓?€斿け璐ワ紝璇锋仮澶嶆暟鎹?簱鐨勫埌鍘熷?浠芥暟鎹?簱锛屽幓闄ら敊璇?悗閲嶆柊杩愯?鏈?▼搴
    <li><span style="color:red">杩涜?璇ユ搷鍋氬墠璇蜂竴瀹氬?浠芥偍鐨勬暟鎹?簱锛岃?杞?崲鍙?兘杩涜?涓€娆★紝濡傛灉杞?崲澶辫触璇蜂娇鐢ㄦ偍鐨勬暟鎹?簱澶囦唤杩樺師鏁版嵁搴撳悗閲嶆柊杩涜?杞?崲銆侟/span>
</ol>

<p>鎮ㄥ綋鍓嶇▼搴忎笌鏁版嵁搴撶殑淇℃伅锛欬/p>
<ul>
    <li>绋嬪簭鐗堟湰锛?ecshop_version</li>
    <li>绋嬪簭缂栫爜锛?ecshop_charset</li>
    <li>MySQL鐗堟湰锛?mysql_version</li>
    <li>MySQL缂栫爜锛?mysql_charset</li>
</ul>
$ext_msg
EOT;
    instfooter();
} elseif ($step == 'halt') {
    echo <<<EOT
    <br /><p><h4>鎮ㄥ綋鍓嶇殑绋嬪簭鐗堟湰灏忎簬2.6.0 锛岃?鍏堟洿鏂版偍鐨勭▼搴忓啀杩涜?杞?崲銆侟/h4></p><br />
EOT;
    instfooter();
} elseif ($step == 'start') {
    $ecshop_charset = isset($_POST['ecshop_charset'])? $_POST['ecshop_charset'] : $ecshop_charset;
    $mysql_charset = isset($_POST['mysql_charset'])? $_POST['mysql_charset'] : $mysql_charset;
    if ($ecshop_charset == $mysql_charset) {
        $ext_msg = '<span style="color:red;font-size:14px;font-weight:bold">鎮ㄧ殑绋嬪簭缂栫爜涓庢暟鎹?簱缂栫爜涓€鑷达紝鏃犻渶杩涜?杞?崲銆侟/span><br /><a href="index.php"><font size="2"><b>&gt;&gt;&nbsp;濡傛灉鎮ㄩ渶瑕佹墽琛屽崌绾х▼搴忥紝璇风偣杩欓噷杩涜?鍗囩骇</b></font></a>';
        showmessage($ext_msg);
    }
    $act = getgpc('act', 'P');
    if (init_convert_tables($convert_tables_file)) {
        include( ROOT_PATH . $convert_tables_file);
    } else {
        showmessage('<span style="color:red;font-size:14px;font-weight:bold">娌℃湁鏁版嵁琛ㄥ彲浠ヨ浆鎹↑/span>');
    }
    $tables_keys = array_keys($convert_tables);

    if (empty($act)) {
        $backup_count = backup_tables($tables_keys);
        $extra = '
        <input type="hidden" name="ecshop_charset" value="'. $ecshop_charset .'" />
        <input type="hidden" name="mysql_charset" value="'. $mysql_charset .'" />
        <input type="hidden" name="act" value="convert" />
        <input type="hidden" name="table_name" value="'.$tables_keys[0].'" />';
        showmessage("鏁版嵁搴撳?浠藉畬鎴愶紝".$backup_count." 涓?師鏁版嵁琛ㄥ潎閲嶅懡鍚嶄负浠 _bak 涓哄悗缂€锛?, '?step=start', 'form', $extra );
    } else {
        convert_table(getgpc('table_name', 'P'));
    }
}
ob_end_flush();

function instheader() {
    global $charset, $tools_version;

    echo "<html><head>".
        "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=$charset\">".
        "<title>ECShop 鏁版嵁搴撶紪鐮佽浆鎹㈠伐鍏?tools_version</title>".
        "<style type=\"text/css\">
        a {
            color: #3A4273;
            text-decoration: none
        }

        a:hover {
            color: #3A4273;
            text-decoration: underline
        }

        body, table, td {
            color: #3A4273;
            font-family: Tahoma, Verdana, Arial;
            font-size: 12px;
            line-height: 20px;
            scrollbar-base-color: #E3E3EA;
            scrollbar-arrow-color: #5C5C8D
        }
        form {
            margin:0;
            padding:0
        }
        input {
            color: #085878;
            font-family: Tahoma, Verdana, Arial;
            font-size: 12px;
            background-color: #3A4273;
            color: #FFFFFF;
            scrollbar-base-color: #E3E3EA;
            scrollbar-arrow-color: #5C5C8D
        }

        .install {
            font-family: Arial, Verdana;
            font-size: 20px;
            font-weight: bold;
            color: #000000
        }

        .message {
            background: #E3E3EA;
            padding: 20px;
        }

        .altbg1 {
            background: #E3E3EA;
        }

        .altbg2 {
            background: #EEEEF6;
        }

        .header td {
            color: #FFFFFF;
            background-color: #3A4273;
            text-align: center;
        }

        .option td {
            text-align: center;
        }

        .redfont {
            color: #FF0000;
        }
        .p_indent{
            text-indent:2em;
        }
        div.msg{
            text-indent:2em;
            line-height:30px;
            height:30px;
        }
        </style>
        <script type=\"text/javascript\">
        function redirect(url) {
            window.location=url;
        }
        function $(id) {
            return document.getElementById(id);
        }
        </script>
        </head>".
        "<body bgcolor=\"#298296\" text=\"#000000\"><div id=\"append_parent\"></div>".
        "<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=\"#FFFFFF\" align=\"center\"><tr><td>".
              "<table width=\"98%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\"><tr>".
              "<td class=\"install\" height=\"30\" valign=\"bottom\"><font color=\"#FF0000\">&gt;&gt;</font> ECShop 鏁版嵁搴撶紪鐮佽浆鎹㈠伐鍏?tools_version".
              "</td></tr><tr><td><hr noshade align=\"center\" width=\"100%\" size=\"1\"></td></tr><tr><td colspan=\"2\">";
}

function instfooter() {
    echo "</td></tr><tr><td><hr noshade align=\"center\" width=\"100%\" size=\"1\"></td></tr>".
            "<tr><td align=\"center\">".
                "<b style=\"font-size: 11px\">Powered by <a href=\"http://www.ecshop.com\" target=\"_blank\"><span style=\"color:#FF6100\">ECShop</span>".
              "</a></b>&nbsp; &copy; 2005-2011 涓婃捣鍟嗘淳缃戠粶绉戞妧鏈夐檺鍏?徃銆侟br /><br />".
              "</td></tr></table></td></tr></table>".
        "</body></html>";
}

function showmessage($message, $url_forward = '', $msgtype = 'message', $extra = '', $delaymsec = 1000) {
    //浠ヨ〃鍗曠殑褰㈠紡鏄剧ず淇℃伅
    if($msgtype == 'form') {
        $message = "<form method=\"post\" action=\"$url_forward\" name=\"hidden_form\">".
        "<br><p class=\"p_indent\">$message</p>\n $extra</form><br>".
        '<script type="text/javascript">
            setTimeout("document.forms[\'hidden_form\'].submit()", '. $delaymsec .');
        </script>';
    } else {
        if($url_forward) {
            $message .= "<script>setTimeout(\"redirect('$url_forward');\", $delaymsec);</script>";
            $message .= "<br><div align=\"right\">[<a href=\"$script_name\" style=\"color:red\">鍋滄?杩愯?</a>]<br><br><a href=\"$url_forward\">濡傛灉鎮ㄧ殑娴忚?鍣ㄩ暱鏃堕棿娌℃湁鑷?姩璺宠浆锛岃?鐐瑰嚮杩欓噷锛?/a></div>";
        } else {
            $message .= "<br /><br /><br />";
        }
        $message = "<br>$message$extra<br><br>";
    }

    echo $message;
    instfooter();
    exit;
}

function display($msg) {
    echo '<div class="msg">'.$msg.'</div>';
}

function get_mysql_charset() {
    global $db, $prefix;
    $tmp_charset = '';
    $query = $db->query("SHOW CREATE TABLE `{$prefix}users`", 'SILENT');
    if ($query) {
        $tablestruct = $db->fetch_array($query, MYSQL_NUM);
        preg_match("/CHARSET=(\w+)/", $tablestruct[1], $m);
        if (!empty($m)){
            if (strpos($m[1], 'utf') === 0) {
                $tmp_charset = str_replace('utf', 'utf-', $m[1]);
            } else {
                $tmp_charset = $m[1];
            }
        }
    }
    return $tmp_charset;
}

function getgpc($k, $var='G') {
    switch($var) {
        case 'G': $var = &$_GET; break;
        case 'P': $var = &$_POST; break;
        case 'C': $var = &$_COOKIE; break;
        case 'R': $var = &$_REQUEST; break;
    }
    return isset($var[$k]) ? $var[$k] : NULL;
}

function init_convert_tables($file) {
    if (is_file(ROOT_PATH . $file)) {
        return true;
    }
    global $db, $prefix;
    $tables = array();
    $query = $db->query('SHOW TABLE STATUS');
    while($result = $db->fetch_array($query)) {
        if (empty($prefix) || (!empty($prefix) && strpos($result['Name'], $prefix) === 0)) {
            //妫€鏌ヤ笉鑳芥湁浠 _bak 缁撳熬鐨勮〃
            if (preg_match('/_bak$/', $result['Name'])) {
                showmessage('鎮ㄧ殑鏁版嵁搴撳凡缁忓仛杩囪?瑷€缂栫爜杞?崲锛屽?闇€閲嶆柊杞?崲璇峰厛杩樺師鏁版嵁搴撳悗鍐嶆?鎵ц?鏈?▼搴忥紒');
            }
            $tables[$result['Name']] = 0;
        }
    }
    if (!empty($tables)) {
        $str = "<?php\n";
        $str .= '$convert_tables = ' . var_export($tables, true) . ";\n";
        $str .= "\n?>";
        file_put_contents(ROOT_PATH.$file, $str);
        return true;
    }
    return false;
}

function write_tables($tables, $file, $var_name) {
    if (!empty($tables)) {
        $str = "<?php\n";
        $str .= '$'.$var_name.' = ' . var_export($tables, true) . ";\n";
        $str .= "\n?>";
        file_put_contents(ROOT_PATH.$file, $str);
        return true;
    }
}

function backup_tables($tables) {
    global $db;
    global $convert_tables, $convert_tables_file;
    $suffix = '_bak';
    $backup_count = 0;
    display('姝ｅ湪杩涜?澶囦唤鏁版嵁琛?);
    if (!empty($tables)) {
        foreach($tables as $tablename) {
            $db->query("DROP TABLE IF EXISTS `{$tablename}{$suffix}`;", 'SILENT');
            $rename_sql = "RENAME TABLE `$tablename` TO `{$tablename}{$suffix}`;";
            if ($db->query($rename_sql, 'SILENT')) {
                $backup_count++;
                $convert_tables[$tablename] = 1;
            }
        }
        write_tables($convert_tables, $convert_tables_file, 'convert_tables');
        return $backup_count;
    }
    return 0;
}

function convert_table($table) {
    if (empty($table)) {
        showmessage('鏁版嵁琛ㄥ悕涓嶈兘涓虹┖锛岃浆鎹?腑姝?紝濡傞渶閲嶆柊杞?崲璇峰厛杩樺師鏁版嵁搴撳悗鍐嶆?鎵ц?鏈?▼搴忥紒');
    }
    display('姝ｅ湪杞?崲 '. $table .' 鏁版嵁琛?紝璇峰嬁鍏抽棴鏈?〉闈㈡垨鍒锋柊銆?);
    global $ecshop_charset, $mysql_charset, $mysql_version;
    global $db, $prefix;
    global $convert_tables, $convert_tables_file, $tables_keys, $rpp;

    $s_charset = str_replace('-', '', $mysql_charset);
    $d_charset = str_replace('-', '', $ecshop_charset);
    if ($convert_tables[$table] == 1) {
        $query = $db->query("SHOW CREATE TABLE `{$table}_bak`", 'SILENT');
        if ($query) {
            $tablestruct = $db->fetch_array($query, MYSQL_NUM);
            $createtable = $tablestruct[1];
            $createtable = preg_replace("/CREATE TABLE `{$table}_bak`/i", "CREATE TABLE `".$table."`", $createtable);
            if ($mysql_version >= '4.1') {
                $createtable = preg_replace("/CHARSET\=".$s_charset."/i", 'CHARSET='.$d_charset, $createtable);
            }
            if ($db->query($createtable, 'SILENT')) {
                $convert_tables[$table] = 2;
                write_tables($convert_tables, $convert_tables_file, 'convert_tables');
            } else {
                showmessage('鍒涘缓琛 ' . $table . ' 鏃跺け璐ワ紒<br /> ' . $createtable . '<br /> ' . mysql_error($db->link_id));
            }
        }
    }

    if ($convert_tables[$table] == 2) {
        if ($mysql_version >= '4.1') {
            $db->query('SET NAMES '.$s_charset);
        }
        $count = isset($_POST['count'])? intval($_POST['count']) : $db->getOne("SELECT COUNT(*) FROM `{$table}_bak`");
        $start = isset($_POST['start'])? intval($_POST['start']) : 0;
        $query = $db->query("SELECT * FROM `{$table}_bak` LIMIT $start, $rpp");
        while($row = $db->fetch_array($query)) {
            $_key = $_value = array();
            $insert_query = "INSERT INTO `{$table}`(`";
            foreach($row as $k => $v) {
                $_key[] = $k;
                $_value[] = addslashes(ecs_iconv($mysql_charset, $ecshop_charset, $v));
            }
            $_key = implode("`,`", $_key);
            $_value = implode("','", $_value);
            $insert_query .= $_key."`) VALUES ('".$_value."');";
            if ($mysql_version >= '4.1') {
                $db->query('SET NAMES '.$d_charset);
                $db->query("SET sql_mode=''");
            }
            if (!$db->query($insert_query, 'SILENT')) {
                showmessage('鎻掑叆 ' . $table . ' 琛ㄦ暟鎹?け璐ワ紒<br /> ' . $insert_query . '<br /> ' . mysql_error($db->link_id));
            }
        }
        if ($start + $rpp > $count) {
            unset($convert_tables[$table]);
            write_tables($convert_tables, $convert_tables_file, 'convert_tables');
            if (count($convert_tables) < 1) {
                @unlink(ROOT_PATH.$convert_tables_file);
                @setcookie('ECCC', $ecshop_charset, 0);
                showmessage('<br /><span style="font-size:14px;font-size:weight">杞?崲缁撴潫锛?/span><br /><a href="index.php"><font size="2"><b>&gt;&gt;&nbsp;濡傛灉鎮ㄩ渶瑕佹墽琛屽崌绾х▼搴忥紝璇风偣杩欓噷杩涜?鍗囩骇</b></font></a>');
            } else {
                array_shift($tables_keys);
                $extra = '
                <input type="hidden" name="ecshop_charset" value="'. $ecshop_charset .'" />
                <input type="hidden" name="mysql_charset" value="'. $mysql_charset .'" />
                <input type="hidden" name="act" value="convert" />
                <input type="hidden" name="table_name" value="'.$tables_keys[0].'" />';
                showmessage("鏁版嵁琛 {$table} 杞?崲瀹屾垚锛屾?鍦ㄨ繘鍏ヤ笅涓€涓?暟鎹?〃", '?step=start', 'form', $extra );
            }
        } else {
            $next_start = $start + $rpp;
            $extra = '
            <input type="hidden" name="ecshop_charset" value="'. $ecshop_charset .'" />
            <input type="hidden" name="mysql_charset" value="'. $mysql_charset .'" />
            <input type="hidden" name="act" value="convert" />
            <input type="hidden" name="start" value="'.$next_start.'" />
            <input type="hidden" name="count" value="'.$count.'" />
            <input type="hidden" name="table_name" value="'.$tables_keys[0].'" />';
            showmessage("姝ｅ湪杞?崲鏁版嵁琛 $table 鐨勭? $start - ".((($start+$rpp) > $count) ? $count : ($start+$rpp))." 鏉℃暟鎹?, '?step=start', 'form', $extra );
        }
    }
}