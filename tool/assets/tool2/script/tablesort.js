/*
        TableSort revisited v3.8 by frequency-decoder.com

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

(function() {

fdTableSort = {
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

        addEvent: function addEvent(obj, type, fn, tmp) {
                tmp || (tmp = true);
                if( obj.attachEvent ) {
                        obj["e"+type+fn] = fn;
                        obj[type+fn] = function(){obj["e"+type+fn]( window.event );};
                        obj.attachEvent( "on"+type, obj[type+fn] );
                } else {
                        obj.addEventListener( type, fn, true );
                };
        },
        
        removeEvent: function removeEvent(obj, type, fn, tmp) {
                tmp || (tmp = true);
                try {
                        if( obj.detachEvent ) {
                                obj.detachEvent( "on"+type, obj[type+fn] );
                                obj[type+fn] = null;
                        } else {
                                obj.removeEventListener( type, fn, true );
                        };
                } catch(err) {};
        },

        stopEvent: function stopEvent(e) {
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
        
        initEvt: function initEvent(e) {
                fdTableSort.init(false);
        },
        
        init: function init(tableId) {
                if (!document.getElementsByTagName || !document.createElement || !document.getElementById) return;

                var tables = tableId && document.getElementById(tableId) ? new Array(document.getElementById(tableId)) : document.getElementsByTagName("table");
                var workArr, sortable, headers, thtext, aclone, a, span, columnNum, noArrow, colCnt, cel, allRowArr, rowArr, sortableTable, celCount, colspan, rowspan, rowLength;
                var onLoadTables = [];

                a               = document.createElement("a");
                a.href          = "#";
                span            = document.createElement("span");

                for(var k = 0, tbl; tbl = tables[k]; k++) {

                        // Remove any old dataObj for this table (tables created from an ajax callback require this)
                        if(tbl.id) fdTableSort.removeTableCache(tbl.id);

                        // Remove any old tmpCache object for this table
                        if(tbl.id) fdTableSort.removeTmpCache(tbl.id);

                        allRowArr = tbl.getElementsByTagName('thead').length ? tbl.getElementsByTagName('thead')[0].getElementsByTagName('tr') : tbl.getElementsByTagName('tr');
                        rowArr = [];
                        sortableTable = false;

                        // Grab only the tr's that contain no td's and check at least one th has the class "sortable"
                        for(var i = 0, tr; tr = allRowArr[i]; i++) {
                                if(tr.getElementsByTagName('td').length || !tr.getElementsByTagName('th').length) continue;
                                rowArr[rowArr.length] = tr.getElementsByTagName('th');
                                for(var j = 0, th; th = rowArr[rowArr.length - 1][j]; j++) {
                                        if(th.className.search(/sortable/) != -1) sortableTable = true;
                                };
                        };

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

                                        if(workArr[c][i].className.search("fd-column-") == -1 && workArr[c][i].className.search("sortable") != -1) workArr[c][i].className = workArr[c][i].className + " fd-column-" + i;

                                        if(workArr[c][i].className.match('sortable')) {
                                                workArr[c][i].className = workArr[c][i].className.replace(/forwardSort|reverseSort/, "");

                                                if(i == columnNum) sortable = workArr[c][i];
                                                thtext = fdTableSort.getInnerText(workArr[c][i]);

                                                if(workArr[c][i].getElementsByTagName && workArr[c][i].getElementsByTagName('a').length) {
                                                        workArr[c][i].getElementsByTagName('a')[0].onclick = workArr[c][i].getElementsByTagName('a')[0].onkeydown = null;
                                                }

                                                while(workArr[c][i].firstChild) workArr[c][i].removeChild(workArr[c][i].firstChild);

                                                // Create the link
                                                aclone = a.cloneNode(true);
                                                aclone.appendChild(document.createTextNode(thtext));
                                                aclone.title = "Sort on \u201c" + thtext + "\u201d";
                                                aclone.onclick = aclone.onkeydown = workArr[c][i].onclick = fdTableSort.initWrapper;
                                                workArr[c][i].appendChild(aclone);

                                                // Add the span if needs be
                                                if(showArrow) workArr[c][i].appendChild(span.cloneNode(false));

                                                workArr[c][i].className = workArr[c][i].className.replace(/fd-identical|fd-not-identical/, "");
                                                fdTableSort.disableSelection(workArr[c][i]);
                                                aclone = null;
                                        };
                                };
                        };

                        fdTableSort.tmpCache[tbl.id] = {cols:rowLength, headers:workArr};

                        workArr = null;

                        if(sortable) {
                                onLoadTables[onLoadTables.length] = sortable;
                                if(reverse) { onLoadTables[onLoadTables.length] = sortable; };
                        };
                };

                for(var i = 0, thNode; thNode = onLoadTables[i]; i++) {
                        fdTableSort.thNode = thNode;
                        fdTableSort.initSort(false);
                };

                aclone = a = span = workArr = sortable = thNode = onLoadTables = tbl = allRowArr = rowArr = null;
        },

        disableSelection: function disableSelection(element) {
                element.onselectstart = function() {
                        return false;
                };
                element.unselectable = "on";
                element.style.MozUserSelect = "none";
        },

        initWrapper: function initWrapper(e) {
                e = e || window.event;
                var kc = e.type == "keydown" ? e.keyCode != null ? e.keyCode : e.charCode : -1;

                if(fdTableSort.thNode == null && (e.type == "click" || kc == 13)) {
                        var targ = this;
                        while(targ.tagName.toLowerCase() != "th") targ = targ.parentNode;
                        fdTableSort.thNode = targ;
                        fdTableSort.addSortActiveClass();
                        fdTableSort.multi = e.shiftKey;
                        setTimeout(fdTableSort.initSort,5,false);
                        return fdTableSort.stopEvent(e);
                };
                return kc != -1 ? true : false;
        },

        jsWrapper: function jsWrapper(tableid, colNums) {

                if(!(colNums instanceof Array)) colNums = [colNums];
                
                if(!(tableid in fdTableSort.tmpCache)) { return false; }
                if(!(tableid in fdTableSort.tableCache)) {
                        fdTableSort.prepareTableData(document.getElementById(tableid));
                };
                
                fdTableSort.tableId = tableid;
                var len = colNums.length, colNum;
                
                if(fdTableSort.tableCache[tableid].thList.length == colNums.length) {
                        var identical = true;
                        var th;
                        for(var i = 0; i < len; i++) {
                                colNum = colNums[i];
                                th = fdTableSort.tmpCache[tableid].headers[0][colNum];
                                if(th != fdTableSort.tableCache[tableid].thList[i]) {
                                        identical = false;
                                        break;
                                };
                        };
                        if(identical) {
                                fdTableSort.thNode = th;
                                fdTableSort.initSort(true);
                                return;
                        };
                };

                for(var i = 0; i < len; i++) {
                        fdTableSort.multi = i;
                        colNum = colNums[i];
                        fdTableSort.thNode = fdTableSort.tmpCache[tableid].headers[0][colNum];
                        fdTableSort.initSort(true);
                };
        },

        addSortActiveClass: function addSortActiveClass() {
                if(fdTableSort.thNode == null) return;
                fdTableSort.addClass(fdTableSort.thNode, "sort-active");
                fdTableSort.addClass(document.getElementsByTagName('body')[0], "sort-active");
                var tableElem = fdTableSort.thNode;
                while(tableElem.tagName.toLowerCase() != 'table' && tableElem.parentNode) {
                        tableElem = tableElem.parentNode;
                };
                if("sortInitiatedCallback" + tableElem.id in window) {
                        window["sortInitiatedCallback" + tableElem.id]();
                } else if("sortInitiatedCallback" in window) {
                        sortInitiatedCallback(tableElem.id);
                };
        },

        removeSortActiveClass: function removeSortActiveClass() {
                fdTableSort.removeClass(fdTableSort.thNode, "sort-active");
                fdTableSort.removeClass(document.getElementsByTagName('body')[0], "sort-active");
                var tableElem = fdTableSort.thNode;
                while(tableElem.tagName.toLowerCase() != 'table' && tableElem.parentNode) {
                        tableElem = tableElem.parentNode;
                };
                if("sortCompleteCallback" + tableElem.id in window) {
                        window["sortCompleteCallback" + tableElem.id]();
                } else if("sortCompleteCallback" in window) {
                        sortCompleteCallback(tableElem.id);
                };
        },

        addClass: function addClass(e,c) {
                if(new RegExp("(^|\\s)" + c + "(\\s|$)").test(e.className)) return;
                e.className += ( e.className ? " " : "" ) + c;
        },

        removeClass: function removeClass(e,c) {
                e.className = !c ? "" : e.className.replace(new RegExp("(^|\\s*\\b[^-])"+c+"($|\\b(?=[^-]))", "g"), "");
        },

        prepareTableData: function prepareTableData(table) {
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

                for(var tmp = 0; tmp < numberOfCols; tmp++) identical[tmp] = true;

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

                        if(tr.parentNode != start || tr.getElementsByTagName("th").length || (tr.parentNode.tagName && tr.parentNode.tagName.toLowerCase() == "tfoot")) continue;

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
                                } else if (th.className.search(/sortable-keep/) != -1) {
                                        txt = rowCnt;
                                } else if(th.className.search(/sortable-([a-zA-Z\_]+)/) != -1) {
                                        if((th.className.match(/sortable-([a-zA-Z\_]+)/)[1] + "PrepareData") in window) {
                                                txt = window[th.className.match(/sortable-([a-zA-Z\_]+)/)[1] + "PrepareData"](td, txt);
                                        };
                                } else if(txt != "") {
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

                                if(rowCnt > 0 && identical[col] && identVal[col] != txt) {
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
                fdTableSort.tableCache[table.id] = { thList:[], colOrder:{}, data:data, identical:identical, colStyle:colStyle, rowStyle:rowStyle, noArrow:table.className.search(/no-arrow/) != -1 };
                sortableColumnNumbers = data = tr = td = th = trs = identical = identVal = null;
        },

        onUnload: function onUnload() {
                for(tbl in fdTableSort.tableCache) {
                        fdTableSort.removeTableCache(tbl);
                }
                for(tbl in fdTableSort.tmpCache) {
                        fdTableSort.removeTmpCache(tbl);
                }
                fdTableSort.removeEvent(window, "load", fdTableSort.initEvt);
                fdTableSort.removeEvent(window, "unload", fdTableSort.onUnload);
                fdTableSort.tmpCache = fdTableSort.tableCache = null;
        },

        removeTableCache: function removeTableCache(tableId) {
                if(!(tableId in fdTableSort.tableCache)) return;

                var data = fdTableSort.tableCache[tableId].data;
                for(var i = 0, row; row = data[i]; i++) {
                        row[row.length - 1] = null;
                };
                data = row = null;
                fdTableSort.tableCache[tableId] = null;
                delete fdTableSort.tableCache[tableId];

                var tbl = document.getElementById(tableId);
                if(!tbl) return;
                var ths = tbl.getElementsByTagName("th");
                var a;
                for(var i = 0, th; th = ths[i]; i++) {
                        a = th.getElementsByTagName("a");
                        if(a.length) a[0].onkeydown = a[0].onclick = null;
                        th.onclick = th.onselectstart = th = a = null;
                };
        },

        removeTmpCache: function removeTmpCache(tableId) {
                if(!(tableId in fdTableSort.tmpCache)) return;
                var headers = fdTableSort.tmpCache[tableId].headers;
                var a;
                for(var i = 0, row; row = headers[i]; i++) {
                        for(var j = 0, th; th = row[j]; j++) {
                                a = th.getElementsByTagName("a");
                                if(a.length) a[0].onkeydown = a[0].onclick = null;
                                th.onclick = th.onselectstart = th = a = null;
                        };
                };
                fdTableSort.tmpCache[tableId] = null;
                delete fdTableSort.tmpCache[tableId];
        },
        addThNode: function addThNode() {
                var dataObj = fdTableSort.tableCache[fdTableSort.tableId];
                var pos     = fdTableSort.thNode.className.match(/fd-column-([0-9]+)/)[1];
                var alt     = false;
                
                dataObj.colOrder = {};
                
                if(!fdTableSort.multi) {
                        if(dataObj.colStyle) {
                                var len = dataObj.thList.length;
                                for(var i = 0; i < len; i++) {
                                        dataObj.colOrder[dataObj.thList[i].className.match(/fd-column-([0-9]+)/)[1]] = false;
                                };
                        };
                        if(dataObj.thList[0] == fdTableSort.thNode) alt = true;
                        dataObj.thList = [];
                };

                var found = false;
                var l = dataObj.thList.length;

                for(var i = 0, n; n = dataObj.thList[i]; i++) {
                        if(n == fdTableSort.thNode) {
                                found = true;
                                break;
                        };
                };

                if(!found) {
                        dataObj.thList.push(fdTableSort.thNode);
                        if(dataObj.colStyle) { dataObj.colOrder[pos] = true; };
                };

                var ths = document.getElementById(fdTableSort.tableId).getElementsByTagName("th");
                for(var i = 0, th; th = ths[i]; i++) {
                        found = false;
                        for(var z = 0, n; n = dataObj.thList[z]; z++) {
                                if(n == th) {
                                        found = true;
                                        break;
                                };
                        };
                        if(!found) {
                                fdTableSort.removeClass(th, "(forwardSort|reverseSort)");
                                if(!dataObj.noArrow) {
                                        span = th.getElementsByTagName('span');
                                        if(span.length) {
                                                span = span[0];
                                                while(span.firstChild) span.removeChild(span.firstChild);
                                        };
                                };
                        };
                };
                
                if(dataObj.thList.length > 1) {
                        classToAdd = fdTableSort.thNode.className.search(/forwardSort/) != -1 ? "reverseSort" : "forwardSort";
                        fdTableSort.removeClass(fdTableSort.thNode, "(forwardSort|reverseSort)");
                        fdTableSort.addClass(fdTableSort.thNode, classToAdd);
                        dataObj.pos = -1
                } else if(alt) { dataObj.pos = fdTableSort.thNode };
        },

        initSort: function initSort(noCallback) {
                var thNode      = fdTableSort.thNode;

                // Get the table
                var tableElem   = thNode;
                while(tableElem.tagName.toLowerCase() != 'table' && tableElem.parentNode) {
                        tableElem = tableElem.parentNode;
                };

                // If this is the first time that this table has been sorted, create the data object
                if(!tableElem.id || !(tableElem.id in fdTableSort.tableCache)) {
                        fdTableSort.prepareTableData(tableElem);
                };

                // Cache the table id
                fdTableSort.tableId = tableElem.id;

                fdTableSort.addThNode();
                
                // Get the column position using the className added earlier
                fdTableSort.pos = thNode.className.match(/fd-column-([0-9]+)/)[1];

                // Grab the data object for this table
                var dataObj     = fdTableSort.tableCache[tableElem.id];

                // Get the position of the last column that was sorted
                var lastPos     = dataObj.pos && dataObj.pos.className ? dataObj.pos.className.match(/fd-column-([0-9]+)/)[1] : -1;

                // Get the stored data object for this table
                var len1        = dataObj.data.length;
                var len2        = dataObj.data.length > 0 ? dataObj.data[0].length - 1 : 0;
                var identical   = dataObj.identical[fdTableSort.pos];

                // If the same column is being sorted then just reverse the data object contents.
                var classToAdd = "forwardSort";

                if(dataObj.thList.length > 1) {
                        // Multi sort

                        var js  = "var sortWrapper = function(a,b) {\n";
                        var l   = dataObj.thList.length;
                        var cnt = 0;
                        var e,d,th,p,f;
                       
                        for(var i=0; i < l; i++) {
                                th = dataObj.thList[i];
                                p  = th.className.match(/fd-column-([0-9]+)/)[1];
                                if(dataObj.identical[p]) { continue; };
                                cnt++;
                                
                                if(th.className.match(/sortable-(numeric|currency|date|keep)/)) {
                                        f = "fdTableSort.sortNumeric";
                                } else if(th.className.match('sortable-text')) {
                                        f = "fdTableSort.sortText";
                                } else if(th.className.search(/sortable-([a-zA-Z\_]+)/) != -1 && th.className.match(/sortable-([a-zA-Z\_]+)/)[1] in window) {
                                        f = "window['" + th.className.match(/sortable-([a-zA-Z\_]+)/)[1] + "']";
                                } else  f = "fdTableSort.sortText";
                                
                                e = "e" + i;
                                d = th.className.search('forwardSort') != -1 ? "a,b" : "b,a";
                                js += "fdTableSort.pos   = " + p + ";\n";
                                js += "var " + e + " = "+f+"(" + d +");\n";
                                js += "if(" + e + ") return " + e + ";\n";
                                js += "else { \n";
                        };

                        js += "return 0;\n";
                                
                        for(var i=0; i < cnt; i++) {
                                js += "};\n";
                        };

                        if(cnt) js += "return 0;\n";
                        js += "};\n";

                        eval(js);
                        dataObj.data.sort(sortWrapper);
                        identical = false;
                        
                } else if((lastPos == fdTableSort.pos && !identical) || (thNode.className.search(/sortable-keep/) != -1 && lastPos == -1)) {
                        dataObj.data.reverse();
                        classToAdd = thNode.className.search(/reverseSort/) != -1 ? "forwardSort" : "reverseSort";
                        if(thNode.className.search(/sortable-keep/) != -1 && lastPos == -1) fdTableSort.tableCache[tableElem.id].pos = thNode;
                } else {
                        fdTableSort.tableCache[tableElem.id].pos = thNode;
                        classToAdd = thNode.className.search(/forwardSort/) != -1 ? "reverseSort" : "forwardSort";
                        if(!identical) {
                                if(thNode.className.match(/sortable-(numeric|currency|date|keep)/)) {
                                        dataObj.data.sort(fdTableSort.sortNumeric);
                                } else if(thNode.className.match('sortable-text')) {
                                        dataObj.data.sort(fdTableSort.sortText);
                                } else if(thNode.className.search(/sortable-([a-zA-Z\_]+)/) != -1 && thNode.className.match(/sortable-([a-zA-Z\_]+)/)[1] in window) {
                                        dataObj.data.sort(window[thNode.className.match(/sortable-([a-zA-Z\_]+)/)[1]]);
                                };
                        };
                };

                if(dataObj.thList.length == 1) {
                        fdTableSort.removeClass(thNode, "(forwardSort|reverseSort)");
                        fdTableSort.addClass(thNode, classToAdd);
                };

                if(!dataObj.noArrow) {
                        var span = fdTableSort.thNode.getElementsByTagName('span')[0];
                        if(span.firstChild) span.removeChild(span.firstChild);
                        span.appendChild(document.createTextNode(fdTableSort.thNode.className.search(/forwardSort/) != -1 ? " \u2193" : " \u2191"));
                };

                if(!dataObj.rowStyle && !dataObj.colStyle && identical) {
                        if(!noCallback) fdTableSort.removeSortActiveClass();
                        fdTableSort.thNode = null;
                        return;
                };

                var hook = tableElem.getElementsByTagName('tbody');
                hook = hook.length ? hook[0] : tableElem;

                var tr, tds;
                var rowReg = dataObj.rowStyle ? new RegExp("(^|\\s*\\b[^-])"+dataObj.rowStyle+"($|\\b(?=[^-]))", "g") : false;
                var colReg = dataObj.colStyle ? new RegExp("(^|\\s*\\b[^-])"+dataObj.colStyle+"($|\\b(?=[^-]))", "g") : false;

                for(var i = 0; i < len1; i++) {
                        tr = dataObj.data[i][len2];
                        if(dataObj.colStyle) {
                                tds = tr.getElementsByTagName('td');
                                for(thPos in dataObj.colOrder) {
                                        if(!dataObj.colOrder[thPos]) tds[thPos].className = tds[thPos].className.replace(colReg, "");
                                        else fdTableSort.addClass(tds[thPos], dataObj.colStyle);
                                };
                                tds = null;
                        };
                        if(!identical) {
                                if(dataObj.rowStyle) {
                                        if(i % 2) fdTableSort.addClass(tr, dataObj.rowStyle);
                                        else tr.className = tr.className.replace(rowReg, "");
                                };

                                hook.removeChild(tr); // Netscape 8.1.2 requires the removeChild call or it freaks out
                                hook.appendChild(tr);
                        };
                        tr = null;
                };
                if(!noCallback) fdTableSort.removeSortActiveClass();
                fdTableSort.thNode = hook = null;
        },

        getInnerText: function getInnerText(el) {
                if (typeof el == "string" || typeof el == "undefined") return el;
                if(el.innerText) return el.innerText;
                var txt = '', i;
                for(i = el.firstChild; i; i = i.nextSibling) {
                        if(i.nodeType == 3)            txt += i.nodeValue;
                        else if(i.nodeType == 1)       txt += fdTableSort.getInnerText(i);
                };
                return txt;
        },

        dateFormat: function dateFormat(dateIn, favourDMY) {
                var dateTest = [
                        { regExp:/^(0?[1-9]|1[012])([- \/.])(0?[1-9]|[12][0-9]|3[01])([- \/.])((\d\d)?\d\d)$/, d:3, m:1, y:5 },  // mdy
                        { regExp:/^(0?[1-9]|[12][0-9]|3[01])([- \/.])(0?[1-9]|1[012])([- \/.])((\d\d)?\d\d)$/, d:1, m:3, y:5 },  // dmy
                        { regExp:/^(\d\d\d\d)([- \/.])(0?[1-9]|1[012])([- \/.])(0?[1-9]|[12][0-9]|3[01])$/, d:5, m:3, y:1 }      // ymd
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
                        };
                        cnt++;
                };
                return 0;
        },

        sortNumeric:function sortNumeric(a,b) {
                var aa = a[fdTableSort.pos];
                var bb = b[fdTableSort.pos];
                if(aa == bb) return 0;
                if(aa === "" && !isNaN(bb)) return -1;
                if(bb === "" && !isNaN(aa)) return 1;
                return aa - bb;
        },

        sortText:function sortText(a,b) {
                var aa = a[fdTableSort.pos];
                var bb = b[fdTableSort.pos];
                if(aa == bb) return 0;
                if(aa < bb)  return -1;
                return 1;
        }
};

})();

fdTableSort.addEvent(window, "load",   fdTableSort.initEvt);
fdTableSort.addEvent(window, "unload", fdTableSort.onUnload);

/*
        TableSort zebraStripe & Hover plug-in v1.1 by frequency-decoder.com

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
                // If the table has not a rowstyle-XXX className then continue
                if(table.className.search(/rowstyle-([\S]+)/) == -1) continue;

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
                        // Note: Uncomment the code below should you wish the hover effect for IE and change the className "alternative" to suit your installation
                        /*@cc_on
                                /*@if (@_jscript_version >= 5)
                                // fdTableSort.addEvent(tr, "mouseover", function() { fdTableSort.addClass(this, this.className.search("alternative") == -1 ? "ieRowHover" : "ieRowHoverAlt"); });
                                // fdTableSort.addEvent(tr, "mouseout",  function() { fdTableSort.removeClass(this, this.className.search("alternative") == -1 ? "ieRowHover" : "ieRowHoverAlt"); });
                                /*@end
                        @*/
                };
        };
};

