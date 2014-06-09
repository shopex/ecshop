/* 鍒濆?鍖栦竴浜涘叏灞€鍙橀噺 */
var lf = "<br />";
var needupVerList = [];
var curVer = "";
var nextVer = "";
var iframe = null;
var notice = null;
var ui = "";

/* Ajax璁剧疆 */
Ajax.onRunning  = null;
Ajax.onComplete = null;

/* 鍦ㄥ崌绾ц繃绋嬩腑璋冪敤璇ユ柟娉 */
function startNotice() {
    $("js-monitor-loading").src = "images/loading.gif";
    $("js-monitor-wait-please").innerHTML = "<strong style='color:blue'>"
        + $_LANG["wait_please"] + "</strong>";
    $("js-monitor-rollback").innerHTML = '';
};

/* 鍗囩骇瀹屾瘯璋冪敤璇ユ柟娉 */
function stopNotice() {
    $("js-monitor-loading").src = "images/loading2.gif";
    $("js-monitor-wait-please").innerHTML = $_LANG["has_been_stopped"];
    $("js-monitor-rollback").innerHTML = $_LANG["rollback"];
};

/* 椤甸潰鍔犺浇瀹屾瘯鎵ц?涓€浜涙搷浣 */
window.onload = function () {
    if ($("js-submit")) {
        $("js-submit").onclick = function () {
            this.setAttribute("disabled", "true");
            upgrade();
        };
    }

    if ($("js-submit-uc")) {
        $("js-submit-uc").onclick = function () {
            this.setAttribute("disabled", "true");
            importUCenterData();
        }
    }

    iframe = frames[0];
    notice = $("js-notice", iframe);
    var d = new Draggable();
    d.bindDragNode("js-monitor", "js-monitor-title");

    var detail = $("js-monitor-view-detail")
    detail.innerHTML = $_LANG["display_detail"];
    detail.onclick = function () {
        var mn = $("js-monitor-notice");
        if (mn.style.display === "block") {
            mn.style.display = "none"
            this.innerHTML = $_LANG["display_detail"];
        } else {
            mn.style.display = "block"
            this.innerHTML = $_LANG["hide_detail"];
        }
    };

    $("js-monitor-rollback").onclick = function ()
    {
        this.setAttribute("disabled", "true");
        rollback();
    }

    $("js-monitor-close").onclick = function () {
        $("js-monitor").style.display = "none";
        if ($("js-submit")) {
            $("js-submit").removeAttribute("disabled");
        }
        if ($("js-submit-uc")) {
            $("js-submit-uc").removeAttribute("disabled");
        }
    };

    ui = QueryString('ui');
    if (ui == '') {
        ui = 'ecshop';
    }
};

/**
 * 鍗囩骇绋嬪簭涓绘柟娉
 */
function upgrade() {
    startNotice();
    $("js-monitor").style.display = "block";
    notice.innerHTML = $_LANG["initialize"] + $_LANG["suspension_points"];
    delay(1);
    try {
        var result = getVerList();
        if (result === false) {
            displayErrorMsg();
            return;
        }
        curVer = result["cur_ver"];
        needupVerList = result["needup_ver_list"];

       // if (needupVerList.length === 0) {
         //   notice.innerHTML += $_LANG["fail"] + lf
           //     + lf + lf + $_LANG["is_last_version"] + lf;
            //displayErrorMsg();
        //} else {
            notice.innerHTML += $_LANG["success"] + lf;
            go();
       // }
    } catch (ex) {
        ex = typeof(ex) === "object" ? ex.message : ex;
        location.href="index.php?step=error&msg="
            + encodeURI(ex);
    }
}

/**
 * 鐢ㄤ簬閫掕?璋冪敤鐨勬柟娉
 */
function go() {
    if (needupVerList.length === 0) {
        goToDone();
        return;
    }
    nextVer = needupVerList.shift();
    notice.innerHTML += lf + lf + " " +$_LANG["from"] + " " + curVer + " "
        + $_LANG["to"] + " " + nextVer + lf;
    notice.innerHTML += "<div><span id='js-dump-"+nextVer+"'></span>"
        + "<div id='js-files-"+nextVer+"'></div>"
        + "<div><span id='js-structure-"+nextVer+"'></span>"
        + "<span id='js-percent-"+nextVer+"'></span></div>"
        + "<div id='js-data-"+nextVer+"'></div>"
        + "<div id='js-result-"+nextVer+"'></div>";

    dump_database();
}

