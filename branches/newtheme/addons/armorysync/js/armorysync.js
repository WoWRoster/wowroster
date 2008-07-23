/**
 * WoWRoster.net WoWRoster
 *
 * ArmorySync javascript
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2007 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: armorysync.js 344 2007-12-24 02:29:40Z poetter $
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.6.0
*/


function toggleStatus() {
    showHide('update_details','update_details_img','img/minus.gif','img/plus.gif');
    document.linker.StatusHidden.value=(document.linker.StatusHidden.value=='OFF'?'ON':'OFF');
}

function doUpdateStatus(result) {

    if ( result.hasChildNodes() ) {

        var statusInfo = result.getElementsByTagName('statusInfo');
        if ( statusInfo != null ) _doUpdateStatusInfo(statusInfo);

        var errorMessages = result.getElementsByTagName('errormessage');
        if ( errorMessages != null ) _doUpdateErrorMessages(errorMessages);

        var debugMessages = result.getElementsByTagName('debugmessage');
        if ( debugMessages != null ) _doUpdateDebugMessages(debugMessages);

        var reloadMessages = result.getElementsByTagName('reload');
        if ( reloadMessages != null ) _doReload(reloadMessages);
    }
}

function _doUpdateStatusInfo(statusInfo) {

    var statusInfoCount = statusInfo.length;

    for ( var i = 0; i < statusInfoCount; i++ ) {
        var infoChild = statusInfo[i];
        var infoType = infoChild.getAttribute('type');

        switch ( infoType ) {
            case 'text' :
                _doUpdateStatusText(infoChild);
                break;

            case 'image' :
                _doUpdateStatusImage(infoChild);
                break;

            case 'bar' :
                _doUpdateStatusBar(infoChild);
                break;

            case 'overlib' :
                _doUpdateStatusOverlib(infoChild);
                break;
        }
    }
}

function _doUpdateStatusText(info) {

    var targetId = info.getAttribute('targetId');
    var content = _getContent(info);
    var newElement;

    if ( content != null ){
        newElement = document.createTextNode(content);
    } else {
        newElement = document.createTextNode('');
    }
    var docElement = document.getElementById(targetId);
    if ( docElement != null ) {
        docElement.replaceChild(newElement, docElement.firstChild);
    }
}

function _doUpdateStatusImage(info) {

    var targetId = info.getAttribute('targetId');
    var content = _getContent(info);
    var newElement;

    if ( content != null ){
        newElement = document.createElement("img");
        newElement.setAttribute( 'src', content );
        var docElement = document.getElementById(targetId);
        if ( docElement != null ) {
            docElement.replaceChild(newElement, docElement.firstChild);
        }
    }
}

function _doUpdateStatusBar(info) {

    var targetId = info.getAttribute('targetId');
    var perc = _getContent(info);

    if ( perc != null ) {
        var percLeft = 100 - perc;
        var progBar = document.getElementById(targetId);
        if ( progBar != null ) {
            while ( progBar.hasChildNodes() ) {
                var firstChild = progBar.firstChild
                progBar.removeChild(firstChild);
            }

            if ( perc && perc != 0 ) {
                var newTD = document.createElement("td");
                newTD.setAttribute('bgColor', '#660000');
                newTD.setAttribute('height', '12px');
                newTD.setAttribute('width', perc+'%');
                progBar.appendChild(newTD);
            }

            if ( percLeft && percLeft != 0 ) {
                var newTD = document.createElement("td");
                newTD.setAttribute('bgColor', '#FFF7CE');
                newTD.setAttribute('height', '12px');
                newTD.setAttribute('width', percLeft+'%');
                progBar.appendChild(newTD);
            }
        }
    }
}

function _doUpdateStatusOverlib(info) {

    var targetId = info.getAttribute('targetId');
    var overlibType = info.getAttribute('overlibType');
    var content = _getContent(info);

    var image = document.getElementById(targetId).firstChild;

    if ( image != null && overlibType != null && overlibType == 'charLog' ) {
        image.onmouseover = function() {
            return overlib(content,CAPTION,'Update Log',WRAP);
        }
        image.onmouseout = function() { return nd(); }
    }
    if ( image != null && overlibType != null && overlibType == 'memberlistLog' ) {
        image.onclick = function() {
            return overlib('<div style="height:300px;width:500px;overflow:auto;">'+ content+ '</div>',CAPTION,'Update Log',STICKY, WRAP, CLOSECLICK);
        }
        image.onmouseover = function() {
            return overlib('Click to see update log',WRAP);
        }
        image.onmouseout = function() { return nd(); }
    }
}

