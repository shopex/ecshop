/* 鍒濆?鍖栦竴浜涘叏灞€鍙橀噺 */
var lf = "<br />";
var iframe = null;
var notice = null;
var oriDisabledInputs = [];

/* Ajax璁剧疆 */
Ajax.onRunning = null;
Ajax.onComplete = null;

/* 椤甸潰鍔犺浇瀹屾瘯锛屾墽琛屼竴浜涙搷浣 */
window.onload = function () {
    var f = $("js-setup");


    $("js-pre-step").onclick = function() {
        location.href="./index.php?step=uccheck";
    };

    $("js-submit").onclick = function () {
        var params="maxuid=" + f["js-maxuid"].value;
        Ajax.call("./index.php?step=userimporttouc", params, displayres, 'POST', 'JSON');
    }
};

function displayres(res)
{
    if (res.error !== 0)
    {
        $("notice").innerHTML= res.message;
    }
    else
    {
		location.href="index.php?step=check";
    }
}