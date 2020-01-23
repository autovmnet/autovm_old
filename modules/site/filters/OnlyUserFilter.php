<?php

namespace app\modules\site\filters;

use Yii;
use yii\base\ActionFilter;

class OnlyUserFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->response->redirect(['/site/default/login'])->send(); exit;
        }
        
        return parent::beforeAction($action);
    }
}