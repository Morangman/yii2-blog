<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $name
 * @property string $pwd
 * @property string $descr
 * @property string $registered
 * @property string $last_enter
 */
class Users extends \yii\db\ActiveRecord
{
    public $pwd2;
    public $datetime_fields = [
        'registered',
        'last_enter'
    ];
    public $selected_groups;
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }
    
    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
      $db = Yii::$app->getDb();
      if(is_array($this->selected_groups)){
        if (!$insert){
            $db->createCommand('DELETE FROM usergroup WHERE user_id=:user_id', [
                ':user_id' => $this->id,
            ])->execute();
        }
        foreach ($this->selected_groups as $group_id){
          $db->createCommand('INSERT INTO usergroup (user_id,group_id) VALUES (:user_id, :group_id)', [
              ':user_id' => $this->id,
              ':group_id' => $group_id,
          ])->execute();
        }
      }
      return true;
    }    
    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!$insert){
			$this->registered = new \yii\db\Expression('NOW()');
        } 
		if ($this->pwd){
			$this->pwd = md5($this->pwd . date("iHdmY",strtotime($this->registered)));
		}
      return true;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
      return [
        [['pwd','pwd2'], 'pwdRules', 'skipOnEmpty'=> false, 'on' => 'checkpwd'],
        [['name','descr', 'selected_groups'], 'myRules', 'skipOnEmpty'=> false],
        [['registered', 'last_enter'], 'safe'],
        [['name'], 'unique', 'message' => 'Такое имя уже используется'],
      ];
    }
    
    
    public function myRules($attribute, $params, $validator)
    {
      if ($attribute == "name"){
        if (!preg_match('/^[a-zA-Z0-9_]+$/',$this->$attribute)){
          $this->addError($attribute,
            "Имя пользователя должно состоять только "
            ."из английских букв, цифр или знака подчеркивания");
        }
        if (strlen($this->$attribute) < 4){
          $this->addError($attribute,
            "Имя пользователя должно состоять "
            ."минимум из 4 символов");  
        }
        if (strlen($this->$attribute) > 128){
          $this->addError($attribute,
            "Имя пользователя должно состоять "
            ."максимум до 128 символов");  
        }
      }
      if ($attribute == "descr"){
        if (empty($this->$attribute) ){
          $this->addError($attribute,
            "Описание пользователя не может быть пустым");
        }
      }
      if ($attribute == "selected_groups" 
            && is_array($this->$attribute) 
            && count($this->$attribute) == 0 ){
        $this->addError($attribute,
          "Пользователю должна быть присвоена хотя бы одна группа доступа");
      }
    }
    
    public function pwdRules($attribute, $params, $validator)
    {
      if (empty($this->$attribute)){
        $this->addError($attribute,
          "Пароль не может быть пустым"); 
      }
      if ($this->pwd != $this->pwd2 && $attribute == 'pwd2'){
        $this->addError($attribute,
          "Пароль и подтверждение должны совпадать");
      } elseif(!empty($this->pwd) 
          && !empty($this->pwd2) 
          && self::isStrongPwd($this->pwd)) {
        $this->clearErrors('pwd');
        $this->clearErrors('pwd2');
      }
    }
    
    public static function isStrongPwd($pwd)
    {
      return true;
    }
    


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя пользователя',
            'pwd' => 'Пароль',
            'pwd2' => 'Подтверждение пароля',
            'descr' => 'Доп.инфо',
            'registered' => 'Зарегистрировано',
            'last_enter' => 'Последний вход',
            'selected_groups' => 'Выбранные группы',
        ];
    }

    /**
     * @inheritdoc
     * @return UsersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UsersQuery(get_called_class());
    }
    
    /**
     * @inheritdoc
     * @return array
     */
    public function userGroups()
    {
      $groups = Groups::find()
          ->joinWith('usergroups', 'groups.id = usergroup.group_id')
          ->where(['usergroup.user_id' => $this->id])
          ->orderBy('name ASC')
          ->all();
      $this->selected_groups = [];
      foreach ($groups as $g){
        $this->selected_groups []= $g->id;
      }
      return $groups;
    }
    
}
