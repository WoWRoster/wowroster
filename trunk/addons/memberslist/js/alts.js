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
			showElements(getElementsByClass('alt', element));
			setElementsSrc(getElementsByClass('foldout', element, 'img'), ImgShow );
		}
		else
		{
			hideElements(getElementsByClass('alt', element));
			setElementsSrc(getElementsByClass('foldout', element, 'img'), ImgHide );
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
		toolbar = document.getElementById(listname + '-toolbar');
		showElements(getElementsByClass('groupalt', toolbar));
		hideElements(getElementsByClass('ungroupalt', toolbar));

		table = document.getElementById(listname);
		showElements(getElementsByClass('groupalt', table));
		hideElements(getElementsByClass('ungroupalt', table));
		showElements(getElementsByClass('alt', table));
		setElementsSrc(getElementsByClass('foldout', table, 'img'), Img );
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
		toolbar = document.getElementById(listname + '-toolbar');
		showElements(getElementsByClass('groupalt', toolbar));
		hideElements(getElementsByClass('ungroupalt', toolbar));

		table = document.getElementById(listname);
		showElements(getElementsByClass('groupalt', table));
		hideElements(getElementsByClass('ungroupalt', table));
		hideElements(getElementsByClass('alt', table));
		setElementsSrc(getElementsByClass('foldout', table, 'img'), Img );
	}
}

/**
 * Ungroup alts
 *
 * @param listname
 * 		Name of the list to ungroup alts in
 */
function unGroupAlts( listname )
{
	if(document.getElementById)
	{
		toolbar = document.getElementById(listname + '-toolbar');
		hideElements(getElementsByClass('groupalt', toolbar));
		showElements(getElementsByClass('ungroupalt', toolbar));

		table = document.getElementById(listname);
		hideElements(getElementsByClass('groupalt', table));
		showElements(getElementsByClass('ungroupalt', table));
	}
}
