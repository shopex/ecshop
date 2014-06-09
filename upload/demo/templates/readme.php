<html>
<head>
<title> <?php echo $lang['readme_title'];?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $ec_charset; ?>" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#logos { background: #278296; border-bottom: 1px solid #FFF; }
#submenu-div { background: #80BDCB; height: 24px; border-bottom: 1px solid #FFF; }
#wrapper { background: #F4FAFB; padding: 10px; border: 1px solid #BBDDE5; margin-top: 20px; width: 95%;}
</style>
</head>

<body>
<?php include ROOT_PATH . 'demo/templates/header.php';?>

<div id="wrapper" style="text-align:left;">

<h3><?php echo $lang['method'];?></h3>
<p><?php printf($lang['notice'], $new_version);?></p>
<ol>
    <li><?php echo $lang['usage1'];?></li>
    <li><?php echo $lang['usage2'];?></li>
    <li><?php printf($lang['usage3'], $old_version);?></li>
<!--    <li><?php printf($lang['usage4'], $new_version);?></li>
    <li><?php echo $lang['usage5'];?></li>
    <li><?php echo $lang['usage6'];?></li>-->
</ol>
<!--
<h3><?php echo $lang['faq'];?></h3>
<iframe src="templates/faq_<?php echo $updater_lang;?>_<?php echo $ec_charset;?>.htm" width="730" height="350"></iframe>-->
<div align="center">
<input type="submit" id="js-submit" class="button" value="<?php echo $lang['next_step'];?><?php echo $lang['check_system_environment'];?>" onclick="top.location='index.php?step=check&ui=<?php echo $ui;?>'" />

</div>
</div>
<?php include ROOT_PATH . 'demo/templates/copyright.php';?>
</body>
</html>