fdTableSort.addEvent(window, "load", initialZebraStripe);

/*
    sortEnglishDateTime
    -----------------------

    This function sorts English dateTime vaues such as:

    1st January 2003, 23:32:01
    23/03/1972 Ã  10:22:22
    1970/13/03 at 23:22:01
    
    The function is "safe" i.e. non-dateTime data (like the word "Unknown") can be passed in and is sorted properly.
*/
var sortEnglishDateTime = fdTableSort.sortNumeric;

function sortEnglishDateTimePrepareData(tdNode, innerText) {
        // You can localise the function here
        var months = ['january','february','march','april','may','june','july','august','september','october','november','december','jan','feb','mar','apr','may','jun','jul','aug','sep','oct','nov','dec'];

        // Lowercase the text
        var aa = innerText.toLowerCase();

        // Replace the longhand months with an integer equivalent
        for(var i = 0; i < months.length; i++) {
                aa = aa.replace(months[i], (i+13)%12);
        };

        // Replace multiple spaces and anything that is not valid in the parsing of the date, then trim
        aa = aa.replace(/\s+/g, " ").replace(/([^\d\s\/-:.])/g, "").replace(/^\s\s*/, '').replace(/\s\s*$/, '');

        // No timestamp at the end, then return -1
        if(aa.search(/(\d){2}:(\d){2}(:(\d){2})?$/) == -1) { return -1; };

        // Grab the timestamp
        var timestamp = aa.match(/(\d){2}:(\d){2}(:(\d){2})?$/)[0].replace(/:/g, "");

        // Make the timestamp 6 characters by default
        if(timestamp.length == 4) { timestamp += "00"; };

        // Remove it from the string to assist the date parser, then trim
        aa = aa.replace(/(\d){2}:(\d){2}(:(\d){2})?$/, "").replace(/\s\s*$/, '');

        // If you want the parser to favour the parsing of European dd/mm/yyyy dates then leave this set to "true"
        // If you want the parser to favour the parsing of American mm/dd/yyyy dates then set to "false"
        var favourDMY = true;

        // If you have a regular expression you wish to add, add the Object to the end of the array
        var dateTest = [
                       { regExp:/^(0?[1-9]|1[012])([- \/.])(0?[1-9]|[12][0-9]|3[01])([- \/.])((\d\d)?\d\d)$/, d:3, m:1, y:5 },  // mdy
                       { regExp:/^(0?[1-9]|[12][0-9]|3[01])([- \/.])(0?[1-9]|1[012])([- \/.])((\d\d)?\d\d)$/, d:1, m:3, y:5 },  // dmy
                       { regExp:/^(\d\d\d\d)([- \/.])(0?[1-9]|1[012])([- \/.])(0?[1-9]|[12][0-9]|3[01])$/, d:5, m:3, y:1 }      // ymd
                       ];

        var start,y,m,d;
        var cnt = 0;
        var numFormats = dateTest.length;
        while(cnt < numFormats) {
               start = (cnt + (favourDMY ? numFormats + 1 : numFormats)) % numFormats;
               if(aa.match(dateTest[start].regExp)) {
                       res = aa.match(dateTest[start].regExp);
                       y = res[dateTest[start].y];
                       m = res[dateTest[start].m];
                       d = res[dateTest[start].d];
                       if(m.length == 1) m = "0" + String(m);
                       if(d.length == 1) d = "0" + String(d);
                       if(y.length != 4) y = (parseInt(y) < 50) ? "20" + String(y) : "19" + String(y);

                       return y+String(m)+d+String(timestamp);
               };
               cnt++;
        };
        return -1;
};

