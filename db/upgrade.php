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
 * Moodle plugin upgrade functions
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade script (no-op)
 * @param int $oldversion Version number before update
 * @return bool
 */
function xmldb_groupmembers_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager(); // Loads ddl manager and xmldb classes.
    $table = new xmldb_table('groupmembers');

    if ($oldversion < 2022042100) {
        // Define field showphone to be added to groupmembers.
        $field = new xmldb_field('showphone', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'showemail');

        // Conditionally launch add field option_mute_upon_entry.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field showdeptinst to be added to groupmembers.
        $field = new xmldb_field('showdeptinst', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'showphone');

        // Conditionally launch add field option_mute_upon_entry.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field showdesc to be added to groupmembers.
        $field = new xmldb_field('showdesc', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'showdeptinst');

        // Conditionally launch add field option_mute_upon_entry.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }
        upgrade_mod_savepoint(true, 2022042100, 'groupmembers');

    }

    return true;
}
