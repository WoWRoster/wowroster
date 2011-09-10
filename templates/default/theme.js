/**
 * WoWRoster.net WoWRoster
 *
 * Theme Javascript file
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
  $('button, input:submit, input:reset, input:button, .input').button();

  // Create button sets for radio and checkbox groups
  $('.radioset').buttonset();
  $('.checkset').buttonset();

  // Add a style to the text input and file select boxes
  $('input[type=file]').addClass('ui-widget');

  // Style select boxes
  $('select:not([multiple],[class="no-style"])').selectmenu();

  // Slide down the notification box
  $('#notify .close').hover(
    function() { $(this).addClass('ui-state-hover'); }, 
    function() { $(this).removeClass('ui-state-hover'); }
  )
  .click(function() { $(this).parent().slideUp('slow'); });


  // Main menu buttons and panels
  $('#top_nav > a').click(function(){
    var menu_div = $(this).attr('href');

    if($(this).hasClass('active') == false) {
      $('#menu-buttons > div.menu-scope-panel').fadeOut();
      $('#top_nav > a').removeClass('active');
      $(this).addClass('active');
      $(menu_div).fadeIn();
    }
    else {
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