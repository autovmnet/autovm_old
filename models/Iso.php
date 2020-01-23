<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "iso".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 */
class Iso extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'iso';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'path'], 'required'],
            [['name', 'path'], 'string', 'max' => 255]
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
            'path' => Yii::t('app', 'Path'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\IsoQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\IsoQuery(get_called_class());
    }
    
    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['name', 'path'],  
        ];
    }
}
