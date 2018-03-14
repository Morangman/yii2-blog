<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * This is the model class for table "posts".
 *
 * @property integer $id
 * @property string $topic
 * @property string $content
 * @property string $created
 * @property string $modified
 * @property string $user_id
 * @property string $file
 */
class Posts extends \yii\db\ActiveRecord
{
    public $datetime_fields = [
        'created',
        'modified'
    ];
    
    public $user_str;
    public $img_fl;
    
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
      return [
        [['topic','content','user_id'], 'myRules', 'skipOnEmpty'=> false],
        [['created', 'modified'], 'safe'],
        [['img_fl'], 'file', 'extensions' => 'png, jpg, jpeg, gif, bmp, tiff'],
      ];
    }
    
    public function myRules($attribute, $params, $validator)
    {
      if ($attribute == "topic"){
        if (strlen($this->$attribute) < 2){
          $this->addError($attribute,
            "Заголовок поста должен состоять "
            ."минимум из 2 символов");  
        }
        if (strlen($this->$attribute) > 128){
          $this->addError($attribute,
            "Заголовок поста должен состоять "
            ."максимум до 128 символов");  
        }
      }
      if ($attribute == "content"){
        if (empty($this->$attribute) ){
          $this->addError($attribute,
            "Контент поста не может быть пустым");
        }
      }
      if ($attribute == "user_id"){
        if (empty($this->$attribute) || (Users::findOne($this->$attribute) === null)){
          $this->addError($attribute,
            "Автор должен быть указан и зарегистрирован");
        }
      }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'topic' => 'Заголовок',
            'content' => 'Содержание',
            'created' => 'Создано',
            'modified' => 'Обновлено',
            'user_id' => 'Автор',
            'user_str' => 'Автор',
        ];
    }

    /**
     * @inheritdoc
     * @return GroupsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostsQuery(get_called_class());
    }
    
    
    
}
