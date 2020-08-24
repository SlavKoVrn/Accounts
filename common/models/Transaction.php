<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id Переводы по счетам
 * @property string|null $transaction_number Номер перевода
 * @property string|null $transaction_date Дата перевода
 * @property int|null $transaction_user_id Кто перевел
 * @property int|null $debet_account_id Счет получателя
 * @property int|null $credit_account_id Счет отправителя
 * @property int|null $transaction_summ Сумма перевода
 * @property string|null $transaction_description Описание
 * @property int|null $created_at Дата создания
 * @property int|null $updated_at Дата изменения
 * @property int|null $deleted_at Дата удаления
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction_date'], 'safe'],
            [['transaction_user_id', 'debet_account_id', 'credit_account_id', 'transaction_summ', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['transaction_description'], 'string'],
            [['transaction_number'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Переводы по счетам',
            'transaction_number' => 'Номер перевода',
            'transaction_date' => 'Дата перевода',
            'transaction_user_id' => 'Кто перевел',
            'debet_account_id' => 'Счет получателя',
            'credit_account_id' => 'Счет отправителя',
            'transaction_summ' => 'Сумма перевода',
            'transaction_description' => 'Назначение платежа',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'deleted_at' => 'Дата удаления',
        ];
    }

    public function getDebetAccount(){
        return $this->hasOne(Account::class,['id'=>'debet_account_id']);
    }

    public function getCreditAccount(){
        return $this->hasOne(Account::class,['id'=>'credit_account_id']);
    }

    /**
     * {@inheritdoc}
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }

    public function fields()
    {
        return [
            'id',
            'transaction_number',
            'transaction_date',
            'transaction_summ',
            'transaction_description',
        ];
    }
}
