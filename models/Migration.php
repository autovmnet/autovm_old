<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "migration".
 *
 * @property string $version
 * @property integer $apply_time
 */
class Migration extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'migration';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['version'], 'required'],
            [['apply_time'], 'integer'],
            [['version'], 'string', 'max' => 180]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'version' => Yii::t('app', 'Version'),
            'apply_time' => Yii::t('app', 'Apply Time'),
        ];
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\MigrationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \app\models\queries\MigrationQuery(get_called_class());
    }
}
