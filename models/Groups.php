<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\Expression;

/**
 * This is the model class for table "groups".
 *
 * @property integer $id
 * @property string $name
 * @property string $descr
 * @property string $created
 */
class Groups extends \yii\db\ActiveRecord
{
    public $datetime_fields = [
        'created'
    ];
    
    public function getUsergroups()
    {
        return $this->hasMany(Users::className(), ['id' => 'user_id'])
            ->viaTable('usergroup', ['group_id' => 'id']);
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
      return [
        [['name','descr'], 'myRules', 'skipOnEmpty'=> false],
        [['created'], 'safe'],
        [['name'], 'unique', 'message' => 'Такое имя уже используется'],
      ];
    }
    
    public function myRules($attribute, $params, $validator)
    {
      if ($attribute == "name"){
        if (!preg_match('/^[a-zA-Z0-9_]+$/',$this->$attribute)){
          $this->addError($attribute,
            "Имя группы должно состоять только "
            ."из английских букв, цифр или знака подчеркивания");
        }
        if (strlen($this->$attribute) < 4){
          $this->addError($attribute,
            "Имя группы должно состоять "
            ."минимум из 4 символов");  
        }
        if (strlen($this->$attribute) > 128){
          $this->addError($attribute,
            "Имя группы должно состоять "
            ."максимум до 128 символов");  
        }
      }
      if ($attribute == "descr"){
        if (empty($this->$attribute) ){
          $this->addError($attribute,
            "Описание группы не может быть пустым");
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
            'name' => 'Название группы',
            'descr' => 'Полное название группы',
            'created' => 'Создано',
        ];
    }

    /**
     * @inheritdoc
     * @return GroupsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupsQuery(get_called_class());
    }
    
    /**
     * @inheritdoc
     * @return array
     */
    public static function getAllAsArray()
    {
      $data = self::find()
          ->select(['id', new \yii\db\Expression("CONCAT(name, ' {', descr, '}') as name")])
          /*->where(['' => ''])*/
          ->orderBy('name ASC')
          ->asArray()
          ->all();
      return ArrayHelper::map($data, 'id', 'name');
    }
    
    
}
