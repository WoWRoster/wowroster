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

  $select_one = 1;
  foreach ($easing as $value) {
    if ($value == $values['value'] && $select_one) {
      $input_field .= '  <option value="' . $value . '" selected="selected">' . $value . '</option>' . "\n";
      $select_one = 0;
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
