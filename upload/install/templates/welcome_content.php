<script type="text/javascript" src="./js/welcome.js"></script>
<form method="post">
<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
<tr>
<td valign="top"><div id="wrapper" style="padding:30px 0;">
  <iframe id="iframe" src="templates/license_<?php echo $installer_lang;?>.htm" width="730" height="350"></iframe>
</div></td>
<td width="227" valign="top" background="images/install-step1-<?php echo $installer_lang;?>.gif">&nbsp;</td>
</tr>
<tr>
<td align="center" style="padding-top:10px;"><input type="checkbox" id="js-agree" class="p" />
  <label for="js-agree"> <?php echo $lang['agree_license'];?></label><br />
  <span id="install-btn"><input class="button" type="submit" id="js-submit" class="p" value="<?php echo $lang['next_step'];?><?php echo $lang['setup_environment'];?>" /></span>
</td>
<td>&nbsp;</td>
</tr>
</table>
<input name="ucapi" type="hidden" value="<?php echo $ucapi; ?>" />
<input name="ucfounderpw" type="hidden" value="<?php echo $ucfounderpw; ?>" />
</form>