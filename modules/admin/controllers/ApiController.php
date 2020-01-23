<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Api;
use app\models\ApiLog;
use app\modules\admin\filters\OnlyAdminFilter;

class ApiController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }
    
    public function actionIndex()
    {
        $apis = Api::find()->orderBy('id DESC');
        
        $count = clone $apis;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(10);
        
        $apis = $apis->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('index', [
            'apis' => $apis,
            'pages' => $pages,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new Api;
        $model->setKey();
        $model->save(false);
       
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionLog($id)
    {
        $api = Api::findOne($id);
        
        if (!$api) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        } 
        
        $logs = ApiLog::find()->where(['api_id' => $id])->orderBy('id DESC');
        
        $count = clone $logs;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(10);
        
        $logs = $logs->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('log', [
            'api' => $api,
            'logs' => $logs,
            'pages' => $pages,
        ]);
    }
    
    public function actionDelete()
    {
        if (($data = Yii::$app->request->post('data')) && is_array($data)) {
            foreach ($data as $id) {
                Api::findOne($id)->delete();
            }
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
}