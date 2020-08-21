<?php

namespace backend\controllers;

use common\models\Account;
use common\models\AccountSearch;
use common\models\User;
use common\rbac\Rbac;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\web\ForbiddenHttpException;

/**
 * AccountController implements the CRUD actions for Account model.
 */
class AccountController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Account models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AccountSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTransact($id)
    {
        $account = Account::findOne($id);
        if (!Yii::$app->user->can(Rbac::MANAGE_ACCOUNT, ['account' => $account])) {
            Yii::$app->session->setFlash('error', 'Работать можно только со своими счетами');
            throw  new ForbiddenHttpException('Forbidden.');
        }
        $account->scenario = Account::SCENARIO_TRANSACT;

        if ($account->balance_summ<=0){
            Yii::$app->session->setFlash('error', 'На счете недостаточно средств');
            return $this->redirect(['index']);
        }
        $post = Yii::$app->request->post();

        if (isset($post['Account']['transaction_summ'])){
            $transaction=Yii::$app->db->beginTransaction();
            try{
                $account->doTransaction(
                    $account->id,
                    $post['Account']['account_to_whom'],
                    $post['Account']['transaction_summ'],
                    $post['Account']['transaction_description']
                );
                $transaction->commit();
                Yii::$app->session->setFlash('success','Перевод прошел');
            }catch (\RuntimeException $e){
                Yii::error($e);
                $transaction->rollBack();
                Yii::$app->session->setFlash('error','Перевод не прошел');
            }
            return $this->redirect(['index']);
        }

        if (isset($post['Account']['user_to_whom'])){
            $accounts = ArrayHelper::map(Account::findAll(['account_user_id'=>$post['Account']['user_to_whom']]),'id','account_number');
            unset($accounts[$account->id]);
            $account->user_to_whom = User::findOne($post['Account']['user_to_whom'])->username;
            return $this->render('transact', [
                'model' => $account,
                'accounts' => $accounts,
            ]);
        }

        return $this->render('transact', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUsers($q = null, $id = null) {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        if (!is_null($q)) {
            $query = new Query;
            $query->select('id, username AS text')
                ->from(User::tableName())
                ->where(['like', 'username', $q])
                //->andWhere(['!=','id',Yii::$app->user->id])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        elseif ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => User::findOne($id)->username];
        }
        return $out;
    }

    /**
     * Displays a single Account model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $account = Account::findOne($id);
        if (!Yii::$app->user->can(Rbac::MANAGE_ACCOUNT, ['account' => $account])) {
            Yii::$app->session->setFlash('error', 'Работать можно только со своими счетами');
            throw  new ForbiddenHttpException('Forbidden.');
        }
        return $this->render('view', [
            'model' => $account,
        ]);
    }

    /**
     * Creates a new Account model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Account();
        $model->account_user_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Account model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Account::findOne($id);
        if (!Yii::$app->user->can(Rbac::MANAGE_ACCOUNT, ['account' => $model])) {
            Yii::$app->session->setFlash('error', 'Работать можно только со своими счетами');
            throw  new ForbiddenHttpException('Forbidden.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Account model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = Account::findOne($id);
        if (!Yii::$app->user->can(Rbac::MANAGE_ACCOUNT, ['account' => $model])) {
            Yii::$app->session->setFlash('error', 'Работать можно только со своими счетами');
            throw  new ForbiddenHttpException('Forbidden.');
        }

        $model = $this->findModel($id);
        $model->deleted_at = time();
        $model->save();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Account model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Account the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Account::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
