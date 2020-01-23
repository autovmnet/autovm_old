<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class ErrorController extends Controller
{
    public function actionError()
    {
        return $this->renderPartial('error', [
            'exception' => Yii::$app->errorHandler->exception,
        ]);
    }
}