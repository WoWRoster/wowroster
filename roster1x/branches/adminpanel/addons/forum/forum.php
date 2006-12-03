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

$uid = 0; //for development;

//print_r($roster_conf);

$fid = $_POST['fid'];
if (isset($_REQUEST['fid']) && $fid == "")
$fid = $_REQUEST['fid'];
if ($fid == "")$fid = 0;

if (user_forum_hasaccess($uid,$fid,'r'))
	showForum();
else
	message('Access Denied','Sorry, you don\'t have access to this forum.  Please contact a forum administrator.');


function showForum(){
	global $fid,$uid, $wowdb;
	$img = ROSTER_FORUM_IMG_URL;
	$acc = ROSTER_FORUM_ACCESS_URL;
	
	// get forum info into:
	// $forumName
	// $forumDDescription
	if ($currentForumId != 0)
	{
		$sql = 'SELECT * FROM `'.ROSTER_FORUM_TABLE_FORUMS."` WHERE `id` = $fid";
		$result = $wowdb->query($sql);
		if( $result && $wowdb->num_rows($result) != 0 )
		{
			$forumData = $wowdb->fetch_array($result);
			$forumName = $forumData['name'];
			$forumDescription = $forumData['description'];
			$wowdb->free_result($result);
		}
		else
		{
			message('DB Error',"Could not get Forum Data<br>Query: $sql<br />MySQL said: ".$wowdb->error());
		}
	}
	else
	{
		$forumName = "Forum Index";
		$forumDescription = "This is the root of the forums.";
	}
	
	// get subforum info into:
	// $subforums
	$sql = 'SELECT * FROM `'.ROSTER_FORUM_TABLE_FORUMS."` WHERE `pid` = $fid";
	$result = $wowdb->query($sql);
	if( $result )
	{
		$count = 0;
		while($forumData = $wowdb->fetch_array($result))
		{
			if (user_forum_hasaccess(0,0,'r'))
			{
				$subforums[$count]['id'] = $forumData['id'];
				$subforums[$count]['name'] = $forumData['name'];
				$subforums[$count]['description'] = $forumData['description'];
				$count++;
			}
		}
		$wowdb->free_result($result);
	}
	else
	{
		message('DB Error',"Could not get Forum Data<br>Query: $sql<br />MySQL said: ".$wowdb->error());
	}
	
	
	

		
	// get forum threads into:
	// $threads
	$sql = 'SELECT * FROM `'.ROSTER_FORUM_TABLE_THREADS."` WHERE `fid` = $fid";
	$result = $wowdb->query($sql);
	if( $result )
	{
		$count = 0;
		while($forumData = $wowdb->fetch_array($result))
		{
			$threads[$count]['id'] = $forumData['id'];
			$threads[$count]['name'] = $forumData['name'];
			$threads[$count]['flags'] = $forumData['flags'];
			$count++;
		}
		$wowdb->free_result($result);
	}
	else
	{
		message('DB Error',"Could not get Forum Data<br>Query: $sql<br />MySQL said: ".$wowdb->error());
	}
	
	// begin output
	echo border('sgray','start',"$forumName - <i>$forumDescription</i>")."
	<img src='$img/forum-menu-newtopic.gif'><img src='$img/forum-menu-search.gif'>
	<table width='100%'>\n";
	$strip_count = 1;
	
	// echo the subforums
	if (count($subforums) > 0)
		foreach( $subforums as $forum )
		{
			$stripe_class = 'membersRowColor'.( ( ++$strip_count % 2 ) + 1 );
			print "\t<tr class=\"$stripe_class\">\n";
			print "\t\t<td class=\"membersRowCell\"><a href='$acc&fid=".$forum['id']."'><img border='0' src='$img/folder.gif'></a></td>\n";
			print "\t\t<td class=\"membersRowRightCell\"><a href='$acc&fid=".$forum['id']."'>".$forum['name']."</a> - <i>".$forum['description']."</i></td>\n";
			print "\t</tr>\n";
		}
	// echo the threads
	if (count($threads) > 0)
		foreach( $threads as $thread )
		{
			$flagimages = genFlagImg($thread['flags']);
			
			$stripe_class = 'membersRowColor'.( ( ++$strip_count % 2 ) + 1 );
			print "\t<tr class=\"$stripe_class\">\n";
			print "\t\t<td class=\"membersRowCell\"><a href='$acc&tid=".$thread['id']."'>$flagimages</a></td>\n";
			print "\t\t<td class=\"membersRowRightCell\"><a href='$acc&tid=".$thread['id']."'><img hspace='3' align='texttop' border='0' src='$img/square.gif'>".$thread['name']."</a></td>\n";
			print "\t</tr>\n";
		}
		
	// end output
	echo "</table>".border('sgray','end').'<br />';
}


#--[ FUNCTIONS ]-----------------------------------------------------------

// this takes the thread flags and makes the corresponding <img tags for stickies/announcements etc.
function genFlagImg($threadFlags){
	$img = ROSTER_FORUM_IMG_URL;
	if (strpos($threadFlags,'s') === true)
	{
		$output .= "<img src='$img/sticky.gif' alt='sticky'>&nbsp;";
	}
	if (strpos($threadFlags,'l') === true)
	{
		$output .= "<img src='$img/lock-icon.gif' alt='locked'>&nbsp;";
	}
	return $output;
}
// forum access function
function user_forum_hasaccess($uid,$fid,$flags){
	return true;
}
// Debug function
function debugMode( $line,$message,$file=null,$config=null,$message2=null )
{
	if( is_numeric($line) )
		$line -= 1;

	if( !empty($file) )
	{
		$file = "[<span style=\"color:green\">$file</span>]";
	}
	$string = "<strong><span style=\"color:red\">Error</span></strong><br /><br />\n";
	//$string .= " - [<a href=\"../../gd_info.php\">GD Info</a>]<br /><br />\n";
	$string .= "<span style=\"color:blue\">Line $line:</span> $message $file\n<br /><br />\n";
	if( $config )
	{
		$string .= "Check the config file\n<br />\n";
	}
	if( !empty($message2) )
	{
		$string .= "$message2\n";
	}
	exit($string);
}
function message($title,$message){
	echo border('sgray','start',"$title");
	echo $message;
	echo border('sgray','end');
}

?>