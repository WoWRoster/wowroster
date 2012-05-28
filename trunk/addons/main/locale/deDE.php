<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id$
 * @link       http://www.wowroster.net
 * @package    News
 * @subpackage Locale
 */

// -[ deDE Localization ]-

// Installer names
$lang['news']         = 'Neuigkeiten';
$lang['news_desc']    = 'Neuigkeitensystem mit Kommentaren';

// Button names
$lang['cms_button']   = 'Guild News|Post articles and other items of interest';

$lang['no_news']      = 'Keine Neuigkeiten verfügbar';
$lang['bad_news_id']  = 'Falsche Neuigkeiten ID';

$lang['add_news']     = 'Neuigkeiten hinzufügen';
$lang['enable_html']  = 'HTML aktivieren';
$lang['disable_html'] = 'HTML deaktivieren';
$lang['posted_by']    = 'Veröffentlicht von';
$lang['edit']         = 'Bearbeiten';
$lang['edit_news']    = 'Neuigkeit bearbeiten';
$lang['comments']     = 'Kommentare';
$lang['add_comment']  = 'Kommentar hinzufügen';
$lang['edit_comment'] = 'Kommentar bearbeiten';
$lang['n_comment']    = '%s Kommentar';
$lang['n_comments']   = '%s Kommentare';
$lang['whos_online']  = 'Who\'s Online';

$lang['b_title']  = 'Title';
$lang['b_desc']   = 'Desc';
$lang['b_url']    = 'Link';
$lang['b_image']  = 'Image File';
$lang['b_add']    = 'Add Banner';
$lang['b_upload'] = 'Upload a Banner';

$lang['news_edit_success']     = 'Neuigkeit erfolgreich bearbeitet';
$lang['news_add_success']      = 'Neuigkeit erfolgreich hinzugefügt';
$lang['banner_add_success']    = 'Banner erfolgreich hinzugefügt';
$lang['news_error_process']    = 'Es ist ein Problem beim Verarbeiten des Artikels aufgetreten';

$lang['comment_edit_success']  = 'Kommentar erfolgreich editiert';
$lang['comment_add_success']   = 'Kommentar erfolgreich hinzugefügt';
$lang['comment_error_process'] = 'Es ist ein Problem beim Verarbeiten des Kommentars aufgetreten';

// Config strings
$lang['admin']['cmsmain_conf']      = 'Neuigkeiten Konfiguration|Grundlegende Einstellungen';
$lang['admin']['cmsmain_slider']    = 'Slider|Image Slider configuration';
$lang['admin']['cmsmain_banner']    = 'Banner';
$lang['admin']['cmsmain_banneradd'] = 'Banner hinzufügen';
$lang['admin']['news_add']          = 'Neuigkeiten hinzufügen|Minimum benötigtes Login Level um Neuigkeiten hinzuzufügen.';
$lang['admin']['news_edit']         = 'Neuigkeiten bearbeiten|Minimum benötigtes Login Level um Neuigkeiten zu bearbeiten.';
$lang['admin']['comm_add']          = 'Kommentar hinzufügen|Minimum benötigtes Login Level um Kommentare hinzuzufügen.';
$lang['admin']['comm_edit']         = 'Kommentare bearbeiten|Minimum benötigtes Login Level um Kommentare zu bearbeiten.';
$lang['admin']['news_html']         = 'HTML Neuigkeiten|Standardmäßige Aktivierung oder Deaktivierung von HTML in Neuigkeiten, oder generelles Verbot von HTML.<br />Hat keine Auswirkung auf bereits existierende Neuigkeiten';
$lang['admin']['comm_html']         = 'HTML Kommentare|Standardmäßige Aktivierung oder Deaktivierung von HTML in Kommentaren, oder generelles Verbot von HTML.<br />Hat keine Auswirkung auf bereits existierende Kommentare';
$lang['admin']['news_nicedit']      = 'Nicedit Textbox|Aktivierung oder Deaktivierung der Niceedit Textbox.';

