<?php

namespace app\models;

use Yii;
use yii\base\Model;

class Helper extends Model
{
    public static function dates2ansi($model){
        for ($i = 0; $i < count($model->datetime_fields) ; $i++){
          $field = $model->datetime_fields[$i];
          $model->$field = date("Y-m-d H:i", strtotime(str_replace('.','-',$model->$field)));
          if ((!strtotime($model->$field)) || (strtotime($model->$field) === -1)){
            $model->$field = null;
          }
        }
    }
    
    public static function ansi2dates($model){
        for ($i = 0; $i < count($model->datetime_fields) ; $i++){
          $field = $model->datetime_fields[$i];
          if (!((!strtotime($model->$field)) || (strtotime($model->$field) === -1))){
            $model->$field = date("d.m.Y H:i", strtotime($model->$field));;
          } else {
            $model->$field = null;
          }
        }
    }
}
