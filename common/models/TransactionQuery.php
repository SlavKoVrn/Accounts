<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Transaction]].
 *
 * @see Transaction
 */
class TransactionQuery extends \yii\db\ActiveQuery
{
    public function account($account_id)
    {
        return $this
            ->andWhere(['debet_account_id'=>$account_id])
            ->orWhere(['credit_account_id'=>$account_id]);
    }

    /**
     * {@inheritdoc}
     * @return Transaction[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Transaction|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