/*
    sortAlphaNumeric
    -----------------------

    This function sorts alphaNumeric values e.g. 1, e, 1a, -23c, 54z
    
    Notice how the prepareData function actually returns an Array i.e. you are not limited
    in the type of data you return to the tableSort script.
*/
function sortAlphaNumericPrepareData(tdNode, innerText){
        var aa = innerText.toLowerCase().replace(" ", "");
        var reg = /((\-|\+)?(\s+)?[0-9]+\.([0-9]+)?|(\-|\+)?(\s+)?(\.)?[0-9]+)([a-z]+)/;

        if(reg.test(aa)) {
                var aaP = aa.match(reg);
                return [aaP[1], aaP[8]];
        };

        // Return an array
        return isNaN(aa) ? ["",aa] : [aa,""];
}

function sortAlphaNumeric(a, b){
        // Get the previously prepared array
        var aa = a[fdTableSort.pos];
        var bb = b[fdTableSort.pos];

        // If they are equal then return 0
        if(aa[0] == bb[0] && aa[1] == bb[1]) { return 0; };

        // Check numeric parts if not equal
        if(aa[0] != bb[0]) {
                if(aa[0] != "" && bb[0] != "") { return aa[0] - bb[0]; };
                if(aa[0] == "" && bb[0] != "") { return -1; };
                return 1;
        };
        
        // Check alpha parts if numeric parts equal
        if(aa[1] == bb[1]) return 0;
        if(aa[1] < bb[1])  return -1;
        return 1;
}

