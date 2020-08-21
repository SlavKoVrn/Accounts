<?php
use common\models\TransactionSearch;

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\data\ActiveDataProvider;


/* @var $this yii\web\View */
/* @var $searchModel common\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Счета';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить счет', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        //'pjax'=>true,
        //'pjaxSettings' => ['options' => ['id' => 'pjax-accounts']],
        'columns' => [
            //'id',
            [
                'header' => '<font size="2">Переводы</font>',
                'class' =>\kartik\grid\ExpandRowColumn::class,
                'allowBatchToggle' => false,
                'expandOneOnly' => true,
                'value'=>function ($model, $key, $index,$column){
                    return GridView::ROW_COLLAPSED;
                },
                'detail'=>function ($model, $key, $index,$column){
                    $searchModel = new TransactionSearch();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model->id);
                    //$dataProvider->pagination->pageSize=2;

                    return Yii::$app->controller->renderPartial('transactions', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                        'account_id' => $model->id,
                    ]);
                },
            ],
            //'id',
            //'owner.username',
            [
                'attribute'=>'account_number',
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
            ],
            [
                'attribute' => 'balance_date',
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->balance_date);
                }
            ],
            [
                'attribute'=>'balance_summ',
                'contentOptions' => [
                    'class' => 'text-right',
                    'style' => 'width:10%; white-space: normal;',
                ],
            ],
            'account_description:ntext',
            //'status',
            [
                'class' => \yii\grid\ActionColumn::class,
                'template' => '{transact} {update} {view} {delete}',
                'buttons' => [
                    'transact' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-arrow-right" style="cursor:pointer"></span>',
                            '/admin/account/'.$model['id'].'/transact',
                            [
                                'title' => 'Перевод',
                                'data-pjax' => 0,
                                //'target' => '_blank',
                            ]
                        );
                    },
                ],
            ],
        ],
    ]); ?>

</div>
