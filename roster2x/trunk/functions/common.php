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

// ----[ Prevent Direct Access to this file ]-------------------
if( !defined('ROSTER_INCLUDED') )
{
	exit("You can't access this file directly!");
}


/**
 * Format a tooltip and stick it in the global array $tooltips[]
 *
 * @param array $data Item data
 * @param string $varname ID name of the tooltip
 * @param string $locale Locale
 * @return	string	cleaned tooltip ID name
 */
function formatTooltip( $data , $varname , $locale=null )
{
	if( is_null($locale) )
	{
		$locale = GetConfigValue('lang');
	}

	$varname = str_replace(' ','',$varname );
	$varname = str_replace('(','',$varname );
	$varname = str_replace(')','',$varname );
	$varname = str_replace('-','',$varname );
	$varname = str_replace("'",'',$varname );
	$varname = str_replace(':','',$varname );

	$first_line = true;
	$newline = '';
	$varstr = '';

	foreach( explode("\n", $data['item_tooltip']) as $line )
	{
		$line = str_replace('<br />','',$line );
		$line = str_replace('<br>','',$line );
		$line = str_replace("'","\\'",$line );
		$line = str_replace('\\"','&quot;', $line);

		if( $first_line )
		{
			$color = substr( $data['item_color'], 2, 6 ).';font-weight: bold';
			$first_line = false;

			$linkline = preg_replace('|\\>|','&gt;', $line );
			$linkline = preg_replace('|\\<|','&lt;', $linkline );
			$linkline = '<span style="color:#'.$color.';">'.$linkline.'</span><br />';
		}
		else
		{
			if( substr( $line, 0, 2 ) == '|c' )
			{
				$color = substr( $line, 4, 6 );
				$line = substr( $line, 10, -2 );
			}
			elseif ( strpos( $line, getLocaleValue('tooltip_use',$locale) ) === 0 )
			{
				$color = '00ff00';
			}
			elseif ( strpos( $line, getLocaleValue('tooltip_requires',$locale) ) === 0 )
			{
				$color = 'ff0000';
			}
			elseif ( strpos( $line, getLocaleValue('tooltip_reinforced',$locale) ) === 0 )
			{
				$color = '00ff00';
			}
			elseif ( strpos( $line, getLocaleValue('tooltip_equip',$locale) ) === 0 )
			{
				$color = '00ff00';
			}
			elseif ( strpos( $line, getLocaleValue('tooltip_chance',$locale) ) === 0 )
			{
				$color = '00ff00';
			}
			elseif ( strpos( $line, getLocaleValue('tooltip_enchant',$locale) ) === 0 )
			{
				$color = '00ff00';
			}
			elseif ( strpos( $line, getLocaleValue('tooltip_soulbound',$locale) ) === 0 )
			{
				$color = '00bbff';
			}
			elseif ( strpos( $line, getLocaleValue('tooltip_set',$locale) ) === 0 )
			{
				$color = '00ff00';
			}
			elseif ( strpos( $line, '&quot;' ) === 0 )
			{
				$color = 'ffd517';
			}
			elseif ( strpos( $line, '<' ) === 0 )
			{
				$color = '00ff00';
			}
		}
		$line = preg_replace('|\\>|','&gt;', $line );
		$line = preg_replace('|\\<|','&lt;', $line );

		if( strpos($line,"\t") )
		{
			$line = str_replace("\t",'</td><td align="right">', $line);
			$newline = '<table width="180" cellspacing="0" cellpadding="0"><tr><td>'.$line.'</td></tr></table>';
		}
		elseif( !empty($line) )
		{
			if( !empty($color) )
			{
				$newline = '<span style="color:#'.$color.';">'.$line.'</span><br />';
			}
			else
			{
				$newline = $line.'<br />';
			}
		}
		else
		{
			$newline = '<br />';
		}

		$varstr .= $newline;
		$newline = '';
		$color = '';
	}
	//write to the tooltips global variable
	setTooltip($varname,$varstr);
	setTooltip($varname.'_link',$linkline.'<a href="http://www.thottbot.com/index.cgi?i='.urlencode(utf8_decode($data['item_name'])).'" target="_itemlink">Thottbot</a> : <a href="http://wow.allakhazam.com/search.html?q='.urlencode(utf8_decode($data['item_name'])).'" target="_itemlink">Allakhazam</a>');
}

