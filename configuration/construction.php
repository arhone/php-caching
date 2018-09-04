<?php

return [
    'Cache' => [
        'class' => 'arhone\caching\CacheFileSystemAdapter',
        'construct' => [
            ['array' => [
                'status'    => false,
                'directory' => __DIR__ . '/../../../../cache'
            ]]
        ]
    ]
];