<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'View Wallet';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1 style="margin-bottom: 50px;"><?= Html::encode($this->title) ?></h1>

<div class="user-wallet" style="padding: 30px;">
    <h2>Username: <?= Yii::$app->user->identity->username ?></h2>

    <?php if (!$editing) : ?>
        <h3 style="margin-bottom: 30px;">Wallet Amount: $<?= number_format($wallet->amount, 2) ?></h3>
        <?= Html::a('Edit Wallet', ['edit-wallet', 'id' => $wallet->id], ['class' => 'btn btn-primary']) ?>
    <?php else : ?>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($wallet, 'amount')->textInput(['type' => 'number', 'step' => '0.01', 'min' => $wallet->amount, 'style' => 'width: 250px'])->label('New Amount') ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['view-wallet'], ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    <?php endif; ?>

    <div style="margin-top: 30px;">
        <?= Html::a('Go to Admin Panel', ['admin-wallet'], ['class' => 'btn btn-primary']) ?>
    </div>
</div>