<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "account".
 *
 * @property int $id Счет
 * @property int|null $account_user_id Владелец
 * @property string|null $account_number Номер счета
 * @property string|null $balance_date Баланс на дату
 * @property int|null $balance_summ Сумма баланса
 * @property string|null $account_description Описание
 * @property int $account_status
 * @property int|null $created_at Дата создания
 * @property int|null $updated_at Дата изменения
 * @property int|null $deleted_at Дата удаления
 */
class Account extends \yii\db\ActiveRecord
{
    public $user_to_whom;
    public $account_to_whom;
    public $transaction_summ = 0;
    public $transaction_description = '';

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

    const SCENARIO_TRANSACT = 'transaction';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_user_id', 'balance_summ','transaction_summ', 'account_status', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['balance_date','user_to_whom','account_to_whom'], 'safe'],
            [['account_description','transaction_description'], 'string'],
            [['account_number'], 'string', 'max' => 50],
            [['account_to_whom','transaction_summ','transaction_description'],'required','on'=>self::SCENARIO_TRANSACT],
            ['transaction_summ', 'compare', 'compareAttribute' => 'balance_summ', 'operator' => '<=','on'=>self::SCENARIO_TRANSACT],
            ['transaction_summ', 'compare', 'compareValue' => 0, 'operator' => '>','on'=>self::SCENARIO_TRANSACT],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Счет',
            'account_user_id' => 'Владелец',
            'account_number' => 'Номер счета',
            'balance_date' => 'Остаток на дату',
            'balance_summ' => 'Сумма остатка',
            'account_description' => 'Назначение счета',
            'account_status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
            'deleted_at' => 'Дата удаления',

            'user_to_whom' => 'Перевод кому',
            'account_to_whom' => 'Перевод на счет',
            'transaction_summ' => 'Сумма перевода',
            'transaction_description' => 'Назначение платежа',
        ];
    }

    public function getBalanceSummAttribute() {
        return $this->balance_summ;
    }

    public function getOwner()
    {
        return $this->hasOne(User::class,['id'=>'account_user_id']);
    }

    public function doTransaction($account_from_id,$account_to_id,$summ,$description)
    {
        $transaction = new Transaction();
        $transaction->transaction_date = (new \yii\db\Expression('NOW()'));
        $transaction->transaction_number = Yii::$app->security->generateRandomString(12);
        $transaction->transaction_user_id = Yii::$app->user->id;
        $transaction->debet_account_id = $account_to_id;
        $transaction->credit_account_id = $account_from_id;
        $transaction->transaction_summ = $summ;
        $transaction->transaction_description = $description;
        $transaction->save();

        $account_from = Account::findOne($account_from_id);
        $account_from->balance_summ -= $summ;
        $account_from->balance_date = (new \yii\db\Expression('NOW()'));
        $account_from->save();

        $account_to = Account::findOne($account_to_id);
        $account_to->balance_summ += $summ;
        $account_to->balance_date = (new \yii\db\Expression('NOW()'));
        $account_to->save();

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
