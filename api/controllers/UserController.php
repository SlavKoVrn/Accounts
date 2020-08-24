<?php

namespace api\controllers;

use common\models\User;
use common\models\UserSearch;

use Yii;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = User::class;

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view'],$actions['create'],$actions['update'],$actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $searchModel = new UserSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

}
