<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_password".
 *
 * @property string $id
 * @property string $user_id
 * @property integer $hash
 * @property string $salt
 * @property string $password
 * @property string $created_at
 *
 * @property User $user
 */
class UserPassword extends \yii\db\ActiveRecord
{
    const ALGO_MD5 = 1;
    const ALGO_SHA1 = 2;
    const ALGO_SHA256 = 3;

    const SCENARIO_RESET_PASSWORD = 'reset-password';
    const SCENARIO_CHANGE_PASSWORD = 'change-password';

    public $repeatPassword;

    public function init()
    {
        $this->hash = self::ALGO_SHA1;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_password';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'password', 'repeatPassword'], 'required'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password'],
            [['user_id', 'hash', 'created_at'], 'integer'],
            [['salt', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'hash' => Yii::t('app', 'Hash'),
            'salt' => Yii::t('app', 'Salt'),
            'password' => Yii::t('app', 'Password'),
            'repeatPassword' => Yii::t('app', 'Repeat Password'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\UserPasswordQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\UserPasswordQuery(get_called_class());
    }

    public function scenarios()
    {        
        return [
            self::SCENARIO_DEFAULT => ['password', 'repeatPassword'],
            self::SCENARIO_RESET_PASSWORD => ['password', 'repeatPassword'],
            self::SCENARIO_CHANGE_PASSWORD => ['password', 'repeatPassword'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function setPassword($password)
    {
        $this->salt = Yii::$app->helper->randomString(16);
        $this->password = $this->hashPassword($password);
    }

    public function hashPassword($password)
    {
        $password .= $this->salt;

        if($this->hash == self::ALGO_MD5) {
            return md5($password);
        } else if($this->hash == self::ALGO_SHA1) {
            return sha1($password);
        } else {
            return hash('sha256', $password);
        }
    }

    public function validatePassword($password)
    {
        return $this->password == $this->hashPassword($password);
    }
}
