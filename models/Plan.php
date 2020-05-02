<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "plan".
 *
 * @property string $id
 * @property string $name
 * @property string $ram
 * @property string $cpu_mhz
 * @property string $cpu_core
 * @property string $hard
 * @property integer $band_width
 * @property integer $is_public
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Vps[] $vps
 */
class Plan extends \yii\db\ActiveRecord
{
    const IS_PUBLIC = 1;
    const IS_NOT_PUBLIC = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'ram', 'cpu_mhz', 'cpu_core', 'hard'], 'required'],
            [['ram', 'cpu_mhz', 'cpu_core', 'hard', 'is_public', 'created_at', 'updated_at','band_width'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'ram' => Yii::t('app', 'Ram'),
            'cpu_mhz' => Yii::t('app', 'Cpu Mhz'),
            'cpu_core' => Yii::t('app', 'Cpu Core'),
            'hard' => Yii::t('app', 'Hard'),
            'is_public' => Yii::t('app', 'Is Public'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'band_width'=> Yii::t('app', 'BandWidth'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVps()
    {
        return $this->hasMany(Vps::className(), ['plan_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\PlanQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\PlanQuery(get_called_class());
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name', 'ram', 'cpu_mhz', 'cpu_core', 'hard', 'band_width', 'is_public'],
        ];
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    public function getIsPublic()
    {
        return $this->is_public == self::IS_PUBLIC;
    }
    
    public static function getPublicYesNo()
    {
        return [
            self::IS_PUBLIC => Yii::t('app', 'Yes'),
            self::IS_NOT_PUBLIC => Yii::t('app', 'No'),
        ];
    }
}
