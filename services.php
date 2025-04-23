<?php

$functions = [ //
    'local_groupmanager_create_groups' => [
        'classname'   => 'local_groupmanager\external\create_groups',
        'description' => 'Creates new groups.',
        'type'        => 'write',
        'ajax'        => true,
        'services' => [
            MOODLE_OFFICIAL_MOBILE_SERVICE,
        ]
    ],
];
