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
 * @since      File available since Release 1.8.0
*/

if( !defined('ROSTER_INSTALLED') )
{
    exit('Detected invalid access to this file!');
}

$header_title = $act_words['search'];

require_once ROSTER_LIB . 'item.php';
require_once ROSTER_LIB . 'recipes.php';
include_once ROSTER_LIB . 'sitesearch.lib.php';


$output = "<br />\n";

$output .= "<table cellspacing=\"6\">\n  <tr>\n";

$output .= '    <td valign="top">';
$output .= sitesearch('thott');
$output .= "    </td>\n";

$output .= '    <td valign="top">';
$output .= sitesearch('alla');
$output .= "    </td>\n";

$output .= "  </tr>\n  <tr>\n";

$output .= '    <td valign="top">';
$output .= sitesearch('wowhead');
$output .= "    </td>\n";

$output .= '    <td valign="top">';
$output .= sitesearch('wwndata');
$output .= "    </td>\n";

$output .= "  </tr>\n</table>\n";

$output .= "<br />\n";

$search = (isset($_GET['s']) ? $_GET['s'] : '');

$searchname = ( isset($_GET['name']) ? $_GET['name'] : '' );
$searchtooltip = ( isset($_GET['tooltip']) ? $_GET['tooltip'] : '' );
$searchstring = '';

if( empty($searchname) && empty($searchtooltip) )
{
	$searchname = '1';
}

$body_action = 'onload="initARC(\'searchform\',\'radioOn\',\'radioOff\',\'checkboxOn\',\'checkboxOff\');"';

$input_form = '<form id="searchform" action="' . makelink() . '" method="get">
' . linkform() . '
	<input type="text" class="wowinput192" name="s" value="' . $search . '" size="30" maxlength="30" />

	<div align="left">
		<input type="checkbox" id="name" name="name" value="1"' . (!empty($searchname) ? ' checked="checked"' : '' ) . ' />
			<label for="name">' . $act_words['search_names'] . '</label><br />
		<input type="checkbox" id="tooltip" name="tooltip" value="1"' . (!empty($searchtooltip) ? ' checked="checked"' : '' ) . ' />
			<label for="tooltip">' . $act_words['search_tooltips'] . '</label>
	</div>

	<input type="submit" value="search" />
</form>';

$output .= messagebox($input_form,$act_words['find'],'sgreen');

if( !empty($search) )
{
	$search = $wowdb->escape($search);

	// Set a ank for link to top of page
	$output .= '<a name="top">&nbsp;</a>
<div style="color:white;text-align;center">
	<a href="#items">'.$act_words['items'].'</a> - <a href="#recipes">'.$act_words['recipes'].'</a>
</div>
<br /><br />';

	$output .= '<a name="items"></a><div class="headline_1"><a href="#top">' . $act_words['items'] . '</a></div>';

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
	       . " FROM `" . ROSTER_ITEMSTABLE . "` AS i,`" . ROSTER_PLAYERSTABLE . "` AS p"
	       . " WHERE `i`.`member_id` = `p`.`member_id` AND $searchstring"
	       . " ORDER BY `p`.`name` ASC;";
	$result = $wowdb->query( $query );

	if (!$result)
	{
		die_quietly('There was a database error trying to fetch matching items. MySQL said: <br />' . $wowdb->error(),$act_words['search'],basename(__FILE__),__LINE__,$query);
	}

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';
		$rc = 0;
		while( $data = $wowdb->fetch_assoc($result) )
		{
			$row_st = (($rc%2)+1);
			$char_url = makelink('char&amp;member=' . $data['member_id']);

			if( $cid != $data['member_id'] )
			{
				if( $cid != '' )
				{
					$output .= "</table>\n" . border('sblue','end') . "<br />\n";
				}
				$output .= border('sblue','start','<a href="' . $char_url . '">' . $data['name'] . '</a>') . '<table cellpadding="0" cellspacing="0" width="600">';
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
    <td class="membersRowRight1">No ' . $act_words['items'] . '</td>
  </tr>'."</table>\n" . border('sblue','end');
	}


	$output .= "<br /><hr />\n";

	$output .= '<a name="recipes"></a><div class="headline_1"><a href="#top">' . $act_words['recipes'] . '</a></div>';

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
	       . " FROM `" . ROSTER_RECIPESTABLE . "` AS r,`" . ROSTER_PLAYERSTABLE . "` AS p"
	       . " WHERE `r`.`member_id` = `p`.`member_id` AND $searchstring"
	       . " ORDER BY `p`.`name` ASC, `r`.`recipe_name` ASC;";
	$result = $wowdb->query( $query );

	if( !$result )
	{
		die_quietly('There was a database error trying to fetch matching recipes. MySQL said: <br />' . $wowdb->error(),$act_words['search'],basename(__FILE__),__LINE__,$query);
	}

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';

		$rc = 0;
		while( $data = $wowdb->fetch_assoc( $result ) )
		{
			$row_st = (($rc%2)+1);

			$char_url = makelink('char-recipes&amp;member=' . $data['member_id']);
			if( $cid != $data['member_id'] )
			{
				if( $cid != '' )
				{
					$output .= "</table>\n" . border('syellow','end') . "<br />\n";
				}
				$output .= border('syellow','start','<a href="' . $char_url . '">' . $data['name'] . '</a>') . '<table border="0" cellpadding="0" cellspacing="0" width="600">
  <tr>
    <th colspan="2" class="membersHeader">' . $act_words['item'] . '</th>
    <th class="membersHeaderRight">' . $act_words['reagents'] . '</th>
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
    <td class="membersRowRight1">No ' . $act_words['recipes'] . "</td>
  </tr>
</table>\n".border('sblue','end');
	}
}

print $output;