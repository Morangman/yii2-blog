<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
$this->title="Смена пароля";
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title . " (смена пароля)";
?>
<div class="users-pwd-update">
<h1><?= Html::encode($this->title) ?></h1>
<div class="users-form">

    <?php $form = ActiveForm::begin(['id'=>'Users_FORM','enableAjaxValidation' => true]); ?>

    <?= $form->field($model, 'pwd')->passwordInput() ?>
    <?= $form->field($model, 'pwd2')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', 
        ['class' => 'btn btn-primary',
          'id' => 'Users_CHPWD_BUTTON'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>