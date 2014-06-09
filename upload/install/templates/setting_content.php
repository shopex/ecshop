<form id="js-setting">

<table border="0" cellpadding="0" cellspacing="0" style="margin:0 auto;">
<tr>
<td valign="top">
<div id="wrapper">

  <h3><?php echo $lang['db_account'];?></h3>

<table width="450" class="list">
<tr>
    <td width="90" align="left"><?php echo $lang['db_host'];?></td>
    <td align="left"><input type="text" name="js-db-host"  value="localhost" /></td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['db_port'];?></td>
    <td align="left"><input type="text" name="js-db-port"  value="3306" /></td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['db_user'];?></td>
    <td align="left"><input type="text" name="js-db-user"  value="root" /></td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['db_pass'];?></td>
    <td align="left"><input type="password" name="js-db-pass"  value="" /></td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['db_name'];?></td>
    <td align="left"><input type="text" name="js-db-name"  value="" />
        <select name="js-db-list">
            <option><?php echo $lang['db_list'];?></option>
        </select>
        <input type="button" name="js-go" class="button" value="<?php echo $lang['go'];?>" />
   </td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['db_prefix'];?></td>
    <td align="left"><input type="text" name="js-db-prefix"  value="ecs_" /><span class="comment">&nbsp; (<?php echo $lang['change_prefix'];?>)</span></td>
</tr>
</table>
<div id="js-monitor" style="display:none;text-align:left;position:absolute;top:45%;left:35%;width:300px;z-index:1000;border:1px solid #000;">
    <h3 id="js-monitor-title"><?php echo $lang['monitor_title'];?></h3>
    <div style="background:#fff;padding-bottom:20px;">
        <img id="js-monitor-loading" src='images/loading.gif' /><br /><br />
        <strong id="js-monitor-wait-please" style='color:blue;width:65%;float:left;margin-left:3px;'></strong>
        <span id="js-monitor-view-detail" style="color:gray;cursor:pointer;;float:right;margin-right:3px;"></span>
    </div>
    <div id="js-monitor-notice" name="js-monitor-notice" style="display:none;">
        <div id="js-notice" style="background:#fff;margin:0px;padding:5px 0 0 3px;height:100%;font:12px  Arial, Helvetica, sans-serif;line-height:130%; border-color: #BBDDE5 -moz-use-text-color #BBDDE5 #BBDDE5;border-style: dashed;border-width: 1px 0 0 0;"></div>
        <a id="js-bottom"></a>
    </div>
    <img id="js-monitor-close" src='./images/close.gif' style="position:absolute;top:10px;right:10px;cursor:pointer;" />
</div>
<h3><?php echo $lang['admin_account'];?></h3>
<table width="450" class="list">
<tr>
    <td width="90" align="left"><?php echo $lang['admin_name'];?></td>
    <td align="left"><input type="text" name="js-admin-name"  value="" /></td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['admin_password'];?></td>
    <td align="left"><input type="password" name="js-admin-password"  value="" /><span id="js-admin-password-result"></span></td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['password_intensity'];?></td>
    <td align="left"><table width="132" cellspacing="0" cellpadding="1" border="0">
              <tbody><tr align="center">
                <td width="33%" id="pwd_lower" style="border-bottom: 2px solid red;"><?php echo $lang['pwd_lower'];?></td>
                <td width="33%" id="pwd_middle" style="border-bottom: 2px solid rgb(218, 218, 218);"><?php echo $lang['pwd_middle'];?></td>
                <td width="33%" id="pwd_high" style="border-bottom: 2px solid rgb(218, 218, 218);"><?php echo $lang['pwd_high'];?></td>
              </tr>
            </tbody></table></td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['admin_password2'];?></td>
    <td align="left"><input type="password" name="js-admin-password2"  value="" /><span id="js-admin-confirmpassword-result"></span></td>
</tr>
<tr>
    <td width="90" align="left"><?php echo $lang['admin_email'];?></td>
    <td align="left"><input type="text" name="js-admin-email"  value="" /></td>
</tr>
</table>

