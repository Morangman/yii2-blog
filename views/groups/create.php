<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Groups */

$this->title = 'Добавление группы пользователей';
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
