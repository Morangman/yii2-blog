<?php

/* @var $this yii\web\View */
/* @var $posts [] app\model\Posts */

$this->title = 'ФотоОн - посты с фотками, наверное...';
?>
<div class="site-index">

    <!--div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div-->

    <div class="body-content">
        <?php 
          foreach($posts as $p){
        ?>
        <div class="row">
          <div class="thumbnail">
            <div class="caption">
              <div class="row">
                <span class="label label-primary" title="Дата создания"><?= \Yii::$app->formatter->asDatetime($p->created, "php:d.m.Y  H:i"); ?></span>
                <span class="label label-default" title="<?= $p->user->descr ?>"><?= $p->user->name ?></span>
                </div>
              <h3><?= $p->topic ?></h3>
              <p><?= $p->content ?></p>
            </div>
            <img src="<?= $p->file ?>" alt="<?= $p->file ?>" />
          </div>
        
          <!--div class="panel panel-default">
            <div class="panel-heading top-heading">
              <div class="row">
                <span class="label label-primary" title="Дата создания"><?= \Yii::$app->formatter->asDatetime($p->created, "php:d.m.Y  H:i"); ?></span>
                <span class="label label-default" title="<?= $p->user->descr ?>"><?= $p->user->name ?></span>
                </div>
              <h3><?= $p->topic ?></h3>
            </div>
            <div class="panel-body">
              <div><?= $p->content ?></div>
              
            </div>
          </div-->
        </div>
        
        <?php } ?>

    </div>
</div>
