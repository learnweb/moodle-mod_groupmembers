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
 * Plugin entrance point: Main page of coursemodule instances
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__. '/../../config.php');
require_once(__DIR__. '/lib.php');

$id = required_param('id', PARAM_INT);  // Course Module ID.
$PAGE->set_url(new moodle_url('/mod/groupmembers/view.php', array('id' => $id)));

// Load course module.
if (! $cm = get_coursemodule_from_id('groupmembers', $id)) {
    print_error('invalidcoursemodule');
}

// Load corresponding course.
if (! $course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error('coursemisconf');
}

require_course_login($course, false, $cm);
$context = context_module::instance($cm->id);

// Load groupmembers object.
if (! $groupmembers = groupmembers_get_groupmembers($cm->instance)) {
    print_error('invalidcoursemodule');
}

$PAGE->set_title($groupmembers->name);
$PAGE->set_heading($course->fullname);

// Completion and trigger event.
groupmembers_view($groupmembers, $course, $cm, $context);

// Theme and module header/intro.
echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($groupmembers->name), 2, null);

if (!empty($groupmembers->intro)) {
    echo $OUTPUT->box(format_module_intro('groupmembers', $groupmembers, $cm->id), 'generalbox', 'intro');
}

// Collect applicable groups and their members.
$groupsandmembers = \mod_groupmembers\groups::get_groups_and_members($course->id, $groupmembers->listgroupingid,
    $USER->id, $groupmembers->showgroups == GROUPMEMBERS_SHOWGROUPS_OWN);

// Output special texts if no group was retrieved; otherwise render list.
if (count($groupsandmembers) === 0) {
    if ($groupmembers->showgroups == GROUPMEMBERS_SHOWGROUPS_OWN) {
        echo $OUTPUT->box(get_string('noowngroupsavailable', 'groupmembers'));
    } else {
        echo $OUTPUT->box(get_string('nogroupsavailable', 'groupmembers'));
    }
} else {
    /** @var mod_groupmembers_renderer $renderer */
    $renderer = $PAGE->get_renderer('mod_groupmembers');
    echo $renderer->render_allgroups($groupsandmembers, $groupmembers->showemail, $groupmembers->showphone,
                                     $groupmembers->showdeptinst, $groupmembers->showdesc);
}

// Theme footer.
echo $OUTPUT->footer();
