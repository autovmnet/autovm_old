<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

use app\models\UserLogin;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $auth_key
 * @property integer $is_admin
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 *
 * @property LostPassword[] $lostPasswords
 * @property UserEmail[] $userEmails
 * @property UserLogin[] $userLogins
 * @property UserPassword[] $userPasswords
 * @property Vps[] $vps
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    
    const IS_ADMIN = 1;
    const IS_NOT_ADMIN = 2;
    
    const SCENARIO_CREATE = 'create';
    const SCENARIO_PROFILE = 'profile';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['first_name', 'last_name'], 'required'],
            [['is_admin', 'created_at', 'updated_at', 'status'], 'integer'],
            [['first_name', 'last_name', 'auth_key'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'is_admin' => Yii::t('app', 'Is Admin'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLostPasswords()
    {
        return $this->hasMany(LostPassword::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserEmails()
    {
        return $this->hasMany(UserEmail::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmail()
    {
        return $this->hasOne(UserEmail::className(), ['user_id' => 'id'])->descScope();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmail2()
    {
        return $this->hasOne(UserEmail::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserLogins()
    {
        return $this->hasMany(UserLogin::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserPasswords()
    {
        return $this->hasMany(UserPassword::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasMany(Vps::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPassword()
    {
        return $this->hasOne(UserPassword::className(), ['user_id' => 'id'])->orderBy(['id' => SORT_DESC]);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\UserQuery(get_called_class());
    }
    
    public function scenarios()
    {        
        return [
            self::SCENARIO_DEFAULT => ['first_name', 'last_name', 'is_admin', 'status'],
            self::SCENARIO_PROFILE => ['first_name', 'last_name'],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getFullName()
    {
        return implode(' ', [$this->first_name, $this->last_name]);
    }

    public static function findIdentity($id)
    {
        return self::find()->where(['id' => $id])->active()->one();
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return false;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }
    
    public function setAuthKey()
    {
        $this->auth_key = Yii::$app->helper->randomString(16);
    }
    
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }

    public function saveLogin($status)
    {
        $login = new UserLogin;
        $login->user_id = $this->id;
        $login->status = ($status ? UserLogin::STATUS_SUCCESSFUL : UserLogin::STATUS_UNSUCCESSFUL);
        $login->save(false);
    }
    
    public function getIsAdmin()
    {
        return $this->is_admin == self::IS_ADMIN;
    }
    
    public static function getAdminYesNo()
    {
        return [
            self::IS_NOT_ADMIN => Yii::t('app', 'No'),
            self::IS_ADMIN => Yii::t('app', 'Yes'),
        ];
    }
    
    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
        ];
    }
}
