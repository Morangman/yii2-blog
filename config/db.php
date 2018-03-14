<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=mydb',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    /*'on afterOpen' => function($event) {
      $event->sender->createCommand("SET time_zone = '+02:00'")->execute();
    }    */

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
