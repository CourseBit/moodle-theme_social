<?php
// This file is part of Moodle _ http://moodle.org/
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
 * Settings
 *
 * @package   theme_moodlebook
 * @copyright 2015 CourseBit LLC | www.coursebit.net
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$settings = null;
defined('MOODLE_INTERNAL') || die;
$ADMIN->add('themes', new admin_category('theme_moodlebook', 'Social'));
//
// Branding settings
//
$temp = new admin_settingpage('theme_moodlebook_branding',  get_string('setting:page:branding', 'theme_moodlebook'));

$name = 'theme_moodlebook/logo';
$title = get_string('setting:logo', 'theme_moodlebook');
$description = get_string('setting:logo:desc', 'theme_moodlebook');
$setting = new admin_setting_configstoredfile($name, $title, $description, 'logo');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_moodlebook/company_name';
$title = get_string('setting:company_name', 'theme_moodlebook');
$description = get_string('setting:company_name:desc', 'theme_moodlebook');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_moodlebook/body_bg';
$title = get_string('setting:body_bg', 'theme_moodlebook');
$description = get_string('setting:body_bg:desc', 'theme_moodlebook');
$default = '#f5f8fa';
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_moodlebook/primary_color';
$title = get_string('setting:primary_color', 'theme_moodlebook');
$description = get_string('setting:primary_color:desc', 'theme_moodlebook');
$default = '#3097d1';
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_moodlebook/inverse_navbar';
$title = get_string('setting:inverse_navbar', 'theme_moodlebook');
$description = get_string('setting:inverse_navbar:desc', 'theme_moodlebook');
$default = 'true';
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, 'true', 'false');
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_moodlebook/footer_bg_color';
$title = get_string('setting:footer_bg_color', 'theme_moodlebook');
$description = get_string('setting:footer_bg_color:desc', 'theme_moodlebook');
$default = '#333333';
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_moodlebook/footer_color';
$title = get_string('setting:footer_color', 'theme_moodlebook');
$description = get_string('setting:footer_color:desc', 'theme_moodlebook');
$default = '#ffffff';
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, $default, $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$ADMIN->add('theme_moodlebook', $temp);
