<?php

namespace mod_groupmembers\event;
defined('MOODLE_INTERNAL') || die();

/**
 * The mod_groupmembers course module viewed event class.
 *
 * @package    mod_groupmembers
 * @copyright  2016 Dennis Riehle
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class course_module_viewed extends \core\event\course_module_viewed {

    /**
     * Init method.
     */
    protected function init() {
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
        $this->data['objecttable'] = 'groupmembers';
    }

    public static function get_objectid_mapping() {
        return array('db' => 'groupmembers', 'restore' => 'groupmembers');
    }
}
