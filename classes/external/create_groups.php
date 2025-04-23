<?php
namespace local_groupmanager\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->dirroot/group/lib.php");

use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use context_course;
use invalid_parameter_exception;

class create_groups extends \core_external\external_api {

    /**
     * Define the input parameters for the web service.
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'groups' => new external_multiple_structure(
                new external_single_structure([
                    'courseid' => new external_value(PARAM_INT, 'ID of the course'),
                    'name' => new external_value(PARAM_TEXT, 'Group name'),
                    'description' => new external_value(PARAM_RAW, 'Group description'),
                    'enrolmentkey' => new external_value(PARAM_RAW, 'Enrolment key'),
                ])
            )
        ]);
    }

    /**
     * The core function that runs when the web service is called.
     */
    public static function execute($groups) {
        global $DB;

        // Validate the input parameters
        $params = self::validate_parameters(self::execute_parameters(), ['groups' => $groups]);

        $transaction = $DB->start_delegated_transaction(); // Begin DB transaction

        $createdgroups = [];

        foreach ($params['groups'] as $groupdata) {
            $group = (object)$groupdata;

            if (trim($group->name) === '') {
                throw new invalid_parameter_exception('Invalid group name');
            }

            if ($DB->record_exists('groups', ['courseid' => $group->courseid, 'name' => $group->name])) {
                throw new invalid_parameter_exception('Group with the same name already exists in the course');
            }

            // Security: validate context and permissions
            $context = context_course::instance($group->courseid);
            self::validate_context($context);
            require_capability('moodle/course:managegroups', $context);

            // Create the group
            $group->id = groups_create_group($group);
            $createdgroups[] = (array)$group;
        }

        $transaction->allow_commit(); // Commit DB changes
        return $createdgroups;
    }

    /**
     * Define the return structure for the web service.
     */
    public static function execute_returns() {
        return new external_multiple_structure(
            new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Group ID'),
                'courseid' => new external_value(PARAM_INT, 'Course ID'),
                'name' => new external_value(PARAM_TEXT, 'Group name'),
                'description' => new external_value(PARAM_RAW, 'Group description'),
                'enrolmentkey' => new external_value(PARAM_RAW, 'Enrolment key'),
            ])
        );
    }
}
