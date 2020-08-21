<?php
return [
    [
        'id'=> 1,
        'username'=>'user1',
        'auth_key'=> Yii::$app->security->generateRandomString(),
        'password_hash'=> Yii::$app->security->generatePasswordHash('123'),
        'email'=>'user1@mail.com',
        'status'=>10,
        'created_at'=>time(),
        'updated_at'=>time(),
    ],
    [
        'id'=> 2,
        'username'=>'user2',
        'auth_key'=> Yii::$app->security->generateRandomString(),
        'password_hash'=> Yii::$app->security->generatePasswordHash('123'),
        'email'=>'user2@mail.com',
        'status'=>10,
        'created_at'=>time(),
        'updated_at'=>time(),
    ],
    [
        'id'=> 3,
        'username' => 'erau',
        'auth_key' => 'tUu1qHcde0diwUol3xeI-18MuHkkprQI',
        // password_0
        'password_hash' => '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne',
        'password_reset_token' => 'RkD_Jw0_8HEedzLk7MM-ZKEFfYR7VbMr_1392559490',
        'created_at' => '1392559490',
        'updated_at' => '1392559490',
        'email' => 'sfriesen@jenkins.info',
    ],
];
