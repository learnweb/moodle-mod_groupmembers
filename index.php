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

// there is no "superview" in case the module is instanced multiple timews
print_error('invalidcourseid');



