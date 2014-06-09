<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $lang['setting_title'];?></title>
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/transport.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/draggable.js"></script>
<script type="text/javascript" src="js/setting.js"></script>
<script type="text/javascript">
var $_LANG = {};
<?php foreach($lang['js_languages'] as $key => $item): ?>
$_LANG["<?php echo $key;?>"] = "<?php echo $item;?>";
<?php endforeach; ?>
</script>
</head>
<body id="checking">
<?php include ROOT_PATH . 'install/templates/header.php';?>
<div id="content">
<p style="font-size:30px;text-align: center;margin-top:50px;">
<?php echo $lang['loading'];?>
</p>
<img id="js-monitor-loading" src='images/loading.gif' style="margin:30px 0 50px 0;"/>
</div>
<div id="copyright">
    <div id="copyright-inside">

      <?php include ROOT_PATH . 'install/templates/copyright.php';?></div>
</div>
<script type="text/javascript">
Ajax.call('cloud.php?step=setting_ui','', setting_ui_api, 'GET', 'TEXT','FLASE');
function setting_ui_api(result)
{
  if(result)
  {
    setInnerHTML('content',result);
    setInputCheckedStatus();
    var f = $("js-setting");

    f.setAttribute("action", "javascript:install();void 0;");

    f["js-db-name"].onblur = function () {
        var list = getDbList();
        for (var i = 0; i < list.length; i++) {
            if (f["js-db-name"].value === list[i]) {
                var answer = confirm($_LANG["db_exists"]);
                if (answer === false) {
                    f["js-db-name"].value = "";
                }
            }
        }
    }
    f["js-admin-password"].onblur = function  () {
            var password = f['js-admin-password'].value;
            var confirm_password = f['js-admin-password2'].value;
            if (!(password.length >= 8 && /\d+/.test(password) && /[a-zA-Z]+/.test(password)))
            {
                $("js-install-at-once").setAttribute("disabled", "true");
                if (!(password.length >= 8)){
                    $("js-admin-password-result").innerHTML="<span class='comment'><img src='images\/no.gif'>"+$_LANG["password_short"]+"<\/span>";
                }
                else
                {
                    $("js-admin-password-result").innerHTML="<span class='comment'><img src='images\/no.gif'>"+$_LANG["password_invaild"]+"<\/span>";
                }
            }
            else
            {
                $("js-admin-password-result").innerHTML="<img src='images\/yes.gif'>";
                if (password==confirm_password)
                {
                    $("js-install-at-once").removeAttribute("disabled");
                    $("js-admin-confirmpassword-result").innerHTML="<img src='images\/yes.gif'>";
                }
                else
                {
                    $("js-install-at-once").setAttribute("disabled", "true");
                    if (confirm_password!='')
                    {
                    $("js-admin-confirmpassword-result").innerHTML="<span class='comment'><img src='images\/no.gif'>"+$_LANG["password_not_eq"]+"<\/span>";
                    }
                }
            }
        }
    f["js-admin-password2"].onblur = function  () {
        var password = f['js-admin-password'].value;
        var confirm_password = f['js-admin-password2'].value;
        if (!(confirm_password.length >= 8 && /\d+/.test(confirm_password) && /[a-zA-Z]+/.test(confirm_password) && password==confirm_password))
        {
          $("js-install-at-once").setAttribute("disabled", "true");
            
          if (!(confirm_password.length >= 8)){
                    $("js-admin-confirmpassword-result").innerHTML="<span class='comment'><img src='images\/no.gif'>"+$_LANG["password_short"]+"<\/span>";
          }
          else
          {
                    if (password==confirm_password){
                        $("js-admin-confirmpassword-result").innerHTML="<span class='comment'><img src='images\/no.gif'>"+$_LANG["password_invaild"]+"<\/span>";
                    }
                    else
                    {
                        $("js-admin-confirmpassword-result").innerHTML="<span class='comment'><img src='images\/no.gif'>"+$_LANG["password_not_eq"]+"<\/span>";
                    }
          }
        }
        else
        {
            $("js-install-at-once").removeAttribute("disabled");
            $("js-admin-confirmpassword-result").innerHTML="<img src='images\/yes.gif'>";
        }
    }
    f["js-admin-password"].onkeyup = function () {
      var pwd = f['js-admin-password'].value;
      var Mcolor = "#FFF",Lcolor = "#FFF",Hcolor = "#FFF";
      var m=0;

      var Modes = 0;
      for (i=0; i<pwd.length; i++)
      {
        var charType = 0;
        var t = pwd.charCodeAt(i);
        if (t>=48 && t <=57)
        {
          charType = 1;
        }
        else if (t>=65 && t <=90)
        {
          charType = 2;
        }
        else if (t>=97 && t <=122)
          charType = 4;
        else
          charType = 4;
        Modes |= charType;
      }

      for (i=0;i<4;i++)
      {
        if (Modes & 1) m++;
          Modes>>>=1;
      }

      if (pwd.length<=4)
      {
        m = 1;
      }

      switch(m)
      {
        case 1 :
          Lcolor = "2px solid red";
          Mcolor = Hcolor = "2px solid #DADADA";
        break;
        case 2 :
          Mcolor = "2px solid #f90";
          Lcolor = Hcolor = "2px solid #DADADA";
        break;
        case 3 :
          Hcolor = "2px solid #3c0";
          Lcolor = Mcolor = "2px solid #DADADA";
        break;
        case 4 :
          Hcolor = "2px solid #3c0";
          Lcolor = Mcolor = "2px solid #DADADA";
        break;
        default :
          Hcolor = Mcolor = Lcolor = "";
        break;
      }
      if (document.getElementById("pwd_lower"))
      {
        document.getElementById("pwd_lower").style.borderBottom  = Lcolor;
        document.getElementById("pwd_middle").style.borderBottom = Mcolor;
        document.getElementById("pwd_high").style.borderBottom   = Hcolor;
      }


    }
    f["js-go"].onclick = displayDbList;

    f["js-monitor-close"].onclick = function () {
        $("js-monitor").style.display = "none";
        unlockSpecInputs();
    };

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
//alert(1);
    //iframe = frames['js-monitor-notice'];
    notice = $("js-notice");
    var d = new Draggable();
    d.bindDragNode("js-monitor", "js-monitor-title");

    $("js-system-lang-" + getAddressLang()).setAttribute("checked", "checked");

    $("js-pre-step").onclick = function () {
        location.href = "./index.php?lang=" + getAddressLang() + "&step=check";
    };

    f["js-install-demo"].onclick = switchInputsStatus;
  }
}
</script>
</body>
</html>
