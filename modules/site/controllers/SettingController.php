<?php

namespace app\modules\site\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;

use app\models\User;
use app\models\UserEmail;
use app\models\UserPassword;
use app\modules\site\filters\OnlyUserFilter;

class SettingController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyUserFilter::className(),  
        ];
    }
    
    public function actionIndex()
    {
        $user = clone Yii::$app->user->identity;
        $user->setScenario(User::SCENARIO_PROFILE);
        
        $password = new UserPassword;
        
        $email = new UserEmail;
        $email->email = Yii::$app->user->identity->email->email;
        
        return $this->render('index', compact('user', 'password', 'email'));
    }
    
    public function actionProfile()
    {
        $model = clone Yii::$app->user->identity;
        $model->setScenario(User::SCENARIO_PROFILE);
        
        $model->load(Yii::$app->request->post());
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            Yii::$app->session->addFlash('success', Yii::t('app', 'Done successfuly'));
        }
        
        return $this->redirect(['index']);
    }
    
    public function actionPassword()
    {
        $model = new UserPassword;
        
        $model->user_id = Yii::$app->user->id;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            $model->setPassword($model->password);
            
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Done successfuly'));   
            }
        }
        
        return $this->redirect(['index']);
    }
    
    public function actionEmail()
    {
        $model = new UserEmail;
        
        $model->setKey();
        $model->user_id = Yii::$app->user->id;
        $model->is_primary = UserEmail::IS_PRIMARY;
        $model->is_confirmed = UserEmail::IS_CONFIRMED;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            Yii::$app->session->addFlash('success', Yii::t('app', 'Done successfuly'));
        }
        
        return $this->redirect(['index']);
    }
}