/**
* Format a tooltip for talents and stick it in the global array $tooltips[]
*
* @param	string	$tooltip	tooltip string
* @param	string	$varname	ID name of the tooltip (for use with overlib)
* @param	string	$locale		Locale of character
* @return	string	cleaned tooltip ID name
*/
function formatTalentTooltip( $tooltip , $varname , $locale=null )
{
	if( is_null($locale) )
	{
		$locale = GetConfigValue('lang');
	}

	$first_line = true;
	$newline = '';
	$varstr = '';

	$varname = str_replace(' ','',$varname );
	$varname = str_replace('(','',$varname );
	$varname = str_replace(')','',$varname );
	$varname = str_replace('-','',$varname );
	$varname = str_replace("'",'',$varname );
	$varname = str_replace(':','',$varname );

	foreach( explode("\n", $tooltip) as $line )
	{
		$line = str_replace('<br />','',$line );
		$line = str_replace('<br>','',$line );
		$line = str_replace("'","\\'",$line );
		$line = str_replace('"','&quot;', $line);

		if( $first_line )
		{
			$color = 'ffffff;font-weight: bold';
			$first_line = false;
		}
		else
		{
			if( strpos( $line, getLocaleValue('tooltip_rank',$locale) ) === 0 )
			{
				$color = 'ffffff';
			}
			elseif( strpos( $line, getLocaleValue('tooltip_next_rank',$locale) ) === 0 )
			{
				$color = 'ffffff';
			}
			elseif( strpos( $line, getLocaleValue('tooltip_requires',$locale) ) === 0 )
			{
				$color = 'ff0000';
			}
			else
			{
				$color = 'ffdd00';
			}
		}
		$line = preg_replace('|\\>|','&gt;', $line );
		$line = preg_replace('|\\<|','&lt;', $line );

		if( !empty($line) )
		{
			$newline = '<span style="color:#'.$color.';">'.$line.'</span><br />';
		}
		else
		{
			$newline = '<br />';
		}

		$varstr .= $newline;
		$newline = '';
	}
	//write to the tooltips global variable
	setTooltip($varname,$varstr);

	return $varname;
}

/**
 * prep a tooltip to be inserted into the db
 * @author six
 */
function prep_tooltip_for_db($tooltip)
{
    if(!is_array($tooltip))
    {
	$tooltip = explode('<br>', $tooltip);
    }
    foreach($tooltip as $this_tip){
	$tip[]=trim($this_tip);
    }
    $tooltip = implode("\n", $tip);
    return($tooltip);
}

/**
 * Obviously, this function cleans any string passed to it of special characters
 *
 * @param string $data Any data we want/need cleaned
 * @return string Cleaned String
 */
function clean_string( $data )
{
       return (htmlspecialchars(stripslashes($data)));
}

/**
 * Clean replacement for die(), outputs a message with debugging info if needed and ends output
 *
 * @param string $text Text to display on error page
 * @param string $title Title to place on web page
 * @param string $file Filename to display
 * @param string $line Line in file to display
 * @param string $sql Any SQL text to display
 */
function die_quietly( $text='', $title='', $file='', $line='', $sql='' )
{
    global $tpl;

    if( !is_object($tpl) )
    {
    	echo "$text\n<br />FILE: $file\n<br />LINE: $line\n<br />SQL: $sql";
    	exit();
    }
    elseif( !$tpl->template_exists('die_page.tpl') )
    {
    	echo "$text\n<br />FILE: $file\n<br />LINE: $line\n<br />SQL: $sql";
    	exit();
    }
    else
    {
    	if( !empty($title) )
    	{
    		$tpl->assign( 'page_title', $title );
    	}
    	if( !empty($text) )
    	{
    		$tpl->assign( 'text', $text );
    	}
	   	if( !empty($sql) )
	   	{
	   		$tpl->assign( 'sql',sql_highlight($sql) );
	   	}
	   	if( !empty($file) )
	   	{
	   		$tpl->assign( 'file',$file );
	   	}
	   	if( !empty($line) )
	   	{
	   		$tpl->assign( 'line',$line );
	   	}

		$tpl->display( 'die_page.tpl' );
	    exit();
    }
}


/**
 * Makes a tootip and places it into the tooltip array
 *
 * @param string $var
 * @param string $content
 */
function setTooltip( $var , $content )
{
	global $tooltips;

	$tooltips += array($var=>$content);
}


/**
 * Gathers all tootips and places them into javascript variables
 *
 * @param array $tooltipArray
 * @return string Tooltips placed in javascript variables
 */
function getAllTooltips()
{
	global $tooltips;

	if( is_array($tooltips) )
	{
		$ret_string = "<script type=\"text/javascript\">\n";
		foreach ($tooltips as $var => $content)
		{
			$ret_string .= "\n\tvar $var = '$content';";
		}
		$ret_string .= "\n</script>";

		return $ret_string;
	}
	else
	{
		return '';
	}
}

/**
 * Makes a sql string and places it into the tooltip array
 *
 * @param string $var
 * @param string $content
 */
function setSqlQuery( $sql )
{
	global $sqlqueries;

	$sqlqueries[] = $sql;
}


/**
 * Gathers all the sql queries and groups them together
 *
 * @return string
 */
function getSqlQueries()
{
	global $sqlqueries;

    $ret_string = '';
	if( is_array($sqlqueries) )
	{
		foreach ($sqlqueries as $val)
		{
			$ret_string .= "\n\t$val;";
		}

		return $ret_string;
	}
	else
	{
		return null;
	}
}