/*
    sortDutchCurrencyValues
    -----------------------

    This function sorts Dutch currency values (of the type 100.000,00)
    The function is "safe" i.e. non-currency data (like the word "Unknown") can be passed in and is sorted properly.
*/
var sortDutchCurrencyValues = fdTableSort.sortNumeric;

function sortDutchCurrencyValuesPrepareData(tdNode, innerText) {
        innerText = parseInt(innerText.replace(/[^0-9\.,]+/g, "").replace(/\./g,"").replace(",","."));
        return isNaN(innerText) ? "" : innerText;
}

/*
   sortByTwelveHourTimestamp
   -------------------------

   This custom sort function sorts 12 hour timestamps of an hour/minute nature.
   The hour/minute dividor can be a full-stop or a colon and it correctly calculates that 12.30am is before 1am etc
   The am/pm part can be written in lower or uppercase and can optionally contain full-stops e.g.

   am, a.m, a.m., AM, A.M etc

   Additionally, the values "12 midnight" and "12 noon" are also handled correctly.

   The question remains... does "12p.m." mean "midnight" or "12 noon"? I've decided here that it's 12 noon.

   The function is "safe" i.e. non-timestamp data (like the word "Unknown") can be passed in and is sorted properly.
*/
var sortByTwelveHourTimestamp = fdTableSort.sortNumeric;

