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
 * Static methods for collecting relevant groups and members
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_groupmembers;

/**
 * Static methods for collecting relevant groups and members
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class groups {
    /**
     * Fetch the relevant groups and members. Note that this function does not access module instance
     * settings itself; instead, you are expected to pass relevant settings (esp. Grouping ID and whether only own
     * groups should be displayed) yourself.
     *
     * @param int $courseid ID of the course
     * @param int $groupingid ID of the grouping (0 if irrelevant)
     * @param int $userid ID of the current user
     * @param bool $onlyown Whether only groups of the given user $userid should be returned
     * @return array Numerically-indexed array of all relevant groups. Each element is an array of the form [
     *      'group' => \stdClass, 'members' => array[\stdClass], 'ismember' => bool ]
     */
    public static function get_groups_and_members($courseid, $groupingid, $userid, $onlyown) {
        $groupsandmembers = array();

        // Fetch relevant groups.
        if ($onlyown) {
            $groups = groups_get_all_groups($courseid, $userid, $groupingid);
        } else {
            $groups = groups_get_all_groups($courseid, 0, $groupingid);
        }

        foreach ($groups as $group) {
            // Check whether current user is a member. If only own groups are displayed, this check is trivial.
            if ($onlyown) {
                $ismember = true;
            } else {
                $ismember = groups_is_member($group->id, $userid);
            }

            // Get members of group.
            $members = groups_get_members($group->id);
            $groupsandmembers[] = array(
                'group' => $group,
                'members' => $members,
                'ismember' => $ismember
            );
        }

        return $groupsandmembers;
    }
}
