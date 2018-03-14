<?php

namespace app\models;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;
    public $salt;
    public $group_names;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
      $u = Users::findOne($id);
      return  self::initFromUsers($u);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
      $u = Users::find()->where("concat(pwd,name) = '".$token."'");
      return  self::initFromUsers($u);
    }
    
    /**
     * Creates IdentityInterface from Users model attributes.
     * @param Users $u Users model
     * @return $this the model instance itself.
     */
    public static function initFromUsers($u)
    {
      if (!$u){
        return null;
      }
      $_group_names = [];
      foreach ($u->userGroups() as $g){
        $_group_names[]= strtoupper(trim($g->name));
      }
      return  new static([
        'id' => $u->id,
        'username' => $u->name,
        'password' => $u->pwd,
        'salt' => date("iHdmY",strtotime($u->registered)),
        'authKey' => $u->name.$u->pwd,
        'accessToken' => $u->pwd.$u->name,
        'group_names' => $_group_names,
      ]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
      return ($this->password === md5($password.$this->salt));
    }
    
    public function inGroup($group_name){
      if (!$this->group_names){
        return false;
      }
      foreach ($this->group_names as $g){
        if ($g === strtoupper(trim($group_name))){
          return true;
        }
      }
      return false;
    }
    
    public function inOneOfGroups($group_names){
      if (!is_array($group_names)){
        return false;
      }
      foreach ($this->group_names as $g){
        foreach ($group_names as $gk){
          if ($g === strtoupper(trim($gk))){
            return true;
          }
        }
      }
      return false;
    }
}