function _doUpdateDebugMessages(infoDebug) {

    var infoCount = infoDebug.length;
    if ( infoCount > 0 ) {

        var target = infoDebug[0].getAttribute('target');
        var debugTable = document.getElementById(target);
        var debugTBody = debugTable.getElementsByTagName('tbody')[0];

        if ( debugTable != null ) {

            var debugTableTrs = debugTBody.getElementsByTagName('tr');
            var k = debugTableTrs.length - 1;
            rowClass = debugTableTrs[k].getElementsByTagName('td')[0].className.substr(10,1);

            for (var i = 0; i < infoCount; i++) {

                var r = switch_row_class();
                var infoDebugMessage = infoDebug[i];
                var messages = infoDebugMessage.getElementsByTagName('dmesg');
                var messagesCount = messages.length;
                var newTr = document.createElement("tr");

                for ( var j = 0; j < messagesCount; j++) {

                    var message = messages[j];
                    var content = _getContent(message);
                    var newTd = document.createElement("td");
                    var newTdClass = 'membersRow';
                    if ( j == messagesCount - 1 ) {
                        newTdClass += 'Right';
                    }
                    newTdClass += armorysync_debugdata == 1 ? 1 : r;
                    newTd.className = newTdClass;
                    var newTextNode = document.createTextNode(content);
                    newTd.appendChild(newTextNode);
                    newTr.appendChild(newTd);
                }
                debugTBody.appendChild(newTr);

                if ( armorysync_debugdata == 1 ) {

                    var messages = infoDebugMessage.getElementsByTagName('ddata');
                    var messagesCount = messages.length;
                    var header = ['Arguments', 'Returns']

                    for ( var j = 0; j < messagesCount; j++) {
                        var message = messages[j];
                        var content = _getContent(message);

                        var newHeadTh = document.createElement("th");
                        newHeadTh.colSpan = '7';
                        newHeadTh.className = 'membersHeaderRight';
                        newHeadTh.appendChild(document.createTextNode(header[j]));

                        var newHeadTr = document.createElement("tr");
                        newHeadTr.appendChild(newHeadTh);

                        debugTBody.appendChild(newHeadTr);

                        var newDataTd = document.createElement("td");
                        newDataTd.colSpan = '7';
                        newDataTd.className = 'membersRow1';
                        newDataTd.innerHTML = '<div style="max-height:300px;max-width:100%;overflow:auto;">'+ content+ '</div>';

                        var newDataTr = document.createElement("tr");
                        newDataTr.appendChild(newDataTd);

                        debugTBody.appendChild(newDataTr);
                    }
                }
            }
        }
    }
}

function _doUpdateErrorMessages(infoError) {

    var infoCount = infoError.length;
    if ( infoCount > 0 ) {

        var target = infoError[0].getAttribute('target');
        var errorTable = document.getElementById(target);

        var errorTableCreated = false;

        if ( errorTable == null ) {

            errorTable = _createErrorTable();
            rowClass = 2;
            errorTableCreated = true;
        }

        if ( errorTable != null ) {

            if ( ! errorTableCreated ) {
                var errorTableTBody = errorTable.getElementsByTagName('tbody')[0];

                if ( errorTableTBody != null ) {

                    var errorTableTrs = errorTableTBody.getElementsByTagName('tr');
                    var k = errorTableTrs.length - 1;
                    var _test = errorTableTrs[k].getElementsByTagName('td')[0].className.substr(10,1);
                    rowClass = errorTableTrs[k].getElementsByTagName('td')[0].className.substr(10,1);

                } else {

                    var errorTableTrs = errorTable.getElementsByTagName('tr');

                    if ( errorTableTrs != null ) {
                        var k = errorTableTrs.length - 1;
                        var _test = errorTableTrs[k].getElementsByTagName('td')[0].className.substr(10,1);
                        rowClass = errorTableTrs[k].getElementsByTagName('td')[0].className.substr(10,1);

                        if ( rowClass == null ) {
                            rowClass = 2;
                        }
                    } else {
                        rowClass = 2;
                    }
                }
            }


            for (var i = 0; i < infoCount; i++) {

                var r = switch_row_class();
                var infoErrorMessage = infoError[i];
                var messages = infoErrorMessage.getElementsByTagName('emesg');
                var messagesCount = messages.length;
                var newTr = document.createElement("tr");

                for ( var j = 0; j < messagesCount; j++) {

                    var message = messages[j];
                    var content = _getContent(message);
                    var newTd = document.createElement("td");
                    var newTdClass = 'membersRow';
                    if ( j == messagesCount - 1 ) {
                        newTdClass += 'Right';
                    }
                    newTdClass += (armorysync_debugdata == 1 ? 1 : r );
                    newTd.className = newTdClass;
                    var newTextNode = document.createTextNode(content);
                    newTd.appendChild(newTextNode);
                    newTr.appendChild(newTd);
                }
                errorTable.appendChild(newTr);

                if ( armorysync_debugdata == 1 ) {

                    var messages = infoErrorMessage.getElementsByTagName('ddata');
                    var messagesCount = messages.length;
                    var header = ['Arguments', 'Returns']

                    for ( var j = 0; j < messagesCount; j++) {
                        var message = messages[j];
                        var content = _getContent(message);

                        var newHeadTh = document.createElement("th");
                        newHeadTh.colSpan = '7';
                        newHeadTh.className = 'membersHeaderRight';
                        newHeadTh.appendChild(document.createTextNode(header[j]));

                        var newHeadTr = document.createElement("tr");
                        newHeadTr.appendChild(newHeadTh);

                        errorTable.appendChild(newHeadTr);

                        var newDataTd = document.createElement("td");
                        newDataTd.colSpan = '7';
                        newDataTd.className = 'membersRow1';
                        newDataTd.innerHTML = '<div style="max-height:300px;max-width:100%;overflow:auto;">'+ content+ '</div>';

                        var newDataTr = document.createElement("tr");
                        newDataTr.appendChild(newDataTd);

                        errorTable.appendChild(newDataTr);
                    }
                }
            }
        }
    }
}

