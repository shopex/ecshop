<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $ec_charset; ?>" />
<title><?php echo $lang['users_importto_ucenter'];?></title>
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
<?php include ROOT_PATH . 'upgrade/templates/header.php';?>
<form id="js-setup" method="post" onsubmit="return setupUCenter()">
<?php if ($not_match) { ?>
<table border="0" cellpadding="0" cellspacing="0" class="uc_table">
<tr>
<td><?php echo $lang['ucenter_not_match'];?></td>
</tr>
</table>
<?php } else { ?>
<?php if ($noucdb) { ?>
<table border="0" cellpadding="0" cellspacing="0" class="uc_table">
<tr>
<td><?php echo $lang['ucenter_no_database'];?></td>
</tr>
</table>
<?php } else { ?>
<table border="0" cellpadding="0" cellspacing="0" class="uc_table">
<tr>
<td valign="top">
<div id="wrapper">
  <h3><?php echo $lang['users_importto_ucenter'];?></h3>

<table width="550" class="list">
<tr>
    <td colspan="2"><?php printf($lang['user_startid_intro'], $maxuid, $maxuid); ?></td>
</tr>
<tr>
    <td width="125" align="left"><?php echo $lang['user_merge_method']; ?></td>
    <td align="left"><input type="radio" name="js-merge" id="js-merge-1" value="1" checked="true" /><label for="js-merge-1"><?php echo $lang['user_merge_method_1']; ?></label><br /><input name="js-merge" type="radio" value="2" id="js-merge-2" /><label for="js-merge-2"><?php echo $lang['user_merge_method_2']; ?></label>
      <span id="notice" style="color:#FF0000"></span></td>
</tr>
<tr><td>&nbsp;</td><td></td></tr>
</table>


  </div></td>
<td width="227" valign="top" background="images/install-step3-<?php echo $installer_lang;?>.gif">&nbsp;</td>
</tr>
<tr>
  <td>
  <div id="install-btn">
  <input type="button" class="button" id="js-pre-step" class="button" value="<?php echo $lang['prev_step'];?>" />
  <input id="js-submit-uc" type="button" class="button" value="<?php echo $lang['next_step'];?><?php echo $lang['ucenter_import_members']; ?>" />
</div>
  </td><td></td>
</tr>
</table>
<?php } ?>
<?php } ?>
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
<div id="copyright">
    <div id="copyright-inside">

      <?php include ROOT_PATH . 'install/templates/copyright.php';?></div>
</div>
</form>
</body>
</html>