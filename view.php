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
 * Plugin entrance point: Main page
 *
 * @package    mod_groupmembers
 * @copyright  2016 Dennis Riehle, Jan C. Dageförde
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
$output = false;

foreach ($groups as $group) {
    // skip group, if user is not in the group and only own groups are to be displayed
    if ($groupmembers->showgroups == GROUPMEMBERS_SHOWGROUPS_OWN && !groups_is_member($group->id, $USER->id)) {
        continue;
    }

    // generate HTML table with fixed widths
    $table = new html_table();
    $table->head = array(
        get_string('user:picture', 'groupmembers'),
        get_string('user:fullname', 'groupmembers'),
        get_string('user:contact', 'groupmembers'),
    );
    $table->size = array('15%', '35%', '50%');
    $table->data = array();

    // output members
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

    echo $OUTPUT->heading($group->name, 3, '', 'group-' . $group->id);
    echo html_writer::table($table);
    $output = true;
}

if (!$output && $groupmembers->showgroups == GROUPMEMBERS_SHOWGROUPS_OWN) {
    echo $OUTPUT->box(get_string('noowngroupsavailable', 'groupmembers'));
}
elseif (!$output) {
    echo $OUTPUT->box(get_string('nogroupsavailable', 'groupmembers'));
}

echo $OUTPUT->footer();
