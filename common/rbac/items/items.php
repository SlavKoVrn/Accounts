<?php
return [
    'manageAccount' => [
        'type' => 2,
        'ruleName' => 'manageAccountRule',
    ],
    'manageTransaction' => [
        'type' => 2,
        'ruleName' => 'manageTransactionRule',
    ],
    'user' => [
        'type' => 1,
        'children' => [
            'manageAccount',
            'manageTransaction',
        ],
    ],
];
