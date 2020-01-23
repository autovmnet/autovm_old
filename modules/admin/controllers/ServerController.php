<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use stdClass;
use app\models\Log;
use app\models\Os;
use app\models\Server;
use app\models\Datastore;
use app\modules\admin\filters\OnlyAdminFilter;
use app\models\searchs\searchServer;

use app\extensions\Api;

class ServerController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }

    public function actionIndex()
    {
        $searchModel = new searchServer();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionView($id)
    {
        $server = Server::findOne($id);
        
        if (!$server) {
            throw new NotFoundHttpException;   
        }
        
        $data = ['server' => $server->getAttributes()];
        
        $api = new Api($data);
        
        $result = $api->request(Api::ACTION_CHECK);
                
        return $this->render('view', compact('server', 'api', 'result'));
    }

    public function actionCreate()
    {
        $model = new Server;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Your new server has been created'));

                return $this->refresh();
            }
        }

        return $this->render('create', compact('model'));
    }

    public function actionEdit($id)
    {
        $model = Server::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Server has been edited'));

                return $this->refresh();
            }
        }

        return $this->render('edit', compact('model'));
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->post('data');
        
        foreach ($data as $id) {
         
            $server = Server::find()->where(['id' => $id])->one();
            
            if ($server) {
                
                $deleted = $server->delete();
                
                if ($deleted) {
                    Log::log(sprintf('Server %s was deleted by %s', $server->ip, Yii::$app->user->identity->fullName));   
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }
}
