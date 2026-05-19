<?php
// Controller action scenarios for integration testing.
// These scenarios are intended to simulate safe request flows without destructive writes.
return [
    'Login.php' => [
        [
            'name' => 'login-post',
            'method' => 'POST',
            'post' => [
                'email' => 'test@example.com',
                'password' => 'wrongpassword',
            ],
            'session' => ['user_role' => 'guest'],
            'server' => ['HTTP_HOST' => 'localhost'],
        ],
    ],
    'Parentreportviewcontroller.php' => [
        [
            'name' => 'parent-report-post',
            'method' => 'POST',
            'post' => [
                'child_id' => '1',
            ],
            'session' => ['user_role' => 'parent', 'user_id' => '1'],
            'server' => ['HTTP_HOST' => 'localhost'],
        ],
    ],
    'Medicalallergyalertcontroller.php' => [
        [
            'name' => 'medical-allergy-get',
            'method' => 'GET',
            'session' => ['user_role' => 'teacher', 'user_id' => '1'],
            'server' => ['HTTP_HOST' => 'localhost'],
        ],
    ],
    'Eventgallerycontroller.php' => [
        [
            'name' => 'event-gallery-get',
            'method' => 'GET',
            'session' => ['user_role' => 'admin', 'user_id' => '1'],
            'server' => ['HTTP_HOST' => 'localhost'],
        ],
    ],
    'Eventremindercontroller.php' => [
        [
            'name' => 'event-reminder-cli',
            'method' => 'CLI',
            'server' => [],
        ],
    ],
    'DueDateAlertController.php' => [
        [
            'name' => 'due-date-alert-cli',
            'method' => 'CLI',
            'server' => [],
        ],
    ],
    'Eventcreationcontroller.php' => [
        [
            'name' => 'event-create-get',
            'method' => 'GET',
            'session' => ['user_role' => 'admin', 'user_id' => '1'],
            'server' => ['HTTP_HOST' => 'localhost'],
        ],
    ],
];
