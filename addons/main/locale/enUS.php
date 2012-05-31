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

// -[ enUS Localization ]-

// Installer names
$lang['news']         = 'News';
$lang['news_desc']    = 'News system, with comments';

// Button names
$lang['cms_button']   = 'Guild Portal|Post articles and other items of interest';

$lang['no_news']      = 'No news available';
$lang['bad_news_id']  = 'Invalid news ID';

$lang['add_news']     = 'Add News';
$lang['enable_html']  = 'Enable HTML';
$lang['disable_html'] = 'Disable HTML';
$lang['posted_by']    = 'Posted By';
$lang['edit']         = 'Edit';
$lang['edit_news']    = 'Edit News';
$lang['comments']     = 'Comments';
$lang['add_comment']  = 'Add Comment';
$lang['edit_comment'] = 'Edit Comment';
$lang['n_comment']    = '%s comment';
$lang['n_comments']   = '%s comments';
$lang['whos_online']  = 'Who\'s Online';

$lang['b_title']  = 'Title';
$lang['b_desc']   = 'Desc';
$lang['b_url']    = 'Link';
$lang['b_image']  = 'Image File';
$lang['b_add']    = 'Add Slider Image';
$lang['b_upload'] = 'Upload a Slider Image';
$lang['b_video'] = 'Video';

$lang['news_edit_success']     = 'News edited successfully';
$lang['news_add_success']      = 'News added successfully';
$lang['slider_add_success']    = 'Slider image %1$s added successfully';
$lang['slider_error_db']       = 'There was a Database error while adding the slider image';
$lang['slider_file_error']     = 'Your upload was not completed. Image [%1$s] could not be uploaded.';
$lang['news_error_process']    = 'There was a problem processing the article';

$lang['comment_edit_success']  = 'Comment edited successfully';
$lang['comment_add_success']   = 'Comment added successfully';
$lang['comment_error_process'] = 'There was a problem processing your comment';

// Config strings
$lang['admin']['cmsmain_conf']          = 'News|Basic news configuration';
$lang['admin']['cmsmain_slider']        = 'Slider|Image Slider configuration';
$lang['admin']['cmsmain_slider_images'] = 'Slider Images';
$lang['admin']['cmsmain_slider_add']    = 'Add Images';
$lang['admin']['news_add']              = 'Add news|Minimum login level needed to add news.';
$lang['admin']['news_edit']             = 'Edit news|Minimum login level needed to edit news.';
$lang['admin']['comm_add']              = 'Add comments|Minimum login level needed to add comments.';
$lang['admin']['comm_edit']             = 'Edit comments|Minimum login level needed to edit comments.';
$lang['admin']['news_html']             = 'HTML news|Whether to enable or disable html in news by default, or disallow it entirely.<br />Does not affect existing news';
$lang['admin']['comm_html']             = 'HTML comments|Whether to enable or disable html in comments by default, or disallow it entirely.<br />Does not affect existing comments';
$lang['admin']['news_nicedit']          = 'Nicedit text Box|Whether to enable or disable the Nicedit text box.';

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
