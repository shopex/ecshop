<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $lang['checking_title'];?></title>
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/transport.js"></script>
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
Ajax.call('cloud.php?step=check','', check_api, 'GET', 'TEXT','FLASE');
function check_api(result)
{
  if(result)
  {
    setInnerHTML('content',result);
    setInputCheckedStatus();

    $("js-pre-step").onclick = function() {
        location.href="./index.php?lang=" + getAddressLang() + "&step=welcome";
    };
    $("js-recheck").onclick = function () {
        location.href="./index.php?lang=" + getAddressLang() + "&step=check";
    };
    $("js-submit").onclick = function () {
        this.form.action="index.php?lang=" + getAddressLang() + "&step=setting_ui" + "&ui=" + $('userinterface').value;
    };
  }
}
</script>
</body>
</html>