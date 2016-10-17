<?php

/**
 * Definition of groupmembers event handlers
 *
 * @package    mod_groupmembers
 * @copyright  2016 Dennis Riehle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_groupmembers_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    // code may be needed later

    return true;
}
