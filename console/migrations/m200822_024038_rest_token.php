<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m200822_024038_rest_token
 */
class m200822_024038_rest_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'rest_token', $this->string());
        $this->addColumn('{{%user}}', 'rest_token_expired_at', $this->dateTime());
        $users = User::find()->all();
        foreach ($users as $user){
            $user->rest_token = \Yii::$app->security->generateRandomString();
            $user->rest_token_expired_at = new \yii\db\Expression('NOW()+INTERVAL 1 day');
            $user->save();
        }
        $this->alterColumn('{{%user}}', 'rest_token', $this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'rest_token');
        $this->dropColumn('{{%user}}', 'rest_token_expired_at');
    }

}