/**
 * 澶囦唤鏂囦欢
 */
function dump_database()
{
    localize("js-bottom", iframe);
    var dump = $("js-dump-"+nextVer, iframe);
    dump.innerHTML += lf + $_LANG["dump_database"]
        + $_LANG["suspension_points"];

    var params="next_ver=" + nextVer;
    Ajax.call("./index.php?step=dump_database", params, function (result){
        if (result.replace(/^\s+/g, '') === "OK") {
            dump.innerHTML += $_LANG["success"];
            updateFiles();
        } else {
            dump.innerHTML += $_LANG["fail"];
            notice.innerHTML += result;
            displayErrorMsg();
        }
    });
}

/**
 * 鍗囩骇鏂囦欢
 */
function updateFiles() {
    localize("js-bottom", iframe);
    $("js-files-"+nextVer, iframe).innerHTML += lf + $_LANG["update_files"]
        + $_LANG["suspension_points"];
    var params = "next_ver=" + nextVer;
    Ajax.call("./index.php?step=update_files", params, function (result) {
        var type="",
            msg = "";
        if (typeof(result) === "object") {
            if (result["msg"] === "OK") {
                type = $_LANG["success"];
            } else if (result["type"] === "NOTICE") {
                type = $_LANG["notice"];
                msg = result["msg"];
            } else {
                type = $_LANG["fail"];
                msg = result["msg"];
            }
        } else {
            type = $_LANG["fail"];
            msg = result;
        }
        $("js-files-"+nextVer, iframe).innerHTML += type;
        notice.innerHTML += msg;

        if (result["msg"] === "OK" || result["type"] === "NOTICE") {
            var recNum = getRecordNumber('structure');
            updateStructure(1, recNum);
        } else {
            displayErrorMsg();
        }
    }, "GET", "JSON");
}

/**
 * 鍗囩骇鏁版嵁缁撴瀯
 */
function updateStructure(curPos, recNum) {
    localize("js-bottom", iframe);

    var structure = $("js-structure-"+nextVer, iframe);
    structure.innerHTML = lf + $_LANG["update_structure"];
    if (!recNum) {
        structure.innerHTML += $_LANG["suspension_points"] + $_LANG["success"];
        updateData();
        return;
    }

    var params ="next_ver=" + nextVer + "&cur_pos=" + curPos;
    Ajax.call("./index.php?step=update_structure", params, function (result) {
        var percent = $("js-percent-"+nextVer, iframe);
        if (result.replace(/^\s+/g, '') === "OK") {
            percent.innerHTML = " (" + curPos + '/' + recNum + ")"
                + $_LANG["suspension_points"];
            if (++curPos <= recNum) {
                updateStructure(curPos, recNum);
            } else {
                percent.innerHTML += $_LANG["success"];
                updateData();
            }
        } else {
            percent.innerHTML += $_LANG["fail"];
            notice.innerHTML += result;
            displayErrorMsg();
        }
    });
}

/**
 * 鍗囩骇鏁版嵁
 */
function updateData() {
    localize("js-bottom", iframe);

    var data = $("js-data-"+nextVer, iframe);
    data.innerHTML += lf + $_LANG["update_others"]
        + $_LANG["suspension_points"];

    var params="next_ver=" + nextVer;
    Ajax.call("./index.php?step=update_data", params, function (result){
        if (result.replace(/^\s+/g, '') === "OK") {
            data.innerHTML += $_LANG["success"];
            curVer = nextVer;
            updateVersion();
        } else {
            data.innerHTML += $_LANG["fail"];
            $("js-result-"+nextVer, iframe).innerHTML = result;
            displayErrorMsg();
        }

        data = null;
    });
}

/**
 * 鍗囩骇鐗堟湰
 */
function updateVersion() {
    if ((ui == 'ucenter') && (nextVer == 'v2.6.0') && typeof(arguments[0]) == 'undefined') {
        goToUCenter();
    } else {
        var params = "next_ver=" + nextVer;
        Ajax.call("./index.php?step=update_version", params, function (result) {
            if (result.replace(/^\s+/g, '') === "OK") {
                go();
            } else {
                notice.innerHTML += $_LANG["fail"] + lf
                notice.innerHTML += result;
                displayErrorMsg();
            }
        });
    }
}

/**
 * 鑾峰緱鐗堟湰鍒楄〃
 */
