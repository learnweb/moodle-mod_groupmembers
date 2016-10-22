<?php

/**
 * Definition of groupmembers event handlers
 *
 * @package    mod_groupmembers
 * @copyright  2016 Dennis Riehle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once("lib.php");

$id = required_param('id', PARAM_INT);  // Course Module ID
$PAGE->set_url(new moodle_url('/mod/groupmembers/view.php', array('id'=>$id)));

// load course module
if (! $cm = get_coursemodule_from_id('groupmembers', $id)) {
    print_error('invalidcoursemodule');
}

// load corresponding course
if (! $course = $DB->get_record("course", array("id" => $cm->course))) {
    print_error('coursemisconf');
}

require_course_login($course, false, $cm);
$context = context_module::instance($cm->id);

// load groupmembers object
if (! $groupmembers = groupmembers_get_groupmembers($cm->instance)) {
    print_error('invalidcoursemodule');
}

$PAGE->set_title($groupmembers->name);
$PAGE->set_heading($course->fullname);

// Completion and trigger event.
groupmembers_view($groupmembers, $course, $cm, $context);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($groupmembers->name), 2, null);

$groups = groups_get_all_groups($course->id, 0, $groupmembers->listgroupingid);

foreach ($groups as $group) {
    $table = new html_table();
    $table->head(array(
        get_string("lastname", "groupmembers"),
        get_string("firstname", "groupmembers"),
        get_string("email", "groupmembers")
    ));

    $members = groups_get_members($group->id);
    foreach ($members as $member) {
        $table->data[] = array(
            $member->lastname,
            $member->firstname,
            $member->email
        );
    }

    echo '<h3>' . htmlspecialchars($group->name) . '</h3>';
    html_writer::table($table);
}

echo $OUTPUT->footer();
