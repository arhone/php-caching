<?php

return [
    'Cacher' => [
        'class' => 'arhone\caching\cacher\CacherFileSystemAdapter',
        'construct' => [
            [
                'array' => [
                    'state'     => false,
                    'directory' => __DIR__ . '/../../../../cache'
                ]
            ]
        ]
    ]
];