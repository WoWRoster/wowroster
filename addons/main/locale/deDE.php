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
$lang['cms_button']   = 'Guild Portal|Post articles and other items of interest';

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
$lang['b_add']    = 'Add Slider Image';
$lang['b_upload'] = 'Upload a Slider Image';
$lang['b_video'] = 'Video';

$lang['news_edit_success']     = 'Neuigkeit erfolgreich bearbeitet';
$lang['news_add_success']      = 'Neuigkeit erfolgreich hinzugefügt';
$lang['slider_add_success']    = 'Slider image %1$s added successfully';
$lang['slider_error_db']       = 'There was a Database error while adding the slider image';
$lang['slider_file_error']     = 'Your upload was not completed. Image [%1$s] could not be uploaded.';
$lang['news_error_process']    = 'Es ist ein Problem beim Verarbeiten des Artikels aufgetreten';

$lang['comment_edit_success']  = 'Kommentar erfolgreich editiert';
$lang['comment_add_success']   = 'Kommentar erfolgreich hinzugefügt';
$lang['comment_error_process'] = 'Es ist ein Problem beim Verarbeiten des Kommentars aufgetreten';

// Config strings
$lang['admin']['cmsmain_conf']          = 'Neuigkeiten Konfiguration|Grundlegende Einstellungen';
$lang['admin']['cmsmain_slider']        = 'Slider|Image Slider configuration';
$lang['admin']['cmsmain_slider_images'] = 'Slider Images';
$lang['admin']['cmsmain_slider_add']    = 'Slider hinzufügen';
$lang['admin']['news_add']              = 'Neuigkeiten hinzufügen|Minimum benötigtes Login Level um Neuigkeiten hinzuzufügen.';
$lang['admin']['news_edit']             = 'Neuigkeiten bearbeiten|Minimum benötigtes Login Level um Neuigkeiten zu bearbeiten.';
$lang['admin']['comm_add']              = 'Kommentar hinzufügen|Minimum benötigtes Login Level um Kommentare hinzuzufügen.';
$lang['admin']['comm_edit']             = 'Kommentare bearbeiten|Minimum benötigtes Login Level um Kommentare zu bearbeiten.';
$lang['admin']['news_html']             = 'HTML Neuigkeiten|Standardmäßige Aktivierung oder Deaktivierung von HTML in Neuigkeiten, oder generelles Verbot von HTML.<br />Hat keine Auswirkung auf bereits existierende Neuigkeiten';
$lang['admin']['comm_html']             = 'HTML Kommentare|Standardmäßige Aktivierung oder Deaktivierung von HTML in Kommentaren, oder generelles Verbot von HTML.<br />Hat keine Auswirkung auf bereits existierende Kommentare';
$lang['admin']['news_nicedit']          = 'Nicedit Textbox|Aktivierung oder Deaktivierung der Niceedit Textbox.';

// Slider options
$lang['admin']['slider_skin']              = 'Slider Skin|Select the style of the buttons and icons for the slider';
$lang['admin']['slider_alignment']         = 'Alignment|topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight';
$lang['admin']['slider_autoAdvance']       = 'Auto Advance|Sets the slider to auto-advance';
$lang['admin']['slider_mobileAutoAdvance'] = 'Mobile Auto Advance|Auto-advancing for mobile devices';
$lang['admin']['slider_barDirection']      = 'Bar Direction|';
$lang['admin']['slider_barPosition']       = 'Bar Position|';
$lang['admin']['slider_easing']            = 'Easing|';
$lang['admin']['slider_mobileEasing']      = 'Mobile Easing|Leave empty if you want to display the same easing on mobile devices and on desktop etc.';
$lang['admin']['slider_fx']                = 'Effect|Choose the type of sliding effect<br />- You can select more than one effect';
$lang['admin']['slider_mobileFx']          = 'Mobile Effect|Leave empty if you want to display the same effect on mobile devices and on desktop etc.';
$lang['admin']['slider_gridDifference']    = 'Grid Difference|To make the grid blocks slower than the slices, this value must be smaller than transPeriod';
$lang['admin']['slider_height']            = 'Height|Height of the slider images.<br />- You can type pixels (for instance \'300px\'), a percentage (relative to the width of the slideshow, for instance \'50%\') or auto';
$lang['admin']['slider_hover']             = 'Hover|Pause on mouse hover. Not available for mobile devices';
$lang['admin']['slider_loader']            = 'Loader Type|Loader display type.<br />- Even if you choose "pie", old browsers like IE8- can\'t display it... they will display always a loading bar';
$lang['admin']['slider_loaderColor']       = 'Loader Color|Color of the loader image';
$lang['admin']['slider_loaderBgColor']     = 'Loader Background Color|Background color of the loader image';
$lang['admin']['slider_loaderOpacity']     = 'Loader Opacity|Transparentcy of the loader image';
$lang['admin']['slider_loaderPadding']     = 'Loader Padding|How many empty pixels you want to display between the loader and its background';
$lang['admin']['slider_loaderStroke']      = 'Loader Stroke|The thickness both of the pie loader and of the bar loader.<br />Remember: for the pie, the loader thickness must be less than a half of the pie diameter';
$lang['admin']['slider_minHeight']         = 'Min Height|Minimum hieght for the slider<br />- You can also leave it blank';
$lang['admin']['slider_navigation']        = 'Navigation|Display the navigation buttons (prev, next and play/stop buttons)';
$lang['admin']['slider_navigationHover']   = 'Navigation Hover|If on, the navigation buttons (prev, next and play/stop buttons) will be visible on hover state only, if off they will be visible always';
$lang['admin']['slider_mobileNavHover']    = 'Mobile Navigation Hover|Same as above, but for mobile devices';
$lang['admin']['slider_opacityOnGrid']     = 'Opacity On Grid|Applies a fade effect to blocks and slices<br />- If your slideshow is fullscreen or simply big, it\'s recommended to set it false to have a smoother effect';
$lang['admin']['slider_overlayer']         = 'Overlayer|Places a layer over the images to prevent the users from saving them simply by clicking the right button of their mouse';
$lang['admin']['slider_pagination']        = 'Pagination|Show the pagination icons';
$lang['admin']['slider_playPause']         = 'Play/Pause|Display the play/pause buttons';
$lang['admin']['slider_pauseOnClick']      = 'Pause On Click|Stops the slideshow when you click the sliders';
$lang['admin']['slider_pieDiameter']       = 'Pie Diameter|Diameter of the pie loader';
$lang['admin']['slider_piePosition']       = 'Pie Position|Where the pie loader should be placed';
$lang['admin']['slider_portrait']          = 'Portrait|Enable if you don\'t want your images to be cropped';
$lang['admin']['slider_cols']              = 'Columns|';
$lang['admin']['slider_rows']              = 'Rows|';
$lang['admin']['slider_slicedCols']        = 'Sliced Columns|If 0 the same value of cols';
$lang['admin']['slider_slicedRows']        = 'Sliced Rows|If 0 the same value of rows';
$lang['admin']['slider_slideOn']           = 'Slide On|When the transition effect will be applied';
$lang['admin']['slider_thumbnails']        = 'Thumbnails|Display thumbnails when you hover the pagination icons';
$lang['admin']['slider_time']              = 'Time|milliseconds between the end of the sliding effect and the start of the next one';
$lang['admin']['slider_transPeriod']       = 'Transition Period|Length of the sliding effect in milliseconds';
