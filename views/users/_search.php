<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\UsersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-search well well-sm">
    <?php 
    $userSearchParams = Yii::$app->getRequest()->getQueryParam('UserSearch');
    $filter_on = $userSearchParams['filter_on'];
    $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'id' => 'UserSearchForm',
        'options' => (($filter_on)? [] : ['class' => 'hidden'])
    ]); ?>
    <div class="row form-group">
    <div class="col-xs-12 col-sm-3 col-md-2">
    <?= $form->field($model, 'id')->textInput()->label('ID') ?>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-4">
    <?= $form->field($model, 'name') ?>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-6">
    <?= $form->field($model, 'descr') ?>
    </div>
    </div>
    <div class="row form-group">
    <?php 
    for ($i = 0; $i < count($model->datetime_fields); $i++){ 
        $f = $model->datetime_fields[$i];
        $fv=[0,0];
        $fv[0] = $userSearchParams[$f][0];
        $fv[1] = $userSearchParams[$f][1];
        if (empty($fv[0]) || empty($fv[1])){
            $fv[0] = ""; $fv[1] = "";
        }
    ?>
      <div class="col-xs-12 col-sm-6">
      <div class="form-group">
      <label class="control-label" for="<?= $f.'0' ?>">
      <?= $model->attributeLabels()[$f] ?> (интервал) 
      </label>
      <a href="#" title="Очистить интервал" class="clear-interval" data-clear="<?= $f ?>">
        <i class="glyphicon glyphicon-remove"></i>
      </a>
      <?php
      for ($j = 0; $j < 2; $j++){
        echo DateTimePicker::widget([
            'name' => 'UserSearch['.$f.'][]',
            'id' => $f.$j,
            'value' => $fv[$j],
            'removeButton' => false,
            'pickerButton' => ['icon' => 'time'],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'dd.mm.yyyy hh:ii'
            ]
        ]);
      } ?>
      </div>
      </div>
    <?php } ?>
    </div>
    <input type="hidden" name="UserSearch[filter_on]" value="1" />
    <div class="form-group">
        <?= Html::submitButton('Фильтр', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Очистить', ['/users/index'], ['class'=>'btn btn-default']) ?> 
    </div>

    <?php ActiveForm::end(); ?>
    <a href="#" id="UserSearchFormToggle">Фильтр</a>
</div>
