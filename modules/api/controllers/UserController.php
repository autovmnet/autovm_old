<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;

use app\models\User;
use app\models\UserEmail;
use app\models\UserPassword;
use app\modules\api\filters\Auth;
use app\modules\api\components\Status;

class UserController extends Controller
{
	public function behaviors()
	{
		return [
			Auth::className(),
		];
	}
	
	public function actionInfo()
	{
		$email = UserEmail::find()->where(['email' => Yii::$app->request->post('email')])->one();
		
		if (!$email) {
			return ['ok' => false, 'status' => Status::NOT_FOUND];
		}
		
		return [
			'ok' => true,
			'id' => $email->user->id,
			'firstName' => $email->user->first_name,
			'lastName' => $email->user->last_name,
			'email' => $email->email,
		];
	}
	
	public function actionCreate()
	{
		$request = Yii::$app->request;
		
		$transaction = Yii::$app->db->beginTransaction();
		
		try {
			$user 	= new User;
			$email 	= new UserEmail;
			$pass 	= new UserPassword;
		
			$user->first_name = $request->post('firstName');
			$user->last_name = $request->post('lastName');
			$user->is_admin = User::IS_NOT_ADMIN;
			$user->setAuthKey();
		
			if (!$user->save(false)) {
				throw new \Exception('Cannot save user');
			}
			
			$email->user_id = $user->id;
			$email->email = $request->post('email');
			$email->setKey();
			
			if (!$email->save(false)) {
				throw new \Exception('Cannot save user email');
			}
			
			$pass->user_id = $user->id;
			$pass->setPassword($request->post('password'));
			
			if (!$pass->save(false)) {
				throw new \Exception('Cannot save user password');
			}
			
			$transaction->commit();
			
			return [
				'ok' => true,
				'id' => $user->id,
				'firstName' => $user->first_name,
				'lastName' => $user->last_name,
				'email' => $email->email,
			];
			
		} catch (\Exception $e) {
			$transaction->rollBack();
			
			return ['ok' => false, 'e' => $e->getMessage(), 'status' => Status::ERROR_SYSTEM];
		}
	}
}
