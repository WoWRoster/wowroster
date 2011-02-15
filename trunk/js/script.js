/**
 * WoWRoster.net WoWRoster
 *
 * JQuery Javascript file
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.1.0
 */

$(function() {
	// Apply jQuery UI button styles on EVERYTHING
	$('button, input:submit, input:reset, .input').button();

	// Create button sets for radio and checkbox groups
	$('.radioset').buttonset();
	$('.checkset').buttonset();

	// Add a style to the text input and file select boxes
	$('input[type=text], input[type=password], input[type=file]').addClass('ui-widget');

	// Style select boxes
	$('select:not([multiple],[class="no-style"])').selectmenu({ style:'popup' });

	// Slide down the notification box
	$('#notify').slideDown('slow');
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

	// Main menu buttons and panels
	$('#top_nav > a').click(function(){
		var menu_div = $(this).attr('href');

		if($(this).hasClass('active') == false)
		{
			$('#menu-buttons > div.menu-scope-panel').fadeOut();
			$('#top_nav > a').removeClass('active');
			$(this).addClass('active');
			$(menu_div).fadeIn();
		}
		else
		{
			$(this).removeClass('active');
			$(menu_div).fadeOut();
		}

		return false;
	});

	$('.mini-list-click').click(function(){
		$('.mini-list').fadeOut();
		$('#top_nav > a').removeClass('active');
	});

	// Check all script
	// Matches based on rel attribute
	$('.checkall').click(function () {
		var rel = $(this).attr('rel');
		$('input[name*="' + rel + '"]:checkbox').attr('checked', this.checked);
	});

});
