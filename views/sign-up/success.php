<?php

use yii\helpers\Html;

$this->title = 'Sign Up Success';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<p>Congratulations! You have successfully signed up.</p>

<div class="form-group">
    <?= Html::a('Proceed to Login', ['login/index'], ['class' => 'btn btn-primary']) ?>
</div>