<?php
/******************************
 * WoWRoster.net  Roster
 * Copyright 2002-2006
 * Licensed under the Creative Commons
 * "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * Short summary
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/
 *
 * Full license information
 *  http://creativecommons.org/licenses/by-nc-sa/2.5/legalcode
 * -----------------------------
 *
 * $Id$
 *
 ******************************/

if ( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}


if( isset($_GET['op']) )
{
	$op = $_GET['op'];
}
elseif( isset($_POST['op']) )
{
	$op = $_POST['op'];
}
else
{
	$op = '';
}

if( isset($_GET['id']) )
{
	$id = $_GET['id'];
}
elseif( isset($_POST['id']) )
{
	$id = $_POST['id'];
}
else
{
	$id = '';
}

switch($op)
{
	case 'deactivate':
		processActive($id,0);
		break;

	case 'activate':
		processActive($id,1);
		break;

	default:
		break;
}



// Generate addons table
$output =
'<table class="bodyline" cellspacing="0">
	<tr>
		<th class="membersHeader">Icon</th>
		<th class="membersHeader">Addon Info</th>
		<th class="membersHeader">Status</th>
		<th class="membersHeaderRight">Installation</th>
	</tr>
';
foreach( getAddonList() as $addon )
{
	$output .= '	<tr>
		<td class="membersRowCell membersRowColor1">[icon]</td>
		<td class="membersRowCell membersRowColor1"><table cellpadding="0" cellspacing="0">
			<tr>
				<td><span style="font-size:18px;" class="green">'.ucfirst($addon['fullname']).'</span> v'.$addon['version'].'</td>
			</tr>
			<tr>
				<td>'.$addon['description'].'</td>
			<tr>
				<td><span class="blue">Author: '.$addon['author'].'<span></td>
			</tr>
		</table></td>
		<td class="membersRowCell membersRowColor1">'.( $addon['install'] == 3 ? '&nbsp;' : activeInactive($addon['active'],$addon['id']) ).'</td>
		<td class="membersRowRightCell membersRowColor1">'.installUpgrade($addon['install'],$addon['basename']).'</td>
	</tr>
';
}
$output .= '</table>';

$body = messagebox($output,'Addons','sblue');



function activeInactive( $mode,$id )
{
	global $script_filename, $roster_conf;

	if( $mode )
	{
		$type = '<form name="deactivate_'.$id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.$script_filename.'?page=install">
		<input type="hidden" name="op" value="deactivate" />
		<input type="hidden" name="id" value="'.$id.'" />
		<input '.makeOverlib('Click to Deactivate','Activated').'type="image" src="'.$roster_conf['img_url'].'admin/green.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}
	else
	{
		$type = '<form name="activate_'.$id.'" style="display:inline;" method="post" enctype="multipart/form-data" action="'.$script_filename.'?page=install">
		<input type="hidden" name="op" value="activate" />
		<input type="hidden" name="id" value="'.$id.'" />
		<input '.makeOverlib('Click to Activate','Deactivated').' type="image" src="'.$roster_conf['img_url'].'admin/red.png" style="height:16px;width:16px;border:0;" alt="" />
	</form>';
	}

	return $type;
}

function installUpgrade( $mode,$name )
{
	global $roster_conf;

	if( $mode == -1 )
	{
		$type = '<img '.makeOverlib('Replace files with latest version','Old Version Overwrite').' src="'.$roster_conf['img_url'].'admin/purple.png" style="height:16px;width:16px;border:0;" alt="" />';
	}
	elseif( $mode == 0 )
	{
		$type = '<a href="installer.php?addon='.$name.'&amp;dbname='.$name.'&amp;type=uninstall"><img '.makeOverlib('Click to Uninstall','Installed').' src="'.$roster_conf['img_url'].'admin/green.png" style="height:16px;width:16px;border:0;" alt="" /></a>';
	}
	elseif( $mode == 1 )
	{
		$type = '<a href="installer.php?addon='.$name.'&amp;dbname='.$name.'&amp;type=upgrade"><img '.makeOverlib('Click to Upgrade','Upgrade Available').' src="'.$roster_conf['img_url'].'admin/blue.png" style="height:16px;width:16px;border:0;" alt="" /></a>';
	}
	elseif( $mode == 3 )
	{
		$type = '<a href="installer.php?addon='.$name.'&amp;dbname='.$name.'&amp;type=install"><img '.makeOverlib('Click to Install','Not Installed').' src="'.$roster_conf['img_url'].'admin/red.png" style="height:16px;width:16px;border:0;" alt="" /></a>';
	}

	return $type;
}


/**
 * Gets the list of currently installed roster addons
 *
 * @return string formatted list of addons
 */
function getAddonList()
{
	global $roster_conf, $wordings, $wowdb;

	$addonsPath = ROSTER_BASE.'addons'.DIR_SEP;

	// Initialize output
	$addons = '';
	$output = '';

	if ($handle = opendir($addonsPath))
	{
		while (false !== ($file = readdir($handle)))
		{
			if ($file != '.' && $file != '..' && $file != '.svn' )
			{
				$addons[] = $file;
			}
		}
	}

	$aCount = 0; //addon count

	if( is_array($addons) )
	{
		foreach ($addons as $addon)
		{
			$installfile = $addonsPath.$addon.DIR_SEP.'install.def.php';

			if (file_exists($installfile))
			{
				include_once($installfile);

				$addonstuff = new $addon;

				$query = "SELECT * FROM `".$wowdb->table('addon')."` WHERE `basename` = '$addon'";
				$result = $wowdb->query($query);
				if (!$result)
					die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);


				if( $wowdb->num_rows($result) > 0 )
				{
					$row = $wowdb->fetch_assoc($result);

					$output[$addon]['id'] = $row['addon_id'];
					$output[$addon]['active'] = $row['active'];

					// -1 = overwrote newer version
					//  0 = same version
					//  1 = upgrade available
					$output[$addon]['install'] = version_compare($addonstuff->version,$row['version']);

				}
				else
				{
					$output[$addon]['install'] = 3;
				}

				$output[$addon]['basename'] = $addon;
				$output[$addon]['fullname'] = $addonstuff->fullname;
				$output[$addon]['author'] = $addonstuff->credits[0]['name'];
				$output[$addon]['version'] = $addonstuff->version;
				$output[$addon]['description'] = $addonstuff->description;

				unset($addonstuff);
			}
		}
	}
/*
	print '<pre>';
	print_r($output);
	print '</pre>';
*/
	return $output;
}

function processActive($id,$mode)
{
	global $wowdb;

	$query = "UPDATE `".$wowdb->table('addon')."` SET `active` = '$mode' WHERE `addon_id` = '$id' LIMIT 1;";
	$result = $wowdb->query($query);
	if (!$result)
		die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);
}


?>