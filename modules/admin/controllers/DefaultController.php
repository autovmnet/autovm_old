<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Vps;
use app\models\User;
use app\models\Setting;
use app\models\UserLogin;
use app\models\VpsAction;
use app\models\Bandwidth;
use app\modules\admin\filters\OnlyAdminFilter;
use app\modules\admin\models\forms\SettingForm;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }
    
    public function actionIndex()
    {
        $stats = new \stdClass;
        
        $stats->totalVps = Vps::find()->count();
        $stats->totalUsers = User::find()->count();
        $stats->vpsActions = VpsAction::find()->orderBy('id DESC')->limit(6)->all();
		$stats->bandwidth = Bandwidth::find()->sum('pure_used');
		
		$stats->logins = UserLogin::find()->orderBy('id DESC')->limit(8)->all();
        
        return $this->render('index', compact('stats'));
    }
    
    public function actionLogin()
    {
        $logins = UserLogin::find()->orderBy('id DESC');
        
        $count = clone $logins;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(8);
        
        $logins = $logins->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('login', [
            'logins' => $logins,
            'pages' => $pages,
        ]);
    }
    
    public function actionSetting()
    {
        $model = new SettingForm;
        
        $model->setAttributes(Yii::$app->setting->all());
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            foreach ($model->getAttributes() as $key => $value) {
                $setting = Setting::find()->where(['key' => $key])->one();
                
                if ($setting) {
                    $setting->value = $value;
                    $setting->save(false);  
                }
            }
            
            return $this->refresh();
        } 
        
        return $this->render('setting', compact('model'));
    }
}