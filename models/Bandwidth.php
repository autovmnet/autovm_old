<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "bandwidth".
 *
 * @property string $id
 * @property string $vps_id
 * @property string $used
 * @property string $pure_used
 * @property string $created_at
 * @property integer $status
 *
 * @property Vps $vps
 */
class Bandwidth extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 2;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bandwidth';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vps_id', 'used', 'pure_used'], 'required'],
            [['vps_id', 'used', 'pure_used', 'created_at', 'status'], 'integer']
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
            'used' => Yii::t('app', 'Used'),
            'pure_used' => Yii::t('app', 'Pure Used'),
            'created_at' => Yii::t('app', 'Created At'),
			'status' => Yii::t('app', 'Status'),
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
     * @return \app\models\queries\BandwidthQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\BandwidthQuery(get_called_class());
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
	
	public function getIsActive()
	{
		return $this->status == self::STATUS_ACTIVE;
	}
}
