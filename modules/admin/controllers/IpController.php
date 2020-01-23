<?php

namespace app\modules\admin\controllers;

use app\models\VpsIp;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use app\models\Log;
use app\models\Ip;
use app\models\Server;
use app\modules\admin\filters\OnlyAdminFilter;
use app\models\searchs\searchIp;

class IpController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }

    public function actionIndex()
    {
        $searchModel = new searchIp();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->request->post('id')) {
            // instantiate your book model for saving
            $IpId = Yii::$app->request->post('id');
            $model = Ip::findOne($IpId);
            //var_dump(Yii::$app->request->post());exit;
            // store a default json response as desired by editable
            $model->mac_address = $_POST['value'];
            $model->save(false);
            echo 1;
            return;
        }



        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionCreate($id)
    {
        $server = Server::findOne($id);

        if (!$server) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        $model = new Ip;
		$model->server_id = $id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->to=$_POST['Ip']['to'];
            //var_dump($model->to);exit;
            if($model->to)
            {
                $from_slice =explode('.',$model->ip);
                $to_slice =explode('.',$model->to);
                if(count($from_slice)!=4 || count($to_slice)!=4)
                {
                    $model->addError('ip','incorrect ip or range');
                    return $this->render('create', [
                        'server' => $server,
                        'model' => $model,
                    ]);
                }
                if (!($to_slice[0]==$from_slice[0] && $to_slice[1]==$from_slice[1] && $to_slice[2]==$from_slice[2]))
                {
                    $model->addError('ip','ip  range error');
                    return $this->render('create', [
                        'server' => $server,
                        'model' => $model,
                    ]);
                }
                if($to_slice[3] <= $from_slice[3])
                {
                    $model->addError('to','range error : to must bigger than from');
                    return $this->render('create', [
                        'server' => $server,
                        'model' => $model,
                    ]);
                }
                for ($k=$from_slice[3];$k<=$to_slice[3];$k++)
                {
                    $model = new Ip();
                    $model->server_id = $id;
                    $model->load(Yii::$app->request->post());
                    $model->ip = $to_slice[0].'.'.$to_slice[1].'.'.$to_slice[2].'.'.strval($k);
                    $model->save();

                }
                Yii::$app->session->addFlash('success', Yii::t('app', 'Your new ip/ips has been created'));
                return $this->refresh();

            }
            if ($model->save(false)) {

                Yii::$app->session->addFlash('success', Yii::t('app', 'Your new ip/ips has been created'));

                return $this->refresh();
            }
        }

        return $this->render('create', [
            'server' => $server,
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $model = Ip::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'Ip has been edited'));

				return $this->refresh();
            }
        }

        return $this->render('edit', compact('model'));
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->post('data');
        
        foreach ($data as $id) {
         
            $ip = Ip::find()->where(['id' => $id])->one();
            
            if ($ip) {
             
                $deleted = $ip->delete();
                
                if ($deleted) {
                    Log::log(sprintf('Ip %s was deleted by %s', $ip->ip, Yii::$app->user->identity->fullName));   
                }
            }
        }
        
        return $this->redirect(Yii::$app->request->referrer);
    }
}
