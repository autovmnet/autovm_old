<?php

namespace app\filters;

use Yii;
use yii\web\Response;
use yii\base\ActionFilter;

class Format extends ActionFilter
{
    public function beforeAction($action)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        return parent::beforeAction($action);
    }
}