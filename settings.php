<?php
// This file is part of a plugin for Moodle - http://moodle.org/
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
 * Plugin definition
 *
 * @package    mod_groupmembers
 * @copyright  2018 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Include lib.php (to be able to use the plugin's constants).
require_once(__DIR__ . '/lib.php');

if ($ADMIN->fulltree) {
    // Settings title to group admin settings with a common heading. We don't want a description here.
    $name = 'mod_groupmembers/adminsettingsheading';
    $title = get_string('setting_adminsettingsheading', 'mod_groupmembers', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $settings->add($setting);

    // Setting to enable/disable showing e-mail addresses.
    $name = 'mod_groupmembers/showemailenable';
    $title = get_string('setting_showemailenable', 'mod_groupmembers', null, true);
    $description = get_string('setting_showemailenable_desc', 'mod_groupmembers', null, true);
    $setting = new admin_setting_configcheckbox($name, $title, $description, 1);
    $settings->add($setting);

    // Settings title to group module defaults with a common heading. We don't want a description here.
    $name = 'mod_groupmembers/defaultsheading';
    $title = get_string('setting_defaultsheading', 'mod_groupmembers', null, true);
    $setting = new admin_setting_heading($name, $title, null);
    $settings->add($setting);

    // Setting for the default of the showgroups module instance setting.
    $name = 'mod_groupmembers/showgroupsdefault';
    $title = get_string('setting_showgroupsdefault', 'mod_groupmembers', null, true);
    $description = get_string('setting_showgroupsdefault_desc', 'mod_groupmembers', null, true);
    $options = array(
            GROUPMEMBERS_SHOWGROUPS_ALL => get_string('showgroups:all', 'groupmembers'),
            GROUPMEMBERS_SHOWGROUPS_OWN => get_string('showgroups:own', 'groupmembers'),
    );
    $setting = new admin_setting_configselect($name, $title, $description, GROUPMEMBERS_SHOWGROUPS_ALL, $options);
    $settings->add($setting);

    // Setting for the default of the showemail module instance setting.
    $name = 'mod_groupmembers/showemaildefault';
    $title = get_string('setting_showemaildefault', 'mod_groupmembers', null, true);
    $description = get_string('setting_showemaildefault_desc', 'mod_groupmembers', null, true);
    $options = array(
            GROUPMEMBERS_SHOWEMAIL_NO => get_string('showemail:no', 'groupmembers'),
            GROUPMEMBERS_SHOWEMAIL_OWNGROUP => get_string('showemail:owngroup', 'groupmembers'),
            GROUPMEMBERS_SHOWEMAIL_ALLGROUPS => get_string('showemail:allgroups', 'groupmembers'),
    );
    $setting = new admin_setting_configselect($name, $title, $description, GROUPMEMBERS_SHOWEMAIL_NO, $options);
    $settings->add($setting);
}
