<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use dosamigos\fileupload\FileUpload;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

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
            ['attribute' =>'topic', 
              'headerOptions' => ['style' => 'width:25%; min-width: 150px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'content', 
              'format' => 'html',
              'headerOptions' => ['style' => 'width:30%; min-width: 200px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'created', 
              'format' => ['date', 'php:d.m.Y H:i'],
              'headerOptions' => ['style' => 'width:10%; min-width: 100px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'modified', 
              'format' => ['date', 'php:d.m.Y H:i'],
              'headerOptions' => ['style' => 'width:10%; min-width: 100px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
            ['attribute' =>'user_str', 
              'enableSorting' => true,
              'value' => function($model){
                return "<span class='label label-primary' title='".htmlspecialchars($model->user->descr)."'>".$model->user->name."</span>";
              },
              'format'=>'html',
              'headerOptions' => ['style' => 'width:10%; min-width: 60px;'],
              'filterInputOptions' => [
                'class' => 'form-control input-sm', 
                'id' => null
              ], ],
              
            ['class' => 'yii\grid\ActionColumn',
                          'template'=>'{addfile} {update} {view} * {delete}',
                            'buttons'=>[
                              'addfile' => function ($url, $model) {     
                                return FileUpload::widget([
                                  'model' => $model,
                                  'options' => [
                                    'id' => 'Posts_'.$model->id,
                                   ],
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
                                                              alert("Файл загружен!");
                                                          }',
                                      'fileuploadfail' => 'function(e, data) {
                                                              alert("Ошибка загрузки файла!");
                                                              console.log(e);
                                                              console.log(data);
                                                          }',
                                  ],
                                ]);
                              }
                            ]
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
