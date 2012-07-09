<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Character display configuration
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @version    SVN: $Id: config.func.php 2222 2010-12-05 10:05:37Z c.treyce@gmail.com $
 * @link       http://www.wowroster.net
 * @package    CharacterInfo
 */

if( !defined('IN_ROSTER') )
{
  exit('Detected invalid access to this file!');
}

function sliderEasing($values) {
  global $roster;

  $easing = array(
    'linear',
    'swing',
    'easeInQuad',
    'easeOutQuad',
    'easeInOutQuad',
    'easeInCubic',
    'easeOutCubic',
    'easeInOutCubic',
    'easeInQuart',
    'easeOutQuart',
    'easeInOutQuart',
    'easeInQuint',
    'easeOutQuint',
    'easeInOutQuint',
    'easeInSine',
    'easeOutSine',
    'easeInOutSine',
    'easeInExpo',
    'easeOutExpo',
    'easeInOutExpo',
    'easeInCirc',
    'easeOutCirc',
    'easeInOutCirc',
    'easeInElastic',
    'easeOutElastic',
    'easeInOutElastic',
    'easeInBack',
    'easeOutBack',
    'easeInOutBack',
    'easeInBounce',
    'easeOutBounce',
    'easeInOutBounce',
  );

  $input_field = '<select name="config_' . $values['name'] . '">' . "\n";

  // create a 'none' value
  if ($values['value'] == '') {
    $input_field .= '  <option value="" selected="selected">none</option>' . "\n";
    $not_selected = FALSE;
  }
  else {
    $input_field .= '  <option value="">none</option>' . "\n";
    $not_selected = TRUE;
  }

  foreach ($easing as $value) {
    if ($value == $values['value'] && $not_selected) {
      $input_field .= '  <option value="' . $value . '" selected="selected">' . $value . '</option>' . "\n";
      $not_selected = FALSE;
    }
    else {
      $input_field .= '  <option value="' . $value . '">' . $value . '</option>' . "\n";
    }
  }
  $input_field .= '</select>';

  return $input_field;
}

function sliderFx($values) {
  global $roster;

  $fx = array(
    'random',
    'simpleFade',
    'curtainTopLeft',
    'curtainTopRight',
    'curtainBottomLeft',
    'curtainBottomRight',
    'curtainSliceLeft',
    'curtainSliceRight',
    'blindCurtainTopLeft',
    'blindCurtainTopRight',
    'blindCurtainBottomLeft',
    'blindCurtainBottomRight',
    'blindCurtainSliceBottom',
    'blindCurtainSliceTop',
    'stampede',
    'mosaic',
    'mosaicReverse',
    'mosaicRandom',
    'mosaicSpiral',
    'mosaicSpiralReverse',
    'topLeftBottomRight',
    'bottomRightTopLeft',
    'bottomLeftTopRight',
    'bottomLeftTopRight',
    'scrollLeft',
    'scrollRight',
    'scrollHorz',
    'scrollBottom',
    'scrollTop'
  );

  $input_field = '<select name="config_' . $values['name'] . '" multiple="multiple" class="multiselect">' . "\n";

  foreach ($fx as $value) {
    if ($value == $values['value']) {
      $input_field .= '  <option value="'. $value .'" selected="selected">'. $value ."</option>\n";
    }
    else {
      $input_field .= '  <option value="'. $value .'">'. $value ."</option>\n";
    }
  }
  $input_field .= '</select>';

  return $input_field;
}

function sliderSkin($values) {
  global $roster;

  $skin = array(
    'camera_amber_skin',
    'camera_ash_skin',
    'camera_azure_skin',
    'camera_beige_skin',
    'camera_black_skin',
    'camera_blue_skin',
    'camera_brown_skin',
    'camera_burgundy_skin',
    'camera_charcoal_skin',
    'camera_chocolate_skin',
    'camera_coffee_skin',
    'camera_cyan_skin',
    'camera_fuchsia_skin',
    'camera_gold_skin',
    'camera_green_skin',
    'camera_grey_skin',
    'camera_indigo_skin',
    'camera_khaki_skin',
    'camera_lime_skin',
    'camera_magenta_skin',
    'camera_maroon_skin',
    'camera_orange_skin',
    'camera_olive_skin',
    'camera_pink_skin',
    'camera_pistachio_skin',
    'camera_pink_skin',
    'camera_red_skin',
    'camera_tangerine_skin',
    'camera_turquoise_skin',
    'camera_violet_skin',
    'camera_white_skin',
    'camera_yellow_skin'
  );

  $input_field = '<select name="config_' . $values['name'] . '">' . "\n";

  $not_selected = TRUE;
  foreach ($skin as $value) {
    if ($value == $values['value'] && $not_selected) {
      $input_field .= '  <option value="' . $value . '" selected="selected">' . $value . '</option>' . "\n";
      $not_selected = FALSE;
    }
    else {
      $input_field .= '  <option value="' . $value . '">' . $value . '</option>' . "\n";
    }
  }
  $input_field .= '</select>';

  return $input_field;
}
