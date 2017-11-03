<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
//    'AppPath' => dirname(dirname(__DIR__)),
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
