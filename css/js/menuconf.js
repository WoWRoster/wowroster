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
		for (var j=0; j<aElts[i].length; j++)
		{
			if (aElts[i][j] == dd.obj)
			{
				removeGridElement(i,j);
			}
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
	var mode;

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
	arrX = Math.max(margLef, Math.min(x - (x-margLef)%dx, margLef + (aElts.length)*dx));
	var posX = (arrX-margLef)/dx;
	var y = dd.obj.y - dd.elements.array.y + dy/2;
	var posY;

	if (posX == aElts.length)
	{
		arrY = margTop;
		posY = 0;
		mode = 1;
	}
	else
	{
		arrY = Math.max(margTop, Math.min(y - (y-margTop)%dy, margTop + (aElts[posX].length)*dy));
		posY = (arrY-margTop)/dy;
		mode = 2;
	}

	// Check for closest position in the palet
	var y = dd.obj.y - dd.elements.palet.y + dy/2;
	palY = Math.max(margTop, Math.min(y - (y-margTop)%dy, margTop + (palet.length)*dy));
	palPosY = (palY-margTop)/dy;
	var palX = margLef;

	// Check which is closer: The closest array position or the closest palet position
	if (sqr(dd.obj.x-dd.elements.palet.x-palX)+sqr(dd.obj.y-dd.elements.palet.y-palY) <
		sqr(dd.obj.x-dd.elements.array.x-x)+sqr(dd.obj.y-dd.elements.array.y-y))
	{
		x = palX;
		y = palY;
		posY = (palY-margTop)/dy;
		mode = 3;
	}
	else
	{
		x = arrX;
		y = arrY;
	}

	// Actually do the moving
	if (mode == 1)
	{
		insertGridColumn(posX,dd.obj);
	}
	else if (mode == 2)
	{
		insertGridElement(posX,posY,dd.obj);
	}
	else if (mode == 3)
	{
		insertListElement(posY,dd.obj);
	}

	updatePositions();
}

/**
 * Remove a button from the grid. It is assumed the calling code already saved
 * a reference to the object represending the button.
 *
 * @param posX
 * @param posY
 *		The grid location that needs to be cleared.
 */
function removeGridElement(posX,posY)
{
	// Remove the element from the grid
	for (i=posY+1; i<aElts[posX].length; i++)
	{
		aElts[posX][i-1] = aElts[posX][i];
	}

	aElts[posX].length--;

	// Check if it leaves an empty column. If so, fill it up.
	if (aElts[posX].length == 0)
	{
		for (i=posX; i<aElts.length-1; i++)
		{
			aElts[i] = aElts[i+1];
		}
		aElts.length--;
		dd.elements.array.resizeBy(-dx,0);
		arrayWidth--;
	}
	// Check if we need to reduce the height of the palet
	else if (aElts[posX].length == arrayHeight - 1)
	{
		var max = 0;
		for (i=0; i<aElts.length; i++)
		{
			max = Math.max(max,aElts[i].length);
		}
		if (max < arrayHeight)
		{
			dd.elements.array.resizeBy(0,-dy);
			arrayHeight--;
		}
	}
}

/**
 * Insert a button in the menu grid in an existing column
 *
 * @param posX
 * @param posY
 *		The grid location the button should be added in
 * @param obj
 *		The object representing the button to be added
 */
function insertGridElement(posX,posY,obj)
{
	// Insert the element
	aElts[posX].length++;

	for (i=aElts[posX].length-1; i>posY; i--)
	{
		aElts[posX][i] = aElts[posX][i-1];
	}

	aElts[posX][posY] = obj;

	// Increase the grid height if needed
	if (aElts[posX].length > arrayHeight)
	{
		dd.elements.array.resizeBy(0,dy);
		arrayHeight++;
	}
}

/**
 * Insert a button in a new column in the menu grid.
 *
 * @param posX
 *		The grid location the column should be inserted at.
 * @param obj
 *		The object representing the button to be added in the new column.
 */
function insertGridColumn(posX,obj)
{
	// Create the column and insert the element in it
	aElts.length++;
	for (i=aElts.length-1; i>posX; i--)
	{
		aElts[i] = aElts[i-1];
	}

	aElts[posX] = Array();
	aElts[posX][0] = obj;

	// increase the grid width
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
function removeListElement(posY)
{
	for (i=posY+1; i<palet.length; i++)
	{
		palet[i-1] = palet[i];
	}

	palet.length--;
	dd.elements.palet.resizeBy(0,-dy);
}

/**
 * Insert an button into the palet.
 *
 * @param posY
 *		The palet location the button should be inserted at
 * @param obj
 *		The dhtml object representing the button to be added
 */
function insertListElement(posY,obj)
{
	palet.length++;
	for (i=palet.length-1; i>posY; i--)
	{
		palet[i] = palet[i-1];
	}

	palet[posY] = obj;
	dd.elements.palet.resizeBy(0,dy);
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
		for (j=0;j<aElts[i].length;j++)
		{
			aElts[i][j].moveTo(dd.elements.array.x+margLef+dx*i,dd.elements.array.y+margTop+dy*j);
		}
	}

	for (i=0;i<palet.length;i++)
	{
		palet[i].moveTo(dd.elements.palet.x+margLef,dd.elements.palet.y+margTop+dy*i);
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
	//var show  = document.getElementById('config_access').value;
	//loadXMLDoc(roster_url+'ajax.php?method=menu_button_add&cont=doAddElement','title='+title+'&url='+escape(url)+'&show='+show);
	loadXMLDoc(roster_url+'ajax.php?method=menu_button_add&cont=doAddElement','title='+title+'&url='+escape(url));
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
		if (i>0) value += '|';
		for (j=0;j<aElts[i].length; j++)
		{
			if (j>0) value += ':';
			value += aElts[i][j].div.id;
		}
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
