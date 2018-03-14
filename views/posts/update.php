<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Posts */

$this->title = 'Редактирование поста #' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Посты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->topic, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="posts-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
