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
$lang['cms_button']   = 'Gildenportal|Schreibt Artikel und andere Items von interesse';

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

$lang['whos_online']  = 'Wer ist online';
$lang['total']			= 'Total';
$lang['reg']			= 'Registered';
$lang['guests']			= 'Guests';
$lang['bots']			= 'Bots';

$lang['b_title']  = 'Titel';
$lang['b_desc']   = 'Beschreibung';
$lang['b_url']    = 'Link';
$lang['b_image']  = 'Bilddatei';
$lang['b_add']    = 'Slider Bild hinzufügen';
$lang['b_upload'] = 'Slider Bild hochladen';
$lang['b_video'] = 'Video';

$lang['news_edit_success']     = 'Neuigkeit erfolgreich bearbeitet';
$lang['news_add_success']      = 'Neuigkeit erfolgreich hinzugefügt';
$lang['slider_add_success']    = 'Slider Bild %1$s erfolgreich hinzugefügt';
$lang['slider_error_db']       = 'Es gab ein Datenbankproblem beim hinzufügen des Slider Bildes';
$lang['slider_file_error']     = 'Der Upload wurde abgebrochen. Das Bild [%1$s] konnte nicht hochgeladen werden.';
$lang['news_error_process']    = 'Es ist ein Problem beim Verarbeiten des Artikels aufgetreten';

$lang['comment_edit_success']  = 'Kommentar erfolgreich editiert';
$lang['comment_add_success']   = 'Kommentar erfolgreich hinzugefügt';
$lang['comment_error_process'] = 'Es ist ein Problem beim Verarbeiten des Kommentars aufgetreten';

// Config strings
$lang['admin']['cmsmain_conf']          = 'Neuigkeiten Konfiguration|Grundlegende Einstellungen';
$lang['admin']['cmsmain_slider']        = 'Slider|Bildslider Konfiguration';
$lang['admin']['cmsmain_slider_images'] = 'Slider Billder';
$lang['admin']['cmsmain_slider_add']    = 'Slider hinzufügen';
$lang['admin']['news_add']              = 'Neuigkeiten hinzufügen|Minimum benötigtes Login Level um Neuigkeiten hinzuzufügen.';
$lang['admin']['news_edit']             = 'Neuigkeiten bearbeiten|Minimum benötigtes Login Level um Neuigkeiten zu bearbeiten.';
$lang['admin']['comm_add']              = 'Kommentar hinzufügen|Minimum benötigtes Login Level um Kommentare hinzuzufügen.';
$lang['admin']['comm_edit']             = 'Kommentare bearbeiten|Minimum benötigtes Login Level um Kommentare zu bearbeiten.';
$lang['admin']['news_html']             = 'HTML Neuigkeiten|Standardmäßige Aktivierung oder Deaktivierung von HTML in Neuigkeiten, oder generelles Verbot von HTML.<br />Hat keine Auswirkung auf bereits existierende Neuigkeiten';
$lang['admin']['comm_html']             = 'HTML Kommentare|Standardmäßige Aktivierung oder Deaktivierung von HTML in Kommentaren, oder generelles Verbot von HTML.<br />Hat keine Auswirkung auf bereits existierende Kommentare';
$lang['admin']['news_nicedit']          = 'Nicedit Textbox|Aktivierung oder Deaktivierung der Niceedit Textbox.';

