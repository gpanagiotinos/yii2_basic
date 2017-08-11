<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "User".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $password
 * @property integer $role_id
 * @property integer $info_id
 * @property boolean $active
 *
 * @property Info $info
 * @property Roles $role
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'User';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'password', 'role_id', 'active'], 'required'],
            [['role_id'], 'integer'],
            [['username', 'email'], 'string', 'max' => 255],
            [['password'], 'match', 'pattern' => '/^(?=.*[a-zA-Z0-9]).{6,}$/', 'message' => 'You password must contain at least 6 characters, 1 lesser, 1 capital and 1 number.'],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['role_id' => 'role_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Username',
            'email' => 'Email',
            'password' => 'Password',
            'role_id' => 'Role ID',
            'active' => 'Active'
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Roles::className(), ['role_id' => 'role_id']);
    }

    public static function findByUsername($username)
    {
            $user = User::find()
            ->where(['username' => $username])->one();
            if ($user) {
 
                return $user;
            }
        

        return null;
    }

    public function validatePassword($password, $username)
    {

        $user = User::find()
        ->select(['password'])
        ->where(['username' => $username])->one();


        //$hash = Yii::$app->getSecurity()->generatePasswordHash($password);
        if(Yii::$app->getSecurity()->validatePassword($password, $user->password)){
            return $user->password;
        }else{
            return null;
        }

        
    }
//Methods needed by IntentityInterface
    public static function findIdentity($id)
    {
        $user = User::find()
        ->where(['id' => $id])->one();
        return $user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
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
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

}