function sortByTwelveHourTimestampPrepareData(tdNode, innerText) {
        tmp = innerText
        innerText = innerText.replace(":",".");

        // Check for the special cases of "12 noon" or "12 midnight"
        if(innerText.search(/12([\s]*)?noon/i) != -1) return "12.00";
        if(innerText.search(/12([\s]*)?midnight/i) != -1) return "24.00";

        var regExpPM = /^([0-9]{1,2}).([0-9]{2})([\s]*)?(p[\.]?m)/i;
        var regExpAM = /^([0-9]{1,2}).([0-9]{2})([\s]*)?(a[\.]?m)/i;

        if(innerText.search(regExpPM) != -1) {
                var bits = innerText.match(regExpPM);
                if(parseInt(bits[1]) < 12) { bits[1] = parseInt(bits[1]) + 12; }
        } else if(innerText.search(regExpAM) != -1) {
                var bits = innerText.match(regExpAM);
                if(bits[1] == "12") { bits[1] = "00"; }
        } else return "";

        if(bits[2].length < 2) { bits[2] = "0" + String(bits[2]); }

        innerText = bits[1] + "." + String(bits[2]);

        return isNaN(innerText) ? "" : innerText;
}
/*
   sortEnglishLonghandDateFormat
   -----------------------------

   This custom sort function sorts dates of the format:

   "12th April, 2006" or "12 April 2006" or "12-4-2006" or "12 April" or "12 4" or "12 Apr 2006" etc

   The function expects dates to be in the format day/month/year. Should no year be stipulated,
   the function treats the year as being the current year.

   The function is "safe" i.e. non-date data (like the word "Unknown") can be passed in and is sorted properly.
*/
var sortEnglishLonghandDateFormat = fdTableSort.sortNumeric;

