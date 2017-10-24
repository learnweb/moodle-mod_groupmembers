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
 * Moodle interface functions for activity modules
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Define some constants
 */
define('GROUPMEMBERS_SHOWGROUPS_ALL', 0);
define('GROUPMEMBERS_SHOWGROUPS_OWN', 1);
define('GROUPMEMBERS_SHOWEMAIL_NO', 0);
define('GROUPMEMBERS_SHOWEMAIL_OWNGROUP', 1);
define('GROUPMEMBERS_SHOWEMAIL_ALLGROUPS', 2);

/**
 * List of features supported in groupmembers module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function groupmembers_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:
            return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_GROUPS:
            return false;
        case FEATURE_GROUPINGS:
            return false;
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GRADE_OUTCOMES:
            return false;
        case FEATURE_BACKUP_MOODLE2:
            return false;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        default:
            return null;
    }
}

/**
 * Add groupmembers instance.
 * @param stdClass $data
 * @param mod_groupmembers_mod_form $mform
 * @return int new groupmembers instance id
 */
function groupmembers_add_instance(stdClass $data, mod_groupmembers_mod_form $mform) {
    global $DB;

    $data->timemodified = time();
    $data->id = $DB->insert_record('groupmembers', $data);

    return $data->id;
}

/**
 * Update groupmembers instance.
 * @param stdClass $data
 * @param mod_groupmembers_mod_form $mform
 * @return bool true
 */
function groupmembers_update_instance($data, $mform) {
    global $DB;

    $data->timemodified = time();
    $data->id           = $data->instance;
    $DB->update_record('groupmembers', $data);

    return true;
}

/**
 * Delete groupmembers instance.
 * @param int $id
 * @return bool true
 */
function groupmembers_delete_instance($id) {
    global $DB;

    if (!$groupmembers = $DB->get_record('groupmembers', ['id' => $id])) {
        return false;
    }

    // Note: all context files are deleted automatically.
    $DB->delete_records('groupmembers', ['id' => $groupmembers->id ]);

    return true;
}

/**
 * Gets a full groupmembers record
 *
 * @param int $groupmembersid
 * @return stdClass|bool The groupmembers or false
 */
function groupmembers_get_groupmembers($groupmembersid) {
    global $DB;

    if ($groupmembers = $DB->get_record('groupmembers', ['id' => $groupmembersid])) {
        return $groupmembers;
    }
    return false;
}

/**
 * Mark the activity completed (if required) and trigger the course_module_viewed event.
 *
 * @param  stdClass $groupmembers   groupmembers object
 * @param  stdClass $course     course object
 * @param  stdClass $cm         course module object
 * @param  stdClass $context    context object
 * @since Moodle 3.0
 */
function groupmembers_view($groupmembers, $course, $cm, $context) {

    // Trigger course_module_viewed event.
    $params = array(
        'context' => $context,
        'objectid' => $groupmembers->id
    );

    $event = \mod_groupmembers\event\course_module_viewed::create($params);
    $event->add_record_snapshot('course_modules', $cm);
    $event->add_record_snapshot('course', $course);
    $event->add_record_snapshot('groupmembers', $groupmembers);
    $event->trigger();

    // Completion.
    $completion = new completion_info($course);
    $completion->set_module_viewed($cm);
}
