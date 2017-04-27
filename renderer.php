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

defined('MOODLE_INTERNAL') || die();
require_once(__DIR__. '/lib.php');

class mod_groupmembers_renderer extends plugin_renderer_base {
    public function render_allgroups(array $groups, $showemail, $context) {
        global $USER, $COURSE, $CFG;
        $data = array(
            'groups' => []
        );
        foreach ($groups as $group) {
            $members = array();
            foreach ($group['members'] as $member) {
                $memberemail = null;
                if ($showemail == GROUPMEMBERS_SHOWEMAIL_ALLGROUPS ||
                    ($showemail == GROUPMEMBERS_SHOWEMAIL_OWNGROUP && $group['ismember'])) {
                    $memberemail = $member->email;
                }
                $membermessage = null;
                if (!empty($CFG->messaging) && $USER->id != $member->id && has_capability('moodle/site:sendmessage', $context)) {
                    $membermessage = new moodle_url('/message/index.php', ['id' => $member->id]);
                }

                $members[] = array(
                    'id' => $member->id,
                    'picture' => $this->output->user_picture($member),
                    'displayname' => fullname($member),
                    'mail' => $memberemail,
                    'profileurl' => new moodle_url('/user/view.php', ['id' => $member->id, 'course' => $COURSE->id]),
                    'messageurl' => $membermessage
                );
            }

            $data['groups'][] = array(
                'id' => $group['group']->id,
                'name' => $group['group']->name,
                'members' => $members,
            );
        }
        return $this->render_from_template('mod_groupmembers/allgroups', $data);
    }
}