<h3><?php echo $lang['mix_options'];?></h3>
<table width="450" class="list">
<tr>
    <?php if (EC_CHARSET == 'gbk'){ ?>
    <td width="90" align="left"><?php echo $lang['select_lang_package'];?></td>
    <td align="left"><input type="radio" class="p" name="js-system-lang" id="js-system-lang-zh_cn" value="zh_cn" checked='true'/><label for="js-system-lang-zh_cn"><?php echo $lang['simplified_chinese'];?></label></td>
    <?php } elseif (EC_CHARSET == 'utf-8') { ?>
    <td width="90" align="left"><?php echo $lang['select_lang_package'];?></td>
    <td align="left"><input type="radio" class="p" name="js-system-lang" id="js-system-lang-zh_cn" value="zh_cn" /><label for="js-system-lang-zh_cn"><?php echo $lang['simplified_chinese'];?></label>
    <input type="radio" name="js-system-lang" id="js-system-lang-zh_tw" value="zh_tw" /><label for="js-system-lang-zh_tw"><?php echo $lang['traditional_chinese'];?></label>
        <input type="radio" name="js-system-lang" id="js-system-lang-en_us" value="en_us" /><label for="js-system-lang-en_us"><?php echo $lang['americanese'];?></label>
        </td>
    <?php } elseif (EC_CHARSET == 'big5') { ?>
    <td width="90" align="left"><?php echo $lang['select_lang_package'];?></td>
    <td align="left"><input type="radio" name="js-system-lang" id="js-system-lang-zh_tw" value="zh_tw" checked='true'/><label for="js-system-lang-zh_tw"><?php echo $lang['traditional_chinese'];?></label></td>
    <?php } ?>
</tr>
<?php if ($show_timezone == "yes"):?>
<tr>
    <td width="90" align="left"><?php echo $lang['set_timezone'];?></td>
    <td align="left">
     <select name="js-timezones">
<?php foreach($timezones as $key => $item): ?>
            <option value="<?php echo $key;?>" <?php if ($key == $local_timezone):?>selected="true"<?php $found = true;endif;?>><?php echo $item;?></option>
<?php endforeach; ?>
<?php if (!$found) :?>
            <option value="<?php echo $local_timezone;?>" selected="true"><?php echo $local_timezone;?></option>
<?php endif;?>
   </select>
    </td>
</tr>
<?php endif;?>
<tr>
    <td width="90" align="left"><?php echo $lang['disable_captcha'];?></td>
    <td align="left"><input type="checkbox" class="p" name="js-disable-captcha" <?php echo $checked . $disabled;?> /> <span class="comment"> (<?php echo $lang['captcha_notice'];?>)</span></td>
</tr>
<tr>
    <td width="90" align="left" valign="top"><?php echo $lang['pre_goods_types'];?></td>
    <td align="left" >
<?php $i=0; foreach($goods_types as $key => $item): ?>
    <input type="checkbox" class="p" name="js-goods-type[]" value="<?php echo $key;?>" checked="true" /> <?php echo $item;?>
    <?php if (++$i == 5):?><br /><?php endif;?>
<?php endforeach; ?>
    </td>
</tr>

<tr <?php
if(!file_exists(ROOT_PATH .'demo'))
{
?>
style="display:none"
<?php
}
?>
>

    <td width="90" align="left"><?php echo $lang['install_demo'];?></td>
    <td align="left"><input type="checkbox" class="p" name="js-install-demo" /> <span class="comment">(<?php echo $lang['demo_notice'];?>)</span></td>
</tr>

<tr>
    <td align="center" colspan="2">
     </td>
</tr>
</table>

</div>
</td>
<td width="227" valign="top" background="images/install-step3-<?php echo $installer_lang;?>.gif">&nbsp;</td>
</tr>
<tr>
  <td><div id="install-btn"><input type="button" id="js-pre-step" class="button" value="<?php echo $lang['prev_step'] . $lang['check_system_environment'];?>" /> <input id="js-install-at-once" type="submit" class="button" value="<?php echo $lang['install_at_once'];?>" /></div>
  </td><td></td>
</tr>
</table>
<input name="userinterface" type="hidden" value="<?php echo $userinterface; ?>" />
<input name="ucapi" type="hidden" value="<?php echo $ucapi; ?>" />
<input name="ucfounderpw" type="hidden" value="<?php echo $ucfounderpw; ?>" />
</form>
