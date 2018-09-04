<?php

return [
    'Cache' => [
        'class' => 'arhone\caching\CacheFileSystemAdapter',
        'construct' => [
            ['array' => [
                'state'     => false,
                'directory' => __DIR__ . '/../../../../cache'
            ]]
        ]
    ]
];