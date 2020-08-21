<?php

namespace common\rbac\rules;

use yii\base\InvalidCallException;
use yii\rbac\Rule;

class ManageAccountRule extends Rule
{
    public $name = 'manageAccountRule';

    public function execute($userId, $item, $params)
    {
        if (empty($params['account'])) {
            throw new InvalidCallException('Какой счет ?');
        }

        return $params['account']->account_user_id == $userId;
    }
}
