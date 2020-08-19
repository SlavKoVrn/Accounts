<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "account".
 *
 * @property int $id Счет
 * @property int|null $user_id Владелец
 * @property string|null $number Номер счета
 * @property string|null $balance_date Баланс на дату
 * @property int|null $balance_summ Сумма баланса
 * @property string|null $description Описание
 * @property int $status
 * @property int|null $created_at Дата создания
 * @property int|null $updated_at Дата изменения
 * @property int|null $deleted_at Дата удаления
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
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
            [['user_id', 'balance_summ', 'status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['balance_date'], 'safe'],
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
            'id' => 'Счет',
            'user_id' => 'Владелец',
            'number' => 'Номер счета',
            'balance_date' => 'Баланс на дату',
            'balance_summ' => 'Сумма баланса',
            'description' => 'Описание',
            'status' => 'Status',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'deleted_at' => 'Дата удаления',
        ];
    }

    /**
     * {@inheritdoc}
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountQuery(get_called_class());
    }
}
