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

function submitonce(theform)
{
	//if IE 4+ or NS 6+
	if (document.all||document.getElementById)
	{
		//screen thru every element in the form, and hunt down "submit" and "reset"
		for (i=0;i<theform.length;i++)
		{
			var tempobj=theform.elements[i];
			if(tempobj.type.toLowerCase()=='submit'||tempobj.type.toLowerCase()=='reset')
				//disable em
				tempobj.disabled=true;
		}
	}
}

/* Basic id based show */
function show(ElementID)
{
	if(document.getElementById)
		if(document.getElementById(ElementID))
			document.getElementById(ElementID).style.display = '';
}

/* Basic id based hide */
function hide(ElementID)
{
	if(document.getElementById)
		if(document.getElementById(ElementID))
			document.getElementById(ElementID).style.display = 'none';
}

/* Swaps visability of two ids */
function swapShow(ElementID,ElementID2)
{
	if(document.getElementById)
	{
		if(document.getElementById(ElementID).style.display == 'none')
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


var previoustab='';

function expandcontent(cid, aobject)
{
	if (document.getElementById)
	{
		highlighttab(aobject);
		detectSourceindex(aobject);
		if (previoustab!='')
			document.getElementById(previoustab).style.display='none';

		document.getElementById(cid).style.display='block';
		previoustab=cid;
		if (aobject.blur)
			aobject.blur();

		return false;
	}
	else
		return true;
}

function highlighttab(aobject)
{
	if (typeof tabobjlinks=='undefined')
		collecttablinks();

	for (i=0; i<tabobjlinks.length; i++)
		tabobjlinks[i].className='inactive';

	aobject.className='active';
}

function collecttablinks()
{
	var tabobj=document.getElementById('tab_menu');
	tabobjlinks=tabobj.getElementsByTagName('a');
}

function detectSourceindex(aobject)
{
	for (i=0; i<tabobjlinks.length; i++)
	{
		if (aobject==tabobjlinks[i])
		{
			tabsourceindex=i; //source index of tab bar relative to other tabs
			break;
		}
	}
}

function do_onload()
{
	if(typeof savetabname=='undefined')
		var cookiename=window.location.pathname;
	else
		var cookiename=window.location.pathname+savetabname;

	var cookiecheck=window.get_cookie && get_cookie(cookiename).indexOf('|')!=-1;
	collecttablinks();

	if (cookiecheck)
	{
		var cookieparse=get_cookie(cookiename).split('|');
		var whichtab=cookieparse[0];
		var tabcontentid=cookieparse[1];
		expandcontent(tabcontentid, tabobjlinks[whichtab]);
	}
	else
		expandcontent(initialtab[1], tabobjlinks[initialtab[0]-1]);
}


function get_cookie(Name)
{
	var search = Name + '=';
	var returnvalue = '';
	if (document.cookie.length > 0)
	{
		offset = document.cookie.indexOf(search);
		if (offset != -1)
		{
			offset += search.length;
			end = document.cookie.indexOf(';', offset);
			if (end == -1)
				end = document.cookie.length;

			returnvalue=unescape(document.cookie.substring(offset, end));
		}
	}
	return returnvalue;
}

function savetabstate()
{
	if(typeof savetabname=='undefined')
		var cookiename=window.location.pathname;
	else
		var cookiename=window.location.pathname+savetabname;

	var cookievalue=tabsourceindex+'|'+previoustab;
	document.cookie=cookiename+'='+cookievalue;
}

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
			document.getElementById('tlab' +i).className = 'tablabel';
			document.getElementById('tlabbg' +i).src = imgurl + '/itab.gif';
		}
		obj.style.display = 'block';
		document.getElementById('tlab' + o).className = 'tablabelactive';
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