/**
 * WoWRoster.net WoWRoster
 *
 * JQuery Javascript file
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @since      File available since Release 2.1.0
 */

$(function() {

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
