<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Редактирование пользователя ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::a('Сменить пароль', ['chpwd', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></p>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
