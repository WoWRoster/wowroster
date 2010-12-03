/**
 * WoWRoster.net WoWRoster
 *
 * Overlib tooltip JS plugin
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
*/
//\/////
//\  overLIB Popup-In-Popup (PIP) Plugin
//\
//\  You may not remove or change this notice.
//\  Copyright Erik Bosrup 1998-2003. All rights reserved.
//\  Contributors are listed on the homepage.
//\  See http://www.bosrup.com/web/overlib/ for details.
//\/////
////////
// PRE-INIT
// Ignore these lines, configuration is below.
////////
if (typeof olInfo == 'undefined' || typeof olInfo.meets == 'undefined' || !olInfo.meets(4.14)) alert('overLIB 4.14 or later is required for the Popup-In-Popup Plugin.');
else {
registerCommands('puid,capalign,textalign');
////////
// DEFAULT CONFIGURATION
// Settings you want everywhere are set here. All of this can also be
// changed on your html page or through an overLIB call.
////////
if (typeof ol_puid=='undefined') var ol_puid='';
if (typeof ol_capalign=='undefined') var ol_capalign='';
if (typeof ol_textalign=='undefined') var ol_textalign='';
////////
// END OF CONFIGURATION
// Don't change anything below this line, all configuration is above.
////////
////////
// INIT
////////
// Runtime variables init. Don't change for config!
var o3_puid='overDiv2';
var o3_capalign='';
var o3_textalign='';
var olZindex,over2=null,olSecondPUParent;
////////
// PLUGIN FUNCTIONS
////////
function setSecondPUVariables() {
	o3_puid=ol_puid;
	o3_capalign=ol_capalign;
	o3_textalign=ol_textalign;
	olSecondPUParent=null;
}
// Parses PIP Commands
function parseSecondPUExtras(pf,i,ar){
	var k=i;
	if (k < ar.length) {
		if (ar[k]==PUID) { eval(pf+'puid="'+ar[++k]+'"'); return k; }
		if (ar[k]==CAPALIGN) { eval(pf+'capalign="'+ar[++k]+'"'); return k; }
		if (ar[k]==TEXTALIGN) { eval(pf+'textalign="'+ar[++k]+'"'); return k; }
	}
	return -1;
}
//////
// PIP SUPPORT FUNCTIONS
//////
function overlib2() {
	var args=overlib2.arguments;
	if (o3_showingsticky==0||args.length==0||o3_mouseoff||(typeof o3_draggable!='undefined'&&o3_draggable)||o3_close=='') return;
	resetDefaults();
	parseTokens('o3_',args);
	if(!o3_puid) o3_puid='overDiv2';
	dispSecondPU(o3_puid);
}
function nd2() {
	if(over2) hideSecondObject(over2);
	if(olSecondPUParent) over=olSecondPUParent;
}
function resetDefaults(){
	// Load defaults to runtime for secondary popups
	o3_text=ol_text;
	o3_cap=ol_cap;
	o3_background=ol_background;
	o3_hpos=ol_hpos;
	o3_offsetx=ol_offsetx;
	o3_offsety=ol_offsety;
	o3_fgcolor=ol_fgcolor;
	o3_bgcolor=ol_bgcolor;
	o3_textcolor=ol_textcolor;
	o3_capcolor=ol_capcolor;
	o3_width=ol_width;
	o3_aboveheight=ol_aboveheight;
	o3_border=ol_border;
	o3_snapx=ol_snapx;
	o3_snapy=ol_snapy;
	o3_fixx=ol_fixx;
	o3_fixy=ol_fixy;
	o3_relx=ol_relx;
	o3_rely=ol_rely;
	o3_fgbackground=ol_fgbackground;
	o3_bgbackground=ol_bgbackground;
	o3_vpos=ol_vpos;
	o3_textfont=ol_textfont;
	o3_captionfont=ol_captionfont;
	o3_textsize=ol_textsize;
	o3_captionsize=ol_captionsize;
	o3_function=ol_function;
	o3_hauto=ol_hauto;
	o3_vauto=ol_vauto;
	o3_wrap=ol_wrap;
	setSecondPUVariables();
}
function dispSecondPU(theDiv) {
	var args=dispSecondPU.arguments, styleType, layerHtml, l, iLetter=['f','b'], cStr, colors=new Array();
	over2=createDivContainer(theDiv);
	olSecondPUParent=over;
	over=over2;
	if (o3_css == CSSOFF || o3_css == CSSCLASS) {
		for(var i=0; i<iLetter.length; i++) {
			cStr=eval('o3_'+iLetter[i]+'gcolor');
			if(/bgcolor/.test(cStr)){
				l=cStr.indexOf('#')
			  cStr=cStr.substring(l,cStr.length-1);
			}
			colors[i]=cStr;
			cStr=eval('o3_'+iLetter[i]+'gbackground');
			if(/background/.test(cStr)){
				l=cStr.indexOf('=')
			  cStr=cStr.substring(l+1,cStr.length-1);
			}
			colors[i+2]=cStr;
		}
		if(colors[0]) o3_fgcolor=colors[0];
		if(colors[1]) o3_bgcolor=colors[1];
		if(colors[2]) o3_fgbackground=colors[2];
		if(colors[3]) o3_bgbackground=colors[3];
	}
	if (o3_fgbackground!="") o3_fgbackground="background=\""+o3_fgbackground+"\"";
	styleType=(pms[o3_css-1-pmStart]=="cssoff"||pms[o3_css-1-pmStart]=="cssclass");
	if (o3_bgbackground!="") o3_bgbackground=( styleType ? "background=\""+o3_bgbackground+"\"" : o3_bgbackground);
	if (o3_fgcolor!="") o3_fgcolor=(styleType ? "bgcolor=\""+o3_fgcolor+"\"" : o3_fgcolor);
	if (o3_bgcolor!="") o3_bgcolor=(styleType ? "bgcolor=\""+o3_bgcolor+"\"" : o3_bgcolor);
	if (o3_height > 0) o3_height=(styleType ? "height=\""+o3_height+"\"" : o3_height);
	else o3_height="";
	if(typeof olZindex=='undefined') olZindex=getDivZindex();
	if (!olNs4) {
		with(over2.style) {
			position='absolute';
			visibility='hidden';
			backgroundImage=(o3_background ? 'url('+o3_background+')' : 'none');
		}
		over2.style.zIndex=olZindex+1;
	} else if (olNs4) {
		over2.background.src=(o3_background ? o3_background : null);
		over2.zIndex=olZindex+1;
	}
	layerHtml=(o3_cap) ? runHook('ol_content_caption',FALTERNATE,o3_css,o3_text,o3_cap,"") : runHook('ol_content_simple',FALTERNATE,o3_css,o3_text);
	writeSecondLayer(layerHtml,over2);
	placeLayer();
	showSecondObject(over2);
}
function getDivZindex(id) {
	var obj;
	if(id==''||id==null) id='overDiv';
	obj=layerReference(id);
	obj=(olNs4 ? obj : obj.style);
	return obj.zIndex;
}
function writeSecondLayer(theHtml,obj) {
	theHtml += "\n";
	if (olNs4) {
		var lyr=obj.document
		lyr.write(theHtml)
		lyr.close()
	} else if (typeof obj.innerHTML != 'undefined') {
		if(olIe5&&isMac) obj.innerHTML=''
		obj.innerHTML=theHtml
	}
}
// Makes simple table without caption
function ol_content_simple_two(text) {
	txt='<table width="'+o3_width+ '" border="0" cellpadding="'+o3_border+'" cellspacing="0" '+o3_bgcolor+' '+o3_height+'><tr><td><table width="100%" border="0" cellpadding="'+o3_cellpad+'" cellspacing="0" '+o3_fgcolor+' '+o3_fgbackground+' '+o3_height+'><tr><td valign="TOP"'+(o3_textalign ? ' align="'+o3_textalign+'"' : '')+'>'+wrapStr(0,o3_textsize,'text')+text+wrapStr(1,o3_textsize)+'</td></tr></table></td></tr></table>';
	set_background("");
	return txt;
}
// Makes table with caption and optional close link
function ol_content_caption_two(text,title,close) {
	var nameId;
	closing="";
	closeevent="onMouseOver";
	if (o3_closeclick==1) closeevent= (o3_closetitle ? "title='" + o3_closetitle +"'" : "") + " onClick";
	if (o3_capicon!="") {
	  nameId=' hspace=\"5\"'+' align=\"middle\" alt=\"\"';
	  if (typeof o3_dragimg!='undefined'&&o3_dragimg) nameId=' hspace=\"5\"'+' name=\"'+o3_dragimg+'\" id=\"'+o3_dragimg+'\" align=\"middle\" alt=\"Drag Enabled\" title=\"Drag Enabled\"';
	  o3_capicon='<img src=\"'+o3_capicon+'\"'+nameId+' />';
	}
	if (close!="")
	 closing='<td align="RIGHT"><a href="javascript:return '+fnRef+'cClick();" '+closeevent+'="return '+fnRef+'cClick();">'+wrapStr(0,o3_closesize,'close')+close+wrapStr(1,o3_closesize,'close')+'</a></td>';
	txt='<table width="'+o3_width+ '" border="0" cellpadding="'+o3_border+'" cellspacing="0" '+o3_bgcolor+' '+o3_bgbackground+' '+o3_height+'><tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td'+(o3_capalign ? ' align="'+o3_capalign + '"': '')+'><b>'+wrapStr(0,o3_captionsize,'caption')+o3_capicon+title+wrapStr(1,o3_captionsize)+'</b></td>'+closing+'</tr></table><table width="100%" border="0" cellpadding="'+o3_cellpad+'" cellspacing="0" '+o3_fgcolor+' '+o3_fgbackground+' '+o3_height+'><tr><td valign="TOP"'+(o3_textalign ? ' align="'+o3_textalign+'"' : '')+'>'+wrapStr(0,o3_textsize,'text')+text+wrapStr(1,o3_textsize)+'</td></tr></table></td></tr></table>';
	set_background("");
	return txt;
}
function showSecondObject(obj) {
	obj=(olNs4 ? obj : obj.style);
	obj.visibility='visible';
}
function hideSecondObject(obj) {
	obj=(olNs4 ? obj : obj.style);
	obj.visibility='hidden';
	if(isMac&&olIe5) resetOverDiv(obj.id);
}
////////
// PLUGIN REGISTRATIONS
////////
registerRunTimeFunction(setSecondPUVariables);
registerCmdLineFunction(parseSecondPUExtras);
registerHook("ol_content_simple",ol_content_simple_two,FREPLACE,CSSOFF);
registerHook("ol_content_caption",ol_content_caption_two,FREPLACE,CSSOFF);
}
