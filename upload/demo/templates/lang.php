<html>
<head>
<title> <?php echo $lang['select_language_title'];?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $ec_charset; ?>" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#wrapper { background: #F4FAFB; padding: 10px; border: 1px solid #BBDDE5; margin: 20px 0 20px; width: 95%;}
#wrapper fieldset{border:1px solid #BBDDE5;padding:0.5em;}
#wrapper fieldset legend{font-weight:bold;}
#wrapper fieldset select{width:90%;}
</style>
</head>

<body>
<?php include ROOT_PATH . 'demo/templates/header.php';?>

<div id="wrapper" style="text-align:left;">
<form target="_parent" action="" method="post">
<fieldset>
    <legend dir="ltr"><?php echo $lang['lang_title']; ?></legend>
    <select dir="ltr" onchange="this.form.submit();" name="lang" style="width:300px;">
    <?php
        foreach($lang['lang_charset'] as $key => $val) {
            if ($updater_lang.'_'.$ec_charset == $key) {
                $lang_selected = 'selected="selected" ';
            } else {
                $lang_selected = '';
            }
    ?>
        <option <?php echo $lang_selected; ?>value="<?php echo $key; ?>"><?php echo $val; ?></option>
    <?php
        }
    ?>
    </select>
</fieldset>
</form>
<form target="_parent" action="" method="post">
<fieldset>
    <legend dir="ltr"><?php echo $lang['ui_title']; ?></legend>
    <input type="radio" id="ui_1" name="ui" value="ecshop" checked="checked" /><label for="ui_1"><?php echo $lang['ui_ecshop']; ?></label>
    <!--<input type="radio" id="ui_2" name="ui" value="ucenter" /><label for="ui_2"><?php echo $lang['ui_ucenter']; ?></label>-->
</fieldset>
<fieldset>
    <legend><?php echo $lang['lang_description']; ?></legend>
    <ul>
        <?php
        foreach($lang['lang_desc'] as $desc) {
        ?>
        <li><?php echo $desc; ?></li>
        <?php
        }
        ?>
    </ul>
    <input type="hidden" name="step" value="readme" />
    <input type="hidden" name="lang" value="<?php echo $updater_lang.'_'.$ec_charset; ?>" />
    <input type="submit" class="button" value="<?php echo $lang['next_step'];?><?php echo $lang['readme_page'];?>" />
    <!--<input type="button" class="button btn-1" value="<?php echo $lang['goto_charset_convert'];?>" onclick="top.location='convert.php'" />
    <input type="button" class="button btn-1" value="<?php echo $lang['goto_members_import'];?>" onclick="top.location='ucimport.php'" />-->
</fieldset>
</form>

</div>

<?php include ROOT_PATH . 'demo/templates/copyright.php';?>
</body>
</html>
