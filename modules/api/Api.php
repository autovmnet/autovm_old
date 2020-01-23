<?php

namespace app\modules\api;

use Yii;
use yii\base\Module;
use yii\web\Response;

class Api extends Module
{
	public function init()
	{
		parent::init();
		
		Yii::$app->request->enableCsrfValidation = false;
		
		Yii::$app->response->format = Response::FORMAT_JSON;
	}
}