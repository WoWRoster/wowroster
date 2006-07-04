<?php
/*******************************
 * $Id$
 *******************************/

	// Get the current settings
	$configData = $checkData;

	// Get the current memberlist
	$member_list = $functions->getDbList( (ROSTER_MEMBERSTABLE),'`name`',"`guild_id` = '$guild_id'" );

	// Get the background files
	$backgFilesArr = $functions->listFiles( $sigconfig_dir.$configData['image_dir'].$configData['backg_dir'],'png' );

	// Get the font files
	$fontFilesArr = $functions->listFiles( ROSTER_BASE.$configData['font_dir'],'ttf' );

	// Get regular image files
	$imageFilesArr = $functions->listFiles( $sigconfig_dir.$configData['image_dir'],'png' );

	// Get list of columns for background selection
	$table_name = ( $configData['backg_data_table'] == 'members' ? (ROSTER_MEMBERSTABLE) : (ROSTER_PLAYERSTABLE) );
	$backgColumsArr = $functions->getDbColumns( $table_name );

	// Get a list of colors
	$colorArr = array();
	for($n=1;$n<=10;$n++)
	{
		$name = "[$n] ".$configData['color'.$n];
		$value = 'color'.$n;
		$colorArr += array( $name => $value );
	}

	// Get a list of fonts
	$fontArr = array();
	for($n=1;$n<=8;$n++)
	{
		$name = "[$n] ".$configData['font'.$n];
		$value = 'font'.$n;
		$fontArr += array( $name => $value );
	}

	// Make alignment array
	$alignArr = array('Left' => 'left','Center' => 'center','Right' => 'right');
	// Make image mode array
	$imgTypeArr = array('png' => 'png','jpeg' => 'jpg','gif' => 'gif');

?>

<!-- Begin Main Body -->
<form action="<?php print $script_filename; ?>" method="post" enctype="multipart/form-data" id="config" onsubmit="submitonce(this)">
	<input type="hidden" name="sc_op" value="process" />
  <input type="submit" value="Save Settings" />
  <input type="reset" name="Reset" value="Reset" />
  <br />
  <br />

