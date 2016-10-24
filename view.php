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

if (!empty($groupmembers->intro)) {
    echo $OUTPUT->box(format_module_intro('groupmembers', $groupmembers, $cm->id), 'generalbox', 'intro');
}

$groups = groups_get_all_groups($course->id, 0, $groupmembers->listgroupingid);

foreach ($groups as $group) {
    $table = new html_table();
    $table->head = array(
        get_string('user:picture', 'groupmembers'),
        get_string('user:fullname', 'groupmembers'),
        get_string('user:contact', 'groupmembers'),
    );
    $table->size = array('15%', '35%', '50%');
    $table->data = array();

    $members = groups_get_members($group->id);
    foreach ($members as $member) {
        $userurl = new moodle_url('/user/view.php', array('id' => $member->id, 'course' => $cm->id));
        if ($groupmembers->showemail == GROUPMEMBERS_SHOWEMAIL_ALLGROUPS ||
            ($groupmembers->showemail == GROUPMEMBERS_SHOWEMAIL_OWNGROUP && groups_is_member($group->id, $USER->id)))
        {
            $contacturl = new moodle_url('mailto:' . $member->email);
            $contacttext = $member->email;
        }
        else {
            $contacturl = new moodle_url('/message/index.php', array('id' => $member->id));
            $contacttext = get_string('sendmessage', 'groupmembers');
        }
        $table->data[] = array(
            $OUTPUT->user_picture($member),
            html_writer::link($userurl, fullname($member)),
            html_writer::link($contacturl, $contacttext)
        );
    }

    echo '<h3>' . htmlspecialchars($group->name) . '</h3>';
    echo html_writer::table($table);
}

echo $OUTPUT->footer();
