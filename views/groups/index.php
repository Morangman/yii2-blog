<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\GroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Группы пользователей';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groups-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id' => 'pjax_users_grid']); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['attribute' =>'id', 
              'value' => function($model){
                return "<b>".$model->id."</b>";
              },
              'format'=>'html',
              'headerOptions' => ['style' => 'width:5%; min-width: 60px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'name', 
              'headerOptions' => ['style' => 'width:25%; min-width: 150px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'descr', 
              'format' => 'text',
              'headerOptions' => ['style' => 'width:40%; min-width: 250px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'created', 
              'format' => ['date', 'php:d.m.Y H:i'],
              'headerOptions' => ['style' => 'width:20%; min-width: 100px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
