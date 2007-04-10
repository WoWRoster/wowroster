<?php

$addons = makeAddonList();

function makeAddonList()
{
	global $paths,$roster_lang,$wordings;

	$sep = DIRECTORY_SEPARATOR;
	$addonsPath = $paths[0].'/addons';
	$addonsURL = $paths[1].'/addons';

	if ($handle = opendir(realpath($addonsPath)))
	{
		while (false !== ($file = readdir($handle)))
		{
			if ($file != '.' && $file != '..')
			{
				$addons[$i] =$file;
				$i++;
			}
		}
	}

	$aCount = 0; //addon count
	$lCount = 0; //link count
	$newl = true; //new line , omit leading -

	
	foreach ($addons as $addon)
	{
		$menufile = $addonsPath.$sep.$addon.$sep.'menu.php';
		if (file_exists($menufile))
		{
			$localizationFile = $paths[0].$sep.'addons'.$sep.$addon.$sep.'localization.php';
			if (file_exists($localizationFile))include($localizationFile);

			include($menufile);
			
			if (0 >= $config['menu_min_user_level']) //modify this line for user level / authentication stuff (show the link for user level whatever for this addon)  you understand :P
			{
				if (isset($config['menu_index_file'][0]))
				{
					//$config['menu_index_file'] is the new array type
					foreach ($config['menu_index_file'] as $addonLink)
					{
						if (!$newl)$output .= ' - '; else $newl = false;
						$fullQuery = "?roster_addon_name=$addon" . $addonLink[0];
						$query = str_replace(' ','%20',$fullQuery);
						$output .="<a href='addon.php$query'>" . $addonLink[1]."</a>\n";
						$lCount++;

						if ($lCount%5 == 0)
						{
							$output .= "<br />";
							$newl = true;
						}
					}
				}
			}
		}
	}
	$output .= "\n";

	if ($lCount < 1)
		return '';

	return $output;
}

?>
<!-- Begin SigGen Menu -->
<table class="sc_table" cellspacing="1" cellpadding="2">
  <tr class="sc_row1">
    <td>
      <span style="font-size:14px;"><a href="<?php print $website_address.'">'.$guild_name.'</a></span> of '.$server_name." ($server_type)"; ?></td>
  </tr>
  <tr class="sc_row1">
    <td align="center">
      <a href="<?php print $roster_dir ?>/index.php"><?php print $wordings[$roster_lang]['roster'] ?></a> - 
      <a href="<?php print $roster_dir ?>/index.php?s=class"><?php print $wordings[$roster_lang]['byclass'] ?></a> - 
      <a href="<?php print $roster_dir ?>/indexAlt.php"><?php print $wordings[$roster_lang]['alternate'] ?></a> - 
      <a href="<?php print $roster_dir ?>/indexStat.php"><?php print $wordings[$roster_lang]['menustats'] ?></a> - 
      <a href="<?php print $roster_dir ?>/indexHonor.php"><?php print $wordings[$roster_lang]['menuhonor'] ?></a> - 
      <a href="<?php print $roster_dir ?>/tradeskills.php"><?php print $wordings[$roster_lang]['professions'] ?></a><br />
      <a href="<?php print $roster_dir ?>/indexInst.php"><?php print $wordings[$roster_lang]['keys'] ?></a> - 
      <a href="<?php print $roster_dir ?>/indexquests.php"><?php print $wordings[$roster_lang]['team'] ?></a> - 
<?php

if ($show_guildbank)
print '      <a href="'.$roster_dir.'/guildbank'.$guildbank_ver.'.php">'.$wordings[$roster_lang]['guildbank'].'</a> - 
';

if ($show_raid)
print '      <a href="'.$roster_dir.'/indexRaid.php">'.$wordings[$roster_lang]['raid'].'</a> - 
';

?>
        <a href="<?php print $roster_dir ?>/indexSearch.php"><?php print $wordings[$roster_lang]['search'] ?></a> - 
        <a href="<?php print $roster_dir ?>/credits.php"><?php print $wordings[$roster_lang]['credit'] ?></a> - 
        <a href="<?php print $roster_dir ?>/admin/update.php"><?php print $wordings[$roster_lang]['upprofile'] ?></a>
    </td>
  </tr>
  <tr class="sc_row1">
    <td align="center">
      <span style="color:#0099FF;font-size:10px;"><?php print $wordings[$roster_lang]['Addon'] ?></span>
      <?php print $addons ?>
    </td>
  </tr>
</table>
<!-- End SigGen Menu -->