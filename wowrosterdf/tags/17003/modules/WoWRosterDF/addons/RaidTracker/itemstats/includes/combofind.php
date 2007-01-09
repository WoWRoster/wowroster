<?php

include_once(dirname(__FILE__) . '/xmlhelper.php');
include_once(dirname(__FILE__) . '/urlreader.php');

define('ICON_LINK_PLACEHOLDER', '{ITEM_ICON_LINK}');
define('DEFAULT_ICON', 'INV_Misc_QuestionMark');

// The main interface to Thottbot
class InfoSite
{
	var $xml_helper;

	// Constructor
	function InfoSite()
	{
		$this->xml_helper = new XmlHelper();
	}

	// Cleans up resources used by this object.
	function close()
	{
		$this->xml_helper->close();
	}

	// Attempts to retrieve data for the specified item from Allakhazam, or if not found from Thottbot.
	function getItem($name)
	{
		// Ignore blank names.
		$name = trim($name);
		if (empty($name))
		{
			return null;
		}

		$item = array('name' => $name);

		// Perform the search.
		$data = itemstats_read_url('http://wow.allakhazam.com/search.html?q=' . urlencode($name));
		
		// Look for a name match in the search result.
		if (preg_match_all('#images/icons/(.*?)\.png(.*?)witem=(.*?)">#s', $data, $matches))
		{
			foreach ($matches[0] as $key => $match)
			{
				// Extract the item's ID from the match.
				$item_id = $matches[3][$key];

				// Retrieve the XML for this item from Allakhazam.
				$xml_data = itemstats_read_url('http://wow.allakhazam.com/dev/wow/item-xml.pl?witem=' . $item_id);

				// Parse out the name of this item from the XML and check if we have a match
				$xml_name = $this->xml_helper->parse($xml_data, 'name1');
				if (strcasecmp($item['name'], $xml_name) == 0)
				{
					// If we have a match, grab additional information about this item and break out of this loop.
					$item['name'] = $xml_name;
					$item['icon'] = $matches[1][$key];
					$item['link'] = 'http://wow.allakhazam.com/db/item.html?witem=' . $item_id;

					$item_found = true;
					break;
				}
			}
		}

		// If a match was found, retrieve additional info about it.
		if ($item_found)
		{
			// Parse out the display html of this item from the XML
			$item['html'] = $this->xml_helper->parse($xml_data, 'display_html');

			// Fix up the html a bit.
			$item['html'] = str_replace('"', '\'', $item['html']);
			$item['html'] = preg_replace('#(<[ /]*br[ /]*>)<[ /]*br[ /]*>#', '$1', $item['html']);
			$item['html'] = str_replace("<a href='http://wow.allakhazam.com/db/itemset", "<br><a class='set' href='http://wow.allakhazam.com/db/itemset", $item['html']);
			$item['html'] = str_replace("<a", "<a target='_new'", $item['html']);
			$item['html'] = preg_replace("#<a ([^>]*) class='itemcreatelink'>#", "<br><span class='itemeffectlink'>Creates: </span>\\0", $item['html']);

			// Extract the item color from the HTML.
			preg_match_all("#<span class='(.*?)'>#s", $item['html'], $matches);
			foreach ($matches[1] as $match) {
				if ($match!="iname" && $match!="") {
					$item['color'] = $match;
					break;
				}
			}
			// If this is a set, grab the set bonuses and add it to the html.
			$item_set_id = $this->xml_helper->parse($xml_data, 'setid');
			if (!empty($item_set_id) && ($item_set_id != '0'))
			{
				// Read the item set page.
				$data = itemstats_read_url('http://wow.allakhazam.com/db/itemset.html?setid=' . $item_set_id);

				// Extract the set bonus html from this page.
				preg_match('#Set Bonuses:</div>(.*?)<br/><div class#s', $data, $match);
				$item_set_bonuses = $match[1];

				// Fix up the html a bit
				$item_set_bonuses = str_replace('/db/spell.html', 'http://wow.allakhazam.com/db/spell.html', $item_set_bonuses);
				$item_set_bonuses = str_replace("<a", "<a class='setbonus' target='_new'", $item_set_bonuses);
				$item_set_bonuses = str_replace('"', '\'', $item_set_bonuses);
				$item_set_bonuses = preg_replace('#<[ /]*br[ /]*>$#','',$item_set_bonuses);
				$item_set_bonuses = "<span class='setbonus'>" . $item_set_bonuses . "</span>";

				// Insert the set bonus text into the display html;
				$item['html'] = preg_replace('#setid=(.*?)</span></a>#s', '\\0' . $item_set_bonuses, $item['html']);
			}

			// Build the final HTML by merging the template and the data we just prepared.
			$template_html = trim(file_get_contents(dirname(__FILE__) . '/../templates/popup.tpl'));
			$item['html'] = str_replace('{ITEM_HTML}', $item['html'], $template_html);
		}
		else
		{
			// Perform a search on Thottbot for the specified item name.
			$data = itemstats_read_url('http://www.thottbot.com/?i=' . urlencode($name));

			// First check if thottbot redirected us to the actual item page.
			$item_found = 	preg_match("#<input type='hidden' name='i' value=\"(.*?)\">#", $data, $match);
			// If we were redirected, extract the item ID and move on.
			if ($item_found)
			{
				$item_id = $match[1];
				//echo "Item found\n";
				//echo $item_id;

			}
			// If a match was not found, we're probably on the search page.
			else
			{
				//	echo "Item not found\n";
				// Look for search result that matches the item name.
				if (preg_match_all("#\?i=(.*?)'><script>i(.*?)class=quality(.*?)>(.*?)</span>(.*?)&nbsp;(.*?)1.(.*?).(.*?)</font>#", $data, $matches))
				{
					// Look for a match with a version of 1.xx.xx
					// echo "Match found on search results page\n";

					foreach ($matches[0] as $key => $match)
					{

						if($matches[4][$key] == $name) {

							// If we have a match, grab additional information about this item and break out of this loop.
							$item_id = $matches[1][$key];

							// Read the page specific to this item.
							$data = itemstats_read_url('http://www.thottbot.com/?i=' . $item_id);

							$item_found = true;
							break;
						}
					}
				}
			}

			// If the item was found, we got lots of work to do.  Start molding the HTML to the way we want it.
			if ($item_found)
			{
				// Grab the proper name of this item.
				preg_match("#<span class=quality[0-9]>(.*?)</span>#", $data, $match);
				$item['name'] = $match[1];
				// Grab the icon for this item.
				preg_match('#Icons/(.*?).jpg#', $data, $match);
				$item['icon'] = $match[1];

				// Create the link for this item.
				$item['link'] = 'http://www.thottbot.com/?i=' . $item_id;

				// Grab the display html of this item.
				if (preg_match("/<table class\=ttb(.*?)" . $item['name'] . "(.*?)<\/table>/", $data, $match))
				{
					$item['html'] = $match[0];

					// Extract the item color from the HTML.
					preg_match('/quality[1-9]/', $item['html'], $match);
					$item['color'] = $match[0];

					// Look for a set id for this item.
					if (preg_match("/href='\?set=(.*?)'/", $data, $match))
					{
						// Create a link to the set page.
						$set_link = 'http://www.thottbot.com/?set=' . $match[1];

						// Extract the name of the set for this item.
						if (preg_match('/Set: (.*?) \((.*?)\)/', $item['html'], $match))
						{
							$set_name = $match[1];
						}

						// Extract the set bonuses.
						if (preg_match('/worn:<br>(.*?)<center>/s', $data, $match))
						{
							$set_bonuses = str_replace("<a", "<a class='setbonus'", $match[1]);
						}

						// Build the set bonus html.
						$set_html  = "<tr><td colspan=2><a class='set' href='" . $set_link . "'>" . $set_name . "</a><span class='setbonus'><br />";
						$set_html .= $set_bonuses;
						$set_html .= '</span></td></tr>';

						// Fix the "Set" part of the HTML.
						$item['html'] = preg_replace('/Set: (.*?) \((.*?)\)/', '&nbsp;</td></tr>' . $set_html, $item['html']);
					}

					// Remove the "Sells for" part of the HTML.
					$item['html'] = preg_replace('/<tr><td colspan=2>Sells for(.*?)<\/td><\/tr>/', '', $item['html']);

					// Remove the "Item Level" part of the HTML.
					$item['html'] = preg_replace('/<tr><td colspan=2>Item Level(.*?)<\/td><\/tr>/', '', $item['html']);

					// Remove the "Durability" part of the HTML.
					$item['html'] = preg_replace('/<tr><td colspan=2>Durability(.*?)<\/td><\/tr>/', '', $item['html']);

					// Remove the "Source" part of the HTML.
					$item['html'] = preg_replace('/<tr><td colspan=2><small><font color=(.*?)>Source(.*?)<\/table>/', '</table>', $item['html']);
					$item['html'] = preg_replace('/<tr><td colspan=2><h6>(.*?)<\/h6><\/td><\/tr>/', '', $item['html']);

					// Replace the 'ttd' table style with 'wowitemt'.
					$item['html'] = str_replace('class=ttb', "class='wowitemt'", $item['html']);

					$item['html'] = str_replace('<table', '<table  cellspacing="0"', $item['html']);

					// Replace the 'spell' style with 'itemeffectlink'.
					$item['html'] = str_replace("class='spell'", "class='itemeffectlink'", $item['html']);

					// Remove any underline tags
					$item['html'] = preg_replace('/<[ \/]*u[ \/]*>/', '', $item['html']);

					// Fix up some last bits of the HTML.
					$item['html'] = str_replace('"', '\'', $item['html']);
					$item['html'] = str_replace("href='?", "href='http://www.thottbot.com/?", $item['html']);
					$item['html'] = str_replace("<a", "<a target='_new'", $item['html']);

					// Build the final HTML by merging the template and the data we just prepared.
					$template_html = trim(file_get_contents(dirname(__FILE__) . '/../templates/popup.tpl'));
					$item['html'] = str_replace('{ITEM_HTML}', $item['html'], $template_html);
				}
			}
			else
			{
				// If Allakhazam was busy or this item doesn't exist and this item isn't cached yet, create some error html.
				$item['color'] = 'greyname';
				$item['icon'] = DEFAULT_ICON;
	
				// Read the template html for an item.
				$template_html = trim(file_get_contents(dirname(__FILE__) . '/../templates/popup-error.tpl'));
				$item['html'] = str_replace('{INFO_SITE}', 'Allakhazam', $template_html);
			}
		}
		return $item;
	}
}
?>
