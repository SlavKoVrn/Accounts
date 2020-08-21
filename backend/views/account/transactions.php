<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel common\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="transaction-index">

    <?php Pjax::begin([
        'id' => 'pjax-transactions',
        'timeout' => 0,
    ]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pjax'=>true,
        'pjaxSettings' => ['options' => [
            'id' => 'pjax-transactions',
            'timeout' => 0,
        ]],
        'columns' => [
            //'debet_account_id',
            //'credit_account_id',
            [
                'attribute'=>'transaction_number',
                'contentOptions' => ['style' => 'width:10%; white-space: normal;'],
            ],
            [
                'attribute'=>'transaction_date',
                'value'=> function($model) use ($account_id){
                    return Yii::$app->formatter->asDate($model->transaction_date);
                }
            ],
            [
                'header'=>'Приход',
                'attribute'=>'debet_account_id',
                'filter'=>false,
                'value'=> function($model) use ($account_id){
                    if ($account_id==$model->debet_account_id)
                        return $model->transaction_summ;
                    else
                        return '';
                },
                'contentOptions' => function($model) use ($account_id){
                    if ($account_id==$model->debet_account_id)
                        return [
                            'class' => 'text-right',
                            'style'=>'background-color:#b0ffb0',
                        ];
                },
            ],
            [
                'header'=>'Расход',
                'attribute'=>'credit_account_id',
                'filter'=>false,
                'value'=> function($model) use ($account_id){
                    if ($account_id==$model->credit_account_id)
                        return $model->transaction_summ;
                    else
                        return '';
                },
                'contentOptions' => function($model) use ($account_id){
                    if ($account_id==$model->credit_account_id)
                        return [
                            'class' => 'text-right',
                            'style'=>'background-color:#ff9389',
                        ];
                },
            ],
            [
                'header'=>'от кого / кому',
                'filter'=>false,
                'value'=> function($model) use ($account_id){
                    if ($account_id==$model->debet_account_id)
                        return $model->creditAccount->owner->username;
                    else
                        return $model->debetAccount->owner->username;
                    return '';
                }
            ],
            [
                'attribute'=>'transaction_description',
                'contentOptions' => ['style' => 'width:500px; white-space: normal;'],
            ]

        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
