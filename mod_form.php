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
 * Settings mod_form for mod_groupmembers
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Settings mod_form for mod_groupmembers
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */
class mod_groupmembers_mod_form extends moodleform_mod {

    /**
     * Coursemodule settings form definition.
     */
    protected function definition() {
        global $CFG, $COURSE, $DB;

        $mform    =& $this->_form;

        // -------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('groupmembersname', 'groupmembers'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $this->standard_intro_elements(get_string('description', 'groupmembers'));

        // -------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('modulename', 'groupmembers'));

        // Groupings selector - used to select grouping for groups in activity.
        $options = array();
        if ($groupings = $DB->get_records('groupings', array('courseid' => $COURSE->id))) {
            foreach ($groupings as $grouping) {
                $options[$grouping->id] = format_string($grouping->name);
            }
        }
        core_collator::asort($options);
        $options = array(0 => get_string('none')) + $options;
        $mform->addElement('select', 'listgroupingid', get_string('listgrouping', 'groupmembers'), $options);
        $mform->addRule('listgroupingid', null, 'required', null, 'client');

        // Group visibility selector - used to decide which groups to show in listing.
        $options = array(
            GROUPMEMBERS_SHOWGROUPS_ALL => get_string('showgroups:all', 'groupmembers'),
            GROUPMEMBERS_SHOWGROUPS_OWN => get_string('showgroups:own', 'groupmembers'),
        );
        $mform->addElement('select', 'showgroups', get_string('showgroups', 'groupmembers'), $options);
        $mform->addRule('showgroups', null, 'required', null, 'client');

        // E-Mail visibility selector - used to decide whether e-mail adresses should be shown to other users.
        $options = array(
            GROUPMEMBERS_SHOWEMAIL_NO => get_string('showemail:no', 'groupmembers'),
            GROUPMEMBERS_SHOWEMAIL_OWNGROUP => get_string('showemail:owngroup', 'groupmembers'),
            GROUPMEMBERS_SHOWEMAIL_ALLGROUPS => get_string('showemail:allgroups', 'groupmembers'),
        );
        $mform->addElement('select', 'showemail', get_string('showemail', 'groupmembers'), $options);
        $mform->addRule('showemail', null, 'required', null, 'client');

        // -------------------------------------------------------------------------------
        $this->standard_coursemodule_elements();
        // -------------------------------------------------------------------------------
        $this->add_action_buttons();
    }
}

