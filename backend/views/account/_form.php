<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'account_description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'account_status')->dropDownList([
        1 => 'Действующий',
        0 => 'Заблокированный',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