<!-- ===[ Begin Menu 1 : Main Settings ]=============================================== -->
<div id="t1" style="display:none">
<?php print border('sgray','start','Main Settings'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Size of the final generated image','Master Image Size' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Width: <input name="main_image_size_w" type="text" value="<?php print $configData['main_image_size_w']; ?>" size="5" maxlength="5" /><br />
        Height: <input name="main_image_size_h" type="text" value="<?php print $configData['main_image_size_h']; ?>" size="5" maxlength="5" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Must be a directory in &quot;'.$sigconfig_dir.'&quot;','Images directory' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="image_dir" type="text" value="<?php print $configData['image_dir']; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Sub directory of main image directory','Character images directory' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="char_dir" type="text" value="<?php print $configData['char_dir']; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Sub directory of main image directory','Class images directory' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="class_dir" type="text" value="<?php print $configData['class_dir']; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Sub directory of main image directory','Background images directory' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="backg_dir" type="text" value="<?php print $configData['backg_dir']; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Sub directory of main image directory','Member images directory' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="user_dir" type="text" value="<?php print $configData['user_dir']; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Sub directory of main image directory','PvP logo images directory' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="pvplogo_dir" type="text" value="<?php print $configData['pvplogo_dir']; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Realative to the &quot;/roster/&quot; directory','Fonts directory' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="font_dir" type="text" value="<?php print $configData['font_dir']; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td class="sc_row_right<?php echo (((++$row)%2)+1); ?>" align="left" colspan="2"><?php print $functions->createTip( 'These <u><strong>MUST</strong></u> be separated with a ( : )<br />Choices { back | frames | char | border | pvp | lvl | class }','Image layer order' ); ?>
        <input name="image_order" type="text" value="<?php print $configData['image_order']; ?>" size="50" maxlength="128" />
      </td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Allows a web-browser to cache the image<br />May not work for all browsers or web servers','eTag Cacheing' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="etag_cache" value="1" <?php print ( $configData['etag_cache'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="etag_cache" value="0" <?php print ( !$configData['etag_cache'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Image format of the generated image','Output Image format' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($imgTypeArr,$configData['image_type'],'image_type' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controls quality for saved or output jpeg images','Jpeg image quality' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="image_quality" type="text" value="<?php print $configData['image_quality']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controls dithering mode for saved or output gif images','Gif image dithering' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="gif_dither" value="1" <?php print ( $configData['gif_dither'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="gif_dither" value="0" <?php print ( !$configData['gif_dither'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
  </table>
<?php

print border('sgray','end');

print "<br />\n";

if( $allow_save )
{
	print border('syellow','start','Save Images');
?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Save generated images to a directory<br />Directory is specified below','Save images to server' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="save_images" value="1" <?php print ( $configData['save_images'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="save_images" value="0" <?php print ( !$configData['save_images'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Supresses image output when script is viewed<br />Only works when &quot;Save Images&quot; is on','Suppress Image Output' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="save_only_mode" value="1" <?php print ( $configData['save_only_mode'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="save_only_mode" value="0" <?php print ( !$configData['save_only_mode'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Specify a directory to save generated images to<br />Realative to &quot;'.$sigconfig_dir.'&quot;','Saved images directory' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="save_images_dir" type="text" value="<?php print $configData['save_images_dir']; ?>" size="20" maxlength="20" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Image format of saved images','Saved images format' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($imgTypeArr,$configData['save_images_format'],'save_images_format' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Text to place before/after the saved image file','Saved Images Prefix/Suffix' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Prefix: <input name="save_prefix" type="text" value="<?php print $configData['save_prefix']; ?>" size="8" maxlength="8" /><br />
        Suffix: <input name="save_suffix" type="text" value="<?php print $configData['save_suffix']; ?>" size="8" maxlength="8" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'This will generate an image and save it to the server disk for<br />each character when update.php is ran','Auto-save image on character update' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="trigger" value="1" <?php print ( $configData['trigger'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="trigger" value="0" <?php print ( !$configData['trigger'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'This will generate an image and save it to the server disk for<br />EVERY character in the guild when update.php is ran in &quot;Guild Update&quot; mode<br />You much check &quot;Run Update Triggers&quot; on update.php to activate this<br /><br />PLEASE READ THE WARNING IN THE DOCUMENTATION<br />If you do get time-out errors, then you should disable this','Auto-save images on guild update' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="guild_trigger" value="1" <?php print ( $configData['guild_trigger'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="guild_trigger" value="0" <?php print ( !$configData['guild_trigger'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
  </table>
<?php print border('syellow','end');

}
else
{
	print border('sred','start','Save Images');
?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Save generated images to a directory<br />Directory is specified below','Save images to server' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="save_images" value="1" <?php print ( $configData['save_images'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="save_images" value="0" <?php print ( !$configData['save_images'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <th class="sc_header_right" colspan="2" align="center"><?php print $functions->createTip( 'Either the directory doesn&acute;t exist or &quot;Save Images&quot; is turned off','Save Image Functions Disabled' ); ?>
        <input name="save_only_mode" type="hidden" value="0" />
        <input name="save_images_dir" type="hidden" value="<?php print $configData['save_images_dir']; ?>" />
        <input name="save_prefix" type="hidden" value="<?php print $configData['save_prefix']; ?>" />
        <input name="save_suffix" type="hidden" value="<?php print $configData['save_suffix']; ?>" />
        <input name="trigger" type="hidden" value="0" />
        <input name="guild_trigger" type="hidden" value="0" /></th>
    </tr>
  </table>
<?php print border('sred','end');

}
?>
</div>

<!-- ===[ Begin Menu 2 : Backgrounds ]================================================= -->
<div id="t2" style="display:none" >
<?php print border('sgray','start','Backgrounds'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Display an image for the background<br />Settings on how to configure what image is displayed are below','Display background image' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="backg_disp" value="1" <?php print ( $configData['backg_disp'] ? 'checked="checked"' : '' ); ?>/>
        Yes</label> <label>
        <input type="radio" class="checkBox" name="backg_disp" value="0" <?php print ( !$configData['backg_disp'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Turn on to &quot;color fill&quot; the background<br />when &quot;Display background image&quot; is turned off<br />Use &quot;Background fill color&quot; setting to choose the color','Color fill background' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="backg_fill" value="1" <?php print ( $configData['backg_fill'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="backg_fill" value="0" <?php print ( !$configData['backg_fill'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Select a color to fill the background with<br />Setting is for &quot;Color fill background&quot;','Background fill color' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($colorArr,$configData['backg_fill_color'],'backg_fill_color' ); ?> :
      	<?php print $functions->createColorTip($configData['backg_fill_color'],$configData); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Image to use for the outside border<br />Set to &quot;--None--&quot; to disable','Outside border image name' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionListName($imageFilesArr,$configData['outside_border_image'],'outside_border_image' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Image to use for the colored frames<br />Set to &quot;--None--&quot; to disable','Colored frames image name' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionListName($imageFilesArr,$configData['frames_image'],'frames_image' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Image to use when the &quot;Background Selection Configuration&quot;<br />cannot select an image','Default Background Image' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionListName($backgFilesArr,$configData['backg_default_image'],'backg_default_image' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Makes everyone&acute;s background the default<br /><br />Settings this to &quot;Yes&quot; will disable the &quot;Background Selection Configuration&quot; window','Default Background Override' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="backg_force_default" value="1" <?php print ( $configData['backg_force_default'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="backg_force_default" value="0" <?php print ( !$configData['backg_force_default'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
  </table>
<?php print border('sgray','end'); ?>
  <br />
<?php

if( !$configData['backg_force_default'] )
{
	print border('syellow','start','Background Selection Configuration');

?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <th class="sc_header_right" colspan="2" align="center"><?php print $functions->createTip( 'The top box is what to search for<br />The bottom box is what image to set when that search is found<br /><br />The top box must be exactly like in the database<br />The bottom box is automatically filled from the backgrounds directory','Background Selection Help' ); ?></th>
    </tr>
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Select what table/field to use when selecting backgrounds','Search Config' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Table:
        <select class="sc_select" name="backg_data_table">
          <option value="members" <?php print ( $configData['backg_data_table'] == 'members' ? 'selected="selected"' : '' ); ?>>Members Table</option>
          <option value="players" <?php print ( $configData['backg_data_table'] == 'players' ? 'selected="selected"' : '' ); ?>>Players Table</option>
        </select><br />
        Field: <?php print $functions->createOptionListValue($backgColumsArr,$configData['backg_data'],'backg_data' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'This uses &quot;'.str_replace('\\','/',$sigconfig_dir).'localization.php&quot; to translate<br /> the localized name to the english name','Translate &quot;Search Name&quot;' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="backg_translate" value="1" <?php print ( $configData['backg_translate'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="backg_translate" value="0" <?php print ( !$configData['backg_translate'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
<?php

for($c=1;$c<=12;$c++)
{
	print '
    <tr>
      <td class="sc_row'.(((++$row)%2)+1).'" align="left">Background '.$c.'</td>
      <td class="sc_row_right'.((($row)%2)+1).'" align="right">Search: <input name="backg_search_'.$c.'" type="text" value="'.$configData['backg_search_'.$c].'" size="25" maxlength="25" /><br />
        Image Name: '.$functions->createOptionListName($backgFilesArr,$configData['backg_file_'.$c],'backg_file_'.$c ).'</td>
    </tr>';
}
?>

  </table>
<?php print border('syellow','end');
}
else
{
	print border('sred','start','Background Selection Configuration DISABLED');
	print '<div class="sc_row_right1" align="center">This is disabled because &quot;Default Background Override&quot; is ON</div>';
	print border('sred','end');
}
?>
</div>

<!-- ===[ Begin Menu 3 : Font Settings ]=============================================== -->
<div id="t3" style="display:none" >
<table>
  <tr>
    <td valign="top"><?php print border('sgray','start','Fonts'); ?>
      <table width="170" class="sc_table" cellspacing="0" cellpadding="2">
        <tr>
          <th class="sc_header_right" align="center" colspan="2"><?php print $functions->createTip( 'Changes here will not be shown elsewhere<br />until the [Save Settings] button is pressed','Change SigGen Fonts' ); ?></th>
        </tr>
<?php
$row=1;
for($c=1;$c<=8;$c++)
{
	print '
        <tr>
          <td class="sc_row'.(((++$row)%2)+1).'" align="left">Font '.$c.'</td>
          <td class="sc_row_right'.((($row)%2)+1).'" align="right">'.$functions->createOptionList($fontFilesArr,$configData['font'.$c],'font'.$c ).'</td>
        </tr>';
}

?>

      </table><?php print border('sgray','end'); ?></td>

    <td valign="top"><?php print border('sgray','start','Colors'); ?>
      <table class="sc_table" cellspacing="0" cellpadding="2">
        <tr>
          <th class="sc_header_right" align="center" colspan="2"><?php print $functions->createTip( 'Colors are in hex format<br />( ex. black = 000000 : white = FFFFFF )<br /><br />You can also click <img src=&quot;	'.$roster_conf['roster_dir'].'/addons/siggen/inc/color/images/select_arrow.gif&quot;> to pick a color<br /><br />Changes here will not be shown elsewhere<br />until the [Save Settings] button is pressed','Change SigGen Colors' ); ?></th>
        </tr>
<?php
$row=1;
for($c=1;$c<=10;$c++)
{
	print '
        <tr>
          <td class="sc_row'.(((++$row)%2)+1).'" align="left">Color '.$c.'</td>
          <td class="sc_row_right'.((($row)%2)+1).'" align="right">
            <input type="text" maxlength="7" style="background-color:'.$configData['color'.$c].';" value="'.$configData['color'.$c].'" name="color'.$c.'" id="color'.$c.'" size="10"><img src="'.$roster_conf['roster_dir'].'/addons/siggen/inc/color/images/select_arrow.gif" style="cursor:pointer;vertical-align:middle;margin-bottom:2px;" onclick="showColorPicker(this,document.getElementById(\'color'.$c.'\'))"></td>
        </tr>
';
}

?>
      </table><?php print border('sgray','end'); ?></td>
  </tr>

</table>
</div>

<!-- ===[ Begin Menu 4 : eXp Bar Config ]============================================== -->
<div id="t4" style="display:none">
<?php print border('sgray','start','eXp Bar Config'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Select whether to display an eXp bar','Display eXp bar' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="expbar_disp" value="1" <?php print ( $configData['expbar_disp'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="expbar_disp" value="0" <?php print ( !$configData['expbar_disp'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Draw a border around the eXp bar<br />Color settings are below','Display border' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="expbar_disp_bdr" value="1" <?php print ( $configData['expbar_disp_bdr'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="expbar_disp_bdr" value="0" <?php print ( !$configData['expbar_disp_bdr'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Fill the entire eXp bar with a color<br />Color settings are below','Display inside color fill' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="expbar_disp_inside" value="1" <?php print ( $configData['expbar_disp_inside'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="expbar_disp_inside" value="0" <?php print ( !$configData['expbar_disp_inside'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Changes the eXp bar into a filled bar with custom text for &quot;Max Level&quot; characters','Display Max Level Bar' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="expbar_max_disp" value="1" <?php print ( $configData['expbar_max_disp'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="expbar_max_disp" value="0" <?php print ( !$configData['expbar_max_disp'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controls what level to use the &quot;Max Level Bar&quot;<br />This is usually the maximum level attainable in WoW','Max Level' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="expbar_max_level" type="text" value="<?php print $configData['expbar_max_level']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Hide the eXp bar when a character is at the &quot;Max Level&quot;','Hide Max eXp bar' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="expbar_max_hidden" value="1" <?php print ( $configData['expbar_max_hidden'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="expbar_max_hidden" value="0" <?php print ( !$configData['expbar_max_hidden'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'How big and where to draw the eXp bar on the main image','eXp bar placement and size' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="expbar_loc_x" type="text" value="<?php print $configData['expbar_loc_x']; ?>" size="3" maxlength="3" />
        Vert: <input name="expbar_loc_y" type="text" value="<?php print $configData['expbar_loc_y']; ?>" size="3" maxlength="3" /><br />
        Width: <input name="expbar_length" type="text" value="<?php print $configData['expbar_length']; ?>" size="3" maxlength="3" />
        Height: <input name="expbar_height" type="text" value="<?php print $configData['expbar_height']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Display the current eXp level on the exp bar','Display eXp level text' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="expbar_disp_text" value="1" <?php print ( $configData['expbar_disp_text'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="expbar_disp_text" value="0" <?php print ( !$configData['expbar_disp_text'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Choose what to write before the eXp Level text','Text before eXp level display' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="expbar_string_before" type="text" value="<?php print $configData['expbar_string_before']; ?>" size="30" maxlength="30" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Choose what to write after the eXp Level text','Text after eXp level display' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="expbar_string_after" type="text" value="<?php print $configData['expbar_string_after']; ?>" size="30" maxlength="30" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Choose what to write on the eXp bar when &quot;Display Max Level Bar&quot; is turned on','Text on the Max eXp bar' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="expbar_max_string" type="text" value="<?php print $configData['expbar_max_string']; ?>" size="30" maxlength="30" /></td>
    </tr>
  </table>
<?php print border('sgray','end'); ?>
  <br />
<?php print border('syellow','start','eXp Bar Colors'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Border Color</td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left">Inside fill color</td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left">Progress bar color</td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="left">Max eXp bar color</td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>"><?php print $functions->createOptionList($colorArr,$configData['expbar_color_border'],'expbar_color_border' ); ?> :
        <?php print $functions->createColorTip($configData['expbar_color_border'],$configData); ?></td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>"><?php print $functions->createOptionList($colorArr,$configData['expbar_color_inside'],'expbar_color_inside' ); ?> :
        <?php print $functions->createColorTip($configData['expbar_color_inside'],$configData); ?></td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>"><?php print $functions->createOptionList($colorArr,$configData['expbar_color_bar'],'expbar_color_bar' ); ?> :
        <?php print $functions->createColorTip($configData['expbar_color_bar'],$configData); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>"><?php print $functions->createOptionList($colorArr,$configData['expbar_color_maxbar'],'expbar_color_maxbar' ); ?> :
        <?php print $functions->createColorTip($configData['expbar_color_maxbar'],$configData); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Make the border transparent/translucent<br /><br />Accepted values are 0-127<br />0 = Opaque | 127 = Transparent','Transparency Value' ); ?>
        <input name="expbar_trans_border" type="text" value="<?php print $configData['expbar_trans_border']; ?>" size="3" maxlength="3" /></td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Make the inside fill transparent/translucent<br /><br />Accepted values are 0-127<br />0 = Opaque | 127 = Transparent','Transparency Value' ); ?>
        <input name="expbar_trans_inside" type="text" value="<?php print $configData['expbar_trans_inside']; ?>" size="3" maxlength="3" /></td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Make the progress bar transparent/translucent<br /><br />Accepted values are 0-127<br />0 = Opaque | 127 = Transparent','Transparency Value' ); ?>
        <input name="expbar_trans_bar" type="text" value="<?php print $configData['expbar_trans_bar']; ?>" size="3" maxlength="3" /></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Make the &quot;Max Level&quot; bar transparent/translucent<br /><br />Accepted values are 0-127<br />0 = Opaque | 127 = Transparent','Transparency Value' ); ?>
        <input name="expbar_trans_maxbar" type="text" value="<?php print $configData['expbar_trans_maxbar']; ?>" size="3" maxlength="3" /></td>
    </tr>
  </table>
<?php print border('syellow','end'); ?>
  <br />
<?php print border('syellow','start','eXp Bar Font Settings'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Font name</td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left">Font color</td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="left">Font size</td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>"><?php print $functions->createOptionList($fontArr,$configData['expbar_font_name'],'expbar_font_name' ); ?></td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>"><?php print $functions->createOptionList($colorArr,$configData['expbar_font_color'],'expbar_font_color' ); ?> :
        <?php print $functions->createColorTip($configData['expbar_font_color'],$configData); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="expbar_font_size" type="text" value="<?php print $configData['expbar_font_size']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right" colspan="2"><?php print $functions->createOptionList($colorArr,$configData['expbar_text_shadow'],'expbar_text_shadow' ); ?> :
      	<?php print $functions->createColorTip($configData['expbar_text_shadow'],$configData); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Text alignment<br />
      	<?php print $functions->createOptionList($alignArr,$configData['expbar_align'],'expbar_align' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right" colspan="2">Max eXp bar text alignment<br />
      	<?php print $functions->createOptionList($alignArr,$configData['expbar_align_max'],'expbar_align_max' ); ?></td>
    </tr>
  </table>
<?php print border('syellow','end'); ?>
</div>

<!-- ===[ Begin Menu 5 : Level Bubble ]================================================ -->
<div id="t5" style="display:none" >
<?php print border('sgray','start','Level Bubble Settings'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Display the character&acute;s current level','Level Bubble display' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="lvl_disp" value="1" <?php print ( $configData['lvl_disp'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="lvl_disp" value="0" <?php print ( !$configData['lvl_disp'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Image to use for the Level Bubble<br />You can select &quot;--None--&quot; to just display text','Level Bubble image name' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionListName($imageFilesArr,$configData['lvl_image'],'lvl_image' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Where to place the Level Bubble','Level Bubble image placement' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="lvl_loc_x" type="text" value="<?php print $configData['lvl_loc_x']; ?>" size="3" maxlength="3" /><br />
        Vert: <input name="lvl_loc_y" type="text" value="<?php print $configData['lvl_loc_y']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Where to place the level text<br />The starting point is based on the Level Bubble image placement','Level Bubble text placement' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="lvl_text_loc_x" type="text" value="<?php print $configData['lvl_text_loc_x']; ?>" size="3" maxlength="3" /><br />
        Vert: <input name="lvl_text_loc_y" type="text" value="<?php print $configData['lvl_text_loc_y']; ?>" size="3" maxlength="3" /></td>
    </tr>
  </table>
<?php print border('sgray','end'); ?>
  <br />
<?php print border('syellow','start','Level Bubble Font Settings'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Font name</td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left">Font color</td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left">Font size</td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>"><?php print $functions->createOptionList($fontArr,$configData['lvl_font_name'],'lvl_font_name' ); ?></td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>"><?php print $functions->createOptionList($colorArr,$configData['lvl_font_color'],'lvl_font_color' ); ?> :
        <?php print $functions->createColorTip($configData['lvl_font_color'],$configData); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="lvl_font_size" type="text" value="<?php print $configData['lvl_font_size']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right" colspan="2"><?php print $functions->createOptionList($colorArr,$configData['lvl_text_shadow'],'lvl_text_shadow' ); ?> :
      	<?php print $functions->createColorTip($configData['lvl_text_shadow'],$configData); ?></td>
    </tr>
  </table>
<?php print border('syellow','end'); ?>
</div>

<!-- ===[ Begin Menu 6 : Skills Display ]============================================== -->
<div id="t6" style="display:none" >
<?php print border('sgray','start','Skills Display'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controlls display of main professions<br />Ex. Leatherworking, Mining, Engineering, etc...','Display Professions' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="skills_disp_primary" value="1" <?php print ( $configData['skills_disp_primary'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="skills_disp_primary" value="0" <?php print ( !$configData['skills_disp_primary'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controlls display of secondary skills<br />Ex. Cooking, Fishing, etc...','Display Secondary Skills' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="skills_disp_secondary" value="1" <?php print ( $configData['skills_disp_secondary'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="skills_disp_secondary" value="0" <?php print ( !$configData['skills_disp_secondary'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controlls display of riding skill','Display Riding Skill' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="skills_disp_mount" value="1" <?php print ( $configData['skills_disp_mount'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="skills_disp_mount" value="0" <?php print ( !$configData['skills_disp_mount'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controlls display of skill description','Display Skill Description' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="skills_disp_desc" value="1" <?php print ( $configData['skills_disp_desc'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="skills_disp_desc" value="0" <?php print ( !$configData['skills_disp_desc'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controlls display of skill level','Display Skill Level' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="skills_disp_level" value="1" <?php print ( $configData['skills_disp_level'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="skills_disp_level" value="0" <?php print ( !$configData['skills_disp_level'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controlls display of maximum attainable skill level','Display Max Skill Level' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="skills_disp_levelmax" value="1" <?php print ( $configData['skills_disp_levelmax'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="skills_disp_levelmax" value="0" <?php print ( !$configData['skills_disp_levelmax'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Where to place the skills info','Skills Placment' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Description:<br />
      	Horiz: <input name="skills_desc_loc_x" type="text" value="<?php print $configData['skills_desc_loc_x']; ?>" size="3" maxlength="3" />
      	Vert: <input name="skills_desc_loc_y" type="text" value="<?php print $configData['skills_desc_loc_y']; ?>" size="3" maxlength="3" /><br />
        Skill Level:<br />
        Horiz: <input name="skills_level_loc_x" type="text" value="<?php print $configData['skills_level_loc_x']; ?>" size="3" maxlength="3" />
        Vert: <input name="skills_level_loc_y" type="text" value="<?php print $configData['skills_level_loc_y']; ?>" size="3" maxlength="3" /><br />
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Maximum text length for the skill description','Description Text Length' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="skills_desc_length" type="text" value="<?php print $configData['skills_desc_length']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Maximum text length for riding skill text<br />This can be longer because there is no skill level for riding','Riding Skill Text Length' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="skills_desc_length_mount" type="text" value="<?php print $configData['skills_desc_length_mount']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Text Alignment</td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Description: <?php print $functions->createOptionList($alignArr,$configData['skills_align_desc'],'skills_align_desc' ); ?><br />
        Skill Level: <?php print $functions->createOptionList($alignArr,$configData['skills_align_level'],'skills_align_level' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Gap between each line','Line spacing' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Description: <input name="skills_desc_linespace" type="text" value="<?php print $configData['skills_desc_linespace']; ?>" size="3" maxlength="3" /><br />
      	Level: <input name="skills_level_linespace" type="text" value="<?php print $configData['skills_level_linespace']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Controls how big of a space to put between skill groups','Skill Gap' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><input name="skills_gap" type="text" value="<?php print $configData['skills_gap']; ?>" size="3" maxlength="3" /></td>
    </tr>
  </table>
<?php print border('sgray','end'); ?>
  <br />
<?php print border('syellow','start','Skills Font Settings'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Font name</td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left">Font color</td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="left">Font size</td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>"><?php print $functions->createOptionList($fontArr,$configData['skills_font_name'],'skills_font_name' ); ?></td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>"><?php print $functions->createOptionList($colorArr,$configData['skills_font_color'],'skills_font_color' ); ?> :
        <?php print $functions->createColorTip($configData['skills_font_color'],$configData); ?></td>
      <td class="sc_row<?php echo ((($row)%2)+1); ?>" align="right"><input name="skills_font_size" type="text" value="<?php print $configData['skills_font_size']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right" colspan="2"><?php print $functions->createOptionList($colorArr,$configData['skills_shadow'],'skills_shadow' ); ?> :
      	<?php print $functions->createColorTip($configData['skills_shadow'],$configData); ?></td>
    </tr>
  </table>
<?php print border('syellow','end'); ?>
</div>

<!-- ===[ Begin Menu 7 : Character/PvP Logo ]========================================== -->
<div id="t7" style="display:none" >
<?php print border('sgray','start','Char/Class/PvP Logo'); ?>
  <table class="sc_table" cellspacing="0" cellpadding="2">
    <tr>
      <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Display the character portrait/image','Character Image display' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="charlogo_disp" value="1" <?php print ( $configData['charlogo_disp'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="charlogo_disp" value="0" <?php print ( !$configData['charlogo_disp'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Choose an image to use as the default character image','Default character image' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionListName($imageFilesArr,$configData['charlogo_default_image'],'charlogo_default_image' ); ?></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Where to place the character image','Character image placement' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="charlogo_loc_x" type="text" value="<?php print $configData['charlogo_loc_x']; ?>" size="3" maxlength="3" /><br />
        Vert: <input name="charlogo_loc_y" type="text" value="<?php print $configData['charlogo_loc_y']; ?>" size="3" maxlength="3" />
      </td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Display a class logo','Class Logo Display' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="class_img_disp" value="1" <?php print ( $configData['class_img_disp'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="class_img_disp" value="0" <?php print ( !$configData['class_img_disp'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Where to place the class logo','Class Logo Placement' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="class_img_loc_x" type="text" value="<?php print $configData['class_img_loc_x']; ?>" size="3" maxlength="3" /><br />
        Vert: <input name="class_img_loc_y" type="text" value="<?php print $configData['class_img_loc_y']; ?>" size="3" maxlength="3" /></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Display a PvP rank logo','PvP logo display' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
        <input type="radio" class="checkBox" name="pvplogo_disp" value="1" <?php print ( $configData['pvplogo_disp'] ? 'checked="checked"' : '' ); ?> />
        Yes</label> <label>
        <input type="radio" class="checkBox" name="pvplogo_disp" value="0" <?php print ( !$configData['pvplogo_disp'] ? 'checked="checked"' : '' ); ?> />
        No</label></td>
    </tr>
    <tr>
      <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left"><?php print $functions->createTip( 'Where to place the PvP rank logo','PvP logo image placement' ); ?></td>
      <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="pvplogo_loc_x" type="text" value="<?php print $configData['pvplogo_loc_x']; ?>" size="3" maxlength="3" /><br />
        Vert: <input name="pvplogo_loc_y" type="text" value="<?php print $configData['pvplogo_loc_y']; ?>" size="3" maxlength="3" /></td>
    </tr>
  </table>
<?php print border('sgray','end'); ?>
</div>

<!-- ===[ Begin Menu 8 : Text Config ]================================================= -->
<div id="t8" style="display:none">
<?php print border('sgray','start','Text Configuration'); ?>
  <table class="sc_table" cellpadding="2">
    <tr>

      <td valign="top"><!-- ===[ Begin Text Config 1 ]=== -->
        <div id="textT1Col" style="display:inline">
<?php print border('syellow','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT1Col','textT1')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />Name</div>"); ?>
<?php print border('syellow','end'); ?>
        </div>
        <div id="textT1" style="display:none">
<?php print border('sgreen','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT1Col','textT1')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />Name</div>"); ?>
        <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
          <tr>
            <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Display Name</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_name_disp" value="1" <?php print ( $configData['text_name_disp'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_name_disp" value="0" <?php print ( !$configData['text_name_disp'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Name placement</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="text_name_loc_x" type="text" value="<?php print $configData['text_name_loc_x']; ?>" size="3" maxlength="3" /><br />
              Vert: <input name="text_name_loc_y" type="text" value="<?php print $configData['text_name_loc_y']; ?>" size="3" maxlength="3" /></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Name alignment</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($alignArr,$configData['text_name_align'],'text_name_align' ); ?></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="center" colspan="2">
              <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
                <tr>
                  <th colspan="2" class="membersHeaderRight">Font settings</th>
                </tr>
                <tr>
                  <td class="sc_row1" align="left">Font name:<br />
                  	<?php print $functions->createOptionList($fontArr,$configData['text_name_font_name'],'text_name_font_name' ); ?></td>
                  <td class="sc_row_right1" align="right">Font size:<br />
                  	<input name="text_name_font_size" type="text" value="<?php print $configData['text_name_font_size']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr align="left">
                  <td class="sc_row_right2" colspan="2">Font color:<br />
                  	<?php print $functions->createOptionList($colorArr,$configData['text_name_font_color'],'text_name_font_color' ); ?> :
                    <?php print $functions->createColorTip($configData['text_name_font_color'],$configData); ?></td>
                </tr>
                <tr align="left">
                  <td class="sc_row_right1" colspan="2"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?>
                    <?php print $functions->createOptionList($colorArr,$configData['text_name_shadow'],'text_name_shadow' ); ?> :
                    <?php print $functions->createColorTip($configData['text_name_shadow'],$configData); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
<?php print border('sgreen','end'); ?>
        </div></td>

      <td valign="top"><!-- ===[ Begin Text Config 2 ]=== -->
        <div id="textT2Col" style="display:inline">
<?php print border('syellow','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT2Col','textT2')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />Class</div>"); ?>
<?php print border('syellow','end'); ?>
        </div>
        <div id="textT2" style="display:none">
<?php print border('sgreen','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT2Col','textT2')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />Class</div>"); ?>
        <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
          <tr>
            <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Display Class</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_class_disp" value="1" <?php print ( $configData['text_class_disp'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_class_disp" value="0" <?php print ( !$configData['text_class_disp'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Class placement</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="text_class_loc_x" type="text" value="<?php print $configData['text_class_loc_x']; ?>" size="3" maxlength="3" /><br />
              Vert: <input name="text_class_loc_y" type="text" value="<?php print $configData['text_class_loc_y']; ?>" size="3" maxlength="3" /></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Class alignment</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($alignArr,$configData['text_class_align'],'text_class_align' ); ?></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="center" colspan="2">
              <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
                <tr>
                  <th colspan="2" class="membersHeaderRight">Font settings</th>
                </tr>
                <tr>
                  <td class="sc_row1" align="left">Font name:<br />
                  	<?php print $functions->createOptionList($fontArr,$configData['text_class_font_name'],'text_class_font_name' ); ?></td>
                  <td class="sc_row_right1" align="right">Font size:<br />
                  	<input name="text_class_font_size" type="text" value="<?php print $configData['text_class_font_size']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr>
                  <td class="sc_row_right2" align="left" colspan="2">Font color:<br />
                  	<?php print $functions->createOptionList($colorArr,$configData['text_class_font_color'],'text_class_font_color' ); ?> :
                    <?php print $functions->createColorTip($configData['text_class_font_color'],$configData); ?></td>
                </tr>
                <tr>
                  <td class="sc_row_right1" align="left" colspan="2"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?>
                    <?php print $functions->createOptionList($colorArr,$configData['text_class_shadow'],'text_class_shadow' ); ?> :
                    <?php print $functions->createColorTip($configData['text_class_shadow'],$configData); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
<?php print border('sgreen','end'); ?>
        </div></td>

    </tr>
    <tr>

      <td valign="top"><!-- ===[ Begin Text Config 3 ]=== -->
        <div id="textT3Col" style="display:inline">
<?php print border('syellow','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT3Col','textT3')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />PvP Rank</div>"); ?>
<?php print border('syellow','end'); ?>
        </div>
        <div id="textT3" style="display:none">
<?php print border('sgreen','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT3Col','textT3')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />PvP Rank</div>"); ?>
        <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
          <tr>
            <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Display PvP Rank</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_honor_disp" value="1" <?php print ( $configData['text_honor_disp'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_honor_disp" value="0" <?php print ( !$configData['text_honor_disp'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">PvP Rank placement</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="text_honor_loc_x" type="text" value="<?php print $configData['text_honor_loc_x']; ?>" size="3" maxlength="3" /><br />
              Vert: <input name="text_honor_loc_y" type="text" value="<?php print $configData['text_honor_loc_y']; ?>" size="3" maxlength="3" /></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">PvP Rank alignment</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($alignArr,$configData['text_honor_align'],'text_honor_align' ); ?></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="center" colspan="2">
              <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
                <tr>
                  <th colspan="2" class="membersHeaderRight">Font settings</th>
                </tr>
                <tr>
                  <td class="sc_row1" align="left">Font name:<br />
                  	<?php print $functions->createOptionList($fontArr,$configData['text_honor_font_name'],'text_honor_font_name' ); ?></td>
                  <td class="sc_row_right1" align="right">Font size:<br />
                  	<input name="text_honor_font_size" type="text" value="<?php print $configData['text_honor_font_size']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr>
                  <td class="sc_row_right2" align="left" colspan="2">Font color:<br />
                  	<?php print $functions->createOptionList($colorArr,$configData['text_honor_font_color'],'text_honor_font_color' ); ?> :
                    <?php print $functions->createColorTip($configData['text_honor_font_color'],$configData); ?></td>
                </tr>
                <tr>
                  <td class="sc_row_right1" align="left" colspan="2"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?>
                    <?php print $functions->createOptionList($colorArr,$configData['text_honor_shadow'],'text_honor_shadow' ); ?> :
                    <?php print $functions->createColorTip($configData['text_honor_shadow'],$configData); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
<?php print border('sgreen','end'); ?>
        </div></td>

      <td valign="top"><!-- ===[ Begin Text Config 4 ]=== -->
        <div id="textT4Col" style="display:inline">
<?php print border('syellow','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT4Col','textT4')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />Guild Name</div>"); ?>
<?php print border('syellow','end'); ?>
        </div>
        <div id="textT4" style="display:none">
<?php print border('sgreen','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT4Col','textT4')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />Guild Name</div>"); ?>
        <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
          <tr>
            <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Display Guild Name</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_guildname_disp" value="1" <?php print ( $configData['text_guildname_disp'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_guildname_disp" value="0" <?php print ( !$configData['text_guildname_disp'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Guild Name placement</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="text_guildname_loc_x" type="text" value="<?php print $configData['text_guildname_loc_x']; ?>" size="3" maxlength="3" /><br />
              Vert: <input name="text_guildname_loc_y" type="text" value="<?php print $configData['text_guildname_loc_y']; ?>" size="3" maxlength="3" /></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Guild Name alignment</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($alignArr,$configData['text_guildname_align'],'text_guildname_align' ); ?></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="center" colspan="2">
              <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
                <tr>
                  <th colspan="2" class="membersHeaderRight">Font settings</th>
                </tr>
                <tr>
                  <td class="sc_row1" align="left">Font name:<br />
                  	<?php print $functions->createOptionList($fontArr,$configData['text_guildname_font_name'],'text_guildname_font_name' ); ?></td>
                  <td class="sc_row_right1" align="right">Font size:<br />
                  	<input name="text_guildname_font_size" type="text" value="<?php print $configData['text_guildname_font_size']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr>
                  <td class="sc_row_right2" align="left" colspan="2">Font color:<br />
                  	<?php print $functions->createOptionList($colorArr,$configData['text_guildname_font_color'],'text_guildname_font_color' ); ?> :
                    <?php print $functions->createColorTip($configData['text_guildname_font_color'],$configData); ?></td>
                </tr>
                <tr>
                  <td class="sc_row_right1" align="left" colspan="2"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?>
                    <?php print $functions->createOptionList($colorArr,$configData['text_guildname_shadow'],'text_guildname_shadow' ); ?> :
                    <?php print $functions->createColorTip($configData['text_guildname_shadow'],$configData); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
<?php print border('sgreen','end'); ?>
        </div></td>

    </tr>
    <tr>

      <td valign="top"><!-- ===[ Begin Text Config 5 ]=== -->
        <div id="textT5Col" style="display:inline">
<?php print border('syellow','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT5Col','textT5')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />Guild Title/Rank</div>"); ?>
<?php print border('syellow','end'); ?>
        </div>
        <div id="textT5" style="display:none">
<?php print border('sgreen','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT5Col','textT5')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />Guild Title/Rank</div>"); ?>
        <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
          <tr>
            <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Display Guild Title/Rank</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_guildtitle_disp" value="1" <?php print ( $configData['text_guildtitle_disp'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_guildtitle_disp" value="0" <?php print ( !$configData['text_guildtitle_disp'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Guild Title/Rank placement</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="text_guildtitle_loc_x" type="text" value="<?php print $configData['text_guildtitle_loc_x']; ?>" size="3" maxlength="3" /><br />
              Vert: <input name="text_guildtitle_loc_y" type="text" value="<?php print $configData['text_guildtitle_loc_y']; ?>" size="3" maxlength="3" /></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Guild Title/Rank alignment</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($alignArr,$configData['text_guildtitle_align'],'text_guildtitle_align' ); ?></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="center" colspan="2">
              <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
                <tr>
                  <th colspan="2" class="membersHeaderRight">Font settings</th>
                </tr>
                <tr>
                  <td class="sc_row1" align="left">Font name:<br />
                  	<?php print $functions->createOptionList($fontArr,$configData['text_guildtitle_font_name'],'text_guildtitle_font_name' ); ?></td>
                  <td class="sc_row_right1" align="right">Font size:<br />
                  	<input name="text_guildtitle_font_size" type="text" value="<?php print $configData['text_guildtitle_font_size']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr>
                  <td class="sc_row_right2" align="left" colspan="2">Font color:<br />
                  	<?php print $functions->createOptionList($colorArr,$configData['text_guildtitle_font_color'],'text_guildtitle_font_color' ); ?> :
                    <?php print $functions->createColorTip($configData['text_guildtitle_font_color'],$configData); ?></td>
                </tr>
                <tr>
                  <td class="sc_row_right1" align="left" colspan="2"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?>
                    <?php print $functions->createOptionList($colorArr,$configData['text_guildtitle_shadow'],'text_guildtitle_shadow' ); ?> :
                    <?php print $functions->createColorTip($configData['text_guildtitle_shadow'],$configData); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
<?php print border('sgreen','end'); ?>
        </div></td>

      <td valign="top"><!-- ===[ Begin Text Config 6 ]=== -->
        <div id="textT6Col" style="display:inline">
<?php print border('syellow','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT6Col','textT6')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />Realm Name</div>"); ?>
<?php print border('syellow','end'); ?>
        </div>
        <div id="textT6" style="display:none">
<?php print border('sgreen','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT6Col','textT6')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />Realm Name</div>"); ?>
        <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
          <tr>
            <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Display Realm Name</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_servername_disp" value="1" <?php print ( $configData['text_servername_disp'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_servername_disp" value="0" <?php print ( !$configData['text_servername_disp'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Realm Name placement</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="text_servername_loc_x" type="text" value="<?php print $configData['text_servername_loc_x']; ?>" size="3" maxlength="3" /><br />
              Vert: <input name="text_servername_loc_y" type="text" value="<?php print $configData['text_servername_loc_y']; ?>" size="3" maxlength="3" /></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Realm Name alignment</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($alignArr,$configData['text_servername_align'],'text_servername_align' ); ?></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="center" colspan="2">
              <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
                <tr>
                  <th colspan="2" class="membersHeaderRight">Font settings</th>
                </tr>
                <tr>
                  <td class="sc_row1" align="left">Font name:<br />
                  	<?php print $functions->createOptionList($fontArr,$configData['text_servername_font_name'],'text_servername_font_name' ); ?></td>
                  <td class="sc_row_right1" align="right">Font size:<br />
                  	<input name="text_servername_font_size" type="text" value="<?php print $configData['text_servername_font_size']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr>
                  <td class="sc_row_right2" align="left" colspan="2">Font color:<br />
                  	<?php print $functions->createOptionList($colorArr,$configData['text_servername_font_color'],'text_servername_font_color' ); ?> :
                    <?php print $functions->createColorTip($configData['text_servername_font_color'],$configData); ?></td>
                </tr>
                <tr>
                  <td class="sc_row_right1" align="left" colspan="2"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?>
                    <?php print $functions->createOptionList($colorArr,$configData['text_servername_shadow'],'text_servername_shadow' ); ?> :
                    <?php print $functions->createColorTip($configData['text_servername_shadow'],$configData); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
<?php print border('sgreen','end'); ?>
        </div></td>

    </tr>
    <tr>

      <td valign="top"><!-- ===[ Begin Text Config 7 ]=== -->
        <div id="textT7Col" style="display:inline">
<?php print border('syellow','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT7Col','textT7')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />Website Name</div>"); ?>
<?php print border('syellow','end'); ?>
        </div>
        <div id="textT7" style="display:none">
<?php print border('sgreen','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT7Col','textT7')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />Website Name</div>"); ?>
        <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
          <tr>
            <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Display Website Name</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_sitename_disp" value="1" <?php print ( $configData['text_sitename_disp'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_sitename_disp" value="0" <?php print ( !$configData['text_sitename_disp'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Website Name placement</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="text_sitename_loc_x" type="text" value="<?php print $configData['text_sitename_loc_x']; ?>" size="3" maxlength="3" /><br />
              Vert: <input name="text_sitename_loc_y" type="text" value="<?php print $configData['text_sitename_loc_y']; ?>" size="3" maxlength="3" /></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Website Name alignment</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($alignArr,$configData['text_sitename_align'],'text_sitename_align' ); ?></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Remove "http://"<br />
              from website name?</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_sitename_remove" value="1" <?php print ( $configData['text_sitename_remove'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_sitename_remove" value="0" <?php print ( !$configData['text_sitename_remove'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="center" colspan="2">
              <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
                <tr>
                  <th colspan="2" class="membersHeaderRight">Font settings</th>
                </tr>
                <tr>
                  <td class="sc_row1" align="left">Font name:<br />
                  	<?php print $functions->createOptionList($fontArr,$configData['text_sitename_font_name'],'text_sitename_font_name' ); ?></td>
                  <td class="sc_row_right1" align="right">Font size:<br />
                  	<input name="text_sitename_font_size" type="text" value="<?php print $configData['text_sitename_font_size']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr>
                  <td class="sc_row_right2" align="left" colspan="2">Font color:<br />
                  	<?php print $functions->createOptionList($colorArr,$configData['text_sitename_font_color'],'text_sitename_font_color' ); ?> :
                    <?php print $functions->createColorTip($configData['text_sitename_font_color'],$configData); ?></td>
                </tr>
                <tr>
                  <td class="sc_row_right1" align="left" colspan="2"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?>
                    <?php print $functions->createOptionList($colorArr,$configData['text_sitename_shadow'],'text_sitename_shadow' ); ?> :
                    <?php print $functions->createColorTip($configData['text_sitename_shadow'],$configData); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
<?php print border('sgreen','end'); ?>
        </div></td>

      <td valign="top"><!-- ===[ Begin Text Config 8 ]=== -->
        <div id="textT8Col" style="display:inline">
<?php print border('syellow','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT8Col','textT8')\"><img src=\"".$roster_conf['img_url']."plus.gif\" style=\"float:right;\" />Custom Text</div>"); ?>
<?php print border('syellow','end'); ?>
        </div>
        <div id="textT8" style="display:none">
<?php print border('sgreen','start',"<div style=\"cursor:pointer;width:240px;\" onclick=\"swapShow('textT8Col','textT8')\"><img src=\"".$roster_conf['img_url']."minus.gif\" style=\"float:right;\" />Custom Text</div>"); ?>
        <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
          <tr>
            <td class="sc_row<?php echo ((($row=0)%2)+1); ?>" align="left">Display Custom Text</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><label>
              <input type="radio" class="checkBox" name="text_custom_disp" value="1" <?php print ( $configData['text_custom_disp'] ? 'checked="checked"' : '' ); ?> />
              Yes</label> <label>
              <input type="radio" class="checkBox" name="text_custom_disp" value="0" <?php print ( !$configData['text_custom_disp'] ? 'checked="checked"' : '' ); ?> />
              No</label></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Custom Text placement</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right">Horiz: <input name="text_custom_loc_x" type="text" value="<?php print $configData['text_custom_loc_x']; ?>" size="3" maxlength="3" /><br />
              Vert: <input name="text_custom_loc_y" type="text" value="<?php print $configData['text_custom_loc_y']; ?>" size="3" maxlength="3" /></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo (((++$row)%2)+1); ?>" align="left" colspan="2">Custom Text:<br />
              <input name="text_custom_text" type="text" value="<?php print $configData['text_custom_text']; ?>" size="35" maxlength="50" /></td>
          </tr>
          <tr>
            <td class="sc_row<?php echo (((++$row)%2)+1); ?>" align="left">Custom Text alignment</td>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="right"><?php print $functions->createOptionList($alignArr,$configData['text_custom_align'],'text_custom_align' ); ?></td>
          </tr>
          <tr>
            <td class="sc_row_right<?php echo ((($row)%2)+1); ?>" align="center" colspan="2">
              <table width="100%" class="sc_table" cellspacing="0" cellpadding="2">
                <tr>
                  <th colspan="2" class="membersHeaderRight">Font settings</th>
                </tr>
                <tr>
                  <td class="sc_row1" align="left">Font name:<br />
                  	<?php print $functions->createOptionList($fontArr,$configData['text_custom_font_name'],'text_custom_font_name' ); ?></td>
                  <td class="sc_row_right1" align="right">Font size:<br />
                  	<input name="text_custom_font_size" type="text" value="<?php print $configData['text_custom_font_size']; ?>" size="3" maxlength="3" /></td>
                </tr>
                <tr>
                  <td class="sc_row_right2" align="left" colspan="2">Font color:<br />
                  	<?php print $functions->createOptionList($colorArr,$configData['text_custom_font_color'],'text_custom_font_color' ); ?> :
                    <?php print $functions->createColorTip($configData['text_custom_font_color'],$configData); ?></td>
                </tr>
                <tr>
                  <td class="sc_row_right1" align="left" colspan="2"><?php print $functions->createTip( 'Create a pseudo-shadow behind the text<br />Selecting &quot;--None--&quot; turns off the shadow','Shadow Text' ); ?>
                    <?php print $functions->createOptionList($colorArr,$configData['text_custom_shadow'],'text_custom_shadow' ); ?> :
                    <?php print $functions->createColorTip($configData['text_custom_shadow'],$configData); ?></td>
                </tr>
              </table></td>
          </tr>
        </table>
<?php print border('sgreen','end'); ?>
        </div></td>

    </tr>
  </table>
<?php print border('sgray','end'); ?>
</div>

</form>
<!-- End Main Body -->
