<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vps_ip".
 *
 * @property string $id
 * @property string $vps_id
 * @property string $ip_id
 *
 * @property Ip $ip
 * @property Vps $vps
 */
class VpsIp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'vps_ip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vps_id', 'ip_id'], 'required'],
            [['vps_id', 'ip_id'], 'integer']
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
            'ip_id' => Yii::t('app', 'Ip ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIp()
    {
        return $this->hasOne(Ip::className(), ['id' => 'ip_id']);
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
     * @return \app\models\queries\VpsIpQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\VpsIpQuery(get_called_class());
    }
}
