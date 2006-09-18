// $Id$
// Table sorting engine. Originally by Stuard Langridge.

var SORT_COLUMN_INDEX;
var SORT_COLUMNS;
var SORTERS;
var FILTER;

function ts_getInnerText(el)
{
	if (typeof el == "string") return el;
	if (typeof el == "undefined") { return el };

	// First node in the cell is a DIV with style="display:none" containing a simple string/number to sort on
	return el.childNodes[0].innerHTML;
}

function getParent(el, pTagName)
{
	if (el == null)
	{
		return null;
	}
	else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())	// Gecko bug, supposed to be uppercase
	{
		return el;
	}
	else
	{
		return getParent(el.parentNode, pTagName);
	}
}
function ts_sort_date(a,b)
{
	// y2k notes: two digit years less than 50 are treated as 20XX, greater than 50 are treated as 19XX
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
	if (aa.length == 10)
	{
		dt1 = aa.substr(6,4)+aa.substr(3,2)+aa.substr(0,2);
	}
	else
	{
		yr = aa.substr(6,2);
		if (parseInt(yr) < 50)
		{
			yr = '20'+yr;
		}
		else
		{
			yr = '19'+yr;
		}
		dt1 = yr+aa.substr(3,2)+aa.substr(0,2);
	}
	if (bb.length == 10)
	{
		dt2 = bb.substr(6,4)+bb.substr(3,2)+bb.substr(0,2);
	}
	else
	{
		yr = bb.substr(6,2);
		if (parseInt(yr) < 50)
		{
			yr = '20'+yr;
		}
		else
		{
			yr = '19'+yr;
		}
		dt2 = yr+bb.substr(3,2)+bb.substr(0,2);
	}
	if (dt1==dt2)
	{
		return 0;
	}
	if (dt1<dt2)
	{
		return -1;
	}
	return 1;
}

function ts_sort_currency(a,b)
{
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
	return parseFloat(aa) - parseFloat(bb);
}

function ts_sort_numeric(a,b)
{
	aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
	if (isNaN(aa)) aa = 0;
	bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX]));
	if (isNaN(bb)) bb = 0;
	return aa-bb;
}

function ts_sort_numeric_inv(a,b)
{
	aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
	if (isNaN(aa))
	{
		aa = 0;
	}
    bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX]));
    if (isNaN(bb))
    {
		bb = 0;
	}
    return bb-aa;
}

function ts_sort_caseinsensitive(a,b)
{
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();
	if (aa==bb)
	{
		return 0;
	}
	if (aa<bb)
	{
		return -1;
	}
	return 1;
}

function ts_sort_default(a,b) {
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
	if (aa==bb)
	{
		return 0;
	}
	if (aa<bb)
	{
		return -1;
	}
	return 1;
}


function addEvent(elm, evType, fn, useCapture)
// addEvent and removeEvent
// cross-browser event handling for IE5+,  NS6 and Mozilla
// By Scott Andrew
{
	if (elm.addEventListener)
	{
		elm.addEventListener(evType, fn, useCapture);
		return true;
	}
	else if (elm.attachEvent)
	{
		var r = elm.attachEvent("on"+evType, fn);
		return r;
	}
	else
	{
		alert("Handler could not be removed");
	}
}

function dosort(count,listname)
{
	SORT_COLUMNS = new Array();
	SORTERS = new Array();
	FILTER = new Array();

	table = document.getElementById(listname);

	for (i=0; i<count;i++)
	{
		cs = document.getElementById(listname+'_sort_'+i);

		if (cs.value == 'none')
		{
			SORTERS[i] = 'none';
			SORT_COLUMNS[i] = 0;
		}
		else
		{
			SORT_COLUMNS[i] = parseFloat(cs.value);
			sortfn = ts_sort_numeric;
			var itm = table.rows[0].cells[SORT_COLUMNS[i]-1].id;
			if (itm == 'name') sortfn = ts_sort_caseinsensitive;
			if (itm == 'class') sortfn = ts_sort_caseinsensitive;
			if (itm == 'note') sortfn = ts_sort_caseinsensitive;
			if (itm == 'hearth') sortfn = ts_sort_caseinsensitive;
			if (itm == 'zone') sortfn = ts_sort_caseinsensitive;

			SORTERS[i] = sortfn;
			if (cs.value.indexOf('desc') > -1) SORT_COLUMNS[i] = -SORT_COLUMNS[i];
		}
	}

	var newRows = new Array();
	for (var i=0;i<table.rows[0].cells.length;i++)
	{
		FILTER[i] = document.getElementById(listname +'_filter_'+(i+1));
	}
	j=0;
	for (var i=0;i<table.tBodies.length;i++)
	{
		// Don't sort filtered rows
		if (checkfilter(table.tBodies[i].rows[0]))
		{
			newRows[j++] = table.tBodies[i];
			table.tBodies[i].style.display = '';
		}
		else
		{
			table.tBodies[i].style.display = 'none';
		}
	}

	newRows.sort(compare);

	// We appendChild rows that already exist to the tbody, so it moves them rather than creating new ones
	j=0;
	for (var i=0;i<newRows.length;i++)
	{
		if (newRows[i].style.display == '')
			newRows[i].rows[0].className = "membersRowColor"+((j++)%2+1);
		table.appendChild(newRows[i]);
	}
}

function compare(a,b)
{
	for (var i=0; i<SORTERS.length; i++)
	{
		if (SORTERS[i] != 'none')
		{
			SORT_COLUMN_INDEX = Math.abs(SORT_COLUMNS[i])-1
			res = SORTERS[i](a.rows[0],b.rows[0]);
			if (res)
			{
				return SORT_COLUMNS[i]*res;
			}
		}
	}
	return 0;
}

function checkfilter(row)
{
	for (var j=0;j<row.cells.length;j++)
	{
		if ((FILTER[j].value.length > 0) && (row.cells[j].innerHTML.indexOf(FILTER[j].value) == -1))
		{
			return false;
		}
	}
	return true;
}

function enter_sort(e,count,listname)
{
	var key;

	if(window.event) // IE
	{
		key = e.keyCode;
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		key = e.which;
	}

	if (key == 13) {
		dosort(count,listname);
		return false;
	}

	return true;
}

function toggleColumn(colnr,dispcell,listname)
{
	table = document.getElementById(listname);
	if (table.rows[0].cells[colnr].style.display == 'none')
	{
		newstyle = '';
		dispcell.style.backgroundColor = '#2E2D2B';
	}
	else
	{
		newstyle = 'none';
		dispcell.style.backgroundColor = '#5b5955';
	}
	for (i=0;i<table.rows.length;i++)
	{
		table.rows[i].cells[colnr].style.display = newstyle;
	}
}

function sortColumn(colnr,count,listname)
{
	table = document.getElementById(listname);

	cs = new Array();

	for (i=0; i<(count-2); i++)
	{
		cs[i] = document.getElementById(listname+'_sort_'+i);

		if (cs[i].value.indexOf(colnr) > -1)
		{
			if (i == 0)
			{
				dir = (cs[i].value.indexOf('desc')==-1)?'asc':'desc';
			}
			else
			{
				dir = (cs[i].value.indexOf('desc')==-1)?'desc':'asc';
			}

			for (j=i; j>0; j--)
			{
				cs[j].value = cs[j-1].value;
			}

			cs[0].value = colnr + '_' + dir;

			dosort(count,listname);

			return false;
		}
	}

	for (j=(count-3); j>0; j--)
	{
		cs[j].value = cs[j-1].value;
	}

	cs[0].value = colnr + '_asc';

	dosort(count,listname);

	return false;

}
