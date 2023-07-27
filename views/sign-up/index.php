<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Sign Up';
$this->params['breadcrumbs'][] = $this->title;  
/** @var yii\web\View $this */
?>


<h1><?= Html::encode($this->title) ?></h1>
<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput(); ?>

    <div class="form-group">
        <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>

