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
 * @group    mod_groupmembers
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
        $grouping1 = $dg->create_grouping(['courseid' => $course->id]);
        $dg->create_grouping_group(['groupingid' => $grouping1->id, 'groupid' => $group1->id]);
        $dg->create_grouping_group(['groupingid' => $grouping1->id, 'groupid' => $group2->id]);

        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($course->id, 0, $user1->id, true));

        // Add user to group.
        $dg->create_group_member(['userid' => $user1->id, 'groupid' => $group1->id]);

        $result = \mod_groupmembers\groups::get_groups_and_members($course->id, 0, $user1->id, true);
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
        $grouping1 = $dg->create_grouping(['courseid' => $course->id]);
        $grouping2 = $dg->create_grouping(['courseid' => $course->id]);
        $dg->create_grouping_group(['groupingid' => $grouping1->id, 'groupid' => $group1->id]);
        $dg->create_grouping_group(['groupingid' => $grouping1->id, 'groupid' => $group2->id]);

        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($course->id, $grouping1->id, $user1->id, true));
        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($course->id, $grouping2->id, $user1->id, true));

        // Add user to group.
        $dg->create_group_member(['userid' => $user1->id, 'groupid' => $group1->id]);

        $result = \mod_groupmembers\groups::get_groups_and_members($course->id, $grouping1->id, $user1->id, true);
        static::assertCount(1, $result);
        static::assertTrue($result[0]['ismember']);
        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($course->id, $grouping2->id, $user1->id, true));
    }

    /**
     * Test pair:
     * - show all groups
     * - show regardless of grouping
     */
    public function test_all_nogrouping() {
        $this->resetAfterTest();
        $dg = static::getDataGenerator();

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
        $grouping1 = $dg->create_grouping(['courseid' => $course->id]);
        $dg->create_grouping_group(['groupingid' => $grouping1->id, 'groupid' => $group1->id]);
        $dg->create_grouping_group(['groupingid' => $grouping1->id, 'groupid' => $group2->id]);

        $res1 = \mod_groupmembers\groups::get_groups_and_members($course->id, 0, $user1->id, false);
        static::assertCount(2, $res1);
        foreach ($res1 as $group) {
            self::assertFalse($group['ismember']);
        }

        // Add user to group.
        $dg->create_group_member(['userid' => $user1->id, 'groupid' => $group1->id]);

        $res2 = \mod_groupmembers\groups::get_groups_and_members($course->id, 0, $user1->id, false);
        static::assertCount(2, $res2);
        foreach ($res2 as $group) {
            if ($group['group']->id == $group1->id) {
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
        $group3 = $dg->create_group(['courseid' => $course->id]);
        $group4 = $dg->create_group(['courseid' => $course->id]);
        $grouping1 = $dg->create_grouping(['courseid' => $course->id]);
        $grouping2 = $dg->create_grouping(['courseid' => $course->id]);
        $dg->create_grouping_group(['groupingid' => $grouping1->id, 'groupid' => $group1->id]);
        $dg->create_grouping_group(['groupingid' => $grouping1->id, 'groupid' => $group2->id]);

        static::assertEmpty(\mod_groupmembers\groups::get_groups_and_members($course->id, $grouping2->id, $user1->id, false));

        // Add group to grouping
        $dg->create_grouping_group(['groupingid' => $grouping2->id, 'groupid' => $group3->id]);
        $dg->create_grouping_group(['groupingid' => $grouping2->id, 'groupid' => $group4->id]);

        $res1 = \mod_groupmembers\groups::get_groups_and_members($course->id, $grouping2->id, $user1->id, false);
        static::assertCount(2, $res1);
        foreach ($res1 as $group) {
            self::assertFalse($group['ismember']);
        }

        // Add user to group.
        $dg->create_group_member(['userid' => $user1->id, 'groupid' => $group3->id]);

        $res2 = \mod_groupmembers\groups::get_groups_and_members($course->id, $grouping2->id, $user1->id, false);
        static::assertCount(2, $res2);
        foreach ($res2 as $group) {
            if ($group['group']->id == $group3->id) {
                self::assertTrue($group['ismember']);
            } else {
                self::assertFalse($group['ismember']);
            }
        }
    }
}