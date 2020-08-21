<?php

namespace console\controllers;

use common\rbac\Rbac;
use common\rbac\rules\ManageAccountRule;
use common\rbac\rules\ManageTransactionRule;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $rule = new ManageAccountRule();
        $auth->add($rule);

        $manageAccount = $auth->createPermission(Rbac::MANAGE_ACCOUNT);
        $manageAccount->ruleName = $rule->name;
        $auth->add($manageAccount);

        $rule = new ManageTransactionRule();
        $auth->add($rule);

        $manageTransaction = $auth->createPermission(Rbac::MANAGE_TRANSACTION);
        $manageTransaction->ruleName = $rule->name;
        $auth->add($manageTransaction);

        $user = $auth->createRole('user');
        $auth->add($user);
        $auth->addChild($user, $manageAccount);
        $auth->addChild($user, $manageTransaction);
    }
}