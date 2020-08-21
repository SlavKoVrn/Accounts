<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Account */

$this->title = 'Перевод со счета № : ' . $model->account_number;
$this->params['breadcrumbs'][] = ['label' => 'Счета', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->account_number, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Перевод';
?>
<div class="account-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (isset($accounts)) : ?>
        <?= $this->render('_transact_account', [
            'model' => $model,
            'accounts'=>$accounts,
        ]) ?>
    <?php else : ?>
        <?= $this->render('_transact', [
            'model' => $model,
        ]) ?>
    <?php endif; ?>

</div>
