/*
        TableSort revisited v3.1 by frequency-decoder.com

        Released under a creative commons Attribution-ShareAlike 2.5 license (http://creativecommons.org/licenses/by-sa/2.5/)

        Please credit frequency decoder in any derivative work - thanks

        You are free:

        * to copy, distribute, display, and perform the work
        * to make derivative works
        * to make commercial use of the work

        Under the following conditions:

                by Attribution.
                --------------
                You must attribute the work in the manner specified by the author or licensor.

                sa
                --
                Share Alike. If you alter, transform, or build upon this work, you may distribute the resulting work only under a license identical to this one.

        * For any reuse or distribution, you must make clear to others the license terms of this work.
        * Any of these conditions can be waived if you get permission from the copyright holder.
*/

var fdTableSort = {

        regExp_Currency:        /^[£$€¥¤]/,
        regExp_Number:          /^(\-)?[0-9]+(\.[0-9]*)?$/,
        pos:                    -1,
        uniqueHash:             1,
        thNode:                 null,
        tableId:                null,
        tableCache:             {},
        tmpCache:               {},

        /*@cc_on
        /*@if (@_win32)
        colspan:                "colSpan",
        rowspan:                "rowSpan",
        @else @*/
        colspan:                "colspan",
        rowspan:                "rowspan",
        /*@end
        @*/

        addEvent: function(obj, type, fn) {
                if( obj.attachEvent ) {
                        obj["e"+type+fn] = fn;
                        obj[type+fn] = function(){obj["e"+type+fn]( window.event );};
                        obj.attachEvent( "on"+type, obj[type+fn] );
                } else
                        obj.addEventListener( type, fn, false );
        },

        stopEvent: function(e) {
                e = e || window.event;

                if(e.stopPropagation) {
                        e.stopPropagation();
                        e.preventDefault();
                };
                /*@cc_on@*/
                /*@if(@_win32)
                e.cancelBubble = true;
                e.returnValue = false;
                /*@end@*/
                return false;
        },

        init: function() {
                if (!document.getElementsByTagName) return;

                var tables = document.getElementsByTagName("table");
                var workArr, sortable, headers, thtext, aclone, a, span, columnNum, noArrow, colCnt, cel, allRowArr, rowArr, sortableTable, celCount, colspan, rowspan, rowLength;

                a               = document.createElement("a");
                a.href          = "#";
                a.onkeypress    = fdTableSort.keyWrapper;

                span            = document.createElement("span");

                for(var k = 0, tbl; tbl = tables[k]; k++) {

                        // Remove any old dataObj for this table (tables created from an ajax callback require this)
                        if(tbl.id && tbl.id in fdTableSort.tableCache) delete fdTableSort.tableCache[tbl.id];

                        allRowArr = tbl.getElementsByTagName('thead').length ? tbl.getElementsByTagName('thead')[0].getElementsByTagName('tr') : tbl.getElementsByTagName('tr');
                        rowArr = [];
                        sortableTable = false;

                        // Grab only the tr's that contain no td's and check at least one th has the class "sortable"
                        for(var i = 0, tr; tr = allRowArr[i]; i++) {
                                if(tr.getElementsByTagName('td').length || !tr.getElementsByTagName('th').length) continue;
                                rowArr[rowArr.length] = tr.getElementsByTagName('th');
                                for(var j = 0, th; th = rowArr[rowArr.length - 1][j]; j++) {
                                        if(th.className.search(/sortable/) != -1) sortableTable = true;
                                }
                        }

                        if(!sortableTable) continue;

                        if(!tbl.id) tbl.id = "fd-table-" + fdTableSort.uniqueHash++;

                        sortable  = false;
                        columnNum = tbl.className.search(/sortable-onload-([0-9]+)/) != -1 ? parseInt(tbl.className.match(/sortable-onload-([0-9]+)/)[1]) - 1 : -1;
                        showArrow = tbl.className.search(/no-arrow/) == -1;
                        reverse   = tbl.className.search(/sortable-onload-([0-9]+)-reverse/) != -1;

                        rowLength = rowArr[0].length;

                        for(var c = 0;c < rowArr[0].length;c++){
                                if(rowArr[0][c].getAttribute(fdTableSort.colspan) && rowArr[0][c].getAttribute(fdTableSort.colspan) > 1){
                                        rowLength = rowLength + (rowArr[0][c].getAttribute(fdTableSort.colspan) - 1);
                                };
                        };

                        workArr = new Array(rowArr.length);

                        for(var c = rowArr.length;c--;){
                                workArr[c]= new Array(rowLength);
                        };

                        for(var c = 0;c < workArr.length;c++){
                                celCount = 0;
                                for(var i = 0;i < rowLength;i++){
                                        if(!workArr[c][i]){
                                                cel = rowArr[c][celCount];
                                                colspan = (cel.getAttribute(fdTableSort.colspan) > 1) ? cel.getAttribute(fdTableSort.colspan):1;
                                                rowspan = (cel.getAttribute(fdTableSort.rowspan) > 1) ? cel.getAttribute(fdTableSort.rowspan):1;
                                                for(var t = 0;((t < colspan)&&((i+t) < rowLength));t++){
                                                        for(var n = 0;((n < rowspan)&&((c+n) < workArr.length));n++) {
                                                                workArr[(c+n)][(i+t)] = cel;
                                                        };
                                                };
                                                if(++celCount == rowArr[c].length) break;
                                        };
                                };
                        };

                        for(var c = 0;c < workArr.length;c++) {
                                for(var i = 0;i < workArr[c].length;i++){

                                        if(workArr[c][i].className.search("fd-column-") == -1) workArr[c][i].className = workArr[c][i].className + " fd-column-" + i;

                                        if(workArr[c][i].className.match('sortable')) {
                                                workArr[c][i].className = workArr[c][i].className.replace(/forwardSort|reverseSort/, "");

                                                if(i == columnNum) sortable = workArr[c][i];
                                                thtext = fdTableSort.getInnerText(workArr[c][i]);

                                                while(workArr[c][i].firstChild) workArr[c][i].removeChild(workArr[c][i].firstChild);

                                                // Create the link
                                                aclone = a.cloneNode(true);
                                                aclone.appendChild(document.createTextNode(thtext));
                                                aclone.title = "Sort on " + thtext;
                                                a.onclick = workArr[c][i].onclick = fdTableSort.clickWrapper;
                                                workArr[c][i].appendChild(aclone);

                                                // Add the span if needs be
                                                if(showArrow) workArr[c][i].appendChild(span.cloneNode(false));

                                                workArr[c][i].className = workArr[c][i].className.replace(/fd-identical|fd-not-identical/, "");
                                                fdTableSort.disableSelection(workArr[c][i]);
                                        };
                                };
                        };

                        fdTableSort.tmpCache[tbl.id] = {cols:rowLength, headers:workArr};

                        if(sortable) {
                                fdTableSort.thNode = sortable;
                                fdTableSort.initSort();
                                if(reverse) {
                                        fdTableSort.thNode = sortable;
                                        fdTableSort.initSort();
                                };
                        };
                };
        },

        disableSelection: function(element) {
                element.onselectstart = function() {
                        return false;
                };
                element.unselectable = "on";
                element.style.MozUserSelect = "none";
        },

        clickWrapper: function(e) {
                e = e || window.event;
                if(fdTableSort.thNode == null) {
                        fdTableSort.thNode = this;
                        fdTableSort.addSortActiveClass();
                        setTimeout("fdTableSort.initSort()",5);

                };
                return fdTableSort.stopEvent(e);
        },

        keyWrapper: function(e) {
                e = e || window.event;
                var kc = e.keyCode != null ? e.keyCode : e.charCode;
                if(kc == 13) {
                        var targ = this;
                        while(targ.tagName.toLowerCase() != "th") targ = targ.parentNode;

                        fdTableSort.thNode = targ;
                        fdTableSort.addSortActiveClass();
                        setTimeout("fdTableSort.initSort()",5);

                        return fdTableSort.stopEvent(e);
                };
                return true;
        },

        jsWrapper: function(tableid, colNum) {
                if(!fdTableSort.tmpCache[tableid] || fdTableSort.tmpCache[tableid].headers[0].length <= colNum || fdTableSort.tmpCache[tableid].headers[0][colNum].className.search(/fd-column/) == -1) return false;
                fdTableSort.thNode = fdTableSort.tmpCache[tableid].headers[0][colNum];
                fdTableSort.addSortActiveClass();
                fdTableSort.initSort();
        },

        addSortActiveClass: function() {
                if(fdTableSort.thNode == null) return;
                fdTableSort.addClass(fdTableSort.thNode, "sort-active");
                fdTableSort.addClass(document.getElementsByTagName('body')[0], "sort-active");
                if("sortInitiatedCallback" in window) {
                        var tableElem   = fdTableSort.thNode;
                        while(tableElem.tagName.toLowerCase() != 'table' && tableElem.parentNode) {
                                tableElem = tableElem.parentNode;
                        };
                        sortInitiatedCallback(tableElem.id);
                };
        },

        removeSortActiveClass: function() {
                fdTableSort.removeClass(fdTableSort.thNode, "sort-active");
                fdTableSort.removeClass(document.getElementsByTagName('body')[0], "sort-active");
                if("sortCompleteCallback" in window) {
                        var tableElem   = fdTableSort.thNode;
                        while(tableElem.tagName.toLowerCase() != 'table' && tableElem.parentNode) {
                                tableElem = tableElem.parentNode;
                        };
                        sortCompleteCallback(tableElem.id);
                };
        },

        addClass: function(e,c) {
                if(new RegExp("(^|\\s)" + c + "(\\s|$)").test(e.className)) return;
                e.className += ( e.className ? " " : "" ) + c;
        },

        removeClass: function(e,c) {
                e.className = !c ? "" : e.className.replace(new RegExp("(^|\\s*\\b[^-])"+c+"($|\\b(?=[^-]))", "g"), "");
        },

        prepareTableData: function(table) {
                var data = [];

                var start = table.getElementsByTagName('tbody');
                start = start.length ? start[0] : table;

                var trs = start.getElementsByTagName('tr');
                var ths = table.getElementsByTagName('th');

                var numberOfRows = trs.length;
                var numberOfCols = fdTableSort.tmpCache[table.id].cols;

                var data = [];
                var identical = new Array(numberOfCols);
                var identVal  = new Array(numberOfCols);

                var tr, td, th, txt, tds, col, row;

                var re = new RegExp(/fd-column-([0-9]+)/);
                var rowCnt = 0;

                var sortableColumnNumbers = [];

                for(var tmp = 0, th; th = ths[tmp]; tmp++) {
                        if(th.className.search(re) == -1) continue;
                        sortableColumnNumbers[sortableColumnNumbers.length] = th;
                };

                // Start to create the 2D matrix of data
                for(row = 0; row < numberOfRows; row++) {

                        tr              = trs[row];

                        // Have we any th tags or are we in a tfoot ?
                        if(tr.getElementsByTagName('th').length > 0 || (tr.parentNode && tr.parentNode.tagName.toLowerCase() == "tfoot")) continue;

                        data[rowCnt]    = [];
                        tds             = tr.getElementsByTagName('td');

                        for(var tmp = 0, th; th = sortableColumnNumbers[tmp]; tmp++) {
                                col = th.className.match(re)[1];

                                td  = tds[col];

                                txt = fdTableSort.getInnerText(td) + " ";

                                txt = txt.replace(/^\s+/,'').replace(/\s+$/,'');

                                if(th.className.search(/sortable-date/) != -1) {
                                        txt = fdTableSort.dateFormat(txt, th.className.search(/sortable-date-dmy/) != -1);
                                } else if(th.className.search(/sortable-numeric|sortable-currency/) != -1) {
                                        txt = parseFloat(txt.replace(/[^0-9\.\-]/g,''));
                                        if(isNaN(txt)) txt = "";
                                } else if(th.className.search(/sortable-text/) != -1) {
                                        txt = txt.toLowerCase();
                                } else if(th.className.search(/sortable-([a-zA-Z\_]+)/) != -1) {
                                        if((th.className.match(/sortable-([a-zA-Z\_]+)/)[1] + "PrepareData") in window) {
                                                txt = window[th.className.match(/sortable-([a-zA-Z\_]+)/)[1] + "PrepareData"](td, txt);
                                        };
                                } else if (th.className.search(/sortable-keep/) != -1) {
                                        txt = rowCnt;
                                } else {
                                        if(txt != "") {
                                                fdTableSort.removeClass(th, "sortable");
                                                if(fdTableSort.dateFormat(txt) != 0) {
                                                        fdTableSort.addClass(th, "sortable-date");
                                                        txt = fdTableSort.dateFormat(txt);
                                                } else if(txt.search(fdTableSort.regExp_Number) != -1 || txt.search(fdTableSort.regExp_Currency) != -1) {
                                                        fdTableSort.addClass(th, "sortable-numeric");
                                                        txt = parseFloat(txt.replace(/[^0-9\.\-]/g,''));
                                                        if(isNaN(txt)) txt = "";
                                                } else {
                                                        fdTableSort.addClass(th, "sortable-text");
                                                        txt = txt.toLowerCase();
                                                };
                                        };
                                };

                                if(rowCnt > 0 && identVal[col] != txt) {
                                        identical[col] = false;
                                };

                                identVal[col]     = txt;
                                data[rowCnt][col] = txt;
                        };

                        // Add the tr for this row
                        data[rowCnt][numberOfCols] = tr;

                        // Increment the row count
                        rowCnt++;
                }

                // Get the row and column styles
                var colStyle = table.className.search(/colstyle-([\S]+)/) != -1 ? table.className.match(/colstyle-([\S]+)/)[1] : false;
                var rowStyle = table.className.search(/rowstyle-([\S]+)/) != -1 ? table.className.match(/rowstyle-([\S]+)/)[1] : false;

                // Cache the data object for this table
                fdTableSort.tableCache[table.id] = { data:data, identical:identical, colStyle:colStyle, rowStyle:rowStyle, noArrow:table.className.search(/no-arrow/) != -1 };
        },

        initSort: function() {

                var span;
                var thNode      = fdTableSort.thNode;

                // Get the table
                var tableElem   = fdTableSort.thNode;
                while(tableElem.tagName.toLowerCase() != 'table' && tableElem.parentNode) {
                        tableElem = tableElem.parentNode;
                };

                // If this is the first time that this table has been sorted, create the data object
                if(!tableElem.id || !(tableElem.id in fdTableSort.tableCache)) {
                        fdTableSort.prepareTableData(tableElem);
                };

                // Cache the table id
                fdTableSort.tableId = tableElem.id;

                // Get the column position using the className added earlier
                fdTableSort.pos = thNode.className.match(/fd-column-([0-9]+)/)[1];

                // Grab the data object for this table
                var dataObj     = fdTableSort.tableCache[tableElem.id];

                // Get the position of the last column that was sorted
                var lastPos     = dataObj.pos ? dataObj.pos.className.match(/fd-column-([0-9]+)/)[1] : -1;

                // Get the stored data object for this table
                var data        = dataObj.data;
                var colStyle    = dataObj.colStyle;
                var rowStyle    = dataObj.rowStyle;
                var len1        = data.length;
                var len2        = data[0].length - 1;
                var identical   = dataObj.identical[fdTableSort.pos] == false ? false : true;
                var noArrow     = dataObj.noArrow;

                if(lastPos != fdTableSort.pos && lastPos != -1) {
                        var th = dataObj.pos;
                        fdTableSort.removeClass(th, "forwardSort");
                        fdTableSort.removeClass(th, "reverseSort");
                        if(!noArrow) {
                                // Remove arrow
                                span = th.getElementsByTagName('span')[0];
                                while(span.firstChild) span.removeChild(span.firstChild);
                        };
                };

                // If the same column is being sorted then just reverse the data object contents.
                var classToAdd = "forwardSort";

                if((lastPos == fdTableSort.pos && !identical) || thNode.className.match(/sortable-keep/)) {
                        data.reverse();
                        classToAdd = thNode.className.search(/reverseSort/) != -1 ? "forwardSort" : "reverseSort";
                        if(thNode.className.match(/sortable-keep/)) fdTableSort.tableCache[tableElem.id].pos = thNode;
                } else {
                        fdTableSort.tableCache[tableElem.id].pos = thNode;
                        if(!identical) {
                                if(thNode.className.match(/sortable-numeric|sortable-currency|sortable-date|sortable-keep/)) {
                                        data.sort(fdTableSort.sortNumeric);
                                } else if(thNode.className.match('sortable-text')) {
                                        data.sort(fdTableSort.sortText);
                                } else if(thNode.className.search(/sortable-([a-zA-Z\_]+)/) != -1 && thNode.className.match(/sortable-([a-zA-Z\_]+)/)[1] in window) {
                                        data.sort(window[thNode.className.match(/sortable-([a-zA-Z\_]+)/)[1]]);
                                };
                        };
                };

                fdTableSort.removeClass(thNode, "forwardSort");
                fdTableSort.removeClass(thNode, "reverseSort");
                fdTableSort.addClass(thNode, classToAdd);

                if(!noArrow) {
                        var arrow = thNode.className.search(/forwardSort/) != -1 ? " \u2193" : " \u2191";
                        span = thNode.getElementsByTagName('span')[0];
                        while(span.firstChild) span.removeChild(span.firstChild);
                        span.appendChild(document.createTextNode(arrow));
                };

                if(!rowStyle && !colStyle && identical) {
                        fdTableSort.removeSortActiveClass();
                        fdTableSort.thNode = null;
                        return;
                }

                var hook = tableElem.getElementsByTagName('tbody');
                hook = hook.length ? hook[0] : tableElem;

                var tr;

                for(var i = 0; i < len1; i++) {
                        tr = data[i][len2];

                        if(colStyle) {
                                if(lastPos != -1) {
                                        fdTableSort.removeClass(tr.getElementsByTagName('td')[lastPos], colStyle);
                                }
                                fdTableSort.addClass(tr.getElementsByTagName('td')[fdTableSort.pos], colStyle);
                        };
                        if(!identical) {

                                if(rowStyle) {
                                        if(i % 2) fdTableSort.addClass(tr, rowStyle);
                                        else fdTableSort.removeClass(tr, rowStyle);
                                };

                                hook.removeChild(tr); /* Netscape 8.1.2 requires the removeChild call */
                                hook.appendChild(tr);
                        };
                };
                fdTableSort.removeSortActiveClass();
                fdTableSort.thNode = null;
        },

        getInnerText: function(el) {
                if (typeof el == "string" || typeof el == "undefined") return el;
                if(el.innerText) return el.innerText;

                var txt = '', i;
                for (i = el.firstChild; i; i = i.nextSibling) {
                        if (i.nodeType == 3)            txt += i.nodeValue;
                        else if (i.nodeType == 1)       txt += fdTableSort.getInnerText(i);
                };

                return txt;
        },

        dateFormat: function(dateIn, favourDMY) {
                var dateTest = [
                        { regExp:/^(0[1-9]|1[012])([- \/.])(0[1-9]|[12][0-9]|3[01])([- \/.])(\d\d?\d\d)$/, d:3, m:1, y:5 },  // mdy
                        { regExp:/^(0[1-9]|[12][0-9]|3[01])([- \/.])(0[1-9]|1[012])([- \/.])(\d\d?\d\d)$/, d:1, m:3, y:5 },  // dmy
                        { regExp:/^(\d\d?\d\d)([- \/.])(0[1-9]|1[012])([- \/.])(0[1-9]|[12][0-9]|3[01])$/, d:5, m:3, y:1 }   // ymd
                        ];

                var start;
                var cnt = 0;
                var numFormats = dateTest.length;

                while(cnt < numFormats) {
                        start = (cnt + (favourDMY ? numFormats + 1 : numFormats)) % numFormats;

                        if(dateIn.match(dateTest[start].regExp)) {
                                res = dateIn.match(dateTest[start].regExp);
                                y = res[dateTest[start].y];
                                m = res[dateTest[start].m];
                                d = res[dateTest[start].d];
                                if(m.length == 1) m = "0" + String(m);
                                if(d.length == 1) d = "0" + String(d);
                                if(y.length != 4) y = (parseInt(y) < 50) ? "20" + String(y) : "19" + String(y);

                                return y+String(m)+d;
                        }

                        cnt++;
                }

                return 0;
        },

        sortDate: function(a,b) {
                var aa = a[fdTableSort.pos];
                var bb = b[fdTableSort.pos];

                return aa - bb;
        },

        sortNumeric:function (a,b) {
                var aa = a[fdTableSort.pos];
                var bb = b[fdTableSort.pos];

                if(aa === "" && !isNaN(bb)) return -1;
                else if(bb === "" && !isNaN(aa)) return 1;
                else if(aa == bb) return 0;

                return aa - bb;
        },

        sortText:function (a,b) {
                var aa = a[fdTableSort.pos];
                var bb = b[fdTableSort.pos];

                if(aa == bb) return 0;
                if(aa < bb)  return -1;

                return 1;
        }
};