function sortEnglishLonghandDateFormatPrepareData(tdNode, innerText) {
        var months = ['january','february','march','april','may','june','july','august','september','october','november','december'];

        var aa = innerText.toLowerCase();

        // Replace the longhand months with an integer equivalent
        for(var i = 0; i < 12; i++) {
                aa = aa.replace(months[i], i+1).replace(months[i].substring(0,3), i+1);
        }

        // If there are still alpha characters then return -1
        if(aa.search(/a-z/) != -1) return -1;

        // Replace multiple spaces and anything that is not numeric

        aa = aa.replace(/\s+/g, " ").replace(/[^\d\s]/g, "");

        // If were left with nothing then return -1
        if(aa.replace(" ", "") == "") return -1;

        // Split on the (now) single spaces
        aa = aa.split(" ");

        // If something has gone terribly wrong then return -1
        if(aa.length < 2) return -1;

        // If no year stipulated, then add this year as default
        if(aa.length == 2) {
                aa[2] = String(new Date().getFullYear());
        }

        // Equalise the day and month
        if(aa[0].length < 2) aa[0] = "0" + String(aa[0]);
        if(aa[1].length < 2) aa[1] = "0" + String(aa[1]);

        // Deal with Y2K issues
        if(aa[2].length != 4) {
                aa[2] = (parseInt(aa[2]) < 50) ? '20' + aa[2] : '19' + aa[2];
        }

        // YMD (can be used as integer during comparison)
        return aa[2] + String(aa[1]) + aa[0];
}
/*
   sortIPAddress
   -------------

   This custom sort function correctly sorts IP addresses i.e. it checks all of the address parts and not just the first.

   The function is "safe" i.e. non-IP address data (like the word "Unknown") can be passed in and is sorted properly.
*/
var sortIPAddress = fdTableSort.sortNumeric;

