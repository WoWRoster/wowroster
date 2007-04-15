<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Shows added and removed members
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

if( !isset($_REQUEST['d']) || empty($_REQUEST['d']) )
	$get_d = 0;
else
	$get_d = intval($_REQUEST['d']);

if( !isset($_REQUEST['s']) || empty($_REQUEST['s']) )
	$get_s = '';
else
	$get_s = $_REQUEST['s'];

// Check for start for pagination
$start = (isset($_REQUEST['start']) ? $_REQUEST['start'] : 0);

require( ROSTER_BASE.'roster_header.tpl' );

$roster_menu = new RosterMenu;
print $roster_menu->makeMenu('main');

$query =
"SELECT *,
 DATE_FORMAT( `update_time`, '".$act_words['timeformat']."' ) AS 'date'
 FROM `".ROSTER_MEMBERLOGTABLE."`";


$chkd['n'] = 1;
$chkd['c'] = 1;
$chkd['l'] = 1;
$chkd['r'] = 1;
$chkd['t'] = 1;
$chkd['d'] = 1;

switch($get_s)
{
	case 'name':
		if( $get_d )
		{
			$query .= ' ORDER BY `name` ASC';
			$chkd['n'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `name` DESC';
		}
	break;

	case 'class':
		if( $get_d )
		{
			$query .= ' ORDER BY `class` ASC';
			$chkd['c'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `class` DESC';
		}
	break;

	case 'level':
		if( $get_d )
		{
			$query .= ' ORDER BY `level` DESC';
			$chkd['l'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `level` ASC';
		}
	break;

	case 'title':
		if( $get_d )
		{
			$query .= ' ORDER BY `guild_rank` ASC';
			$chkd['r'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `guild_rank` DESC';
		}
	break;

	case 'type':
		if( $get_d )
		{
			$query .= ' ORDER BY `type` DESC';
			$chkd['t'] = 0;
		}
		else
		{
			$query .= ' ORDER BY `type` ASC';
		}
	break;

	case 'date':
		if( $get_d )
		{
			$query .= ' ORDER BY `update_time` DESC, `type` ASC';
		}
		else
		{
			$query .= ' ORDER BY `update_time` ASC, `type` ASC';
		}
	break;

	default:
		$query .= ' ORDER BY `update_time` DESC, `type` ASC';
		break;
}

$content = '';

$total_sql = "SELECT `log_id` FROM `".ROSTER_MEMBERLOGTABLE."`;";
$result = $wowdb->query($total_sql) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$total_sql);

$max = $wowdb->num_rows($result);

$wowdb->free_result($result);

$sort_part = ($get_s != '' ? "&amp;s=$get_s" : '');
$sort_part .= ($get_d != 0 ? "&amp;d=$get_d" : '');

if ($start > 0)
{
	$prev = '<a href="'.makelink('memberlog&amp;start=0'.$sort_part).'">&lt;&lt;</a> <a href="'.makelink('memberlog&amp;start='.max($start-30,0).$sort_part).'">&lt;</a> ';
}
else
{
	$prev = '';
}

if (($start+30) < $max)
{
	$lastpage = ceil($max/30)*30;
	$listing = ' <small>['.$start.' - '.($start+30).'] -- '.$max.'</small>';
	$next = ' <a href="'.makelink('memberlog&amp;start='.($start+30).$sort_part).'">&gt;</a> <a href="'.makelink('memberlog&amp;start='.($lastpage-30).$sort_part).'">&gt;&gt;</a>';
}
else
{
	$listing = ' <small>['.$start.' - '.($max).'] -- '.$max.'</small>';
	$next = '';
}

$borderTop = border('sgreen', 'start', $prev.$act_words['memberlog'].$listing.$next);
$tableHeader = '<table width="100%" cellspacing="0" class="bodyline">'."\n";

$borderBottom = border('sgreen', 'end');
$tableFooter = '</table>'."\n";


$query .= ' LIMIT '.$start.' , 30';
$result = $wowdb->query($query) or die_quietly($wowdb->error(),'Database Error',basename(__FILE__),__LINE__,$query);

$striping_counter = 0;
if( $wowdb->num_rows($result) > 0 )
{
	$tableHeaderRow = '	<tr>
	<th class="membersHeader"><a href="'.makelink('memberlog&amp;start='.$start.'&amp;s=name&amp;d='.$chkd['n']).'">'.$act_words['name'].'</a></th>
	<th class="membersHeader"><a href="'.makelink('memberlog&amp;start='.$start.'&amp;s=class&amp;d='.$chkd['c']).'">'.$act_words['class'].'</a></th>
	<th class="membersHeader"><a href="'.makelink('memberlog&amp;start='.$start.'&amp;s=level&amp;d='.$chkd['l']).'">'.$act_words['level'].'</a></th>
	<th class="membersHeader"><a href="'.makelink('memberlog&amp;start='.$start.'&amp;s=title&amp;d='.$chkd['r']).'">'.$act_words['title'].'</a></th>
	<th class="membersHeader"><a href="'.makelink('memberlog&amp;start='.$start.'&amp;s=type&amp;d='.$chkd['t']).'">'.$act_words['type'].'</a></th>
	<th class="membersHeader"><a href="'.makelink('memberlog&amp;start='.$start.'&amp;s=date&amp;d='.$chkd['d']).'">'.$act_words['date'].'</a></th>
	<th class="membersHeaderRight">'.$act_words['note'].'</th>
	</tr>'."\n";

	$body = '';
	while( $row = $wowdb->fetch_assoc($result) )
	{
		foreach( $row as $key => $value )
		{
			$row[$key] = $wowdb->escape($value);
		}

		if( $row['type'] == 0 )
			$row['type'] = '<span class="red">'.$act_words['removed'].'</span>';
		else
			$row['type'] = '<span class="green">'.$act_words['added'].'</span>';

		if( !empty($row['note']) )
			$row['note'] = '<img src="'.$roster_conf['img_url'].'note.gif" style="cursor:help;" class="membersRowimg" alt="'.$act_words['note'].'" '.makeOverlib(stripslashes($row['note']),$act_words['note'],'',1).' />';
		else
			$row['note'] = '<img src="'.$roster_conf['img_url'].'no_note.gif" class="membersRowimg" alt="'.$act_words['note'].'" />';

		$body .= '<tr>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['name'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['class'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['level'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['guild_title'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['type'].'</td>'."\n";
		$body .= '	<td class="membersRow'. (($striping_counter % 2) +1) .'">'.$row['date'].'</td>'."\n";
		$body .= '	<td class="membersRowRight'. (($striping_counter % 2) +1) .'">'.$row['note'].'</td>'."\n";
		$body .= '</tr>'."\n";

		$striping_counter++;
	}
}
else
{
	$body = $act_words['no_memberlog'];
}

$content .= $borderTop;
$content .= $tableHeader;
$content .= $tableHeaderRow;
$content .= $body;
$content .= $tableFooter;
$content .= $borderBottom;

if ($start > 0)
	$content .= $prev;

if (($start+30) < $max)
{
	$content .= '['.$start.' - '.($start+30).'] -- '.$max;
	$content .= $next;
}
else
	$content .= '['.$start.' - '.($max).'] -- '.$max;


$wowdb->closeQuery($result);
$wowdb->free_result($result);


print $content;

require( ROSTER_BASE.'roster_footer.tpl' );
