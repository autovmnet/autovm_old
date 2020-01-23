<?php

namespace app\modules\api\filters;

use Yii;
use yii\base\ActionFilter;

use app\models\Api;
use app\modules\api\components\Status;

class Auth extends ActionFilter
{
	public function beforeAction($action)
	{
		$api = Api::find()->where(['key' => Yii::$app->request->post('key')])->one();
		
		if (!$api) {
			Yii::$app->response->data = ['ok' => false, 'status' => Status::INVALID_API];
			Yii::$app->response->send();
		}
		
		return parent::beforeAction($action);
	}
}