/**
* Highlight certain keywords in a SQL query
*
* @param string $sql Query string
* @return string Highlighted string
*/
function sql_highlight( $sql )
{
	// Make table names bold
	$sql = preg_replace('/' . GetConfigValue('db_prefix') .'(\S+?)([\s\.,]|$)/', '<b>' . GetConfigValue('db_prefix') . "\\1\\2</b>", $sql);

	// Non-passive keywords
	$red_keywords = array('/(INSERT INTO)/','/(UPDATE\s+)/','/(DELETE FROM\s+)/', '/(CREATE TABLE)/', '/(IF (NOT)? EXISTS)/',
						  '/(ALTER TABLE)/', '/(CHANGE)/');

	$red_replace = array_fill(0, sizeof($red_keywords), '<span style="color:#FF0000"\\1</span>');
	$sql = preg_replace( $red_keywords, $red_replace, $sql );


	// Passive keywords
	$green_keywords = array('/(SELECT)/','/(FROM)/','/(WHERE)/','/(LIMIT)/','/(ORDER BY)/','/(GROUP BY)/',
							'/(\s+AND\s+)/','/(\s+OR\s+)/','/(\s+ON\s+)/','/(BETWEEN)/','/(DESC)/','/(LEFT JOIN)/');

	$green_replace = array_fill(0, sizeof($green_keywords), '<span style="color:#00FF00">\\1</span>');
	$sql = preg_replace( $green_keywords, $green_replace, $sql );

	return $sql;
}

/**
 * Simple wrapper function to print_r with <pre> tags around it, for easier viewing in a browser.
 * We also detect whether we're in a browser or not, and if not, we just print_r as normal.
 * @author six
 */
function pre_r($data)
{
    // rudimentary check to see if the "thing" we are sending to accepts html or not
    if(isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'html'))
    {
		$pretag = '<pre>';
		$posttag = "</pre>\n";
    }
    else
    {
		$pretag = '';
		$posttag = '';
    }
    print($pretag);
    print_r($data);
    print("$posttag\n");
}

/**
 * Wrapper for printing data when debug is enabled, and not doing so if it's not. :)
 * Debug can be turned on either in the config file, or via debug=1 added to the URL GET parameters.
 * @author six
 */
function debug_print($string)
{
	global $roster_conf;

	if( ( isset($roster_conf['debug'])  && $roster_conf['debug']==true ) || (isset($_GET['debug']) && $_GET['debug']==1) )
	{
		if( isset($roster_conf['debug_to_error_log']) && $roster_conf['debug_to_error_log']==true )
		{
			error_log($string);
		}
		else
		{
			print($string);
		}
	}
}

/**
 * Searches the language file($locale) for a word($keyword)
 *
 * @param string $keyword	Key to search for
 * @param string $locale	Locale to use for return value (defaults to roster_conf[lang])
 * @return string	Returns localized word, or english word. Returns false on failure
 */
function getLocaleValue( $keyword , $locale=null )
{
	global $roster_wordings;

	if( is_null($locale) )
	{
		$locale = GetConfigValue('lang');
	}

	if( array_key_exists($keyword,$roster_wordings[$locale]) )
	{
		return $roster_wordings[$locale][$keyword];
	}
	elseif( array_key_exists($keyword,$roster_wordings['enUS']) )
	{
		return $roster_wordings['enUS'][$keyword];
	}
	else
	{
		return false;
	}
}

/**
 * Enter description here...
 *
 * @param string $word	Localized word needing translation
 * @param string $locale	Locale to search in (defaults to roster_conf[lang])
 * @return string	Returns english word. Returns null on failure
 */
function getEnglishValue( $word , $locale=null )
{
	global $roster_translate;

	if( is_null($locale) )
	{
		$locale = GetConfigValue('lang');
	}

	if( $newword_key = array_search($word,$roster_translate[$locale]) )
	{
		return $roster_translate['enUS'][$newword_key];
	}
	else
	{
		return null;
	}
}


/**
 * GetHandler(): get a handler reference, and make one if none exists.
 *
 * @author six
 * @param string handler type
 * @return object handler
 */
function GetHandler($type)
{
	static $handlers;
	static $known_handlers = array("recipes"=>"RecipeHandler",
								"reputation"=>"ReputationHandler",
								"skills"=>"SkillHandler",
								"spells"=>"SpellHandler",
								"talents"=>"TalentHandler");

	if(isset($handlers[$type]) && is_object($handlers[$type]))
	{
		// do nothing, the object exists, so we'll return it below.
	}
	elseif(isset($known_handlers[$type]))
	{
		debug_print("No ".$known_handlers[$type]." was available. Creating one.");
		$handlers[$type] = new $known_handlers[$type]();
	}
	else
	{
		debug_print("Request to create Handler of type $type, but that type is unknown.");
		return(false);
	}
	return($handlers[$type]);
}

/**
 * GetConfigValue(): get a configuration value based on the key supplied.
 * @param string key
 * @return mixed value
 */
function GetConfigValue($key)
{
	global $roster_conf;

	if(isset($roster_conf[$key]))
	{
		return($roster_conf[$key]);
	}
	else
	{
		debug_print("Request for config value '$key' not found.");
		return(false);
	}
}

?>
