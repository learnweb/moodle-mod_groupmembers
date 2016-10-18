<?php

/**
 * Definition of groupmembers event handlers
 *
 * @package    mod_groupmembers
 * @copyright  2016 Dennis Riehle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * List of features supported in groupmembers module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function groupmembers_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_OTHER;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_MOD_INTRO:               return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return false;
        case FEATURE_SHOW_DESCRIPTION:        return false;

        default: return null;
    }
}

/**
 * Add groupmembers instance.
 * @param object $data
 * @param object $mform
 * @return int new groupmembers instance id
 */
function groupmembers_add_instance($data, $mform) {
    global $CFG, $DB;

    $cmid = $data->coursemodule;
    $data->timemodified = time();
    $data->id = $DB->insert_record('groupmembers', $data);

    // we need to use context now, so we need to make sure all needed info is already in db
    $DB->set_field('course_modules', 'instance', $data->id, array('id'=>$cmid));

    return $data->id;
}

/**
 * Update groupmembers instance.
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function groupmembers_update_instance($data, $mform) {
    global $CFG, $DB;

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

    if (!$groupmembers = $DB->get_record('groupmembers', array('id'=>$id))) {
        return false;
    }

    // note: all context files are deleted automatically
    $DB->delete_records('groupmembers', array('id'=>$groupmembers->id));

    return true;
}

/**
 * Gets a full groupmembers record
 *
 * @global object
 * @param int $groupmembersid
 * @return object|bool The groupmembers or false
 */
function groupmembers_get_groupmembers($groupmembersid) {
    global $DB;

    if ($groupmembers = $DB->get_record("groupmembers", array("id" => $groupmembersid))) {
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
