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

    public function test_only_own_nogrouping() {
        $this->resetAfterTest();
        //create course
        //create users
        //create enrolments
        //create groups

        //call .\groups::get_groups_and_members (no grouping), assert empty

        //add user to group

        //call .\groups::get_groups_and_members (no grouping), assert 1 group

        //assertTrue ismember on single group


        $this->assertTrue(true);
    }

    public function test_only_own_withgrouping() {
        $this->resetAfterTest();
        //create course
        //create users
        //create enrolments
        //create groups

        //call .\groups::get_groups_and_members (with grouping), assert empty

        //add user to group

        //call .\groups::get_groups_and_members (with grouping), assert empty

        //add group to grouping

        //call .\groups::get_groups_and_members (with grouping), assert 1 group

        //assertTrue ismember on single group

        $this->assertTrue(true);
    }
    public function test_all_nogrouping() {
        $this->resetAfterTest();
        //create course
        //create users
        //create enrolments
        //create groups

        //call .\groups::get_groups_and_members (no grouping), assert all groups
        //assertFalse ismember on all groups

        //add user to group

        //call .\groups::get_groups_and_members (no grouping), assert all groups
        //assertTrue ismember on 1 groups
        $this->assertTrue(false);
    }

    public function test_all_withgrouping() {
        $this->resetAfterTest();
        //create course
        //create users
        //create enrolments
        //create groups

        //call .\groups::get_groups_and_members (with grouping), assert empty

        //add group to grouping

        //call .\groups::get_groups_and_members (with grouping), assert 1 group
        //assertFalse ismember

        //add user to group

        //call .\groups::get_groups_and_members (no grouping), assert 1 group
        //assertTrue ismember

        $this->assertTrue(false);
    }
}