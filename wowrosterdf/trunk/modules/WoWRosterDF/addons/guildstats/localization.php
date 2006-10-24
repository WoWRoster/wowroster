<?php
/******************************
 * worldofwarcraftguilds.com
 * Copyright 2006
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

$localeFilePath = ROSTER_BASE . 'addons' . DIR_SEP . 'guildstats' . DIR_SEP . 'localization' . DIR_SEP;

if ($handle = opendir($localeFilePath))
{
	while (false !== ($file = readdir($handle)))
	{
		if ($file != '.' && $file != '..')
		{
			if( file_exists($localeFilePath.$file) && !is_dir($localeFilePath.$file) )
			{
				require_once ($localeFilePath.$file);
			}
		}
	}
}

?>