// Slider options
$lang['admin']['slider_skin']              = 'Slider Skin|Wähle das Aussehen der Buttons und Icons des Sliders';
$lang['admin']['slider_alignment']         = 'Ausrichtung|obenLinks, openMitte, obenRechts, mitteLinks, mitte, mitteRechts, untenLinks, untenMitte, untenRechts';
$lang['admin']['slider_autoAdvance']       = 'Auto Slide|Alle Bilder automatisch abspielen.';
$lang['admin']['slider_mobileAutoAdvance'] = 'Mobil Auto Slide|Alle Bilder auf mobilen Geräten automatisch abspielen';
$lang['admin']['slider_barDirection']      = 'Leistenrichtung|';
$lang['admin']['slider_barPosition']       = 'Leistenposition|';
$lang['admin']['slider_easing']            = 'Lockerung|';
$lang['admin']['slider_mobileEasing']      = 'Mobil Lockerung|Leer lassen was die Einstellungen die gleich wie bei Lockerung sein sollen.';
$lang['admin']['slider_fx']                = 'Effekt|Art des Slideeffekts<br />- Es kann mehr als ein Effekt ausgewählt werden';
$lang['admin']['slider_mobileFx']          = 'Mobil Effekt|Leer lassen für die gleichen Einstellungen wie bei Effekt.';
$lang['admin']['slider_gridDifference']    = 'Grid Abstand|Um die Grid-Blöcke langsamer als die Slices zu machen, muss dieser Wert kleiner als der Wert von transPeriod sein.';
$lang['admin']['slider_height']            = 'Höhe|Höhe der Slider Bilder.<br />- Es können Pixel (als Beispiel \'300px\'), ein Prozentwert (relativ zur Breite der Slideshow, als Beispiel \'50%\') oder auto angegeben werden.';
$lang['admin']['slider_hover']             = 'Hover|Pause beim überfahren mit der Maus. Nicht verfügbar auf mobilen Geräten.';
$lang['admin']['slider_loader']            = 'Loader Typ|Loader Display Typ.<br />- Wenn "pie" ausgewählt wurde, wird auf älteren Browser wie z.B. IE8 ein Ladebalken angezeigt, wenn diese kein Kreis wiedergeben können.';
$lang['admin']['slider_loaderColor']       = 'Loader Farbe|Farbe des Loaderbildes';
$lang['admin']['slider_loaderBgColor']     = 'Loader Hintergrundfarbe|Hintergrundfarbe des Loaderbildes';
$lang['admin']['slider_loaderOpacity']     = 'Loader Deckkraft|Die Transparenz des Loaderbildes';
$lang['admin']['slider_loaderPadding']     = 'Loader Padding|Wieviel Pixel Abstand soll zwischen dem Loaderbild und dem Hintergrund sein';
$lang['admin']['slider_loaderStroke']      = 'Loader Strich|Die Dicke des Kreises und des Ladebalkens.<br />Bedenke: für den Kreis, muss die Dicke kleiner als die Hälfte des Kreisdurchmessers sein';
$lang['admin']['slider_minHeight']         = 'Min Höhe|Mindesthöhe des Sliders<br />- Kann auch leer gelassen werden';
$lang['admin']['slider_navigation']        = 'Navigation|Zeigt die Nafivationsbuttons (Zurück, Vor und Start/Stop Buttons)';
$lang['admin']['slider_navigationHover']   = 'Navigation Hover|Wenn "ja / yes" aktiviert ist, wird die Navigatio nur beim überfahren mit der Maus angezeigt, bei "nein / no" immer.';
$lang['admin']['slider_mobileNavHover']    = 'Mobile Navigation Hover|Das gleich wie "Navigation Hover" nur für mobile Geräte';
$lang['admin']['slider_opacityOnGrid']     = 'Deckkraft auf Raster|Wendet einen Fade Effekt auf die Blöcke und Slices an<br />- Wenn die Slideshow im Volldbild läuft oder einfach nur groß ist, ist es empfohlen diese Einstellung auf "false" zu setzen für einen glatteren Effekt.';
$lang['admin']['slider_overlayer']         = 'Overlayer|Platziert einen zusätzliche "transparente" Ebene über die Bilder um ein einfaches speichern der Bilder durch Rechtsklick mit der Maus zu verhinden';
$lang['admin']['slider_pagination']        = 'Seitengenierung|Zeigt das Seitengenerierungsicon';
$lang['admin']['slider_playPause']         = 'Start/Pause|Zeigt den Start/Pause Button';
$lang['admin']['slider_pauseOnClick']      = 'Pause bei Klick|Stoppt die Slideshow beim anklicken der Slider';
$lang['admin']['slider_pieDiameter']       = 'Kreisdurchmesser|Durchmesser des Kreises des Loaders';
$lang['admin']['slider_piePosition']       = 'Kreisposition|Wo soll der Kreis des Loaders angezeigt werden';
$lang['admin']['slider_portrait']          = 'Porträt|Aktivieren, wenn Bilder nicht beschnitten werden sollen';
$lang['admin']['slider_cols']              = 'Spalten|';
$lang['admin']['slider_rows']              = 'Reihen|';
$lang['admin']['slider_slicedCols']        = 'Sliced Spalten|Wenn 0 = der Wert von Spalten';
$lang['admin']['slider_slicedRows']        = 'Sliced Reihen|Wenn 0 = der Wert von Reihen';
$lang['admin']['slider_slideOn']           = 'Slide On|Wann soll der Übergangseffekt angewandt werden';
$lang['admin']['slider_thumbnails']        = 'Vorschaubilder|Zeigt Vorschaubilder beim überfahren der Seitenicons mit der Maus';
$lang['admin']['slider_time']              = 'Dauer|Dauer in Millisekunden zwischen zwei Slideeffekten';
$lang['admin']['slider_transPeriod']       = 'Effektdauer|Länge des Übergangseffekts in Millisekunden';