// Slider options
$lang['admin']['sliderSkin']        = 'Slider Skin|Select the style of the buttons and icons for the slider';
$lang['admin']['alignment']         = 'Alignment|topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight';
$lang['admin']['autoAdvance']       = 'Auto Advance|Sets the slider to auto-advance';
$lang['admin']['mobileAutoAdvance'] = 'Mobile Auto Advance|Auto-advancing for mobile devices';
$lang['admin']['barDirection']      = 'Bar Direction|';
$lang['admin']['barPosition']       = 'Bar Position|';
$lang['admin']['easing']            = 'Easing|';
$lang['admin']['mobileEasing']      = 'Mobile Easing|Leave empty if you want to display the same easing on mobile devices and on desktop etc.';
$lang['admin']['fx']                = 'Effect|Choose the type of sliding effect<br />- You can select more than one effect';
$lang['admin']['mobileFx']          = 'Mobile Effect|Leave empty if you want to display the same effect on mobile devices and on desktop etc.';
$lang['admin']['gridDifference']    = 'Grid Difference|To make the grid blocks slower than the slices, this value must be smaller than transPeriod';
$lang['admin']['height']            = 'Height|Height of the slider images.<br />- You can type pixels (for instance \'300px\'), a percentage (relative to the width of the slideshow, for instance \'50%\') or auto';
$lang['admin']['hover']             = 'Hover|Pause on mouse hover. Not available for mobile devices';
$lang['admin']['loader']            = 'Loader Type|Loader display type.<br />- Even if you choose "pie", old browsers like IE8- can\'t display it... they will display always a loading bar';
$lang['admin']['loaderColor']       = 'Loader Color|Color of the loader image';
$lang['admin']['loaderBgColor']     = 'Loader Background Color|Background color of the loader image';
$lang['admin']['loaderOpacity']     = 'Loader Opacity|Transparentcy of the loader image';
$lang['admin']['loaderPadding']     = 'Loader Padding|How many empty pixels you want to display between the loader and its background';
$lang['admin']['loaderStroke']      = 'Loader Stroke|The thickness both of the pie loader and of the bar loader.<br />Remember: for the pie, the loader thickness must be less than a half of the pie diameter';
$lang['admin']['minHeight']         = 'Min Height|Minimum hieght for the slider<br />- You can also leave it blank';
$lang['admin']['navigationHover']   = 'Navigation Hover|If on, the navigation buttons (prev, next and play/stop buttons) will be visible on hover state only, if off they will be visible always';
$lang['admin']['mobileNavHover']    = 'Mobile Navigation Hover|Same as above, but for mobile devices';
$lang['admin']['opacityOnGrid']     = 'Opacity On Grid|Applies a fade effect to blocks and slices<br />- If your slideshow is fullscreen or simply big, it\'s recommended to set it false to have a smoother effect';
$lang['admin']['overlayer']         = 'Overlayer|Places a layer over the images to prevent the users from saving them simply by clicking the right button of their mouse';
$lang['admin']['pagination']        = 'Pagination|Show the pagination icons';
$lang['admin']['playPause']         = 'Play/Pause|Display the play/pause buttons';
$lang['admin']['pauseOnClick']      = 'Pause On Click|Stops the slideshow when you click the sliders';
$lang['admin']['pieDiameter']       = 'Pie Diameter|Diameter of the pie loader';
$lang['admin']['piePosition']       = 'Pie Position|Where the pie loader should be placed';
$lang['admin']['portrait']          = 'Portrait|Enable if you don\'t want your images to be cropped';
$lang['admin']['cols']              = 'Columns|';
$lang['admin']['rows']              = 'Rows|';
$lang['admin']['slicedCols']        = 'Sliced Columns|If 0 the same value of cols';
$lang['admin']['slicedRows']        = 'Sliced Rows|If 0 the same value of rows';
$lang['admin']['slideOn']           = 'Slide On|When the transition effect will be applied';
$lang['admin']['thumbnails']        = 'Thumbnails|Display thumbnails when you hover the pagination icons';
$lang['admin']['time']              = 'Time|milliseconds between the end of the sliding effect and the start of the next one';
$lang['admin']['transPeriod']       = 'Transition Period|Length of the sliding effect in milliseconds';
