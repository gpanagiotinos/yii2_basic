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
 */

class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    //constants for rules
    const WEAK = 0;
    const STRONG = 1;
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
            [['username', 'email', 'password'], 'required'],
            [['username', 'email'], 'string', 'max' => 255],
            //[['password'], 'string', 'max' => 128],
            [['password'], 'match', 'pattern' => '/^(?=.*[a-zA-Z0-9]).{6,}$/', 'message' => 'You password must contain at least 6 characters, 1 lesser, 1 capital and 1 number.'],
            [['username'], 'unique'],
            [['email'], 'unique'],
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
        ];
    }
/*
    public function passwordStrength( $attributes, $params ){
        if ($params['strength'] === SELF::WEAK) {
            
            $pattern = '/^(?=.*[a-zA-Z0-9]).{4,}$/';
        } elseif($params['strength'] === SELF::STRONG) {
            //password rules at least 6 char, 1 lesser, 1 Capital and 1 number
            $pattern = '/^(?=.*[a-zA-Z0-9]).{6,}$/';
        }
        if(!preg_match($pattern, $this->$attribute))
            $this->addError($attribute, 'your password is not strong enough!');
        

    }
    */
}
