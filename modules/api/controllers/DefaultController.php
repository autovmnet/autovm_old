<?php

namespace app\modules\api\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
	public function actionIndex()
	{
		exit('This is your api address, Enter it in hostname');
	}
}
