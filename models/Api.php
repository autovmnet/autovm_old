<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "api".
 *
 * @property string $id
 * @property string $key
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ApiLog[] $apiLogs
 */
class Api extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['created_at', 'updated_at'], 'integer'],
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
            'key' => Yii::t('app', 'Key'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApiLogs()
    {
        return $this->hasMany(ApiLog::className(), ['api_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\ApiQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\ApiQuery(get_called_class());
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
}
