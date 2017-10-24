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
 * Test logic used by the renderer whether to show/hide particular elements
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Test logic used by the renderer whether to show/hide particular elements
 *
 * @package    mod_groupmembers
 * @copyright  2017 Dennis M. Riehle, WWU Münster
 * @copyright  2017 Jan C. Dageförde, WWU Münster
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_groupmembers_groups_datastructure_testcase extends advanced_testcase {

    /**
     * Test pair:
     * - show only own groups
     * - show regardless of grouping
     */
    public function test_only_own_nogrouping() {
        $this->resetAfterTest();
        $dg = static::getDataGenerator();

        list($courseid, $userid, $groupid) = static::prepare_basic_data($dg);

        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($courseid, 0, $userid, true));

        // Add user to group.
        $dg->create_group_member(['userid' => $userid, 'groupid' => $groupid]);

        $result = \mod_groupmembers\groups::get_groups_and_members($courseid, 0, $userid, true);
        static::assertCount(1, $result);
        static::assertTrue($result[0]['ismember']);
    }

    /**
     * Test pair:
     * - show only own groups
     * - limit to particular grouping
     */
    public function test_only_own_withgrouping() {
        $this->resetAfterTest();
        $dg = static::getDataGenerator();
        list($courseid, $userid, $groupid, $groupingid) = $this->prepare_basic_data($dg);

        $grouping2 = $dg->create_grouping(['courseid' => $courseid]);

        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($courseid, $groupingid, $userid, true));
        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($courseid, $grouping2->id, $userid, true));

        // Add user to group.
        $dg->create_group_member(['userid' => $userid, 'groupid' => $groupid]);

        $result = \mod_groupmembers\groups::get_groups_and_members($courseid, $groupingid, $userid, true);
        static::assertCount(1, $result);
        static::assertTrue($result[0]['ismember']);
        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($courseid, $grouping2->id, $userid, true));
    }

    /**
     * Test pair:
     * - show all groups
     * - show regardless of grouping
     */
    public function test_all_nogrouping() {
        $this->resetAfterTest();
        $dg = static::getDataGenerator();
        list($courseid, $userid, $groupid) = $this->prepare_basic_data($dg);

        $res1 = \mod_groupmembers\groups::get_groups_and_members($courseid, 0, $userid, false);
        static::assertCount(2, $res1);
        foreach ($res1 as $group) {
            self::assertFalse($group['ismember']);
        }

        // Add user to group.
        $dg->create_group_member(['userid' => $userid, 'groupid' => $groupid]);

        $res2 = \mod_groupmembers\groups::get_groups_and_members($courseid, 0, $userid, false);
        static::assertCount(2, $res2);
        foreach ($res2 as $group) {
            if ($group['group']->id == $groupid) {
                self::assertTrue($group['ismember']);
            } else {
                self::assertFalse($group['ismember']);
            }
        }

    }

    /**
     * Test pair:
     * - show all groups
     * - limit to particular grouping
     */
    public function test_all_withgrouping() {
        $this->resetAfterTest();
        $dg = static::getDataGenerator();
        list($courseid, $userid) = $this->prepare_basic_data($dg);

        // Create additional groups and groupings.
        $group3 = $dg->create_group(['courseid' => $courseid]);
        $group4 = $dg->create_group(['courseid' => $courseid]);
        $grouping2 = $dg->create_grouping(['courseid' => $courseid]);

        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($courseid, $grouping2->id, $userid, false));

        // Add group to grouping.
        $dg->create_grouping_group(['groupingid' => $grouping2->id, 'groupid' => $group3->id]);
        $dg->create_grouping_group(['groupingid' => $grouping2->id, 'groupid' => $group4->id]);

        $res1 = \mod_groupmembers\groups::get_groups_and_members($courseid, $grouping2->id, $userid, false);
        static::assertCount(2, $res1);
        foreach ($res1 as $group) {
            self::assertFalse($group['ismember']);
        }

        // Add user to group.
        $dg->create_group_member(['userid' => $userid, 'groupid' => $group3->id]);

        $res2 = \mod_groupmembers\groups::get_groups_and_members($courseid, $grouping2->id, $userid, false);
        static::assertCount(2, $res2);
        foreach ($res2 as $group) {
            if ($group['group']->id == $group3->id) {
                self::assertTrue($group['ismember']);
            } else {
                self::assertFalse($group['ismember']);
            }
        }
    }

    /**
     * Prepares a general testing environment that is relevant for this test case, consisting of
     * - 2 users, both enrolled in
     * - 1 course, that contains
     * - 1 grouping, which consists of
     * - 2 groups.
     *
     * Users are not members of any groups.
     *
     * @param testing_data_generator $dg Data generator
     * @return array [ID of course, ID of first user, ID of first group, ID of grouping]
     */
    private static function prepare_basic_data(testing_data_generator $dg) {
        // Create course.
        $course = $dg->create_course();

        // Create users and enrol.
        $user1 = $dg->create_user();
        $user2 = $dg->create_user();
        $dg->enrol_user($user1->id, $course->id);
        $dg->enrol_user($user2->id, $course->id);

        // Create groups and groupings.
        $group1 = $dg->create_group(['courseid' => $course->id]);
        $group2 = $dg->create_group(['courseid' => $course->id]);
        $grouping = $dg->create_grouping(['courseid' => $course->id]);
        $dg->create_grouping_group(['groupingid' => $grouping->id, 'groupid' => $group1->id]);
        $dg->create_grouping_group(['groupingid' => $grouping->id, 'groupid' => $group2->id]);

        return [$course->id, $user1->id, $group1->id, $grouping->id];
    }
}