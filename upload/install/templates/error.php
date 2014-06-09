<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $lang['install_error_title'];?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
</head>

<body style="background:#DDEEF2">
<div id="logos">
  <div id="logos-inside"> <img src="../admin/images/ecshop_logo.gif" alt="ECSHOP" width="160" height="57" /> </div>
</div>

<div style="margin:10px;padding:20px;border: 1px solid #BBDDE5; background: #F4FAFB; ">
  <div style="font-size: 14px; text-align: center">
    <?php echo $err_msg;?>
  </div>
</div>
<div style="padding: 1em; border: 1px solid #BBDDE5; background: #F4FAFB; margin-top: 10px; text-align: center;">
  <?php include ROOT_PATH . 'install/templates/copyright.php';?>
</div>
</body>
</html>