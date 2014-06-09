<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $ec_charset; ?>" />
<title><?php echo $lang['checking_title'];?></title>
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/transport.js" charset="<?php echo EC_CHARSET ?>"></script>
<script type="text/javascript" src="js/common.js"></script>
<script type="text/javascript" src="js/draggable.js"></script>
<script type="text/javascript" src="js/check.js"></script>
<script type="text/javascript">
var $_LANG = {};
<?php foreach($lang['js_languages'] as $key => $item): ?>
$_LANG["<?php echo $key;?>"] = "<?php echo $item;?>";
<?php endforeach; ?>
</script>
</head>
<body id="checking">
<?php include ROOT_PATH . 'demo/templates/header.php';?>
<form method="post">
<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
<tr>
<td valign="top"><div id="wrapper">
        <h3><?php echo $lang['basic_config'];?></h3>
        <div class="list"> <?php foreach($config_info as $config_item): ?>
          <?php echo $config_item[0];?>...........................................................................................
                <span style="color:green;"><?php echo $config_item[1];?></span><br />
         <?php endforeach;?>
        </div>
        <h3><?php echo $lang['dir_priv_checking'];?></h3>
        <div class="list"> <?php foreach($dir_checking as $checking_item): ?>
          <?php echo $checking_item[0];?>...........................................................................................
              <?php if ($checking_item[1] == $lang['can_write']):?>
                    <span style="color:green;"><?php echo $checking_item[1];?></span>
             <?php else:?>
                <span style="color:red;"><?php echo $checking_item[1];?></span>
              <?php endif;?><br />
         <?php endforeach;?>
        </div>

        <h3><?php echo $lang['template_writable_checking'];?></h3>
        <div class="list">
         <?php if ($has_unwritable_tpl == "yes"):?>
              <?php foreach($template_checking as $checking_item): ?>
                            <span style="color:red;"><?php echo $checking_item;?></span><br />
              <?php endforeach; ?>
          <?php else:?>
              <span style="color:green"><?php echo $template_checking;?></span>
          <?php endif;?></div>
        <?php if (!empty($rename_priv)) :?>
        <h3><?php echo $lang['rename_priv_checking']; ?></h3>
        <div class="list">
          <?php foreach($rename_priv as $checking_item): ?>
          <span style="color:red;"><?php echo $checking_item;?></span><br />
          <?php endforeach; ?>
        </div>
        <?php endif;?>
</div></td>
<td>
<div id="js-monitor" style="display:none;text-align:left;position:absolute;top:45%;left:35%;width:300px;z-index:1000;border:1px solid #000;">
    <h3 id="js-monitor-title"><?php echo $lang['monitor_title'];?></h3>
    <div style="background:#fff;padding-bottom:20px;">
        <img id="js-monitor-loading" src='images/loading.gif' /><br /><br />
        <strong id="js-monitor-wait-please" style='color:blue;float:left;margin-left:3px;'></strong>
        <strong id="js-monitor-rollback" style="color:red;cursor:pointer;float:left;margin-left:25px;"></strong>
        <span id="js-monitor-view-detail" style="color:gray;cursor:pointer;float:right;margin-right:3px;"></span>
    </div>
    <iframe id="js-monitor-notice" src="templates/notice.htm" style="display:none;"></iframe>
    <img id="js-monitor-close" src='./images/close.gif' style="position:absolute;top:10px;right:10px;cursor:pointer;" />
</div>
</td>
</tr>
<tr>
  <td>
      <span id="install-btn"><input type="button" class="button" value="<?php echo $lang['prev_step'];?><?php echo $lang['readme_page'];?>"  onclick="location.href='index.php'" />
      <input type="button" class="button" value="<?php echo $lang['recheck'];?>" onclick="location.href='index.php?step=check'" />
      <input type="button" id="js-submit"  class="button" value="<?php echo $lang['update_now'];?>" <?php echo $disabled;?>  /></span>
  </td>
  <td></td>
</tr>
</table>
<div id="copyright">
    <div id="copyright-inside">
      <?php include ROOT_PATH . 'demo/templates/copyright.php';?></div>
</div>
</form>
</body>
</html>