function _doReload(messages) {
    var message = messages[0];
    if ( message != null ) {
        var reloadTime = message.getAttribute('reloadTime');

        if ( reloadTime != null ) {
            self.setTimeout('nextStep()', reloadTime);
        }
    }
}

function _createErrorTable() {

    var headers = new Array('Line', 'Time', 'File', 'Class', 'Function', 'Info', 'Status');
    var newTr = document.createElement('tr');
    for ( var i = 0; i < headers.length; i++ ) {
        var newTh = document.createElement('th');
        if ( i < headers.length -1 ) {
            newTh.setAttribute('class', 'membersHeader');
        } else {
            newTh.setAttribute('class', 'membersHeaderRight');
        }
        newTh.appendChild(document.createTextNode(headers[i]));
        newTr.appendChild(newTh);
    }

    var newTable = document.createElement('table');
    newTable.setAttribute('id', 'armorysync_error_table');
    newTable.setAttribute('width', '100%');
    newTable.setAttribute('cellspacing', '0');
    newTable.setAttribute('cellpadding', '0');
    newTable.setAttribute('align', 'center');
    newTable.appendChild(newTr);

    var newDiv = document.createElement('div');
    newDiv.setAttribute('class', 'armorysync_error');
    newDiv.appendChild(newTable);


    var borderDiv = document.createElement('div');
    borderDiv.setAttribute('class', 'header_text sredborder');
    borderDiv.appendChild(document.createTextNode('ArmorySync Error Infos'))

    var borderTd = document.createElement('td');
    borderTd.setAttribute('class', 'border_color sredborder');
    borderTd.appendChild(borderDiv);
    borderTd.appendChild(newDiv);

    var borderTr = document.createElement('tr');
    borderTr.appendChild(borderTd);

    var borderTable = document.createElement('table');
    borderTable.setAttribute('class', 'border_frame')
    borderTable.setAttribute('cellspacing', '0');
    borderTable.setAttribute('cellpadding', '0');
    borderTable.setAttribute('style', 'width:100%;');
    borderTable.appendChild(borderTr);

    var newBr = document.createElement('br');

    var errorDiv = document.getElementById('armorysync_error');
    errorDiv.appendChild(newBr);
    errorDiv.appendChild(borderTable);

    return document.getElementById('armorysync_error_table');
}

var rowClass = 1;

function switch_row_class(setNew) {
    row_class = ( rowClass == 1 ) ? 2 : 1;

    if( setNew == null && setNew != false )
    {
        rowClass = row_class;
    }

    return row_class;
}

function _getContent(node) {

    var multiPart = node.getAttribute('multipart');
    var content = '';
    if ( multiPart == null ) {
        if ( node.childNodes.length > 0 ) {
            content = node.firstChild.data;
        } else {
            return '';
        }
    } else {
        var parts = node.getElementsByTagName('multi');
        var partCount = parts.length;
        for ( var i = 0; i < partCount; i++ ) {
            var part = parts[i];
            content += part.firstChild.data;
        }
    }
    return decode(content);
}

