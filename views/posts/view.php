<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Posts */

$this->title = $model->topic;
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'topic',
            'content:ntext',
            'created:datetime',
            'modified:datetime',
            [ 
                'format' => 'html',
                'label' => 'Автор',
                'value' => function ($model, $widget){ 
                  $out = "<span class='label label-primary' title='".htmlspecialchars($model->user->descr)."'>"
                      .$model->user->name
                      .'</span>'; 
                  return $out;},
            ],
        ],
    ]) ?>

</div>
