
<!-- Begin Java Link -->
<script type="text/javascript" language="JavaScript">
<!--

function tick(ElementID)
{
	if(document.getElementById)
		document.getElementById(ElementID).checked = true;
}

function show(ElementID)
{
	if(document.getElementById)
		document.getElementById(ElementID).style.display = '';
}

function hide(ElementID)
{
	if(document.getElementById)
		document.getElementById(ElementID).style.display = 'none';
}

function toggleShow(ElementID)
{
	if(document.getElementById)
	{
		if(document.getElementById(ElementID).style.display == 'none')
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


var initialtab = [1, 't1']
var previoustab = ''

function expandcontent(cid, aobject)
{
	if (document.getElementById)
	{
		detectSourceindex(aobject)
		if (previoustab != '')
			document.getElementById(previoustab).style.display = 'none'
		document.getElementById(cid).style.display = ''
		previoustab=cid
		if (aobject.blur)
			aobject.blur()
		return false
	}
	else
		return true
}

function collecttablinks()
{
	var tabobj = document.getElementById('tab_menu')
	tabobjlinks = tabobj.getElementsByTagName('li')
}

function detectSourceindex(aobject)
{
	for (i=0; i<tabobjlinks.length; i++)
	{
		if (aobject==tabobjlinks[i])
		{
			tabsourceindex=i //source index of tab bar relative to other tabs
			break
		}
	}
}

function do_onload()
{
	var cookiename = 'auth_lasttab'
	var cookiecheck = window.get_cookie && get_cookie(cookiename).indexOf('|')!=-1
	collecttablinks()

	if (cookiecheck)
	{
		var cookieparse = get_cookie(cookiename).split('|')
		var whichtab = cookieparse[0]
		var tabcontentid = cookieparse[1]

		expandcontent(tabcontentid, tabobjlinks[whichtab])
	}
	else
		expandcontent(initialtab[1], tabobjlinks[initialtab[0]-1])
}

window.onload=do_onload


function get_cookie(Name)
{
	var search = Name + '='
	var returnvalue = '';

	if (document.cookie.length > 0)
	{
		offset = document.cookie.indexOf(search)
		if (offset != -1)
		{
			offset += search.length
			end = document.cookie.indexOf(';', offset);
			if (end == -1) end = document.cookie.length;
				returnvalue=unescape(document.cookie.substring(offset, end))
		}
	}
	return returnvalue;
}

function savetabstate()
{
	var cookiename = 'auth_lasttab'
	var cookievalue = tabsourceindex+'|'+previoustab

	document.cookie = cookiename+'='+cookievalue
}

window.onunload=savetabstate

//-->
</script>
<!-- End Java Link -->