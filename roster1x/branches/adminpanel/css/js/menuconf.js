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

function my_DropFunc()
{
	// Calculate the snap position which is closest to the drop coordinates
	var mode;

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

	var y = dd.obj.y - dd.elements.palet.y + dy/2;
	palY = Math.max(margTop, Math.min(y - (y-margTop)%dy, margTop + (palet.length)*dy));
	palPosY = (palY-margTop)/dy;
	var palX = margLef;

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

function removeGridElement(posX,posY)
{
	for (i=posY+1; i<aElts[posX].length; i++)
	{
		aElts[posX][i-1] = aElts[posX][i];
	}

	aElts[posX].length--;

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

function insertGridElement(posX,posY,obj)
{
	aElts[posX].length++;

	for (i=aElts[posX].length-1; i>posY; i--)
	{
		aElts[posX][i] = aElts[posX][i-1];
	}

	aElts[posX][posY] = obj;

	if (aElts[posX].length > arrayHeight)
	{
		dd.elements.array.resizeBy(0,dy);
		arrayHeight++;
	}
}

function insertGridColumn(posX,obj)
{
	aElts.length++;
	for (i=aElts.length-1; i>posX; i--)
	{
		aElts[i] = aElts[i-1];
	}

	aElts[posX] = Array();
	aElts[posX][0] = obj;

	dd.elements.array.resizeBy(dx,0);
	arrayWidth++;
}

function removeListElement(posY)
{
	for (i=posY+1; i<palet.length; i++)
	{
		palet[i-1] = palet[i];
	}

	palet.length--;
	dd.elements.palet.resizeBy(0,-dy);
}

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

function sqr(x)
{
	return x*x;
}
