<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php Pjax::begin([
        'id' => 'pjax-transact',
        'timeout' => 0,
    ]); ?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_number')->textInput([
        'maxlength' => true,
        'readonly'=>true,
    ]) ?>

    <?= $form->field($model, 'balance_summ')->textInput([
        'maxlength' => true,
        'readonly' => true,
    ]) ?>

    <?= $form->field($model, 'user_to_whom')->widget(Select2::class, [
        'options' => ['multiple'=>false, 'placeholder' => 'кому перевод...'],
        'pluginOptions' => [
            'allowClear' => true,
            'minimumInputLength' => 3,
            'language' => [
                'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
            ],
            'ajax' => [
                'url' => '/admin/account/users',
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }')
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateResult' => new JsExpression('function(city) { return city.text; }'),
            'templateSelection' => new JsExpression('function (city) { return city.text; }'),
        ],
    ])->label('Кому перевод');    ?>

    <div class="form-group">
        <?= Html::submitButton('Перевод >>>', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end(); ?>

</div>
