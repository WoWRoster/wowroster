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
include_once (ROSTER_BASE.'roster_header.tpl');

require_once ROSTER_LIB.'item.php';
require_once ROSTER_LIB.'recipes.php';


$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');

print "<br />\n";

$search = (isset($_GET['s']) ? $_GET['s'] : '');

$input_form = '<form action="'.makelink().'" method="get">
	'.linkform().'
	<input type="text" class="wowinput192" name="s" value="'.$search.'" size="30" maxlength="30" />
	<input type="submit" value="search" />
</form>
';

print messagebox($input_form,$act_words['find'],'sgreen');

if( !empty($search) )
{
	// Set a ank for link to top of page
	echo '<a name="top">&nbsp;</a>
<div style="color:white;text-align;center">
  <a href="#items">'.$act_words['items'].'</a>
  - <a href="#recipes">'.$act_words['recipes'].'</a>
</div><br /><br />';

	print '<a name="items"></a><a href="#top">'.$act_words['items'].'</a>';

	$query="SELECT players.name,players.server,items.* FROM `".ROSTER_ITEMSTABLE."` items,`".ROSTER_PLAYERSTABLE."` players WHERE items.member_id = players.member_id AND items.item_name LIKE '%$search%' ORDER BY players.name ASC";
	$result = $wowdb->query( $query );

	if (!$result)
	{
		die_quietly('There was a database error trying to fetch matching items. MySQL said: <br />'.$wowdb->error(),'Search',basename(__FILE__),__LINE__,$query);
	}

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';
		$rc = 0;
		while ($data = $wowdb->fetch_assoc( $result ))
		{
			$row_st = (($rc%2)+1);
			$char_url = makelink('char&amp;member='.$data['member_id']);

			if ( $cid != $data['member_id'] )
			{
				if ( $cid != '' )
				{
					print "</table>\n".border('sblue','end')."<br />\n";
				}
				print border('sblue','start','<a href="'.$char_url.'">'.$data['name'].'</a>').'<table cellpadding="0" cellspacing="0" width="600">';
			}

			print '  <tr>
    <td width="45" valign="top" class="membersRow'.$row_st.'">';
			$item = new item($data);
			echo $item->out();
			print "</td>\n";
			print '    <td valign="middle" class="membersRowRight'.$row_st.' overlib_maintext" style="white-space:normal;">';

			$first_line = true;
			$tooltip_out = '';
			$data['item_tooltip'] = stripslashes($data['item_tooltip']);

			print colorTooltip($data['item_tooltip'],$data['item_color']);

			print "</td>\n  </tr>\n";
			$cid = $data['member_id'];
			$rc++;
		}

		if ( $cid != '' )
		{
			print "</table>\n".border('sblue','end')."<br />\n";
		}
	}
	else
	{
		print border('sblue','start').'<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No '.$act_words['items'].'</td>
  </tr>'."</table>\n".border('sblue','end');
	}


	print "<br /><hr />\n";

	print '<a name="recipes"></a><a href="#top">'.$act_words['recipes'].'</a>';

	//$query="SELECT players.name,players.server,recipes.* FROM recipes,players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' OR recipes.recipe_tooltip LIKE '%$search%' OR recipes.reagents LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$query="SELECT players.name,players.server,recipes.* FROM `".ROSTER_RECIPESTABLE."` recipes,`".ROSTER_PLAYERSTABLE."` players WHERE recipes.member_id = players.member_id AND recipes.recipe_name LIKE '%$search%' ORDER BY players.name ASC, recipes.recipe_name ASC";
	$result = $wowdb->query( $query );

	if (!$result)
	{
		die_quietly('There was a database error trying to fetch matching recipes. MySQL said: <br />'.$wowdb->error(),'Search',basename(__FILE__),__LINE__,$query);
	}

	if( $wowdb->num_rows($result) != 0 )
	{
		$cid = '';

		$rc = 0;
		while ($data = $wowdb->fetch_assoc( $result ))
		{
			$row_st = (($rc%2)+1);

			$char_url = makelink('char-recipes&amp;member='.$data['member_id']);
			if ( $cid != $data['member_id'] )
			{
				if ( $cid != '' )
				{
					print "</table>\n".border('syellow','end')."<br />\n";
				}
				print border('syellow','start','<a href="'.$char_url.'">'.$data['name'].'</a>').'<table border="0" cellpadding="0" cellspacing="0" width="600">
  <tr>
    <th colspan="2" class="membersHeader">'.$act_words['item'].'</th>
    <th class="membersHeaderRight">'.$act_words['reagents'].'</th>';
			}

			print '<tr><td width="45" valign="top" align="center" class="membersRow'.$row_st.'">';

			$recipe = new recipe($data);
			echo $recipe->out();
				print '</td>'."\n";
			print '<td valign="top" class="membersRow'.$row_st.'" style="white-space:normal;">';

			$first_line = true;
			$tooltip_out = '';
			$data['item_tooltip'] = stripslashes($data['recipe_tooltip']);

			print colorTooltip($data['item_tooltip'],$data['item_color']);

			print '</td>'."\n".'<td class="membersRowRight'.$row_st.'" width="50%" valign="top">';
			echo "<span class=\"tooltipline\" style=\"color:#ffffff\">".str_replace('<br>','<br />',$data['reagents'])."</span><br /><br />";
			print "</td></tr>\n";
			$cid = $data['member_id'];
			$rc++;
		}

		if ( $cid != '' )
		{
			print "</table>\n".border('syellow','end')."<br />\n";
		}
	}
	else
	{
		print border('sblue','start').'<table cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td class="membersRowRight1">No '.$act_words['recipes'].'</td>
  </tr>'."</table>\n".border('sblue','end');
	}
}

include_once (ROSTER_BASE.'roster_footer.tpl');
