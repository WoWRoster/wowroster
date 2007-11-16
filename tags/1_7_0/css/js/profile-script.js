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

function setActiveTalentWrapper (o,imgurl)
{
	var obj = document.getElementById('talentwrapper' + o);

	if (obj.style.display == 'block')
	{
		return;
	}
	else
	{
		for (var i=1; i < 4; i++)
		{
			document.getElementById('talentwrapper' +i).style.display = 'none';
			document.getElementById('tlab' +i).style.fontWeight = 'normal';
			document.getElementById('tlab' + i).style.color = 'white';
			document.getElementById('tlabbg' +i).src = imgurl + '/itab.gif';
		}
		obj.style.display = 'block';
		document.getElementById('tlab' + o).style.fontWeight = 'bold';
		document.getElementById('tlab' + o).style.color = '#CDAD0F';
		document.getElementById('tlabbg' +o).src = imgurl + '/atab.gif';
	}
	return;
}


function showSpellTree(ElementID)
{
	for (i = 0; i < 4; i++)
	{
		hide('spelltree_' + i);
	}
	show(ElementID);
}


var tabs = new Array();
var tab_count = 0;

function addTab( name )
{
	tabs[tab_count] = name;
	tab_count++;
}

function doTab( div )
{
	for( i=0 ; i<tab_count ; i++ )
	{
		obj = document.getElementById( tabs[i] );
		fontobj = document.getElementById( 'tabfont'+tabs[i] );
		if( tabs[i] == div )
		{
			showElem(obj);
			fontobj.className='white';
		}
		else
		{
			hideElem(obj);
			fontobj.className='yellow';
		}
	}
}

function showPet(cNum)
{
	var ids = new Array();
	ids[0] = 'pet_name';
	ids[1] = 'pet_title';
	ids[2] = 'pet_loyalty';
	ids[3] = 'pet_top_icon';
	ids[4] = 'pet_resistances';
	ids[5] = 'pet_stats_table';
	ids[6] = 'pet_xp_bar';
	ids[7] = 'pet_training_pts';
	ids[8] = 'pet_hpmana';
	ids[9] = 'pet_training_nm';

	for(a = 0; a < 15; a++)
	{
		for(i = 0; i < 15; i++)
		{
			if (cNum != i)
			{
				var oName= document.getElementById(ids[a]+String(i));
				if (oName != null)
				{
					hideElem(oName);
				}
			}
			else
			{
				var oName= document.getElementById(ids[a]+String(i));
				if (oName != null)
				{
					if(oName.style.display == 'none' || oName.style.display == '')
					{
						showElem(oName);
					}
				}
			}
		}
	}
}

function showElem(oName)
{
	oName.style.display='block';
}

function hideElem(oName)
{
	oName.style.display='none';
}