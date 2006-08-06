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



//** Tab Content script- © Dynamic Drive DHTML code library (http://www.dynamicdrive.com)
//** Last updated: June 29th, 06

var tabcontentIDs=new Object()

function expandcontent(linkobj)
{
	var ulid=linkobj.parentNode.parentNode.id //id of UL element
	var ullist=document.getElementById(ulid).getElementsByTagName("li") //get list of LIs corresponding to the tab contents
	for (var i=0; i<ullist.length; i++)
	{
		ullist[i].className=""  //deselect all tabs
		if (typeof tabcontentIDs[ulid][i]!="undefined") //if tab content within this array index exists (exception: More tabs than there are tab contents)
			document.getElementById(tabcontentIDs[ulid][i]).style.display="none" //hide all tab contents
	}
	linkobj.parentNode.className="selected"  //highlight currently clicked on tab
	document.getElementById(linkobj.getAttribute("rel")).style.display="block" //expand corresponding tab content
	saveselectedtabcontentid(ulid, linkobj.getAttribute("rel"))
}

function savetabcontentids(ulid, relattribute)
{// save ids of tab content divs
	if (typeof tabcontentIDs[ulid]=="undefined") //if this array doesn't exist yet
		tabcontentIDs[ulid]=new Array()
	tabcontentIDs[ulid][tabcontentIDs[ulid].length]=relattribute
}

function saveselectedtabcontentid(ulid, selectedtabid)
{ //set id of clicked on tab as selected tab id & enter into cookie
	setCookie(ulid, selectedtabid)
}

function getullistlinkbyId(ulid, tabcontentid)
{ //returns a tab link based on the ID of the associated tab content
	var ullist=document.getElementById(ulid).getElementsByTagName("li")
	for (var i=0; i<ullist.length; i++)
	{
		if (ullist[i].getElementsByTagName("a")[0].getAttribute("rel")==tabcontentid)
		{
			return ullist[i].getElementsByTagName("a")[0]
			break
		}
	}
}

function initializetabcontent()
{
	for (var i=0; i<arguments.length; i++)
	{ //loop through passed UL ids
		var clickedontab=getCookie(arguments[i]) //retrieve ID of last clicked on tab from cookie, if any
		var ulobj=document.getElementById(arguments[i])
		var ulist=ulobj.getElementsByTagName("li") //array containing the LI elements within UL
		for (var x=0; x<ulist.length; x++)
		{ //loop through each LI element
			var ulistlink=ulist[x].getElementsByTagName("a")[0]
			if (ulistlink.getAttribute("rel"))
			{
				savetabcontentids(arguments[i], ulistlink.getAttribute("rel")) //save id of each tab content as loop runs
				ulistlink.onclick=function()
				{
					expandcontent(this)
					return false
				}
				if (ulist[x].className=="selected" && clickedontab=="") //if a tab is set to be selected by default
					expandcontent(ulistlink) //auto load currenly selected tab content
			}
		} //end inner for loop
		if (clickedontab!="")
		{ //if a tab has been previously clicked on per the cookie value
			var culistlink=getullistlinkbyId(arguments[i], clickedontab)
			if (typeof culistlink!="undefined") //if match found between tabcontent id and rel attribute value
				expandcontent(culistlink) //auto load currenly selected tab content
			else //else if no match found between tabcontent id and rel attribute value (cookie mis-association)
				expandcontent(ulist[0].getElementsByTagName("a")[0]) //just auto load first tab instead
		}
	} //end outer for loop
}


function getCookie(Name)
{
	var re=new RegExp(Name+"=[^;]+", "i"); //construct RE to search for target name/value pair
	if (document.cookie.match(re)) //if cookie found
		return document.cookie.match(re)[0].split("=")[1] //return its value
	return ""
}

function setCookie(name, value)
{
	document.cookie = name+"="+value //cookie value is domain wide (path=/)
}