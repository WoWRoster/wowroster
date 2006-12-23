function setOpacity( sEl,val )
{
	oEl = document.getElementById(sEl);
	if(oEl)
	{
		oEl.style.opacity = val/10;
		oEl.style.filter = 'alpha(opacity=' + val*10 + ')';
	}
}

function showLayer(layerName)
{
	obj = document.getElementById(layerName);
	if (obj != null)
	{
		obj.style.display = '';
	}
}

function hideLayer(layerName)
{
	obj = document.getElementById(layerName);
	if (obj != null)
	{
		obj.style.display = 'none';
	}
}

function showAllSkills()
{
	for (i = 0; i < 6; i++)
	{
		showLayer('cs' + i + 'e');
		hideLayer('cs' + i + 'c');
	}
	showLayer('cAll');
	hideLayer('eAll');
}

function hideAllSkills()
{
	for (i = 0; i < 6; i++)
	{
		showLayer('cs' + i + 'c');
		hideLayer('cs' + i + 'e');
	}
	showLayer('eAll');
	hideLayer('cAll');
}

function collapseSkills(num)
{
	hideLayer('cs' + num + 'e');
	showLayer('cs' + num + 'c');
	showLayer('eAll');
	hideLayer('cAll');
}

function expandSkills(num)
{
	mybool=true;
	hideLayer('cs' + num + 'c');
	showLayer('cs' + num + 'e');
	for (i = 0; i < 6; i++)
	{
		obj = document.getElementById('cs' + i + 'e');
		if (obj.style.display == 'none')
		{
			mybool = false;
		}
	}
	if (mybool)
	{
		showLayer('cAll');
		hideLayer('eAll');
	}
}

function collapseRep(num)
{
	hideLayer('rep' + num + 'e');
	showLayer('rep' + num + 'c');
}

function expandRep(num)
{
	hideLayer('rep' + num + 'c');
	showLayer('rep' + num + 'e');
}

function showPet(num)
{
	for (i = 0; i <= 5; i++)
	{
		hideLayer('pet_' + i);
	}
	showLayer(num);
}

function showSpellTree(num)
{
	for (i = 0; i <= 4; i++)
	{
		hideLayer('spelltree_' + i);
	}
	showLayer(num);
}

function displaypage(cid, aobject)
{
	if (document.getElementById)
	{
		changetab(aobject)
		if (previoustab!='')
			document.getElementById(previoustab).style.display='none'
		document.getElementById(cid).style.display=''
		previoustab=cid
		if (aobject.blur)
			aobject.blur()
		return false
	}
	else
		return true
}

function changetab(aobject)
{
	if (typeof tabobjlinks=='undefined')
		collecttablinks()
	for (i=0; i<tabobjlinks.length; i++)
		tabobjlinks[i].className=''
	aobject.className='current_tab'
}

function collecttablinks()
{
	var tabobj=document.getElementById('char_navagation')
	tabobjlinks=tabobj.getElementsByTagName('li')
}

function charpage_onload()
{
	collecttablinks()

	displaypage(initialtab[1], tabobjlinks[initialtab[0]-1])
}



// this function is needed to work around
// a bug in IE related to element attributes
function hasClass(obj)
{
	var result = false;
	if (obj.getAttributeNode('class') != null)
	{
		result = obj.getAttributeNode('class').value;
	}
	return result;
}

function stripe(id)
{
	// the flag we'll use to keep track of
	// whether the current row is odd or even
	var even = false;

	// if arguments are provided to specify the colours
	// of the even & odd rows, then use the them;
	// otherwise use the following defaults:
	var evenColor = arguments[1] ? arguments[1] : '#1F1E1D';
	var oddColor = arguments[2] ? arguments[2] : '#2E2D2B';

	// obtain a reference to the desired table
	// if no such table exists, abort
	var table = document.getElementById(id);
	if (! table)
	{
		return;
	}

	// by definition, tables can have more than one tbody
	// element, so we'll have to get the list of child
	// &lt;tbody&gt;s
	var tbodies = table.getElementsByTagName('tbody');

	// and iterate through them...
	for (var h = 0; h < tbodies.length; h++)
	{
		// find all the &lt;tr&gt; elements...
		var trs = tbodies[h].getElementsByTagName('tr');

		// ... and iterate through them
		for (var i = 0; i < trs.length; i++)
		{
			// avoid rows that have a class attribute
			// or backgroundColor style
			if (! hasClass(trs[i]) && ! trs[i].style.backgroundColor)
			{
				// get all the cells in this row...
				var tds = trs[i].getElementsByTagName('td');

				// and iterate through them...
				for (var j = 0; j < tds.length; j++)
				{
					var mytd = tds[j];

					// avoid cells that have a class attribute
					// or backgroundColor style
					if (! hasClass(mytd) && ! mytd.style.backgroundColor)
					{
						mytd.style.backgroundColor =
						even ? evenColor : oddColor;
					}
				}
			}
			// flip from odd to even, or vice-versa
			even =  ! even;
		}
	}
}