function sortIPAddressPrepareData(tdNode, innerText) {
        // Get the innerText of the TR nodes
        var aa = innerText;

        // Remove spaces
        aa = aa.replace(" ","");

        // If not an IP address then return -1
        if(aa.search(/^([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3})$/) == -1) return -1;

        // Split on the "."
        aa = aa.split(".");

        // If we don't have 4 parts then return -1
        if(aa.length != 4) return -1;

        var retVal = "";

        // Make all the parts an equal length and create a master integer
        for(var i = 0; i < 4; i++) {
                retVal += (String(aa[i]).length < 3) ? "0000".substr(0, 3 - String(aa[i]).length) + String(aa[i]) : aa[i];
        }

        return retVal;
}
/*
   sortScientificNotation
   ----------------------

   This custom sort function sorts numbers stipulated in scientific notation

   The function is "safe" i.e. data like the word "Unknown" can be passed in and is sorted properly.

   N.B. The only way I can think to really sort scientific notation is to convert
        it to a floating point number and then perform the sort on that. If you can think of
        an easier/better way then please let me know.
*/
var sortScientificNotation = fdTableSort.sortNumeric;

function sortScientificNotationPrepareData(tdNode, innerText) {
        var aa = innerText;

        var floatRegExp = /((\-|\+)?(\s+)?[0-9]+\.([0-9]+)?|(\-|\+)?(\s+)?(\.)?[0-9]+)/g;

        aa = aa.match(floatRegExp);

        if(!aa || aa.length != 2) return "";

        var f1 = parseFloat(aa[0].replace(" ",""))*Math.pow(10,parseFloat(aa[1].replace(" ","")));
        return isNaN(f1) ? "" : f1;
}

