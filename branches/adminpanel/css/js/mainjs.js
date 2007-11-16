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
	return true;
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

/* vgjunkie
Swaps the visability of an element
	ElementID = Element ID to show/hide
	ImgID = Element ID of image src
	ImgShow = Image we set when we "Show" the element
	ImgHide= Image we set when we "Hide" the element

	Usage: showHide('id_name','<imgid_name>','<path/to/image1>','<path/to/image2>');
*/
function showHide(ElementID,ImgID,ImgShow,ImgHide)
{
	if(document.getElementById)
	{
		if(document.getElementById(ElementID).style.display == 'none')
		{
			show(ElementID);
			if(ImgShow)
				document.getElementById(ImgID).src = ImgShow;
		}
		else
		{
			hide(ElementID);
			if(ImgHide)
				document.getElementById(ImgID).src = ImgHide;
		}
	}
}

function setOpacity( sEl,val )
{
	oEl = document.getElementById(sEl);
	if(oEl)
	{
		oEl.style.opacity = val/10;
		oEl.style.filter = 'alpha(opacity=' + val*10 + ')';
	}
}

/**
 * AJAX functions. Use loadXMLDoc, pass the URL you need to talk to.
 * Include the following GET paramters in the url:
 *
 * method:	The php-side function reference
 * cont:	The javascript function to call once the reply is in.
 */
var req;

function loadXMLDoc(url,post)
{
	// branch for native XMLHttpRequest object
	if (window.XMLHttpRequest)
	{
		req = new XMLHttpRequest();
		req.onreadystatechange = processReqChange;
		req.open("POST", url, true);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.send(post);
	// branch for IE/Windows ActiveX version
	}
	else if (window.ActiveXObject)
	{
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req)
		{
			req.onreadystatechange = processReqChange;
			req.open("POST", url, true);
			req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			req.send(post);
		}
	}
}

function processReqChange()
{
	// only if req shows "complete"
	if (req.readyState == 4)
	{
		// only if "OK"
		if (req.status == 200)
		{
//			Unescape this to show the result XLM in a pupup for debugging.
//			alert(req.responseText);
			response = req.responseXML.documentElement;
			cont = response.getElementsByTagName('cont')[0].firstChild.data;
			result = response.getElementsByTagName('result')[0];
			status = response.getElementsByTagName('status')[0].firstChild.data;
			errmsg = response.getElementsByTagName('errmsg')[0];
			if (status == 0)
			{
				eval(cont + '(result)');
			}
			else
			{
				alert('Error '+status+': '+errmsg.firstChild.data);
			}
		}
		else
		{
			alert("There was a problem retrieving the XML data:\n" + req.statusText);
        }
    }
}