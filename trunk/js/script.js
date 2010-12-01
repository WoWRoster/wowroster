/**
 * WoWRoster.net WoWRoster
 *
 * JQuery Javascript file
 *
 * LICENSE: Licensed under the Creative Commons
 *          "Attribution-NonCommercial-ShareAlike 2.5" license
 *
 * @copyright  2002-2008 WoWRoster.net
 * @license    http://creativecommons.org/licenses/by-nc-sa/2.5   Creative Commons "Attribution-NonCommercial-ShareAlike 2.5"
 * @version    SVN: $Id: $
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.1.0
 */

$(function() {
	// Apply jQuery UI button styles on EVERYTHING
	$('button, input:submit, input:reset, .input').button();

	$('.radioset').buttonset();
	$('.checkset').buttonset();

	$('input[type=text], input[type=password], input[type=file], select').addClass('ui-widget');

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

});
