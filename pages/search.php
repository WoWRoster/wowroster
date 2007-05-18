<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster Item and recipe search
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    WoWRoster
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

if( isset($_POST['search']) && $_POST['search'] != '' )
{
	$search = urlencode($_POST['search']);
	header('Location: ' . $_POST['url'] . $search);
	exit();
}

require_once ROSTER_LIB . 'item.php';
require_once ROSTER_LIB . 'recipes.php';

$roster->output['title'] = $roster->locale->act['search'];

$roster->output['body_onload'] .= 'initARC(\'searchform\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');';

include_once (ROSTER_BASE.'roster_header.tpl');

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');


$output = "<br />\n";


$search = (isset($_GET['s']) ? $_GET['s'] : '');

$searchname = ( isset($_GET['name']) ? $_GET['name'] : '' );
$searchtooltip = ( isset($_GET['tooltip']) ? $_GET['tooltip'] : '' );
$searchstring = '';

$anchor_link = ( !empty($search) ? '&amp;s=' . $search : '' )
			 . ( !empty($searchname) ? '&amp;name=' . $searchname : '' )
			 . ( !empty($searchtooltip) ? '&amp;tooltip=' . $searchtooltip : '' );

if( empty($searchname) && empty($searchtooltip) )
{
	$searchname = '1';
}


$input_form = '<form id="searchform" action="' . getFormAction() . '" method="get">
' . linkform() . '
	<div align="center">
	<input type="text" class="wowinput192" name="s" value="' . $search . '" size="30" maxlength="30" />
<br /><br />
		<input type="checkbox" id="name" name="name" value="1"' . (!empty($searchname) ? ' checked="checked"' : '' ) . ' />
			<label for="name">' . $roster->locale->act['search_names'] . '</label>
		<input type="checkbox" id="tooltip" name="tooltip" value="1"' . (!empty($searchtooltip) ? ' checked="checked"' : '' ) . ' />
			<label for="tooltip">' . $roster->locale->act['search_tooltips'] . '</label>
<br /><br />

	<input type="submit" value="Search" />
	</div>
</form>';

$output .= messagebox($input_form,$roster->locale->act['find'],'sgreen');