function decode(text) {
    return decode_utf8(URLDecode(text));
}


//
// Just in case we need that someone
//
//function URLEncode(plaintext)
//{
//	// The Javascript escape and unescape functions do not correspond
//	// with what browsers actually do...
//	var SAFECHARS = "0123456789" +					// Numeric
//					"ABCDEFGHIJKLMNOPQRSTUVWXYZ" +	// Alphabetic
//					"abcdefghijklmnopqrstuvwxyz" +
//					"-_.!~*'()";					// RFC2396 Mark characters
//	var HEX = "0123456789ABCDEF";
//
//	var encoded = "";
//	for (var i = 0; i < plaintext.length; i++ ) {
//		var ch = plaintext.charAt(i);
//	    if (ch == " ") {
//		    encoded += "+";				// x-www-urlencoded, rather than %20
//		} else if (SAFECHARS.indexOf(ch) != -1) {
//		    encoded += ch;
//		} else {
//		    var charCode = ch.charCodeAt(0);
//			if (charCode > 255) {
//			    alert( "Unicode Character '"
//                        + ch
//                        + "' cannot be encoded using standard URL encoding.\n" +
//				          "(URL encoding only supports 8-bit characters.)\n" +
//						  "A space (+) will be substituted." );
//				encoded += "+";
//			} else {
//				encoded += "%";
//				encoded += HEX.charAt((charCode >> 4) & 0xF);
//				encoded += HEX.charAt(charCode & 0xF);
//			}
//		}
//	} // for
//
//	return encoded;
//};

function URLDecode(encoded)
{
   // Replace + with ' '
   // Replace %xx with equivalent character
   // Put [ERROR] in output if %xx is invalid.
   var HEXCHARS = "0123456789ABCDEFabcdef";
   var plaintext = "";
   var i = 0;
   while (i < encoded.length) {
       var ch = encoded.charAt(i);
	   if (ch == "+") {
	       plaintext += " ";
		   i++;
	   } else if (ch == "%") {
			if (i < (encoded.length-2)
					&& HEXCHARS.indexOf(encoded.charAt(i+1)) != -1
					&& HEXCHARS.indexOf(encoded.charAt(i+2)) != -1 ) {
				plaintext += unescape( encoded.substr(i,3) );
				i += 3;
			} else {
				//alert( 'Bad escape combination near ...' + encoded.substr(i) );
				plaintext += "%[ERROR]";
				i++;
			}
		} else {
		   plaintext += ch;
		   i++;
		}
	} // while
   return plaintext;
};

//
// Just in case we need that someone
//
//function encode_utf8(rohtext) {
//     // dient der Normalisierung des Zeilenumbruchs
//     rohtext = rohtext.replace(/\r\n/g,"\n");
//     var utftext = "";
//     for(var n=0; n<rohtext.length; n++)
//         {
//         // ermitteln des Unicodes des  aktuellen Zeichens
//         var c=rohtext.charCodeAt(n);
//         // alle Zeichen von 0-127 => 1byte
//         if (c<128)
//             utftext += String.fromCharCode(c);
//         // alle Zeichen von 127 bis 2047 => 2byte
//         else if((c>127) && (c<2048)) {
//             utftext += String.fromCharCode((c>>6)|192);
//             utftext += String.fromCharCode((c&63)|128);}
//         // alle Zeichen von 2048 bis 66536 => 3byte
//         else {
//             utftext += String.fromCharCode((c>>12)|224);
//             utftext += String.fromCharCode(((c>>6)&63)|128);
//             utftext += String.fromCharCode((c&63)|128);}
//         }
//     return utftext;
//}

function decode_utf8(utftext) {
     var plaintext = ""; var i=0; var c=c1=c2=0;
     // while-Schleife, weil einige Zeichen uebersprungen werden
     while(i<utftext.length)
         {
         c = utftext.charCodeAt(i);
         if (c<128) {
             plaintext += String.fromCharCode(c);
             i++;}
         else if((c>191) && (c<224)) {
             c2 = utftext.charCodeAt(i+1);
             plaintext += String.fromCharCode(((c&31)<<6) | (c2&63));
             i+=2;}
         else {
             c2 = utftext.charCodeAt(i+1); c3 = utftext.charCodeAt(i+2);
             plaintext += String.fromCharCode(((c&15)<<12) | ((c2&63)<<6) | (c3&63));
             i+=3;}
         }
     return plaintext;
}
