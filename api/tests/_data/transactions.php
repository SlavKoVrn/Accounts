<?php
return [
    [
        'transaction_number' =>     'transact-1',
        'transaction_user_id' =>    1,
        'debet_account_id' =>       2,
        'credit_account_id' =>      1,
        'transaction_summ' =>       22,
        'transaction_description'=> 'перевод со счета первого пользователя на счет второго',
    ],
    [
        'transaction_number' =>     'transact-2',
        'transaction_user_id' =>    1,
        'debet_account_id' =>       1,
        'credit_account_id' =>      2,
        'transaction_summ' =>       22,
        'transaction_description'=> 'обратный перевод со счета второго пользователя на счет первого',
    ],
    [
        'transaction_number' =>     'transact-3',
        'transaction_user_id' =>    2,
        'debet_account_id' =>       1,
        'credit_account_id' =>      2,
        'transaction_summ' =>       33,
        'transaction_description'=> 'перевод со счета второго пользователя на счет первого',
    ],
    [
        'transaction_number' =>     'transact-4',
        'transaction_user_id' =>    2,
        'debet_account_id' =>       2,
        'credit_account_id' =>      1,
        'transaction_summ' =>       33,
        'transaction_description'=> 'обратный перевод со счета первого пользователя на счет второго',
    ],
];
