<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Account */

$this->title = 'Счет № '.$model->account_number;
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Удалить счет?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'account_user_id',
                'value'=>function($model){
                    return $model->owner->username;
                }
            ],
            'account_number',
            'account_description:ntext',
            [
                'attribute' => 'balance_date',
                'value' => function($model){
                    return Yii::$app->formatter->asDate($model->balance_date,'long');
                }
            ],
            'balance_summ',
            [
                'attribute'=> 'account_status',
                'value' => function($model){
                    switch ($model->account_status){
                        case 1:
                            return 'Действующий';
                        case 0:
                            return 'Заблокированный';
                    }
                }
            ],
        ],
    ]) ?>

</div>
