<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use softark\duallistbox\DualListbox;
use app\models\Groups;

/* @var $this yii\web\View */
/* @var $model app\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(['id'=>'Users_FORM','enableAjaxValidation' => true]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?php
    if ($model->isNewRecord){ ?>
    <div class="col-xs-6">
    <?= $form->field($model, 'pwd')->passwordInput() ?>
    </div>
    <div class="col-xs-6">
    <?= $form->field($model, 'pwd2')->passwordInput() ?>
    </div>
    <?php } ?>
    <?= $form->field($model, 'descr')->textarea(['rows' => 4]) ?>

    <?php
    if (!$model->isNewRecord){ ?>
    <?php
      echo $form->field($model, 'registered')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Дата/время'],
        'pickerButton' => ['icon' => 'time'],
        'pluginOptions' => [
          'autoclose' => true,
          'format' => 'dd.mm.yyyy hh:ii'
        ]
      ]);
      echo $form->field($model, 'last_enter')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Дата/время'],
        'pickerButton' => ['icon' => 'time'],
        'pluginOptions' => [
          'autoclose' => true,
          'format' => 'dd.mm.yyyy hh:ii'
        ]
      ]);
    ?>
    <?php } ?>
    <div class="form-group" id="dual_listbox">
        <?php
            $options = [
                'multiple' => true,
                'size' => 10,
            ];
            echo $form->field($model, 'selected_groups')->widget(DualListbox::className(),[
                'items' => Groups::getAllAsArray(),
                'options' => $options,
                'clientOptions' => [
                    'moveOnSelect' => false,
                    'filterTextClear' => 'Все',
                    'infoTextEmpty' => 'Пусто',
                    'infoText' => '',
                    'selectedListLabel' => [],
                ],
            ]);
        ?>
    </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', 
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
          'id' => 'Users_FORM_BUTTON'
        ]) ?>
    </div>
    

    <?php ActiveForm::end(); ?>

</div>
