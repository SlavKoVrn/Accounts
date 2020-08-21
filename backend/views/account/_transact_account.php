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

    <?= $form->field($model, 'user_to_whom')->textInput([
        'maxlength' => true,
        'readonly'=>true,
    ])->label('Кому перевод');    ?>

    <?= $form->field($model, 'account_to_whom')->dropDownList($accounts)->label('На счет');    ?>

    <?= $form->field($model, 'transaction_summ')->textInput([
        'maxlength' => true,
    ])->label('Сумма перевода') ?>

    <?= $form->field($model, 'transaction_description')->textarea([
        'maxlength' => true,
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Перевод', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php Pjax::end(); ?>

</div>
