<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use yii\helpers\ArrayHelper;
use app\models\Users;
use dosamigos\fileupload\FileUpload;

/* @var $this yii\web\View */
/* @var $model app\models\Posts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(['id'=>'Groups_FORM','enableAjaxValidation' => true]); ?>

    <?= $form->field($model, 'topic')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'content')->textarea(['rows' => 4]) ?>

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
      echo $form->field($model, 'modified')->widget(DateTimePicker::classname(), [
        'options' => ['placeholder' => 'Дата/время'],
        'pickerButton' => ['icon' => 'time'],
        'pluginOptions' => [
          'autoclose' => true,
          'format' => 'dd.mm.yyyy hh:ii'
        ]
      ]);
      echo $form->field($model, 'user_id')
        ->dropDownList(
              ArrayHelper::map(Users::find()->orderBy('name ASC')->all(), 'id', 'name'),  
              ['disabled' => ((Yii::$app->user->identity && Yii::$app->user->identity->inOneOfGroups(['root','admins']))? false:"disabled" )],
              ['options' =>
                        [                        
                          $model->user_id => ['selected' => true],
                        ]
              ]
            );
    ?>
    <div class="form-group">
    <?= (((Yii::$app->user->identity === NULL)? 
                            false : Yii::$app->user->identity->inOneOfGroups(['root','admins','photos'])) ?
        FileUpload::widget([
            'model' => $model,
            'attribute' => 'img_fl',
            'url' => ['posts/image-upload', 'id' => $model->id],
            'clientOptions' => [
                'maxFileSize' => 2000000,
                'singleFileUploads' => true,
            ],
            // ...
            'clientEvents' => [
                'fileuploaddone' => 'function(e, data) {
                                        console.log(e);
                                        console.log(data);
                                    }',
                'fileuploadfail' => 'function(e, data) {
                                        alert("Ошибка загрузки файла!");
                                        console.log(e);
                                        console.log(data);
                                    }',
            ],
        ]) 
        : "" ); ?>
    </div>
    <div class="form-group">
    <?= ((($model->file) && ((Yii::$app->user->identity === NULL)? 
                            false : Yii::$app->user->identity->inOneOfGroups(['root','admins','photos']))) ?
        Html::a('Удалить файл', ['/posts/del-photo?id='.$model->id], ['class'=>'btn btn-danger'])
        : "" ); ?>
    </div>
    
    <?php } ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', 
        ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary',
          'id' => 'Posts_FORM_BUTTON'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
