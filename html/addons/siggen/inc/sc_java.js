/*
* $Date: 2006/01/22 20:08:58 $
* $Revision: 1.3 $
* $Author: zanix $
*/
function show(ElementID)
{
	if(document.getElementById)
		document.getElementById(ElementID).style.display = "inline";
}

function hide(ElementID)
{
	if(document.getElementById)
		document.getElementById(ElementID).style.display = "none";
}

function showPage(ElementID)
{
	for (i = 1; i <= 8; i++)
	{
		hide('t' + i);
	}
	show(ElementID);
}

function toggleShow(ElementID)
{
	if(document.getElementById)
	{
		if(document.getElementById(ElementID).style.display == "none")
		{
			show(ElementID);
		}
		else
		{
			hide(ElementID);
		}
	}
}

function swapShow(ElementID,ElementID2)
{
	if(document.getElementById)
	{
		if(document.getElementById(ElementID).style.display == "none")
		{
			hide(ElementID2);
			show(ElementID);
		}
		else
		{
			hide(ElementID);
			show(ElementID2);
		}
	}
}