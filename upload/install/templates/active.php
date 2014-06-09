<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $lang['install_done_title'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/transport.js"></script>
</head>

<body style="background:#DDEEF2">
<div id="logos">
  <div id="logos-inside"> <img src="../admin/images/ecshop_logo.gif" alt="ECSHOP" width="160" height="57" /> </div>
</div>
<div id="content">
<p style="font-size:30px;text-align: center;margin-top:50px;">
<?php echo $lang['loading'];?>
</p>
<img id="js-monitor-loading" src='images/loading.gif' style="margin:30px 0 50px 0;"/>
</div>
<div style="padding: 1em; border: 1px solid #BBDDE5; background: #F4FAFB; margin-top: 10px; text-align: center;">
<?php include ROOT_PATH . 'install/templates/copyright.php';?>
</div>
<script type="text/javascript">
Ajax.call('cloud.php?step=active','', active_api, 'GET', 'TEXT','FLASE');
function active_api(result)
{
  if(result)
  {
      setInnerHTML('content',result);
  }
}
</script>
</body>
</html>