if( !empty($search) )
{
	$search = $roster->db->escape($search);

	// Set a ank for link to top of page
	$output .= '<a name="top">&nbsp;</a>
<div style="color:white;text-align;center">
	<a href="' . makelink($anchor_link . '#items') . '">'.$roster->locale->act['items'].'</a> - <a href="' . makelink($anchor_link . '#recipes') . '">'.$roster->locale->act['recipes'].'</a>
</div>
<br /><br />';

	$output .= '<a name="items"></a><div class="headline_1"><a href="' . makelink($anchor_link . '#top') . '">' . $roster->locale->act['items'] . '</a></div>';

	if( !empty($searchname) && !empty($searchtooltip) )
	{
		$searchstring = "(`i`.`item_name` LIKE '%$search%' OR `i`.`item_tooltip` LIKE '%$search%' )";
	}
	elseif( !empty($searchname) )
	{
		$searchstring = "`i`.`item_name` LIKE '%$search%'";
	}
	elseif( !empty($searchtooltip) )
	{
		$searchstring = "`i`.`item_tooltip` LIKE '%$search%'";
	}

	$query = "SELECT `p`.`name`, `p`.`server`, `i`.*"
	       . " FROM `" . $roster->db->table('items') . "` AS i,`" . $roster->db->table('players') . "` AS p"
	       . " WHERE `i`.`member_id` = `p`.`member_id` AND $searchstring"
	       . " ORDER BY `p`.`name` ASC;";
	$result = $roster->db->query( $query );

	if (!$result)
	{
		die_quietly('There was a database error trying to fetch matching items. MySQL said: <br />' . $roster->db->error(),$roster->locale->act['search'],__FILE__,__LINE__,$query);
	}

	if( $roster->db->num_rows($result) != 0 )
	{
		$cid = '';
		$rc = 0;
		while( $data = $roster->db->fetch($result) )
		{
			$row_st = (($rc%2)+1);
			$char_url = ( active_addon('info') ? '<a href="' . makelink('char-info&amp;member=' . $data['member_id']) . '">' . $data['name'] . '</a>' : $data['name'] );

			if( $cid != $data['member_id'] )
			{
				if( $cid != '' )
				{
					$output .= "</table>\n" . border('sblue','end') . "<br />\n";
				}
				$output .= border('sblue','start',$char_url) . '<table cellpadding="0" cellspacing="0" width="600">';
			}

			$output .= '  <tr>
    <td width="45" valign="top" class="membersRow' . $row_st . '">';
			$item = new item($data);
			$output .= $item->out();
			$output .= "</td>\n";
			$output .= '    <td valign="middle" class="membersRowRight' . $row_st . ' overlib_maintext" style="white-space:normal;">';

			$first_line = true;
			$tooltip_out = '';
			$data['item_tooltip'] = stripslashes($data['item_tooltip']);

			$output .= colorTooltip($data['item_tooltip'],$data['item_color']);

			$output .= "</td>\n  </tr>\n";
			$cid = $data['member_id'];
			$rc++;
		}

		if( $cid != '' )
		{
			$output .= "</table>\n" . border('sblue','end') . "<br />\n";
		}
	}
	else
	{
		$output .= border('sblue','start') . '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No ' . $roster->locale->act['items'] . '</td>
  </tr>'."</table>\n" . border('sblue','end');
	}


	$output .= "<br /><hr />\n";

	$output .= '<a name="recipes"></a><div class="headline_1"><a href="' . makelink($anchor_link . '#top') . '">' . $roster->locale->act['recipes'] . '</a></div>';

	if( !empty($searchname) && !empty($searchtooltip) )
	{
		$searchstring = "(`r`.`recipe_name` LIKE '%$search%' OR `r`.`recipe_tooltip` LIKE '%$search%' )";
	}
	elseif( !empty($searchname) )
	{
		$searchstring = "`r`.`recipe_name` LIKE '%$search%'";
	}
	elseif( !empty($searchtooltip) )
	{
		$searchstring = "`r`.`recipe_tooltip` LIKE '%$search%'";
	}

	$query = "SELECT `p`.name, `p`.server, `r`.*"
	       . " FROM `" . $roster->db->table('recipes') . "` AS r,`" . $roster->db->table('players') . "` AS p"
	       . " WHERE `r`.`member_id` = `p`.`member_id` AND $searchstring"
	       . " ORDER BY `p`.`name` ASC, `r`.`recipe_name` ASC;";
	$result = $roster->db->query( $query );

	if( !$result )
	{
		die_quietly('There was a database error trying to fetch matching recipes. MySQL said: <br />' . $roster->db->error(),$roster->locale->act['search'],__FILE__,__LINE__,$query);
	}

	if( $roster->db->num_rows($result) != 0 )
	{
		$cid = '';

		$rc = 0;
		while( $data = $roster->db->fetch( $result ) )
		{
			$row_st = (($rc%2)+1);

			$char_url = ( active_addon('info') ? '<a href="' . makelink('char-info-recipes&amp;member=' . $data['member_id']) . '">' . $data['name'] . '</a>' : $data['name'] );
			if( $cid != $data['member_id'] )
			{
				if( $cid != '' )
				{
					$output .= "</table>\n" . border('syellow','end') . "<br />\n";
				}
				$output .= border('syellow','start',$char_url) . '<table border="0" cellpadding="0" cellspacing="0" width="600">
  <tr>
    <th colspan="2" class="membersHeader">' . $roster->locale->act['item'] . '</th>
    <th class="membersHeaderRight">' . $roster->locale->act['reagents'] . '</th>
  </tr>';
			}

			$output .= '<tr><td width="45" valign="top" align="center" class="membersRow' . $row_st . '">';

			$recipe = new recipe($data);
			$output .= $recipe->out();
			$output .= "</td>\n";
			$output .= '<td valign="top" class="membersRow' . $row_st . ' overlib_maintext" style="white-space:normal;">';

			$first_line = true;
			$tooltip_out = '';
			$data['item_tooltip'] = stripslashes($data['recipe_tooltip']);

			$output .= colorTooltip($data['item_tooltip'],$data['item_color']);

			$output .= "</td>\n" . '<td class="membersRowRight' . $row_st . '" width="50%" valign="top">';
			$output .= "<span class=\"tooltipline\" style=\"color:#ffffff\">" . str_replace('<br>','<br />',$data['reagents']) . "</span><br /><br />";
			$output .= "</td></tr>\n";
			$cid = $data['member_id'];
			$rc++;
		}

		if( $cid != '' )
		{
			$output .= "</table>\n" . border('syellow','end') . "<br />\n";
		}
	}
	else
	{
		$output .= border('sblue','start') . '<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No ' . $roster->locale->act['recipes'] . "</td>
  </tr>
</table>\n".border('sblue','end');
	}
}

print $output;


include_once (ROSTER_BASE.'roster_footer.tpl');