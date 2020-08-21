<?php

use yii\db\Migration;

/**
 * Class m200819_033047_add_users_accounts_transactions
 */
class m200819_033047_add_users_accounts_transactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->insert('{{%user}}',[
            'id'=> 1,
            'username'=>'user1',
            'auth_key'=> Yii::$app->security->generateRandomString(),
            'password_hash'=> Yii::$app->security->generatePasswordHash('123'),
            'email'=>'user1@mail.com',
            'status'=>10,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);

        $this->insert('{{%user}}',[
            'id'=> 2,
            'username'=>'user2',
            'auth_key'=> Yii::$app->security->generateRandomString(),
            'password_hash'=> Yii::$app->security->generatePasswordHash('123'),
            'email'=>'user2@mail.com',
            'status'=>10,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);

        $this->insert('{{%user}}',[
            'id'=> 3,
            'username'=>'erau',
            'auth_key'=> 'tUu1qHcde0diwUol3xeI-18MuHkkprQI',
            // password_0
            'password_hash'=> '$2y$13$nJ1WDlBaGcbCdbNC5.5l4.sgy.OMEKCqtDQOdQ2OWpgiKRWYyzzne',
            'email'=>'sfriesen@jenkins.info',
            'status'=>10,
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);

        $this->createTable('{{%account}}', [

            'id' =>             $this->primaryKey()->comment('Счет'),
            'account_user_id' =>        $this->integer()->comment('Владелец'),
            'account_number' =>         $this->string(50)->comment('Номер счета'),
            'balance_date' =>   $this->dateTime()->comment('Баланс на дату')->defaultValue(new \yii\db\Expression('NOW()')),
            'balance_summ' =>   $this->integer()->comment('Сумма баланса')->defaultValue(0),
            'account_description' =>    $this->text()->comment('Описание'),
            'account_status' =>         $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' =>     $this->integer()->comment('Дата создания'),
            'updated_at' =>     $this->integer()->comment('Дата изменения'),
            'deleted_at' =>     $this->integer()->comment('Дата удаления')->defaultValue(0),

        ], $tableOptions);

        $this->createIndex('IDX_account_user_id', '{{%account}}', ['account_user_id']);
        $this->createIndex('IDX_account_balance_date', '{{%account}}', ['balance_date']);

        $this->insert('{{%account}}', [
            'id' =>             1,
            'account_user_id' =>        1,
            'account_number' =>         'schet-1',
            'account_description' =>    'счет 1 первого пользователя',
            'balance_date' =>  (new \yii\db\Expression('NOW()-INTERVAL 3 day')),
        ]);

        $this->insert('{{%account}}', [
            'id' =>             2,
            'account_user_id' =>        1,
            'account_number' =>         'schet-2',
            'account_description' =>    'счет 2 первого пользователя',
            'balance_date' =>  (new \yii\db\Expression('NOW()-INTERVAL 4 day')),
        ]);

        $this->insert('{{%account}}', [
            'id' =>             3,
            'account_user_id' =>        2,
            'account_number' =>         'schet-3',
            'account_description' =>    'счет 1 второго пользователя',
            'balance_date' =>  (new \yii\db\Expression('NOW()-INTERVAL 1 day')),
        ]);

        $this->insert('{{%account}}', [
            'id' =>             4,
            'account_user_id' =>        2,
            'account_number' =>         'schet-4',
            'account_description' =>    'счет 1 второго пользователя',
            'balance_date' =>  (new \yii\db\Expression('NOW()-INTERVAL 2 day')),
        ]);

        $this->createTable('{{%transaction}}', [

            'id' =>                 $this->primaryKey()->comment('Переводы по счетам'),
            'transaction_number' =>             $this->string(50)->comment('Номер перевода'),
            'transaction_date' =>               $this->dateTime()->comment('Дата перевода')->defaultValue(new \yii\db\Expression('NOW()')),
            'transaction_user_id' =>            $this->integer()->comment('Кто перевел'),
            'debet_account_id' =>   $this->integer()->comment('Счет получателя')->defaultValue(0),
            'credit_account_id' =>  $this->integer()->comment('Счет отправителя')->defaultValue(0),
            'transaction_summ' =>               $this->integer()->comment('Сумма перевода')->defaultValue(0),
            'transaction_description' =>        $this->text()->comment('Описание'),
            'created_at' =>         $this->integer()->comment('Дата создания'),
            'updated_at' =>         $this->integer()->comment('Дата изменения'),
            'deleted_at' =>         $this->integer()->comment('Дата удаления')->defaultValue(0),

        ], $tableOptions);

        $this->createIndex('IDX_transaction_user_id', '{{%transaction}}', ['transaction_user_id']);
        $this->createIndex('IDX_transaction_date', '{{%transaction}}', ['transaction_date']);
        $this->createIndex('IDX_transaction_debet', '{{%transaction}}', ['debet_account_id']);
        $this->createIndex('IDX_transaction_credit', '{{%transaction}}', ['credit_account_id']);

        $this->insert('{{%transaction}}', [
            'transaction_number' =>     'transact-1',
            'transaction_user_id' =>    1,
            'debet_account_id' =>       2,
            'credit_account_id' =>      1,
            'transaction_summ' =>       22,
            'transaction_description'=> 'перевод со счета первого пользователя на счет второго',
        ]);

        $this->insert('{{%transaction}}', [
            'transaction_number' =>     'transact-2',
            'transaction_user_id' =>    1,
            'debet_account_id' =>       1,
            'credit_account_id' =>      2,
            'transaction_summ' =>       22,
            'transaction_description'=> 'обратный перевод со счета второго пользователя на счет первого',
        ]);

        $this->insert('{{%transaction}}', [
            'transaction_number' =>     'transact-3',
            'transaction_user_id' =>    2,
            'debet_account_id' =>       1,
            'credit_account_id' =>      2,
            'transaction_summ' =>       33,
            'transaction_description'=> 'перевод со счета второго пользователя на счет первого',
        ]);

        $this->insert('{{%transaction}}', [
            'transaction_number' =>     'transact-4',
            'transaction_user_id' =>    2,
            'debet_account_id' =>       2,
            'credit_account_id' =>      1,
            'transaction_summ' =>       33,
            'transaction_description'=> 'обратный перевод со счета первого пользователя на счет второго',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('IDX_account_user_id', '{{%account}}');
        $this->dropIndex('IDX_account_balance_date', '{{%account}}');

        $this->dropIndex('IDX_transaction_user_id', '{{%transaction}}');
        $this->dropIndex('IDX_transaction_date', '{{%transaction}}');
        $this->dropIndex('IDX_transaction_debet', '{{%transaction}}');
        $this->dropIndex('IDX_transaction_credit', '{{%transaction}}');

        $this->delete('{{%user}}',['id'=> 1]);
        $this->delete('{{%user}}',['id'=> 2]);
        $this->delete('{{%user}}',['id'=> 3]);

        $this->dropTable('{{%account}}');
        $this->dropTable('{{%transaction}}');
    }

}
