//\/////
//\  overLIB Fade In/Out Plugin
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
if (typeof olInfo == 'undefined' || typeof olInfo.meets == 'undefined' || !olInfo.meets(4.14)) alert('overLIB 4.14 or later is required for the Fade In/Out Plugin.');
else {
registerCommands('fadein,fadeout,fadetime,opacity');
////////
// DEFAULT CONFIGURATION
// Settings you want everywhere are set here. All of this can also be
// changed on your html page or through an overLIB call.
////////
if (typeof ol_fadein=='undefined') var ol_fadein=0;
if (typeof ol_fadeout=='undefined') var ol_fadeout=0;
if (typeof ol_fadetime=='undefined') var  ol_fadetime=300;  // 0.5 sec fade time
if (typeof ol_opacity=='undefined') var ol_opacity=90;
////////
// END OF CONFIGURATION
// Don't change anything below this line, all configuration is above.
////////
////////
// INIT
////////
// Runtime variables init. Don't change for config!
var o3_fadein=0;
var o3_fadeout=0;
var o3_fadetime=500;
var o3_opacity=100;
var o3_fadeInc, o3_fadeTimeInc, o3_fadeid,o3_fadeOpacity;
////////
// PLUGIN FUNCTIONS
////////
function setFadeVariables() {
	o3_fadein=ol_fadein;
	o3_fadeout=ol_fadeout;
	o3_fadetime=ol_fadetime;
	o3_opacity=ol_opacity;
	o3_fadeid=0;
}
// Parses Fade commands
function parseFadeExtras(pf,i,ar) {
	var k=i,v;
	if (k < ar.length) {
		if (ar[k]==FADEIN) { eval(pf +'fadein=('+pf+'fadein==0) ? 1 : 0'); return k; }
		if (ar[k]==FADEOUT) { eval(pf +'fadeout=('+pf+'fadeout==0) ? 1 : 0'); return k; }
		if (ar[k]==FADETIME) { eval(pf+'fadetime='+ar[++k]); return k; }
		if (ar[k]==OPACITY) { eval(pf+'opacity=setOpacity('+ar[++k]+')'); return k; }
	}
	return -1;
}
// Makes sure opacity doesn't exceed 100%
function setOpacity(op) {
	if (op<0) op *= -1;
	return (op < 1 ? Math.round(100*op) : (op>100 ? 100 : Math.round(op)))
}
//////
// POST PARSE CHECKS
//////
function chkFadeParams() {
	var sgn;
	if(o3_fadein||o3_fadeout||(o3_opacity!=100)) {
		if(olNs4||(olIe4&&isMac)) o3_fadein=o3_fadeout=o3_fadeid=0;
		else if(o3_fadein||o3_fadeout) {
			if(o3_fadeout&&o3_sticky) o3_fadeout=0;
			var numSteps=(document.all ? 10 : 20);
			if(o3_fadein&&o3_fadeout) o3_fadeout=0;
			o3_fadeInc = o3_opacity/numSteps;
			sgn = (o3_fadein) ? 1 : -1;
			o3_fadeInc *= sgn;
			o3_fadeOpacity=(o3_fadein ? 0 : o3_opacity);
			o3_fadeTimeInc=parseInt(o3_fadetime/numSteps);
		}
	}
	return true;
}
//////
//  OVER-RIDE CORE FUNCTION
//////
function nd(time) {
	if(olLoaded&&!isExclusive()) {
		if(o3_fadeout&&!o3_delay) {
			if (o3_timerid > 0) clearTimeout(o3_timerid);
			if(time) o3_timerid=setTimeout("o3_fadeid=setInterval('doFadeOut()',"+o3_fadeTimeInc+")",time);
			else o3_fadeid=setInterval('doFadeOut()',o3_fadeTimeInc);
		} else {
			if (time&&!o3_delay) {
				if (o3_timerid > 0) clearTimeout(o3_timerid);
				o3_timerid=setTimeout("cClick()",(o3_timeout=time));
			}
			if ( o3_removecounter >= 1 ) { o3_showingsticky=0 };
			if ( o3_showingsticky==0 ) {
				o3_allowmove=0;
				if (over!=null&&(o3_compatmode ? 1 : o3_timerid==0)) runHook("hideObject",FREPLACE,over);
			} else o3_removecounter++;
		}
	}
	return true;
}
// Displays the popup
function fadeDisp(statustext) {
  // call any routine needed before showing popup
  runHook("disp",FBEFORE,statustext);
	if (o3_allowmove==0) 	{
		runHook("placeLayer",FREPLACE);
		(olNs6&&olShowId<0) ? olShowId=setTimeout("runHook('showObject', FREPLACE, over)", 1) : runHook("showObject", FREPLACE, over);
		if (o3_fadein) doFadeIn();
		o3_allowmove=(o3_sticky||!o3_followmouse) ? 0 : 1;
	}
	runHook("disp",FAFTER,statustext);
	if (statustext!="") self.status=statustext;
}
//////
// FADE FUNCTION
//////
function doFadeOut() {
	if(o3_fadeOpacity<0) {
		clearInterval(o3_fadeid);
		o3_fadeid=0;
		runHook('hideObject',FREPLACE, over);
		o3_allowmove=0;
	} else {
		setPUOpacity(o3_fadeOpacity);
		o3_fadeOpacity += o3_fadeInc;
	}
}
function doFadeIn() {
	if (o3_fadein) {
		if(o3_fadeOpacity>o3_opacity) {
			clearTimeout(o3_fadeid);
			o3_fadeid=0;
		} else {
			setPUOpacity(o3_fadeOpacity);
			o3_fadeOpacity += o3_fadeInc;
			o3_fadeid=setTimeout("doFadeIn()",o3_fadeTimeInc);
		}
	}
}
function setPUOpacity(op){
	if (olNs4) return;
	op = (typeof op=='number') ? op : (o3_fadein ? o3_fadeOpacity : o3_opacity);
	setBrowserOpacity(op);
}
function cleanUpPUOpacity() {
	if (olNs4) return;
	if(o3_fadein&&o3_fadeid) clearTimeout(o3_fadeid);
	o3_fadein = 0;
	cleanUpBrowserOpacity();
}
// multi browser support for opacity
function setBrowserOpacity(op,lyr){
	if (olNs4||!op) return;  // if Ns4.x or opacity not given return;
	lyr=(lyr) ? lyr : over;
	if (olIe4&&typeof lyr.filters != 'undefined') {
		lyr.style.filter='Alpha(Opacity='+op+')';
		lyr.filters.alpha.enabled=true;
	} else {
		var sOp=(typeof(lyr.style.MozOpacity)!='undefined') ? 'MozOpacity' : (typeof(lyr.style.KhtmlOpacity)!='undefined' ? 'KhtmlOpacity' : (typeof(lyr.style.opacity)!='undefined' ? 'opacity' : ''));
		if (sOp) eval('lyr.style.'+sOp+'=op/100');
	}
}
function cleanUpBrowserOpacity(lyr) {
	if (olNs4) return;
	lyr=(lyr) ? lyr : over;
	if (olIe4&&typeof lyr.filters != 'undefined') {
		lyr.style.filter='Alpha(Opacity=100)';
		lyr.filters.alpha.enabled=false;
	} else {
		var sOp=(typeof(lyr.style.MozOpacity)!='undefined') ? 'MozOpacity' : (typeof(lyr.style.KhtmlOpacity)!='undefined' ? 'KhtmlOpacity' : (typeof(lyr.style.opacity)!='undefined' ? 'opacity' : ''));
		if (sOp) eval('lyr.style.'+sOp+'=1.0');
	}
}
////////
// PLUGIN REGISTRATIONS
////////
registerRunTimeFunction(setFadeVariables);
registerCmdLineFunction(parseFadeExtras);
registerPostParseFunction(chkFadeParams);
registerHook("disp",fadeDisp,FREPLACE);
registerHook("showObject",setPUOpacity,FBEFORE);
registerHook('hideObject',cleanUpPUOpacity,FAFTER);
if (olInfo.meets(4.14)) registerNoParameterCommands('fadein,fadeout');
}
//end
