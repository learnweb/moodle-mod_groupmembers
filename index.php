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
 * Page that lists all groupmembers instances of a course (usually one...)
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__. '/../../config.php');
require_once(__DIR__. '/lib.php');

$id = required_param('id', PARAM_INT);   // Course ID.
$PAGE->set_url('/mod/groupmembers/index.php', ['id' => $id]);

$course = $DB->get_record('course', ['id' => $id], '*', MUST_EXIST);

require_course_login($course);
$PAGE->set_pagelayout('incourse');

$eventdata = ['context' => context_course::instance($id)];
$event = \mod_groupmembers\event\course_module_instance_list_viewed::create($eventdata);
$event->add_record_snapshot('course', $course);
$event->trigger();

$strplural = get_string('modulenameplural', 'groupmembers');
$PAGE->set_title($strplural);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strplural);

echo $OUTPUT->header();
echo $OUTPUT->heading($strplural);

if (! $instances = get_all_instances_in_course('groupmembers', $course)) {
    notice(get_string('thereareno', 'moodle', $strplural),
        new moodle_url('/course/view.php', ['id' => $course->id]));
}



$linklist = [];
foreach ($instances as $instance) {
    $linklist[] = html_writer::link(new moodle_url('/mod/groupmembers/view.php', ['id' => $instance->coursemodule]),
        $instance->name);
}
echo html_writer::alist($linklist);

echo $OUTPUT->footer();
