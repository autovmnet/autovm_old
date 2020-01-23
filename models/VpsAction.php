<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "vps_action".
 *
 * @property string $id
 * @property string $vps_id
 * @property integer $action
 * @property string $description
 * @property string $created_at
 *
 * @property Vps $vps
 */
class VpsAction extends \yii\db\ActiveRecord
{
    const ACTION_INSTALL = 1;
    const ACTION_START = 2;
    const ACTION_STOP = 3;
    const ACTION_RESTART = 4;
    const ACTION_SUSPEND = 5;
    const ACTION_UNSUSPEND = 6;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vps_action';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vps_id', 'action'], 'required'],
            [['vps_id', 'action', 'created_at'], 'integer'],
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
            'vps_id' => Yii::t('app', 'Vps ID'),
            'action' => Yii::t('app', 'Action'),
            'description' => Yii::t('app', 'Description'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasOne(Vps::className(), ['id' => 'vps_id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\VpsActionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\VpsActionQuery(get_called_class());
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
    
    public function getIsInstall()
    {
        return $this->action == self::ACTION_INSTALL;
    }
    
    public function getIsStart()
    {
        return $this->action == self::ACTION_START;
    }
    
    public function getIsStop()
    {
        return $this->action == self::ACTION_STOP;
    }
    
    public function getIsRestart()
    {
        return $this->action == self::ACTION_RESTART;
    }
    
    public function getIsSuspend()
    {
        return $this->action == self::ACTION_SUSPEND;   
    }

    public function getIsUnsuspend()
    {
        return $this->action == self::ACTION_UNSUSPEND;
    }
}
