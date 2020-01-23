<?php

namespace app\modules\admin\models\forms;

use Yii;
use yii\base\Model;

class SettingForm extends Model
{
    public $title;
    public $api_url;
    public $terminate;
    public $language;
    public $change_limit;

    public function rules()
    {
        return [
            [['title', 'api_url', 'terminate', 'language', 'change_limit'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'Title'),
            'api_url' => Yii::t('app', 'Api Url'),
            'terminate' => Yii::t('app', 'Terminate'),
            'language' => Yii::t('app', 'Language'),
            'change_limit' => Yii::t('app', 'Change Limit'),
        ];
    }
}
