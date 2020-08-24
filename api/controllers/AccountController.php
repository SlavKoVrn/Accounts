<?php

namespace api\controllers;

use common\models\AccountSearch;
use common\models\Account;
use common\rbac\Rbac;

use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\HttpException;

class AccountController extends ActiveController
{
    public $modelClass = 'common\models\Account';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::class,
            HttpBearerAuth::class,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function actionCreate()
    {
        $model = new Account();
        $model->account_user_id = Yii::$app->user->id;

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->save()) {
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(['view', 'id' => $id], true));
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }

        return $model;
    }

    public function prepareDataProvider()
    {
        $searchModel = new AccountSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($model) {
            if (!Yii::$app->user->can(Rbac::MANAGE_ACCOUNT, ['account' => $model])) {
                throw  new ForbiddenHttpException('Forbidden.');
            }
        }
    }

    public function actionTransact($from,$to){

        $account_from = Account::findOne(['id'=>$from]);
        if (!$account_from){
            throw  new HttpException(422,'с какого счета ?');
        };

        $account_to = Account::findOne(['id'=>$to]);
        if (!$account_to){
            throw  new HttpException(422,'на какой счет ?');
        };

        if ($account_from->id == $account_to->id){
            throw  new HttpException(422,'тот же самый счет');
        };

        $post = Yii::$app->request->post();
        if ($account_from->balance_summ < $post['transaction_summ']){
            throw  new HttpException(422,'недостаточно средств');
        };

        $transaction=Yii::$app->db->beginTransaction();
        try{
            $account_from->doTransaction(
                $from,
                $to,
                $post['transaction_summ'],
                $post['transaction_description']
            );
            $transaction->commit();
        }catch (\RuntimeException $e){
            Yii::error($e);
            $transaction->rollBack();
            throw  new HttpException(422,'платеж не прошел');
        }

        return [
            'account_from'=>Account::findOne(['id'=>$from]),
            'account_to'=>Account::findOne(['id'=>$to]),
        ];
    }

}
