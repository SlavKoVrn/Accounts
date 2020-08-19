<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id Переводы по счетам
 * @property string|null $number Номер перевода
 * @property string|null $date Дата перевода
 * @property int|null $user_id Кто перевел
 * @property int|null $debet_account_id Счет получателя
 * @property int|null $credit_account_id Счет отправителя
 * @property int|null $summ Сумма перевода
 * @property string|null $description Описание
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
            [['date'], 'safe'],
            [['user_id', 'debet_account_id', 'credit_account_id', 'summ', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['description'], 'string'],
            [['number'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Переводы по счетам',
            'number' => 'Номер перевода',
            'date' => 'Дата перевода',
            'user_id' => 'Кто перевел',
            'debet_account_id' => 'Счет получателя',
            'credit_account_id' => 'Счет отправителя',
            'summ' => 'Сумма перевода',
            'description' => 'Описание',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'deleted_at' => 'Дата удаления',
        ];
    }

    /**
     * {@inheritdoc}
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }
}
