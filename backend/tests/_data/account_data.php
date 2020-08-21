<?php
return [
    [
        'id' =>             1,
        'account_user_id' =>        1,
        'account_number' =>         'schet-1',
        'account_description' =>    'счет 1 первого пользователя',
        'balance_date' =>  (new \yii\db\Expression('NOW()-INTERVAL 3 day')),
        'balance_summ' => 222,
    ],
    [
        'id' =>             2,
        'account_user_id' =>        1,
        'account_number' =>         'schet-2',
        'account_description' =>    'счет 2 первого пользователя',
        'balance_date' =>  (new \yii\db\Expression('NOW()-INTERVAL 4 day')),
    ],
    [
        'id' =>             3,
        'account_user_id' =>        2,
        'account_number' =>         'schet-3',
        'account_description' =>    'счет 1 второго пользователя',
        'balance_date' =>  (new \yii\db\Expression('NOW()-INTERVAL 1 day')),
    ],
    [
        'id' =>             4,
        'account_user_id' =>        2,
        'account_number' =>         'schet-4',
        'account_description' =>    'счет 1 второго пользователя',
        'balance_date' =>  (new \yii\db\Expression('NOW()-INTERVAL 2 day')),
    ],
];
