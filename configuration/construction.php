<?php

return [
    'Cacher' => [
        'class' => 'arhone\caching\CacherFileSystemAdapter',
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