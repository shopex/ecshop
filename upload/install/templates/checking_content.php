<script type="text/javascript" src="js/check.js"></script>
<form method="post">
<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
<tr>
<td valign="top"><div id="wrapper">
  <h3><?php echo $lang['system_environment'];?></h3>
        <div class="list"> <?php foreach($system_info as $info_item): ?>
        <?php echo $info_item[0];?>..........................................................................................................................<?php echo $info_item[1];?><br />
          <?php endforeach;?> </div>
        <h3><?php echo $lang['dir_priv_checking'];?></h3>
        <div class="list"> <?php foreach($dir_checking as $checking_item): ?>
          <?php echo $checking_item[0];?>.......................................................................................................................
              <?php if ($checking_item[1] == $lang['can_write']):?>
                    <span style="color:green;"><?php echo $checking_item[1];?></span>
             <?php else:?>
                <span style="color:red;"><?php echo $checking_item[1];?></span>
              <?php endif;?><br />
         <?php endforeach;?></div>
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
<td width="227" valign="top" style="background:url(images/install-bg.gif) repeat-y;"><img src="images/install-step2-<?php echo $installer_lang;?>.gif" alt="" /></td>
</tr>
<tr>
  <td><div id="install-btn"><input type="button" class="button" id="js-pre-step" class="button" value="<?php echo $lang['prev_step'];?><?php echo $lang['welcome_page'];?>"  />
      <input type="button" class="button" id="js-recheck" class="button" value="<?php echo $lang['recheck'];?>"  />
      <input type="submit" class="button" id="js-submit"  class="button" value="<?php echo $lang['next_step'] . $lang['config_system'];?>" <?php echo $disabled;?> /></div>
  </td>
  <td></td>
</tr>
</table>
<input name="userinterface" id="userinterface" type="hidden" value="<?php echo $userinterface; ?>" />
<input name="ucapi" type="hidden" value="<?php echo $ucapi; ?>" />
<input name="ucfounderpw" type="hidden" value="<?php echo $ucfounderpw; ?>" />
</form>