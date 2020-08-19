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

        $this->createTable('{{%account}}', [

            'id' =>             $this->primaryKey()->comment('Счет'),
            'user_id' =>        $this->integer()->comment('Владелец'),
            'number' =>         $this->string(50)->comment('Номер счета'),
            'balance_date' =>   $this->dateTime()->comment('Баланс на дату')->defaultValue(new \yii\db\Expression('NOW()')),
            'balance_summ' =>   $this->integer()->comment('Сумма баланса')->defaultValue(0),
            'description' =>    $this->text()->comment('Описание'),
            'status' =>         $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' =>     $this->integer()->comment('Дата создания'),
            'updated_at' =>     $this->integer()->comment('Дата изменения'),
            'deleted_at' =>     $this->integer()->comment('Дата удаления')->defaultValue(0),

        ], $tableOptions);

        $this->insert('{{%account}}', [
            'id' =>             1,
            'user_id' =>        1,
            'number' =>         '1-1',
            'description' =>    'счет первого пользователя',
        ]);

        $this->insert('{{%account}}', [
            'id' =>             2,
            'user_id' =>        2,
            'number' =>         '2-2',
            'description' =>    'счет второго пользователя',
        ]);

        $this->createTable('{{%transaction}}', [

            'id' =>                 $this->primaryKey()->comment('Переводы по счетам'),
            'number' =>             $this->string(50)->comment('Номер перевода'),
            'date' =>               $this->dateTime()->comment('Дата перевода')->defaultValue(new \yii\db\Expression('NOW()')),
            'user_id' =>            $this->integer()->comment('Кто перевел'),
            'debet_account_id' =>   $this->integer()->comment('Счет получателя')->defaultValue(0),
            'credit_account_id' =>  $this->integer()->comment('Счет отправителя')->defaultValue(0),
            'summ' =>               $this->integer()->comment('Сумма перевода')->defaultValue(0),
            'description' =>        $this->text()->comment('Описание'),
            'created_at' =>         $this->integer()->comment('Дата создания'),
            'updated_at' =>         $this->integer()->comment('Дата изменения'),
            'deleted_at' =>         $this->integer()->comment('Дата удаления')->defaultValue(0),

        ], $tableOptions);

        $this->insert('{{%transaction}}', [
            'number' =>             '11-11',
            'user_id' =>            1,
            'debet_account_id' =>   2,
            'credit_account_id' =>  1,
            'summ' =>               22,
            'description' =>        'перевод со счета первого пользователя на счет второго',
        ]);

        $this->insert('{{%transaction}}', [
            'number' =>             '11-22',
            'user_id' =>            1,
            'debet_account_id' =>   1,
            'credit_account_id' =>  2,
            'summ' =>               22,
            'description' =>        'обратный перевод со счета второго пользователя на счет первого',
        ]);

        $this->insert('{{%transaction}}', [
            'number' =>             '22-11',
            'user_id' =>            2,
            'debet_account_id' =>   1,
            'credit_account_id' =>  2,
            'summ' =>               33,
            'description' =>        'перевод со счета второго пользователя на счет первого',
        ]);

        $this->insert('{{%transaction}}', [
            'number' =>             '22-22',
            'user_id' =>            2,
            'debet_account_id' =>   2,
            'credit_account_id' =>  1,
            'summ' =>               33,
            'description' =>        'обратный перевод со счета первого пользователя на счет второго',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%user}}',['id'=> 1]);
        $this->delete('{{%user}}',['id'=> 2]);
        $this->dropTable('{{%account}}');
        $this->dropTable('{{%transaction}}');
    }

}
