<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_email".
 *
 * @property string $id
 * @property string $user_id
 * @property string $email
 * @property string $key
 * @property integer $is_primary
 * @property integer $is_confirmed
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class UserEmail extends \yii\db\ActiveRecord
{
    const IS_PRIMARY = 1;
    const IS_NOT_PRIMARY = 2;

    const IS_CONFIRMED = 1;
    const IS_NOT_CONFIRMED = 2;
    
    const SCENARIO_CREATE = 'create';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_email';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'email'], 'required'],
            [['user_id', 'is_primary', 'is_confirmed', 'created_at', 'updated_at'], 'integer'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['email'], 'unique'],
            [['key'], 'string', 'max' => 16],
            [['key'], 'unique']
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
            'email' => Yii::t('app', 'Email'),
            'key' => Yii::t('app', 'Key'),
            'is_primary' => Yii::t('app', 'Is Primary'),
            'is_confirmed' => Yii::t('app', 'Is Confirmed'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
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
     * @return \app\models\queries\UserEmailQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\UserEmailQuery(get_called_class());
    }
    
    public function scenarios()
    {        
        return [
            self::SCENARIO_DEFAULT => ['email'],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function setKey()
    {
        $this->key = Yii::$app->helper->randomString(16);
    }
    
    public function isPrimary()
    {
        return $this->is_primary == self::IS_PRIMARY;
    }
    
    public function isConfirmed()
    {
        return $this->is_confirmed == self::IS_CONFIRMED;
    }
}