/*
        sortImage
        ---------

        This is the function called in order to sort the data previously prepared by the function
        "sortImagePrepareData". It does a basic case sensitive comparison on the data using the
        tableSort's in-built sortText method.
*/
var sortImage = fdTableSort.sortText;

/*
        This is the function used to prepare i.e. parse data, to be used during the sort
        of the images within the last table.

        In this case, we are checking to see if the TD node has any child nodes that are
        images and, if an image exists, return it's "src" attribute.
        If no image exists, then we return an empty string.

        The "prepareData" functions are passed the actual TD node and also the TD node inner text
        which means you are free to check for child nodes etc and are not just limited to
        sorting on the TD node's inner text.

        The prepareData functions are not required (only your bespoke sort function is required)
        and only called by the script should they exist.
*/
function sortImagePrepareData(td, innerText) {
        var img = td.getElementsByTagName('img');
        return img.length ? img[0].src: "";
}

/*
        sortFileSize
        ------------

        1 Byte = 8 Bit
        1 Kilobyte = 1024 Bytes
        1 Megabyte = 1048576 Bytes
        1 Gigabyte = 1073741824 Bytes
*/
var sortFileSize = fdTableSort.sortNumeric;

function sortFileSizePrepareData(td, innerText) {
        var regExp = /(kb|mb|gb)/i;

        var type = innerText.search(regExp) != -1 ? innerText.match(regExp)[0] : "";

        switch (type.toLowerCase()) {
                case "kb" :
                        mult = 1024;
                        break;
                case "mb" :
                        mult = 1048576;
                        break;
                case "gb" :
                        mult = 1073741824;
                        break;
                default :
                        mult = 1;
        };

        innerText = parseFloat(innerText.replace(/[^0-9\.\-]/g,''));

        return isNaN(innerText) ? "" : innerText * mult;
};

