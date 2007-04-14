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

if( isset($_POST['doit']) && ($_POST['doit'] == 'doit') )
{
	$query = "TRUNCATE `roster_config`;";
	$wowdb->query($query);

	$query = "TRUNCATE `roster_menu_button`";
	$wowdb->query($query);

	$query = "TRUNCATE `roster_menu`;";
	$wowdb->query($query);

    $db_data_file      = ROSTER_BASE . 'install'.DIR_SEP.'db'.DIR_SEP.'mysql_data.sql';

    // Parse the data file and populate the database tables
    $sql = @fread(@fopen($db_data_file, 'r'), @filesize($db_data_file));
    $sql = preg_replace('#renprefix\_(\S+?)([\s\.,]|$)#', $db_prefix . '\\1\\2', $sql);

    $sql = parse_sql($sql, $DBALS['mysql']['delim']);

    $sql_count = count($sql);
    for ( $i = 0; $i < $sql_count; $i++ )
    {
        $wowdb->query($sql[$i]);
		// Added failure checks to the database transactions
		/*if ( !($wowdb->query($sql[$i])) )
		{
			$tpl->message_die('Install Failed <b>' . $db_name . '</b> as <b>' . $db_user . '@' . $db_host . '</b><br /><br /><a href="install.php">Restart Installation</a>', 'Database Error');
		}*/
    }
    unset($sql);

	$body .= messagebox('Configuration has been reset. Please remember to configure the guild name and server before attempting to upload guild data',$act_words['roster_cp']);
	return;
}

$body .= $roster_login->getMessage().'<br />
<form action="'.makelink().'" method="post" enctype="multipart/form-data" id="conf_change_pass" onsubmit="return confirm(\'This is irreversible. Do you really want to continue?\') && submitonce(this)">
<input type="hidden" name="doit" value="doit" />
	'.border('sred','start','Config Reset').'
	  <table class="bodyline" cellspacing="0" cellpadding="0">
	    <tr>
	      <td class="membersRowRight1" colspan="2"><div style="white-space:normal;">
			This will completely reset your roster configuration. All data in the roster configuration table, including your menu configuration, will be permanently removed, and the default values will be restored. Only the guild, officer, and admin passwords will be saved. To proceed, enter your admin password below and click on \'Proceed\'.
		  </div></td>
	    </tr>
	    <tr>
	      <td class="membersRow2">Password:</td>
	      <td class="membersRowRight2"><input class="wowinput192" type="password" name="password" value="" /></td>
	    </tr>
	    <tr>
	      <td colspan="2" class="membersRowRight1" valign="bottom"><div align="center">
		    <input type="submit" value="Proceed" /></div></td>
	    </tr>
	  </table>
	'.border('sred','end').'
	</form>';



/**
* Parse multi-line SQL statements into a single line
*
* @param    string  $sql    SQL file contents
* @param    char    $delim  End-of-statement SQL delimiter
* @return   array
*/
function parse_sql($sql, $delim)
{
    if ( $sql == '' )
    {
        die('Could not obtain SQL structure/data');
    }

    $retval     = array();
    $statements = explode($delim, $sql);
    unset($sql);

    $linecount = count($statements);
    for ( $i = 0; $i < $linecount; $i++ )
    {
        if ( ($i != $linecount - 1) || (strlen($statements[$i]) > 0) )
        {
            $statements[$i] = trim($statements[$i]);
            $statements[$i] = str_replace("\r\n", '', $statements[$i]) . "\n";

            // Remove 2 or more spaces
            $statements[$i] = preg_replace('#\s{2,}#', ' ', $statements[$i]);

            $retval[] = trim($statements[$i]);
        }
    }
    unset($statements);

    return $retval;
}