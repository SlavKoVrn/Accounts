<?php

namespace common\rbac\rules;

use yii\base\InvalidCallException;
use yii\rbac\Rule;

class ManageTransactionRule extends Rule
{
    public $name = 'manageTransactionRule';

    public function execute($userId, $item, $params)
    {
        if (empty($params['transaction'])) {
            throw new InvalidCallException('Какой перевод ?');
        }

        return $params['transaction']->transaction_user_id == $userId;
    }
}
