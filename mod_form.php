<?php
if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}

require_once ($CFG->dirroot.'/course/moodleform_mod.php');

class mod_groupmembers_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $COURSE, $DB;

        $mform    =& $this->_form;

//-------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('groupmembersname', 'groupmembers'), array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        $this->standard_intro_elements(get_string('description', 'groupmembers'));

//-------------------------------------------------------------------------------
        $mform->addElement('header', 'general', get_string('modulename', 'groupmembers'));

        // Groupings selector - used to select grouping for groups in activity.
        $options = array();
        if ($groupings = $DB->get_records('groupings', array('courseid'=>$COURSE->id))) {
            foreach ($groupings as $grouping) {
                $options[$grouping->id] = format_string($grouping->name);
            }
        }
        core_collator::asort($options);
        $options = array(0 => get_string('none')) + $options;
        $mform->addElement('select', 'listgroupingid', get_string('listgrouping', 'groupmembers'), $options);
        $mform->addRule('listgroupingid', null, 'required', null, 'client');

        // Group visibility selector - used to decide which groups to show in listing
        $options = array(
            GROUPMEMBERS_SHOWGROUPS_ALL=> get_string('showgroups:all', 'groupmembers'),
            GROUPMEMBERS_SHOWGROUPS_OWN => get_string('showgroups:own', 'groupmembers'),
        );
        $mform->addElement('select', 'showgroups', get_string('showgroups', 'groupmembers'), $options);
        $mform->addRule('showgroups', null, 'required', null, 'client');

        // E-Mail visibility selector - used to decide whether e-mail adresses should be shown to other users.
        $options = array(
            GROUPMEMBERS_SHOWEMAIL_NO => get_string('showemail:no', 'groupmembers'),
            GROUPMEMBERS_SHOWEMAIL_OWNGROUP => get_string('showemail:owngroup', 'groupmembers'),
            GROUPMEMBERS_SHOWEMAIL_ALLGROUPS => get_string('showemail:allgroups', 'groupmembers'),
        );
        $mform->addElement('select', 'showemail', get_string('showemail', 'groupmembers'), $options);
        $mform->addRule('showemail', null, 'required', null, 'client');

//-------------------------------------------------------------------------------
        $this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
        $this->add_action_buttons();
    }
}

