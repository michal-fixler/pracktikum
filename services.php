<?php

$functions = [ //
    'local_groupmanager_create_groups' => [
        'classname'   => 'local_groupmanager\external\create_groups',
        'description' => 'Creates new groups.',
        'type'        => 'write',
        'ajax'        => true,
        'services' => [
            MOODLE_OFFICIAL_MOBILE_SERVICE,
    'local_groupmanager_generate_token' => [
    'classname'   => 'local_groupmanager\external\generate_token',
    'description' => 'Generates a token for a given username and password.',
    'type'        => 'read',
    'ajax'        => true,
    'services'    => [MOODLE_OFFICIAL_MOBILE_SERVICE],
],

        ]
    ],
];
