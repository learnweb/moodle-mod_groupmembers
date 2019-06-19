<?php
// This file is part of Moodle - http://moodle.org/
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
 * Define all the backup steps that will be used by the backup_groupmembers_activity_task
 *
 * @package   mod_groupmembers
 * @copyright 2019 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete groupmembers structure for backup, with file and id annotations
 */
class backup_groupmembers_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $groupmembers = new backup_nested_element('groupmembers', array('id'),
                array('name', 'intro', 'introformat', 'listgroupingid', 'showgroups', 'showemail', 'timemodified'));

        // Define sources.
        $groupmembers->set_source_table('groupmembers', array('id' => backup::VAR_ACTIVITYID));

        // Define id annotations.

        // Define file annotations.
        $groupmembers->annotate_files('mod_groupmembers', 'intro', null);
        $groupmembers->annotate_files('mod_groupmembers', 'content', null);

        // Return the root element (groupmembers), wrapped into standard activity structure.
        return $this->prepare_activity_structure($groupmembers);
    }
}
