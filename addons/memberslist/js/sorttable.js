/**
 * WoWRoster.net WoWRoster
 *
 * Table sorting engine. Originally by Stuard Langridge.
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    MembersList
*/


/**
 * Some global variables
 */
var SORT_COLUMN_INDEX;
var SORT_COLUMNS;
var SORTERS;
var FILTER;
var TYPES;

/**
 * Get the embedded sort data field. Little php addition, lot faster JS than the original here.
 *
 * @param el
 *		Table cell to get the value for
 * @return string
 *		Value to use for sorting/filtering
 */
function ts_getInnerText(el)
{
	if (typeof el == "string") return el;
	if (typeof el == "undefined") { return el };

	// First node in the cell is a DIV with style="display:none" containing a simple string/number to sort on
	return el.childNodes[0].innerHTML;
}

/**
 * Get the first parent of a certain type
 *
 * @param el
 *		Element
 * @param pTagName
 *		Tagname we're looking for
 */
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

/**
 * Date compare function. Unused, we pass unix timestamps for date sorting
 */
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

/**
 * Currency compare function. Unused.
 */
function ts_sort_currency(a,b)
{
	aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
	bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g,'');
	return parseFloat(aa) - parseFloat(bb);
}

/**
 * Numeric compare function
 */
function ts_sort_numeric(a,b)
{
	aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
	if (isNaN(aa)) aa = 0;
	bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX]));
	if (isNaN(bb)) bb = 0;
	return aa-bb;
}

/**
 * Case-insensitive string compare function
 */
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

/**
 * Case-sensitive string compare function.
 */
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

/**
 * The sort function. This does all of the control reading etc.
 *
 * @param count
 *		number of sort fields to look for
 * @param listname
 *		tablename we're sorting, also part of the name of various other controls we use.
 */
function dosort(count,listname)
{
	// Reset the global vars
	SORT_COLUMNS = new Array();
	SORTERS = new Array();
	FILTER = new Array();
	TYPES = new Array();

	table = document.getElementById(listname);

	// Look up and store the data from the sort fields
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
			var itm = table.rows[0].cells[SORT_COLUMNS[i]].className.split(' ');
			if (itm.indexOf('ts_string') >= 0)
			{
				SORTERS[i] = ts_sort_caseinsensitive;
			}
			else
			{
				SORTERS[i] = ts_sort_numeric;
			}

			// Descending sorts
			if (cs.value.indexOf('desc') > -1) SORT_COLUMNS[i] = -SORT_COLUMNS[i];
		}
	}

	// Look up and store the data from the filter fields
	for (var i=0;i<table.rows[0].cells.length-1;i++)
	{
		FILTER[i] = document.getElementById(listname +'_filter_'+(i+1));

		var itm = table.rows[0].cells[i+1].className.split(' ');
		if (itm.indexOf('ts_string') >= 0)
		{
			TYPES[i] = 'string';
		}
		else if (itm.indexOf('ts_date') >= 0)
		{
			TYPES[i] = 'date';
		}
		else
		{
			TYPES[i] = 'number';
		}
	}

	// Filter the rows, and add them to a storage array
	var newRows = new Array();
	for (var i=0, j=0;i<table.tBodies.length;i++)
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

	// Sort
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

/**
 * Row compare function. This reads the global variable to check what sort functions
 * to use, on what columns, in what order.
 */
function compare(a,b)
{
	for (var i=0; i<SORTERS.length; i++)
	{
		if (SORTERS[i] != 'none')
		{
			SORT_COLUMN_INDEX = Math.abs(SORT_COLUMNS[i])
			res = SORTERS[i](a.rows[0],b.rows[0]);
			if (res)
			{
				return SORT_COLUMNS[i]*res;
			}
		}
	}
	return 0;
}

/**
 * Filter function. Checks if a row meets the filter criteria.
 */
