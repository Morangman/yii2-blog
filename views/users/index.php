<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="users-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(['id' => 'pjax_users_grid', 'timeout' => 10000]); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/ 
            ['attribute' =>'id', 
              'headerOptions' => ['style' => 'width:5%; min-width: 60px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'name', 
              'headerOptions' => ['style' => 'width:15%; min-width: 100px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'descr', 
              'format' => 'text',
              'headerOptions' => ['style' => 'width:30%; min-width: 150px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'registered', 
              'format' => ['date', 'php:d.m.Y H:i'],
              'headerOptions' => ['style' => 'width:20%; min-width: 100px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'last_enter', 
              'format' => ['date', 'php:d.m.Y H:i'],
              'headerOptions' => ['style' => 'width:20%; min-width: 100px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            [ 
                'format' => 'html',
                'attribute' => '_groups',
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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
