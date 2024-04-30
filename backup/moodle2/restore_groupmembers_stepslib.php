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
 * Structure step to restore one groupmembers activity
 * @package    mod_groupmembers
 * @copyright  2019 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Structure step to restore one groupmembers activity
 */
class restore_groupmembers_activity_structure_step extends restore_activity_structure_step {

    /**
     * Define structure of restore step.
     */
    protected function define_structure() {

        $paths = [];
        $paths[] = new restore_path_element('groupmembers', '/activity/groupmembers');

        // Return the paths wrapped into standard activity structure.
        return $this->prepare_activity_structure($paths);
    }

    /**
     * Restore groupmember activity.
     * @param object $data groupmember activity data to be restored.
     */
    protected function process_groupmembers($data) {
        global $DB;

        $data = (object)$data;
        $data->course = $this->get_courseid();

        // Insert the groupmembers record.
        $newitemid = $DB->insert_record('groupmembers', $data);
        // Immediately after inserting "activity" record, call this.
        $this->apply_activity_instance($newitemid);
    }

    /**
     * Restore related files after execute.
     */
    protected function after_execute() {
        // Add groupmembers related files, no need to match by itemname (just internally handled context).
        $this->add_related_files('mod_groupmembers', 'intro', null);
        $this->add_related_files('mod_groupmembers', 'content', null);
    }
}
