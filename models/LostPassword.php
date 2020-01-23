<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "lost_password".
 *
 * @property string $id
 * @property string $user_id
 * @property string $key
 * @property string $created_at
 * @property string $updated_at
 * @property string $expired_at
 * @property integer $status
 *
 * @property User $user
 */
class LostPassword extends \yii\db\ActiveRecord
{
    const STATUS_SUCCESSFUL = 1;
    const STATUS_UNSUCCESSFUL = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lost_password';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'key', 'expired_at'], 'required'],
            [['user_id', 'created_at', 'updated_at', 'expired_at', 'status'], 'integer'],
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
            'key' => Yii::t('app', 'Key'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'expired_at' => Yii::t('app', 'Expired At'),
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
     * @return \app\models\queries\LostPasswordQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\LostPasswordQuery(get_called_class());
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

    public function setExpireDate()
    {
        $this->expired_at = time() + 86400;
    }
}