fdTableSort.addEvent(window, "load", fdTableSort.init);




/* The sortCompleteCallback does nothing but call the pagination object below, passing in the table id */
function sortCompleteCallback(tableId) {
        tablePaginater.showPage(tableId);
}

/* This is the JS object that paginates the table (N.B: Tables do not have to be sortable to use this object) */
var tablePaginater = {
        tableInfo: {},

        init: function() {
                var tables = document.getElementsByTagName('table');

                for(var t = 0, tbl; tbl = tables[t]; t++) {
                        if(!tbl.id || tbl.id == "" || tbl.className.search(/paginate-([0-9]+)/) == -1) continue;

                        tablePaginater.tableInfo[tbl.id] = {
                                rowsPerPage:tbl.className.match(/paginate-([0-9]+)/)[1],
                                currentPage:0
                        };

                        tablePaginater.showPage(tbl.id, 0);
                        tablePaginater.createPageinationList(tbl.id);
                };
        },

        addClass: function(e,c) {
                if(new RegExp("(^|\\s)" + c + "(\\s|$)").test(e.className)) return;
                e.className += ( e.className ? " " : "" ) + c;
        },

        removeClass: function(e,c) {
                e.className = !c ? "" : e.className.replace(new RegExp("(^|\\s*\\b[^-])"+c+"($|\\b(?=[^-]))", "g"), "");
        },

        /* I shall leave it as an excercise for the reader to create the next|prev links commonly
           found within such pagination systems. I'm just creating a simple linked list for the moment. */
        createPageinationList: function(tableId) {
                var tbl = document.getElementById(tableId);

                var ul = document.createElement("ul");
                ul.className = "tablePaginater";
                ul.id = "paginateList-" + tableId;

                var rows = tablePaginater.getTableRows(tableId);

                var total = rows.length;
                var rowsPerPage
                var pages = Math.max(1, Math.ceil(total / tablePaginater.tableInfo[tableId].rowsPerPage));

                var li = document.createElement("li");
                var a  = document.createElement("a");
                a.href = "#";

                var lic, ac;

                for(var i = 0; i < pages; i++) {
                        lic = li.cloneNode(false);
                        ac  = a.cloneNode(true);
                        ac.title = "View page " + (i + 1);
                        ac.appendChild(document.createTextNode(i+1));
                        lic.onclick = a.onclick = tablePaginater.show;
                        lic.appendChild(ac);

                        if(i == 0) lic.className = "currentPage";

                        ul.appendChild(lic);
                };

                if(tbl.nextSibling) {
                        tbl.parentNode.insertBefore(ul, tbl.nextSibling);
                } else {
                        tbl.parentNode.appendChild(ul);
                };
        },

        getTableRows: function(tableId) {
                var rows = [];
                var tbl = document.getElementById(tableId);

                var tbody = tbl.getElementsByTagName('tbody');

                if(tbody && tbody.length) {
                        tbody = tbody[0];
                        rows = tbody.getElementsByTagName('tr');
                } else {
                        var tmp = tbl.getElementsByTagName('tr');
                        for(var i = tmp.length; i--;) {
                                if(tmp[i].getElementsByTagName('th')) continue;
                                rows[rows.length] = tmp[i];
                        };
                };
                return rows;
        },

        showPage: function(tableId, page) {
                if(!(tableId in tablePaginater.tableInfo)) return;

                var tbl = document.getElementById(tableId);
                var rowsPerPage = tablePaginater.tableInfo[tableId].rowsPerPage;
                var rows = tablePaginater.getTableRows(tableId);

                page = typeof page == "undefined" ? tablePaginater.tableInfo[tableId].currentPage : page;

                var min = rowsPerPage * page;
                var max = Number(min) + Number(rowsPerPage);
                var rowStyle = tbl.className.search(/rowstyle-([\S]+)/) != -1 ? tbl.className.match(/rowstyle-([\S]+)/)[1] : false;
                var cnt = 0;
                var len = rows.length;

                for(var i = 0; i < len; i++) {
                        rows[i].style.display = (i >= min && i < max) ? "" : "none";
                        if(rowsPerPage % 2 && rowStyle && i >= min && i < max) {
                                tablePaginater.removeClass(rows[i], rowStyle);
                                if(cnt++ % 2) tablePaginater.addClass(rows[i], rowStyle);
                        };
                };

                tablePaginater.tableInfo[tableId].currentPage = page;
        },

        /* Event handler for the li|a click event */
        show: function() {
                var tableId = this.parentNode.id.replace("paginateList-", "");
                var cnt = 0;
                var li = this;
                while(li.previousSibling) {
                        li = li.previousSibling;
                        if(li.tagName && li.tagName.toLowerCase() == "li") cnt++;
                };

                tablePaginater.showPage(tableId, cnt);

                var ul = this.parentNode;
                var i = 0;
                while(ul.childNodes[i]) {
                        ul.childNodes[i].className = i == cnt ? "currentPage" : "";
                        i++;
                };
                return false;
        }
}

