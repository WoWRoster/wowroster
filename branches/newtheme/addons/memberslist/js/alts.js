/**
 * WoWRoster.net WoWRoster
 *
 * Table sorting engine. Originally by Stuard Langridge.
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
*/

/**
 * Function to show/hide alts. Based on the showHide function in mainjs, but
 * adapated because I can't have a single element I'm hiding. I want to show/hide
 * all but the first child of the (tbody) element
 */
function toggleAlts(ElementID,ImgID,ImgShow,ImgHide)
{
	if(document.getElementById)
	{
		element = document.getElementById(ElementID);
		image = document.getElementById(ImgID);

		if(image.src.indexOf(ImgHide) >= 0)
		{
			element = element.firstChild;
			while(element = element.nextSibling)
			{
				if( element.tagName !== undefined )
					element.style.display = '';
			}
			image.src = ImgShow;
			image.alt = '-';
		}
		else
		{
			element = element.firstChild;
			while(element = element.nextSibling)
			{
				if( element.tagName !== undefined )
					element.style.display = 'none';
			}
			image.src = ImgHide;
			image.alt = '+';
		}
	}
}

/**
 * Open all alt boxes
 *
 * @param listname
 *		Name of the list to open all alts in
 * @param Img
 *		Image to put on the folding icons
 */
function openAlts(listname, Img)
{
	if(document.getElementById)
	{
		table = document.getElementById(listname);
		for(i=0; i<table.tBodies.length; i++)
		{
			// The first element in the first cell.
			el = table.tBodies[i].rows[0].cells[0].firstChild;
			if( (el !== null) && (el.tagName !== undefined) && (el.tagName.toLowerCase() == 'a'))
			{
				el.firstChild.src = Img;
				el.firstChild.alt = '-';

				// Now hide the rows
				for( j=1; j < table.tBodies[i].rows.length; j++ )
				{
					table.tBodies[i].rows[j].style.display = '';
				}
			}
		}
	}
}

/**
 * Close all alt boxes
 *
 * @param listname
 *		Name of the list to open all alts in
 * @param Img
 *		Image to put on the folding icons
 */
function closeAlts(listname, Img)
{
	if(document.getElementById)
	{
		table = document.getElementById(listname);
		for(i=0; i<table.tBodies.length; i++)
		{
			// The first element in the first cell.
			el = table.tBodies[i].rows[0].cells[0].firstChild;
			if( (el !== null) && (el.tagName !== undefined) && (el.tagName.toLowerCase() == 'a'))
			{
				el.firstChild.src = Img;
				el.firstChild.alt = '+';

				// Now hide the rows
				for( j=1; j < table.tBodies[i].rows.length; j++ )
				{
					table.tBodies[i].rows[j].style.display = 'none';
				}
			}
		}
	}
}

