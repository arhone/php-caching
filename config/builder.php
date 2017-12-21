<?php

return [
    'Cache' => [
        'class' => 'arhone\cache\CacheFile',
        'construct' => [
            ['array' => [
                'status'    => false,
                'directory' => __DIR__ . '/../../../cache'
            ]]
        ]
    ]
];