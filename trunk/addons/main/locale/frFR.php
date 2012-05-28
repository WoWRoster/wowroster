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

// -[ frFR Localization ]-

// Installer names
$lang['news']         = 'Articles';
$lang['news_desc']    = 'Système de gestion d\'articles avec commentaires';

// Button names
$lang['cms_button']   = 'Guild News|Post articles and other items of interest';

$lang['no_news']      = 'Pas d\'article disponible';
$lang['bad_news_id']  = 'Mauvais identifiant d\'article';

$lang['add_news']     = 'Ajouter un article';
$lang['enable_html']  = 'Activer HTML';
$lang['disable_html'] = 'Désactiver HTML';
$lang['posted_by']    = 'Écrit par';
$lang['edit']         = 'Éditer';
$lang['edit_news']    = 'Éditer l\'article';
$lang['comments']     = 'Commentaires';
$lang['add_comment']  = 'Ajouter un commentaire';
$lang['edit_comment'] = 'Éditer un commentaire';
$lang['n_comment']    = '%s commentaire';
$lang['n_comments']   = '%s commentaires';
$lang['whos_online']  = 'Who\'s Online';

$lang['b_title']  = 'Title';
$lang['b_desc']   = 'Desc';
$lang['b_url']    = 'Link';
$lang['b_image']  = 'Image File';
$lang['b_add']    = 'Add Banner';
$lang['b_upload'] = 'Upload a Banner';

$lang['news_edit_success']     = 'Article édité avec succès';
$lang['news_add_success']      = 'Article ajouté avec succès';
$lang['banner_add_success']    = 'Banner added successfully';
$lang['news_error_process']    = 'Il y a eu un problème lors du traitement de l\'article';

$lang['comment_edit_success']  = 'Commentaire édité avec succès';
$lang['comment_add_success']   = 'Commentaire ajouté avec succès';
$lang['comment_error_process'] = 'Il y a eu un problème lors du traitement de votre commentaire';

// Config strings
$lang['admin']['cmsmain_conf']      = 'Configuration des articles|Configuration de base pour les articles';
$lang['admin']['cmsmain_slider']    = 'Slider|Image Slider configuration';
$lang['admin']['cmsmain_banner']    = 'Banners';
$lang['admin']['cmsmain_banneradd'] = 'Add Banners';
$lang['admin']['news_add']          = 'Ajouter des articles|Accréditation minimale pour pouvoir ajouter un article.';
$lang['admin']['news_edit']         = 'Éditer des articles|Accréditation minimale pour pouvoir éditer un article.';
$lang['admin']['comm_add']          = 'Ajouter des commentaires|Accréditation minimale pour pouvoir ajouter un commentaire.';
$lang['admin']['comm_edit']         = 'Éditer des commentaires|Accréditation minimale pour pouvoir éditer un commentaire.';
$lang['admin']['news_html']         = 'HTML dans l\'article|Pour autoriser ou interdire par défaut l\'usage du HTML dans les articles.<br />N\'affecte pas les articles déjà existants.';
$lang['admin']['comm_html']         = 'HTML dans le commentaire|Pour autoriser ou interdire par défaut l\'usage du HTML dans les commentaires.<br />N\'affecte pas les commentaires déjà existants.';
$lang['admin']['news_nicedit']      = 'Nicedit text Box|Whether to enable or disable the Nicedit text box.';

// Slider options
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
