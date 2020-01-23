<?php

namespace app\modules\site\widgets;

use Yii;
use yii\base\Widget;

use app\models\Vps;

class UserVpsList extends Widget
{
    public function run()
    {
        $virtualServers = Vps::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC')->limit(10)->all();
        
        return $this->render('vps-list', [
            'virtualServers' => $virtualServers,
        ]);
    }
}