<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

use app\models\behaviors\ClientInfoBehavior;

/**
 * This is the model class for table "user_login".
 *
 * @property string $id
 * @property string $user_id
 * @property string $ip
 * @property string $os_name
 * @property string $browser_name
 * @property string $created_at
 * @property integer $status
 *
 * @property User $user
 */
class UserLogin extends \yii\db\ActiveRecord
{
    const STATUS_SUCCESSFUL = 1;
    const STATUS_UNSUCCESSFUL = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_login';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'ip', 'os_name', 'browser_name'], 'required'],
            [['user_id', 'created_at', 'status'], 'integer'],
            [['ip'], 'string', 'max' => 45],
            [['os_name', 'browser_name'], 'string', 'max' => 255]
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
            'ip' => Yii::t('app', 'Ip'),
            'os_name' => Yii::t('app', 'Os Name'),
            'browser_name' => Yii::t('app', 'Browser Name'),
            'created_at' => Yii::t('app', 'Created At'),
            'status' => Yii::t('app', 'Status'),
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
     * @return \app\models\queries\UserLoginQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\UserLoginQuery(get_called_class());
    }

    public function behaviors()
    {
        return [
            ClientInfoBehavior::className(),
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }       
    
    public function getIsSuccessful() 
    {
        return $this->status == self::STATUS_SUCCESSFUL;
    }
}
