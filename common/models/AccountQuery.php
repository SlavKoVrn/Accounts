<?php

namespace common\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @see Account
 */
class AccountQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        return $this->andWhere(['account_status'=>1]);
    }

    public function notDeleted()
    {
        return $this->andWhere(['deleted_at'=>0]);
    }

    public function owner()
    {
        return $this->andWhere(['account_user_id'=>Yii::$app->user->id]);
    }

    /**
     * {@inheritdoc}
     * @return Account[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Account|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
