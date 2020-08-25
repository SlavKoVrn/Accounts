<?php

/* @var $this yii\web\View */

$this->title = 'Счета';

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
$js=<<<JS
$(function(){
    $('#get-token-form').submit(function(){
        return false;
    });
    $('#add-account').submit(function(){
        return false;
    });
    $('#get-token').on('click',function(){
        if ($('#loginform-username option:selected').val()==''){
            return;
        }
        if ($('#loginform-password').val()==''){
            return;
        }
        $.ajax({
            url: '/api/auth',
            method: 'POST',
            dataType: 'json',
            headers: {
                'Content-Type':'application/json; charset=utf-8'
            },
            data: JSON.stringify({
                username:$('#select2-loginform-username-container').text().substring(1),
                password:$('#loginform-password').val()
            }),
            success: function(data){
                $('#loginform-token').val(data.token);
                $('#s-account_user_id').val($('#loginform-username option:selected').val());
            },
            error: function(data){
                alert('неправильный логин пароль');
            }
        });
    });
    $('#create-account').on('click',function(){
        if ($('#loginform-token').val()==''){
            alert('Получите токен');
            return false;
        }
        if ($('#s-account_number').val()==''){
            return;
        }
        if ($('#s-balance_summ').val()==''){
            return;
        }
        if ($('#s-account_description').val()==''){
            return;
        }
        $.ajax({
            url: '/api/account',
            method: 'POST',
            dataType: 'json',
            headers: {
                'Content-Type':'application/json; charset=utf-8',
                'Authorization':'Bearer '+$('#loginform-token').val()
            },
            data: JSON.stringify({
                account_user_id:$('#s-account_user_id').val(),
                account_number:$('#s-account_number').val(),
                balance_summ:$('#s-balance_summ').val(),
                account_description:$('#s-account_description').val(),
            }),
            success: function(data){
                $('#s-account_number').attr('readonly','readonly');
                $('#s-balance_summ').attr('readonly','readonly');
                $('#s-account_description').attr('readonly','readonly');
                alert('счет не добавлен');
            },
            error: function(data){
                alert('счет не добавлен');
            }
        });
    });
})
JS;
$this->registerJs($js);
?>
<div class="site-index">

    <h1>создание нового счёта</h1>

    <?php $user_form = ActiveForm::begin(['id'=>'get-token-form']); ?>

    <?= $user_form->field($user, 'username')->widget(Select2::class, [
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
    ])->label('Кому добавить счет') ?>

    <?= $user_form->field($user, 'password')->passwordInput() ?>

    <?= $user_form->field($user, 'token')->textInput(['readonly'=>true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Получить токен', [
            'id' => 'get-token',
            'class' => 'btn btn-primary',
            'name' => 'get-token',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php $account_form = ActiveForm::begin(['id'=>'add-account']); ?>

    <?= $account_form->field($account, 'account_user_id')->hiddenInput()->label(false) ?>

    <?= $account_form->field($account, 'account_number') ?>

    <?= $account_form->field($account, 'balance_summ') ?>

    <?= $account_form->field($account, 'account_description')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить счет', [
            'id' => 'create-account',
            'class' => 'btn btn-primary',
            'name' => 'create-account',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
