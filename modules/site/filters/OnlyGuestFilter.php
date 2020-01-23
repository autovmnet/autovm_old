<?php

namespace app\modules\site\filters;

use Yii;
use yii\base\ActionFilter;

class OnlyGuestFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(['/site/user/index'])->send(); exit;
        }

        return parent::beforeAction($action);
    }
}