fdTableSort.addEvent(window, "load", tablePaginater.init);




/*
        TableSort zebraStripe & Hover plug-in v1.0 by frequency-decoder.com

        Released under a creative commons Attribution-ShareAlike 2.5 license (http://creativecommons.org/licenses/by-sa/2.5/)

        Please credit frequency decoder in any derivative work - thanks

        You are free:

        * to copy, distribute, display, and perform the work
        * to make derivative works
        * to make commercial use of the work

        Under the following conditions:

                by Attribution.
                --------------
                You must attribute the work in the manner specified by the author or licensor.

                sa
                --
                Share Alike. If you alter, transform, or build upon this work, you may distribute the resulting work only under a license identical to this one.

        * For any reuse or distribution, you must make clear to others the license terms of this work.
        * Any of these conditions can be waived if you get permission from the copyright holder.
*/
function initialZebraStripe() {
        var tables = document.getElementsByTagName("table");
        var rowStyle, start, trs;

        // Loop through all the tables
        for(var k = 0, table; table = tables[k]; k++) {
                // If the table has not a rowstyle-XXX className or it will be sorted onLoad anyway then continue
                if(table.className.search(/rowstyle-([\S]+)/) == -1 || table.className.search(/sortable-onload/) != -1) continue;

                // Grab the className for the alternate rows
                rowStyle = table.className.match(/rowstyle-([\S]+)/)[1];

                // Grab the table's TR nodes
                start = table.getElementsByTagName('tbody');
                start = start.length ? start[0] : table;
                trs   = start.getElementsByTagName('tr');

                // Loop through the TR node list
                for(var i = 0, tr; tr = trs[i]; i++) {
                        // Have we any th tags or are we in a tfoot ?
                        if(tr.getElementsByTagName('th').length > 0 || (tr.parentNode && tr.parentNode.tagName.toLowerCase() == "tfoot")) continue;

                        // Stripe the TR
                        if(i % 2) fdTableSort.addClass(tr, rowStyle);
                        else fdTableSort.removeClass(tr, rowStyle);

                        // Do the Internet Explorer hover thang (using conditional compilation for this...)
                        // Note: If you don't want to add the hover effect for Internet Explorer, just remove the code
                        /*@cc_on
                                /*@if (@_jscript_version >= 5)
                                fdTableSort.addEvent(tr, "mouseover", function() { fdTableSort.addClass(this, this.className.search("alternative") == -1 ? "ieRowHover" : "ieRowHoverAlt"); });
                                fdTableSort.addEvent(tr, "mouseout",  function() { fdTableSort.removeClass(this, this.className.search("alternative") == -1 ? "ieRowHover" : "ieRowHoverAlt"); });
                                /*@end
                        @*/
                };
        };
};

fdTableSort.addEvent(window, "load", initialZebraStripe);