function checkfilter(row)
{
	for (var j=0;j<row.cells.length-1;j++)
	{
		if (FILTER[j].value.length == 0)
		{
			continue;
		}

		text = ts_getInnerText(row.cells[j+1]);
		op = FILTER[j].value.substr(0,2);

		// Equals
		if( op[0] == '=' )
		{
			if (text != FILTER[j].value.substr(1)) return false;
			continue;
		}
		// Not equals
		else if( op == '!=' || op == '<>' )
		{
			if (text == FILTER[j].value.substr(2)) return false;
			continue;
		}
		// Regexp match
		else if( op[0] == '~' )
		{
			if (!(new RegExp(FILTER[j].value.substr(1)).test(text))) return false;
			continue;
		}
		// Regexp match
		else if( op == '!~' )
		{
			if (new RegExp(FILTER[j].value.substr(2)).test(text)) return false;
			continue;
		}
		// Number comparisons
		else if( TYPES[j] == 'number' )
		{
			text = Number(text);

			// Lte Number
			if( op == '<=' || op == '=<' )
			{
				if (text > Number(FILTER[j].value.substr(2))) return false;
				continue;
			}
			// Gte Number
			else if( op == '>=' || op == '>=' )
			{
				if (text < Number(FILTER[j].value.substr(2))) return false;
				continue;
			}
			// Lt Number
			else if( op[0] == '<' )
			{
				if (text >= Number(FILTER[j].value.substr(1))) return false;
				continue;
			}
			// Gt Number
			else if( op[0] == '>' )
			{
				if (text <= Number(FILTER[j].value.substr(1))) return false;
				continue;
			}
		}
		// Date comparisons
		else if( TYPES[j] == 'date' )
		{
			text = Number(text);

			// Lte Date
			if( op == '<=' || op == '=<' )
			{
				if (text > Date.parse(FILTER[j].value.substr(2))) return false;
				continue;
			}
			// Gte Date
			else if( op == '>=' || op == '>=' )
			{
				if (text < Date.parse(FILTER[j].value.substr(2))) return false;
				continue;
			}
			// Lt Date
			else if( op[0] == '<' )
			{
				if (text >= Date.parse(FILTER[j].value.substr(1))) return false;
				continue;
			}
			// Gt Date
			else if( op[0] == '>' )
			{
				if (text <= Date.parse(FILTER[j].value.substr(1))) return false;
				continue;
			}
		}

		// Substring on whole field
		if( row.cells[j+1].innerHTML.toLowerCase().indexOf(FILTER[j].value.toLowerCase()) == -1 )
		{
			return false;
		}
	}
	return true;
}

/**
 * Function called onkeydown for some fields. If the key is enter, run the sorter.
 *
 * @param e
 *		onkeydown event
 * @param count
 * @param listname
 *		passed to dosort()
 */
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

/**
 * Make a column visible/invisible. Actually, we're hiding each cell individually.
 *
 * @param colnr
 *		column number to show/hide
 * @param dispcell
 *		button pressed. Passed so the color can be changed.
 * @param listname
 *		table ID to toggle the column for.
 */
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

/**
 * Click-header sorting.
 *
 * @param colnr
 *		column number that was clicked
 * @param count
 * @param listname
 *		as in dosort
 */
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
				dir = (cs[i].value.indexOf('desc')==-1)?'desc':'asc';
			}
			else
			{
				dir = (cs[i].value.indexOf('desc')==-1)?'asc':'desc';
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

/**
 * Function to show/hide alts. Based on the showHide function in mainjs, but
 * adapated because I can't have a single element I'm hiding. I want to show/hide
 * all but the first child of the (tbody) element
 */
function toggleAlts(ElementID,ImgID,ImgShow,ImgHide)
{
	if(document.getElementById)
	{
		element = document.getElementById(ElementID);
		image = document.getElementById(ImgID);

		if(image.src.indexOf(ImgHide) >= 0)
		{
			element = element.firstChild;
			while(element = element.nextSibling)
			{
				if( element.tagName !== undefined )
					element.style.display = '';
			}
			image.src = ImgShow;
			image.alt = '-';
		}
		else
		{
			element = element.firstChild;
			while(element = element.nextSibling)
			{
				if( element.tagName !== undefined )
					element.style.display = 'none';
			}
			image.src = ImgHide;
			image.alt = '+';
		}
	}
}

/**
 * Open all alt boxes
 *
 * @param listname
 *		Name of the list to open all alts in
 * @param Img
 *		Image to put on the folding icons
 */
function openAlts(listname, Img)
{
	if(document.getElementById)
	{
		table = document.getElementById(listname);
		for(i=0; i<table.tBodies.length; i++)
		{
			// The first element in the first cell.
			el = table.tBodies[i].rows[0].cells[0].firstChild;
			if( (el !== null) && (el.tagName !== undefined) && (el.tagName.toLowerCase() == 'a'))
			{
				el.firstChild.src = Img;
				el.firstChild.alt = '-';

				// Now hide the rows
				for( j=1; j < table.tBodies[i].rows.length; j++ )
				{
					table.tBodies[i].rows[j].style.display = '';
				}
			}
		}
	}
}

/**
 * Close all alt boxes
 *
 * @param listname
 *		Name of the list to open all alts in
 * @param Img
 *		Image to put on the folding icons
 */
function closeAlts(listname, Img)
{
	if(document.getElementById)
	{
		table = document.getElementById(listname);
		for(i=0; i<table.tBodies.length; i++)
		{
			// The first element in the first cell.
			el = table.tBodies[i].rows[0].cells[0].firstChild;
			if( (el !== null) && (el.tagName !== undefined) && (el.tagName.toLowerCase() == 'a'))
			{
				el.firstChild.src = Img;
				el.firstChild.alt = '+';

				// Now hide the rows
				for( j=1; j < table.tBodies[i].rows.length; j++ )
				{
					table.tBodies[i].rows[j].style.display = 'none';
				}
			}
		}
	}
}

