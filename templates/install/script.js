/**
 * WoWRoster.net WoWRoster
 *
 * Javascript file
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 */

var ol_width=220;
var ol_offsetx=15;
var ol_offsety=15;
var ol_hauto=1;
var ol_vauto=1;
var ol_fgclass='overlib_fg';
var ol_bgclass='overlib_border';
var ol_textfontclass='overlib_maintext';
var ol_captionfontclass='overlib_captiontext';
var ol_closefontclass='overlib_closetext';

$(function() {
	// Apply jQuery UI button styles on EVERYTHING
	$('button, input:submit, input:reset, .input').button();

	// Create button sets for radio and checkbox groups
	$('.radioset').buttonset();
	$('.checkset').buttonset();

	// Add a style to the text input and file select boxes
	$('input[type=text], input[type=password], input[type=file]').addClass('ui-widget');

	// Style select boxes
	$('select:not([multiple])').selectmenu({ style:'popup' });

	// Slide down the notification box
	$('#notify .close').hover(
		function() { $(this).addClass('ui-state-hover'); }, 
		function() { $(this).removeClass('ui-state-hover'); }
	)
	.click(function() { $(this).parent().slideUp('slow'); });

	// Keep forms from submitting more than once
	$('input[type=submit]').attr('disabled', false);
	$('input[type=reset]').attr('disabled', false);
	$('form').submit(function(){
		$('input[type=submit]', this).attr('disabled', 'disabled');
		$('input[type=reset]', this).attr('disabled', 'disabled');
	});

	// Check all script
	// Matches based on rel attribute
	$('.checkall').click(function () {
		var rel = $(this).attr('rel');
		$('input[name*="' + rel + '"]:checkbox').attr('checked', this.checked);
	});

});

function setOpacity( sEl,val )
{
	oEl = document.getElementById(sEl);
	if(oEl)
	{
		oEl.style.opacity = val/10;
		oEl.style.filter = 'alpha(opacity=' + val*10 + ')';
	}
}
