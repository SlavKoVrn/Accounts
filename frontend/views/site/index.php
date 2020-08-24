<?php

/* @var $this yii\web\View */

$this->title = 'Счета';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\widgets\Select2;
use yii\web\JsExpression;
?>
<div class="site-index">

    <?php Pjax::begin([
        'id' => 'pjax-transact',
        'timeout' => 0,
    ]); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_to_whom')->widget(Select2::class, [
        'options' => ['multiple'=>false, 'placeholder' => 'кому перевод...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Поиск ...'; }"),
            ],
            'ajax' => [
                'url' => '/api/users',
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {"s[username]":params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(user) { return user.username; }'),
            'templateSelection' => new JsExpression('function (user) { return user.username; }'),
        ],
    ])->label('Кому перевод');    ?>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end(); ?>

</div>
