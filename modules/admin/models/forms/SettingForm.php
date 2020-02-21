<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Model;

class SettingForm extends Model
{
    public $title;
    public $terminate;
    public $language;
    public $change_limit;
    public $from_port;
    public $to_port;

    public function rules()
    {
        return [
            [['title', 'terminate', 'language', 'change_limit', 'from_port', 'to_port'], 'required'],
            [['from_port', 'to_port'], 'integer', 'min' => 3000, 'max' => 9000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Title'),
            'terminate' => Yii::t('app', 'Terminate'),
            'language' => Yii::t('app', 'Language'),
            'change_limit' => Yii::t('app', 'Change Limit'),
            'from_port' => Yii::t('app', 'From Port'),
            'to_port' => Yii::t('app', 'To Port'),
        ];
    }
}
