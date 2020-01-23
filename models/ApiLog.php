<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "api_log".
 *
 * @property string $id
 * @property string $api_id
 * @property integer $action
 * @property string $description
 * @property string $created_at
 *
 * @property Api $api
 */
class ApiLog extends \yii\db\ActiveRecord
{
    const ACTION_CREATE_VPS = 1;
    const ACTION_RESTART_VPS = 2;
    const ACTION_STOP_VPS = 3;
    const ACTION_START_VPS = 4;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'api_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['api_id', 'action'], 'required'],
            [['api_id', 'action', 'created_at'], 'integer'],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'api_id' => Yii::t('app', 'Api ID'),
            'action' => Yii::t('app', 'Action'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApi()
    {
        return $this->hasOne(Api::className(), ['id' => 'api_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\ApiLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\ApiLogQuery(get_called_class());
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
    
    public function getIsCreateVps()
    {
        return $this->action == self::ACTION_CREATE_VPS;
    }
    
    public function getIsRestartVps()
    {
        return $this->action == self::ACTION_RESTART_VPS;
    }
    
    public function getIsStopVps()
    {
        return $this->action == self::ACTION_STOP_VPS;
    }
    
    public function getIsStartVps()
    {
        return $this->action == self::ACTION_START_VPS;
    }
}
