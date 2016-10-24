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

$id = required_param('id',PARAM_INT);   // course
$PAGE->set_url('/mod/groupmembers/index.php', array('id'=>$id));

if (!$course = $DB->get_record('course', array('id'=>$id))) {
    print_error('invalidcourseid');
}

require_course_login($course);
$PAGE->set_pagelayout('incourse');

$eventdata = array('context' => context_course::instance($id));
$event = \mod_groupmembers\event\course_module_instance_list_viewed::create($eventdata);
$event->add_record_snapshot('course', $course);
$event->trigger();

$strplural = get_string("modulenameplural", "groupmembers");
$PAGE->set_title($strplural);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strplural);
echo $OUTPUT->header();

if (! $instances = get_all_instances_in_course("groupmembers", $course)) {
    notice(get_string('thereareno', 'moodle', $strplural), "../../course/view.php?id=$course->id");
}

echo '<h1>' . format_string($strplural) . '</h1>';
echo '<ul>';
foreach ($instances as $instance) {
    echo '<li><a href="view.php?id=' . $instance->coursemodule . '">' . format_string($instance->name,true) . '</a></li>';
}
echo '</ul>';

echo $OUTPUT->footer();