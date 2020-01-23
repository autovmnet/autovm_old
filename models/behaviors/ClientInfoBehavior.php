<?php

namespace app\models\behaviors;

use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class ClientInfoBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
        ];
    }

    public function beforeSave()
    {
        $this->owner->ip = Yii::$app->request->userIp;

        $os = new \Sinergi\BrowserDetector\Os;
        $this->owner->os_name = strtolower($os->getName());

        $browser = new \Sinergi\BrowserDetector\Browser;
        $this->owner->browser_name = strtolower($browser->getName());
    }
}