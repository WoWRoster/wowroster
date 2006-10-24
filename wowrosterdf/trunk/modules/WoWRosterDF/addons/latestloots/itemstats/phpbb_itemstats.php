<?php

include_once(dirname(__FILE__) . '/itemstats.php');

function itemstats_parse($message)
{
    $item_stats = new ItemStats();

    // Search for [item] tags, and replace them with HTML for the specified item.
    while (preg_match('#\[item\](.+?)\[/item\]#s', $message, $match))
    {
        // Grab the item name.
        $item_name = $match[1];

        // Get the proper name of this item.
        $item_name = $item_stats->getItemName($item_name, true);
        
        // Initialize the html.
        $item_html = '[' . $item_name . ']';

        //Initialize the icon
        $item_icon =  $item_name;

         // Add the icon to the name.
        $item_icon_link = $item_stats->getItemIconLink($item_name);
        if (!empty($item_icon_link))
        {
            $item_html = "<img class='smallitemicon' src='" . $item_icon_link . "'> " . $item_html;
        }

        // Get the color of this item and apply it to the html.
        $item_color = $item_stats->getItemColor($item_name);
        if (!empty($item_color))
        {
            $item_html = "<span class='" . $item_color . "'>" . $item_html . "</span>";
        }

        // Get the tooltip html for this item and apply it to the html.
        $item_tooltip_html = $item_stats->getItemTooltipHtml($item_name);
        if (!empty($item_tooltip_html))
        {
            $item_html = "<span " . $item_tooltip_html . ">" . $item_html . "</span>";
        }

        // If this item has a link to the info site, add this link to the HTML.  If it doesn't have a link, it
        // means the item hasn't been found yet, so put up a link to the update page instead.
        $item_link = $item_stats->getItemLink($item_name);
        if (!empty($item_link))
        {
            $item_html = "<a class='forumitemlink' target='_blank' href='" . $item_link . "'>" . $item_html . "</a>";
        }
        else
        {
            $item_link = 'itemstats/updateitem.php?item=' . urlencode(urlencode($item_name));
            $item_html = "<a class='forumitemlink' href='$item_link'>" . $item_html . "</a>";
        }

        // Finally, replace the bbcode with the html.
        $message = str_replace($match[0], $item_html, $message);
    }

    return $message;
}

?>