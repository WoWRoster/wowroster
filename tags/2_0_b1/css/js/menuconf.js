/**
 * WoWRoster.net WoWRoster
 *
 * Menu config javascript
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

/**
 * Pick function. This is called if a button is picked up.
 * Here the button's position in the palet or grid is filled up.
 */
function my_PickFunc()
{
	for (var i=0; i<aElts.length; i++)
	{
		if (aElts[i] == dd.obj)
		{
			removeGridElement(i);
		}
	}

	for (var i=0; i<palet.length; i++)
	{
		if (palet[i] == dd.obj)
		{
			removeListElement(i);
		}
	}

	updatePositions();
}

/**
 * Drop function. This is called if a button has been released.
 * This uses quite some 2d calculus to determine what should happen with the
 * button.
 */
function my_DropFunc()
{
	// Check if the button is fully in the delete box, if so ask for confirm, if confirmed delete
	if ((dd.obj.x > dd.elements.rec_bin.x) &&
		(dd.obj.y > dd.elements.rec_bin.y) &&
		((dd.obj.x+dd.obj.w) < (dd.elements.rec_bin.x+dd.elements.rec_bin.w)) &&
		((dd.obj.y+dd.obj.h) < (dd.elements.rec_bin.y+dd.elements.rec_bin.h)) &&
		confirm('This will delete the button'))
	{
		sendDeleteElement(dd.obj);
		return;
	}

	// Check for closest position in the array
	var x = dd.obj.x - dd.elements.array.x + dx/2;
	var arrX = Math.max(margLef, Math.min(x - (x-margLef)%dx, margLef + (aElts.length)*dx));
	var arrY = margTop;

	// Check for closest position in the palet
	x = dd.obj.x - dd.elements.palet.x + dx/2;
	var palX = Math.max(margLef, Math.min(x - (x-margLef)%dx, margLef + (palet.length)*dx));
	var palY = margTop;

	// Check which is closer: The closest array position or the closest palet position
	if (sqr(dd.obj.x-dd.elements.palet.x-palX)+sqr(dd.obj.y-dd.elements.palet.y-palY) <
		sqr(dd.obj.x-dd.elements.array.x-arrX)+sqr(dd.obj.y-dd.elements.array.y-arrY))
	{
		posX = (palX-margLef)/dy;
		insertListElement(posX,dd.obj);
	}
	else
	{
		posX = (arrX-margLef)/dx;
		insertGridElement(posX,dd.obj);
	}

	updatePositions();
}

/**
 * Remove a button from the grid. It is assumed the calling code already saved
 * a reference to the object represending the button.
 *
 * @param pos
 *		The grid location that needs to be cleared.
 */
function removeGridElement(pos)
{
	// Remove the element from the grid
	for (i=pos+1; i<aElts.length; i++)
	{
		aElts[i-1] = aElts[i];
	}

	aElts.length--;
	dd.elements.array.resizeBy(-dx,0);
	arrayWidth--;
}

/**
 * Insert a button in the menu grid in an existing column
 *
 * @param pos
 *		The grid location the button should be added in
 * @param obj
 *		The object representing the button to be added
 */
function insertGridElement(pos,obj)
{
	// Insert the element
	aElts.length++;

	for (i=aElts.length-1; i>pos; i--)
	{
		aElts[i] = aElts[i-1];
	}

	aElts[pos] = obj;
	dd.elements.array.resizeBy(dx,0);
	arrayWidth++;
}

/**
 * Remove a button from the palet. It is assumed the calling code already saved
 * a reference to the object represending the button.
 *
 * @param posY
 *		The palet location that needs to be cleared.
 */
function removeListElement(pos)
{
	for (i=pos+1; i<palet.length; i++)
	{
		palet[i-1] = palet[i];
	}

	palet.length--;
	dd.elements.palet.resizeBy(-dx,0);
}

/**
 * Insert an button into the palet.
 *
 * @param posY
 *		The palet location the button should be inserted at
 * @param obj
 *		The dhtml object representing the button to be added
 */
function insertListElement(pos,obj)
{
	palet.length++;
	for (i=palet.length-1; i>pos; i--)
	{
		palet[i] = palet[i-1];
	}

	palet[pos] = obj;
	dd.elements.palet.resizeBy(dx,0);
}

/**
 * Update all button positions, based on their array/palet grid location and
 * thearray/palet locations themselves.
 * Some variables used that are php-generated, for easy tuning.
 */
function updatePositions()
{
	for (i=0;i<aElts.length;i++)
	{
		aElts[i].moveTo(dd.elements.array.x+margLef+dx*i,dd.elements.array.y+margTop);
	}

	for (i=0;i<palet.length;i++)
	{
		palet[i].moveTo(dd.elements.palet.x+margLef+dx*i,dd.elements.palet.y+margTop);
	}

}

/**
 * Function called if a button has been dropped inside the delete square, and
 * the deletiong has been confirmed. This sends the button name
 * off to the server for deletion.
 *
 * @param obj
 *		DHTML object represending the button.
 */
function sendDeleteElement(obj)
{
	loadXMLDoc(roster_url+'ajax.php?method=menu_button_del&cont=doDeleteElement','button='+obj.name);
}

function doDeleteElement(result)
{
	var obj = dd.elements[result.firstChild.data];
	var div = obj.div;
	obj.del();
	div.parentNode.removeChild(div);
}

/** 
 * Function called by the add button button. Fetches the info from the form
 * fields and sends the request off to the server.
 * After the server is done doAddElement() is called.
 */
function sendAddElement()
{
	var title = document.getElementById('title'        ).value;
	var url   = document.getElementById('url'          ).value;
	var icon  = document.getElementById('icon'         ).value;
	loadXMLDoc(roster_url+'ajax.php?method=menu_button_add&cont=doAddElement','title='+escape(title)+'&url='+escape(url)+'&icon='+escape(icon));
}

/**
 * Handler called after the ADD request has been handled by the server. This
 * creates and adds the button.
 *
 * @param result
 *		XML reply from the server
 */
function doAddElement(result)
{
	// Create the button
	var button = document.createElement('div');
	button.id = result.getElementsByTagName('id')[0].firstChild.data;
	button.className = 'menu_config_div';
	var title = result.getElementsByTagName('title')[0].firstChild.data
	button.appendChild(document.createTextNode(title))
	dd.elements.palet.div.appendChild(button);
	ADD_DHTML(button.id);

	// And add it to the bottom palet position
	palet.length++;
	palet[palet.length-1] = dd.elements[button.id];
	dd.elements.palet.resizeBy(0,dy);

	updatePositions();
}

/**
 * Writes the current configuration of the buttons to the (invisible) form field.
 */
function writeValue()
{
	var value = '';

	for (i=0;i<aElts.length; i++)
	{
		if (i>0) value += ':';
		value += aElts[i].div.id;
	}
	document.getElementById('arrayput').value = value;

	return true;
}

/**
 * Calculates the square of a number
 *
 * @param x
 *		number
 * @return
 *		square of the number
 */
function sqr(x)
{
	return x*x;
}
