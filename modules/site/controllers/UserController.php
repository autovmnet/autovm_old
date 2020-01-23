<?php

namespace app\modules\site\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;

use app\models\Vps;
use app\models\Server;
use app\models\User;
use app\models\UserLogin;
use app\models\UserPassword;
use app\modules\site\filters\OnlyUserFilter;
use app\extensions\Api;
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyUserFilter::className(),
        ];
    }
    
    public function actionIndex()
    {
        $virtualServers = Vps::find()->where(['user_id' => Yii::$app->user->id])->active()->orderBy('id DESC');
        /*$cache = Yii::$app->cache->get('cache.server.uptime.user-'.Yii::$app->user->id);
        if(!$cache || Yii::$app->request->get('refresh',false) == true){
        	$servers = Server::find()->joinWith('vps')->onCondition(['user_id' => Yii::$app->user->id])->all();
            foreach ($servers as $server) {
        	    $data = [
        		'server' => $server->getAttributes(),
        	    ];
        	    $api = new Api;
        	    $api->setUrl(Yii::$app->setting->api_url);
        	    $api->setData($data);
        	    
        	    $result = $api->request(Api::ACTION_ALL);
        			
        	    if ($result){
        		$virtualServers2 = Vps::find()->where(['server_id' => $server->id])->all();
        		foreach ($virtualServers2 as $vps) {
        		    $ip = $vps->ip->ip;
        				
        		    $status = stripos($result->data, $ip);
        		    if ($status) {
        			$vps->power = Vps::ONLINE;
        		    } else {
        			$vps->power = Vps::OFFLINE;
        		    }
        		    $vps->save(false);
        		}
                }
            }
            $cache = Yii::$app->cache->set('cache.server.uptime.user-'.Yii::$app->user->id, true, 180);
        }*/
        $count = clone $virtualServers;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(5);
        
        $virtualServers = $virtualServers->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('index', [
            'virtualServers' => $virtualServers,
            'pages' => $pages,
        ]);
    }
    
    public function actionLogin()
    {
        $logins = UserLogin::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC');
        
        $count = clone $logins;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(5);
        
        $logins = $logins->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('login', [
            'logins' => $logins,
            'pages' => $pages,
        ]);
    }
    
    public function actionPassword()
    {
        $model = new UserPassword;
        $model->user_id = Yii::$app->user->id;
		$model->setScenario(UserPassword::SCENARIO_CHANGE_PASSWORD);
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->setPassword($model->password);
            
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Your password has been changed'));
                
                return $this->refresh();
            }
        }
        
        return $this->render('password', [
            'model' => $model,
        ]);
    }
    
    public function actionProfile()
    {
        $model = clone Yii::$app->user->identity;
        $model->setScenario(User::SCENARIO_PROFILE);
        
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Your profile informations has been changed'));
            
            return $this->refresh();
        }
        
        return $this->render('profile', [
            'model' => $model,
        ]);
    }
}