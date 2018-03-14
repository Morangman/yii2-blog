<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Groups */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(['id'=>'Groups_FORM','enableAjaxValidation' => true]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'descr')->textarea(['rows' => 4]) ?>

    <?php
    if (!$model->isNewRecord){ ?>
    <?php
      echo $form->field($model, 'created')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Дата/время'],
        'pickerButton' => ['icon' => 'time'],
        'pluginOptions' => [
          'autoclose' => true,
          'format' => 'dd.mm.yyyy hh:ii'
        ]
      ]);
    ?>
    <?php } ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', 
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
          'id' => 'Groups_FORM_BUTTON'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
