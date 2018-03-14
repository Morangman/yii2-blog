<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Сменить пароль', ['chpwd', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Подтвердить удаление?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'pwd',
            'descr:ntext',
            'registered:datetime',
            'last_enter:datetime',
            [ 
                'format' => 'html',
                'label' => 'Группы',
                'value' => function ($model, $widget){ 
                  $out = ''; 
                  $arr = []; 
                  foreach($model->userGroups() as $g){ 
                    $arr []= "<span class='label label-primary' title='".htmlspecialchars($g->descr)."'>"
                      .$g->name
                      .'</span>'; 
                  } 
                  $out = implode($arr,', '); return $out;},
            ],
        ],
    ]) ?>

</div>