function getVerList() {
    var params = "IS_AJAX_REQUEST=yes",
        result = Ajax.call("./index.php?step=get_ver_list", params, null, "GET", "JSON", false);
    if (typeof(result) === "object" && result["msg"] === "OK") {
        return result;
    } else {
        notice.innerHTML += $_LANG["fail"] + lf
        notice.innerHTML += result;
        displayErrorMsg();
        return false;
    }
}

/**
 * 鑾峰緱璁板綍鏁
 */
function getRecordNumber(type) {
    var params = "next_ver=" + nextVer + "&" + "type=" + type,
        result = null;

    try {
        result = Ajax.call("./index.php?step=get_record_number", params, null, "GET", "JSON", false);
    } catch (ex) {
        notice.innerHTML += lf + $_LANG["exception"] + lf
        notice.innerHTML += ex;
        displayErrorMsg();
        return false;
    }

    if (typeof(result) === "object" && result["msg"] === "OK") {
        return result["rec_num"];
    } else {
        notice.innerHTML += $_LANG["fail"] + lf
        notice.innerHTML += result;
        displayErrorMsg();
        return false;
    }
}

function rollback()
{
    localize("js-bottom", iframe);

    var dump = $("js-result-"+nextVer, iframe);
    dump.innerHTML = lf + $_LANG["rollback"] + $_LANG["suspension_points"];

    var params ="next_ver=" + nextVer;
    Ajax.call("./index.php?step=rollback", params, function (result){
        if (result.replace(/^\s+/g, '') === "OK") {
            dump.innerHTML += $_LANG["success"];
//            goToDone();
        } else {
            dump.innerHTML += $_LANG["fail"];
            notice.innerHTML += result;
            displayErrorMsg();
        }
    });

    return;
}

/**
 * 璺冲埌瀹屾垚椤
 */
function goToDone() {
    stopNotice();
    window.setTimeout(function () {
        location.href = "./index.php?step=done&ui="+ui;
    }, 1000);
}

/**
 * 鐢ㄤ簬寤舵椂鐨勬柟娉
 */
function delay(seconds) {
    window.setTimeout(function () {
    }, seconds * 1000);
}

/**
 * 鏄剧ず閿欒?淇℃伅
 */
function displayErrorMsg() {
    stopNotice();
    $("js-monitor-view-detail"). innerHTML = $_LANG["hide_detail"];
    $("js-monitor-notice").style.display = "block";
    localize("js-bottom", iframe);
}

/**
 * 椤甸潰鍏冪礌瀹氫綅鏂规硶
 */
function localize(target, winHDL) {
    if (typeof(winHDL) === "undefined") {
        winHDL = window;
    }

    target = typeof(target) === "string" ? $(target, winHDL) : target;
    var pageYOffset = winHDL.document.body.scrollTop || winHDL.pageYOffset || 0,
        y;

    y = target.offsetTop + pageYOffset;
    winHDL.scrollTo(0, y);
}

/**
 * 璺冲埌 UCenter 瀹夎?椤甸潰
 */
function goToUCenter(){
    stopNotice();
    window.setTimeout(function () {
        location.href = "./index.php?step=uccheck";
    }, 1000);
}

/**
 * 瀹夎?UCenter鐨勬暟鎹
 */
function importUCenterData() {
    startNotice();
    $("js-monitor").style.display = "block";
    notice.innerHTML = $_LANG["initialize"] + $_LANG["suspension_points"];
    delay(1);
    try {
        var merge = ($("js-merge-1").checked == true)?$("js-merge-1").value : $("js-merge-2").value;
        var params ="merge=" + merge;
        Ajax.call("./index.php?step=userimporttouc", params, function (result) {
            if (result.error > 0) {
                notice.innerHTML += result.message + lr;
                $("js-monitor-notice").style.display = "block";
                stopNotice();
            } else {
                var result = getVerList();
                if (result === false) {
                    displayErrorMsg();
                    return;
                }
                curVer = result["cur_ver"];
                needupVerList = result["needup_ver_list"];

                //if (needupVerList.length === 0) {
                 //   notice.innerHTML += $_LANG["fail"] + lf
                   //     + lf + lf + $_LANG["is_last_version"] + lf;
                   // displayErrorMsg();
               // } else {
                    notice.innerHTML += $_LANG["success"] + lf;
                    nextVer = needupVerList.shift();
                    updateVersion(true);
              //  }
            }
        }, "POST", "JSON");
    } catch (ex) {
        ex = typeof(ex) === "object" ? ex.message : ex;
        location.href="index.php?step=error&msg="
            + encodeURI(ex);
    }
}
