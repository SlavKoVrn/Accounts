<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'gridview' => [
            'class' => 'kartik\grid\Module',

        ],
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'itemFile' => '@common/rbac/items/items.php',
            'assignmentFile' => '@common/rbac/items/assignments.php',
            'ruleFile' => '@common/rbac/items/rules.php',
            'defaultRoles' => ['user'],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'formatter'    => [
            'class'                  => 'yii\i18n\Formatter',
            'dateFormat'             => 'php:d.m.Y',
            'datetimeFormat'         => 'php:d.m.Y, H:i',
            'timeFormat'             => 'php:H:i:s',
            'defaultTimeZone'        => 'Europe/Moscow',
            'locale'                 => 'ru-RU',
            'thousandSeparator'      => ' ',
            'decimalSeparator'       => '.',
        ],
    ],
];
