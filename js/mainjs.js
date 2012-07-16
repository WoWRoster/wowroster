/**
 * WoWRoster.net WoWRoster
 *
 * Main javascript file
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 1.03
 */

// Now handled by jQuery
function submitonce(f) {
    return true;
}

/**
 * Some cheesy JS code to set a hidden input field because
 * IE doesn't handle the <button> tag like it is supposed to
 */
function setvalue(id, val) {
	$('#' + id).val(val);
}

/* Basic id based show */
function show(id) {
	$('#' + id).show();
}

/* Basic id based hide */
function hide(id) {
	$('#' + id).hide();
}

/* Swaps visability of two ids */
function swapShow(id,id2) {
	if ($('#' + id).is(':visible')) {
		hide(id2);
		show(id);
	}
	else {
		hide(id);
		show(id2);
	}
}


var lpages = new Array();

function addLpage( name ) {
	lpages[lpages.length] = name;
}

function doLpage( div ) {
	for( i=0 ; i<lpages.length ; i++ ) {
		obj = document.getElementById( lpages[i] );
		if( lpages[i] == div ) {
			showElem(obj);
		}
		else {
			hideElem(obj);
		}
	}
}

var rpages = new Array();

function addRpage( name ) {
	rpages[rpages.length] = name;
}

function doRpage( div ) {
	for( i=0 ; i<rpages.length ; i++ ) {
		obj = document.getElementById( rpages[i] );
		if( rpages[i] == div ) {
			showElem(obj);
		}
		else {
			hideElem(obj);
		}
	}
}

function showElem(oName) {
	oName.style.display='';
}

function hideElem(oName) {
	oName.style.display='none';
}

/* vgjunkie
Swaps the visability of an element
	ElementID = Element ID to show/hide
	ImgID = Element ID of image src
	ImgShow = Image we set when we "Show" the element
	ImgHide= Image we set when we "Hide" the element

	Usage: showHide('container element ID','img ID','path/to/image_on_show','path/to/image_on_hidden');
*/
function showHide(ElementID,ImgID,ImgShow,ImgHide) {
	if($('#' + ElementID).is(':visible')) {
		hide(ElementID);
		if(ImgHide) {
			$('#' + ImgID).attr('src',ImgHide);
		}
	}
	else {
		show(ElementID);
		if(ImgShow) {
			$('#' + ImgID).attr('src',ImgShow);
		}
	}
}

function setOpacity( sEl,val ) {
	oEl = document.getElementById(sEl);
	if(oEl) {
		oEl.style.opacity = val/10;
		oEl.style.filter = 'alpha(opacity=' + val*10 + ')';
	}
}

function getElementsByClass( searchClass, domNode, tagName) {
	if( domNode.getElementsByClassName )
		{ return domNode.getElementsByClassName( searchClass ); }
	if (domNode == null)
		{ domNode = document; }
	if (tagName == null)
		{ tagName = '*'; }

	var el = new Array();

	/* tags with matching tagname */
	var tags = domNode.getElementsByTagName(tagName);

	/* Search text */
	var tcl = " "+searchClass+" ";

	for(i=0, j=0; i<tags.length; i++) {
		var test = " " + tags[i].className + " ";
		if (test.indexOf(tcl) != -1) {
			el[j++] = tags[i];
		}
	}
	return el;
}

function hideElements( els ) {
	for( i = 0; i < els.length; i++ ) {
		els[i].style.display = 'none';
	}
}

function showElements( els ) {
	for( i = 0; i < els.length; i++ ) {
		els[i].style.display = '';
	}
}

function setElementsSrc( els, img ) {
	for( i = 0; i < els.length; i++ ) {
		els[i].src = img;
	}
}

function patchHref( els, name, value ) {
	var re = new RegExp("\\b" + name + "=([\\w]*)");
	var replace = name + "=" + value;

	for( i = 0; i < els.length; i++ ) {
		els[i].href = els[i].href.replace(re, replace);
	}
}


/**
 * AJAX functions. Use loadXMLDoc, pass the URL you need to talk to.
 * Include the following GET paramters in the url:
 *
 * method:	The php-side function reference
 * cont:	The javascript function to call once the reply is in.
 * addon:	Addon name the function belongs to, if not a roster function
 */
function loadXMLDoc(url,post,callback) {
	if( arguments.length < 2 ) {
		return false;
	}
	else if( arguments.length == 2 ) {
		callback=loadAjaxResult;
	}

	// branch for native XMLHttpRequest object
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
		req.onreadystatechange = function(){ callback(req ); };
		req.open("POST", url, true);
		req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		req.send(post);
	// branch for IE/Windows ActiveX version
	}
	else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
		if (req) {
			req.onreadystatechange = function(){ callback(req ); };
			req.open("POST", url, true);
			req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			req.send(post);
		}
	}
}

function loadAjaxResult( req )
{
	// only if req shows "complete"
	if (req.readyState == 4) {
		// only if "OK"
		if (req.status == 200) {
//			Unescape this to show the result XML in a popup for debugging.
//			alert(req.responseText);
			if (req.responseXML == null) {
				alert(req.responseText.replace(/<\/?[^>]+>/gi, ''));
			}
			else {
				response = req.responseXML.documentElement;
				cont = response.getElementsByTagName('cont')[0].firstChild.data;
				result = response.getElementsByTagName('result')[0];
				status = response.getElementsByTagName('status')[0].firstChild.data;
				errmsg = response.getElementsByTagName('errmsg')[0];
				if (status == 0) {
					eval(cont + '(result)');
				}
				else {
					alert('Error '+status+': '+errmsg.firstChild.data);
				}
			}
		}
		else {
			alert("There was a problem retrieving the XML data:\n" + req.statusText);
		}
	}
}

/**
 * Use an ajax call to fetch HTML to put in a tag (probably a <div> or <span>) somewhere in your document.
 * Usage: loadXMLDoc( "url", "post", function( req ){ loadAjaxInDiv( req, "id" ); } )
 *
 * url = url to request
 * post = post string
 * id = element ID to insert into
 */
function loadAjaxInDiv( req, id ) {
	if(!document.getElementById) return;

	// only if req shows "complete"
	if (req.readyState == 4) {
		// only if "OK"
		if (req.status == 200) {
//			Unescape this to show the result XML in a popup for debugging.
//			alert(req.responseText);
			document.getElementById( id ).innerHTML = req.responseText;
		}
		else {
			alert("There was a problem retrieving the page data:\n" + req.statusText);
		}
	}
}
