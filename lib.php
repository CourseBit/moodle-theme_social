<?php
// This file is part of The Bootstrap Moodle theme
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * @package    theme_bootstrap
 * @copyright  2014 Bas Brands, www.basbrands.nl
 * @authors    Bas Brands, David Scotson
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


function bootstrap_grid($hassidepre, $hassidepost) {

    if ($hassidepre && $hassidepost) {
        $regions = array('content' => 'col-sm-6 col-sm-push-3 col-lg-8 col-lg-push-2');
        $regions['pre'] = 'col-sm-3 col-sm-pull-6 col-lg-2 col-lg-pull-8';
        $regions['post'] = 'col-sm-3 col-lg-2';
    } else if ($hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-sm-9 col-sm-push-3 col-lg-10 col-lg-push-2');
        $regions['pre'] = 'col-sm-3 col-sm-pull-9 col-lg-2 col-lg-pull-10';
        $regions['post'] = 'emtpy';
    } else if (!$hassidepre && $hassidepost) {
        $regions = array('content' => 'col-sm-9 col-lg-10');
        $regions['pre'] = 'empty';
        $regions['post'] = 'col-sm-3 col-lg-2';
    } else if (!$hassidepre && !$hassidepost) {
        $regions = array('content' => 'col-md-12');
        $regions['pre'] = 'empty';
        $regions['post'] = 'empty';
    }

    if ('rtl' === get_string('thisdirection', 'langconfig')) {
        if ($hassidepre && $hassidepost) {
            $regions['pre'] = 'col-sm-3  col-sm-push-3 col-lg-2 col-lg-push-2';
            $regions['post'] = 'col-sm-3 col-sm-pull-9 col-lg-2 col-lg-pull-10';
        } else if ($hassidepre && !$hassidepost) {
            $regions = array('content' => 'col-sm-9 col-lg-10');
            $regions['pre'] = 'col-sm-3 col-lg-2';
            $regions['post'] = 'empty';
        } else if (!$hassidepre && $hassidepost) {
            $regions = array('content' => 'col-sm-9 col-sm-push-3 col-lg-10 col-lg-push-2');
            $regions['pre'] = 'empty';
            $regions['post'] = 'col-sm-3 col-sm-pull-9 col-lg-2 col-lg-pull-10';
        }
    }
    return $regions;
}

/**
 * Get setting from theme settings.
 * @author Joseph Conradt (joseph.conradt@coursebit.net)
 * @param string $settingname Name of setting
 * @param bool $allow_empty Set true if setting can be empty. Will return empty string
 * @return string|null
 */
function theme_moodlebook_get_setting($settingname, $allow_empty = false) {
    global $PAGE;
    if(isset($PAGE->theme->settings->$settingname)) {
        if($allow_empty) {
            return $PAGE->theme->settings->$settingname;
        } else if(!empty($PAGE->theme->settings->$settingname)) {
            return $PAGE->theme->settings->$settingname;
        }
    }
    return null;
}
/**
 * Check if setting exists from theme settings.
 * @author Joseph Conradt (joseph.conradt@coursebit.net)
 * @param string $settingname Name of setting
 * @param bool $allow_empty Set true if setting can be empty
 * @return bool
 */
function theme_moodlebook_has_setting($settingname, $allow_empty = false) {
    global $PAGE;
    if(isset($PAGE->theme->settings->$settingname)) {
        if($allow_empty) {
            return true;
        } else if(!empty($PAGE->theme->settings->$settingname)) {
            return true;
        }
    }
    return false;
}

function theme_moodlebook_process_css($css, theme_config $theme)
{
    $settings = array(
        'body_bg' => '#f5f8fa',
        'primary_color' => '#3097d1',
        'primary_color_dark' => theme_moodlebook_adjust_color('#3097d1', -50)
    );

    foreach($settings as $key => $setting) {
        $tags[] = sprintf('[[setting:%s]]', $key);
        if(is_array($setting)) {
            switch($setting['type']) {
                case 'image':
                    $values[] = theme_moodlebook_has_setting($key) ? $theme->setting_file_url($key, $setting['filearea']) : $setting['default'];
                break;
                case 'childcolor':
                    if(!theme_reveal_has_setting($key)) {
                        $parentkey = $setting['parent'];
                        $parentcolor = $settings[$parentkey];
                        if(!theme_moodlebook_has_setting($parentkey) && is_array($parentcolor) && $parentcolor['type'] == 'childcolor') {
                            $parentkey = $parentcolor['parent'];
                            $parentcolor = $settings[$parentkey];
                        }
                        $values[] = theme_moodlebook_has_setting($parentkey) ? theme_moodlebook_get_setting($parentkey) : $parentcolor;
                    } else {
                        $values[] = theme_moodlebook_get_setting($key);
                    }
                break;
            }
        } else {
            $values[] = isset($theme->settings->$key) ? $theme->settings->$key : $setting;
        }
    }
    $css = str_replace($tags, $values, $css);
    return $css;
}

function theme_moodlebook_adjust_color($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color   = hexdec($color); // Convert to decimal
        $color   = max(0,min(255,$color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}
