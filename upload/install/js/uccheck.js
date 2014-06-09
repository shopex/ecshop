/* 初始化一些全局变量 */
var lf = "<br />";
var iframe = null;
var notice = null;
var oriDisabledInputs = [];

/* Ajax设置 */
Ajax.onRunning = null;
Ajax.onComplete = null;

/* 页面加载完毕，执行一些操作 */
window.onload = function () {
    setInputCheckedStatus();
    var f = $("js-setup");
    var ucinstalloptions = document.getElementsByName("ucinstall");


    $("js-pre-step").onclick = function() {
        location.href="./index.php?lang=" + getAddressLang() + "&step=welcome";
    };

    $("js-submit").onclick = function () {
        setupUCenter();
    }

    var ui_1 = $('user_interface_1');
    var ui_2 = $('user_interface_2');

    if (ui_1)
    {
        ui_1.onclick = function ()
        {
            if (this.checked === true)
            {
                $('ucenter').style.display = 'none';
            }
        }
    }
    if (ui_2)
    {
        ui_2.onclick = function ()
        {
            if (this.checked === true)
            {
                $('ucenter').style.display = '';
            }
        }
    }

    user_interface_init(ui_1);
};

/**
 * 连接Ucenter
 */
function setupUCenter()
{
   var f = $("js-setup");
   if ($('user_interface_1').checked === true)
   {
       f.action = "index.php?lang=" + getAddressLang() + "&step=check";
       f.submit();
   }
   var ucinstalloptions = document.forms['js-setup'].ucinstall;
   var uccheck = true;


   if(f["js-ucapi"].value.length < 1)
   {
       $("ucapinotice").innerHTML='请填写UCenter的URL';
        uccheck = false;
   }
   else
   {
        $("ucapinotice").innerHTML='';
   }

    if(f["js-ucip"] && f["js-ucip"].value.length < 1) {
        $("ucipnotice").innerHTML='请填写UCenter的IP';
        uccheck = false;
    }
    
    if (f['js-ucfounderpw'].value.length < 1)
    {
        $("ucfounderpwnotice").innerHTML='请填写 UCenter 创始人的密码';
        uccheck = false;
    }
    else
    {
        $("ucfounderpwnotice").innerHTML='';
    }

    if(uccheck == false)
    {
        return uccheck;
    }
    if(f["js-ucip"] && f["js-ucip"].value.length > 1) {
        var params="ucapi=" + encodeURIComponent(f["js-ucapi"].value) + "&" + "ucfounderpw=" + encodeURIComponent(f["js-ucfounderpw"].value)+"&"+"ucip="+encodeURIComponent(f["js-ucip"].value);
    } else {
        var params="ucapi=" + encodeURIComponent(f["js-ucapi"].value) + "&" + "ucfounderpw=" + encodeURIComponent(f["js-ucfounderpw"].value);
    }
    
    Ajax.call("./index.php?step=setup_ucenter", params, displayres, 'POST', 'JSON');

}

function displayres(res)
{
    if (res.error !== 0)
    {
        $("ucfounderpwnotice").innerHTML= res.message;
        if(res.error == 2) {
            var td1 = document.createElement("TD");
            var td2 = document.createElement("TD");
            td1.innerHTML = 'UCenter 的 IP：';
            td1.setAttribute('width', 200);td1.setAttribute('align', 'right');
            td2.innerHTML = '<input name=\"js-ucip\" type=\"text\" id=\"js-ucip\"  value=\"\" size=\"40\" /><span id=\"ucipnotice\" style=\"color:#FF0000\">连接的过程中出了点问题，请您填写服务器 IP 地址，如果您的 UC 与 ECShop 装在同一服务器上，我们建议您尝试填写 127.0.0.1</span>';
            $("ucip").appendChild(td1);
            $("ucip").appendChild(td2);
        }
        
    }
    else
    {
    	var ui = ($('user_interface_1').checked === true)?$('user_interface_1').value:$('user_interface_2').value;
        location.href="index.php?lang=" + getAddressLang() + "&step=check" + "&ui="+ui;
    }
}

function user_interface_init(obj)
{
    if (obj.checked === true)
    {
        $('ucenter').style.display = 'none';
    }
    else
    {
        $('ucenter').style.display = '';
    }
}
