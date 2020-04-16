<?php

namespace app\modules\admin\controllers;

use app\models\Ssh;
use app\models\VpsIp;
use app\extensions\Api;
use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

use app\models\Log;
use app\models\Ip;
use app\models\Os;
use app\models\Vps;
use app\models\User;
use app\models\Plan;
use app\models\Server;
use app\models\Bandwidth;
use app\models\VpsAction;
use app\models\Datastore;
use app\modules\admin\filters\OnlyAdminFilter;
use yii\data\ActiveDataProvider;
use app\models\searchs\searchVps;

class VpsController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => OnlyAdminFilter::className(),
                'except' => ['access'],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new searchVps();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionUpdate($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false, 'a' => true];
        }

        $data = [
            'server' => $vps->server->getAttributes(),
            'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
            'os' => $vps->os->getAttributes(),
            'datastore' => $vps->datastore->getAttributes(),
        ];

        if ($vps->plan) {
            $data['plan'] = $vps->plan->getAttributes();
        }
        
        if (!$vps->os) {
            return ['ok' => false];   
        }

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_UPDATE);

        if (!$result) {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionTerminate($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_DELETE);

        if (!$result) {
            return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
        }
        
        $vps->delete();
        
        Log::log(sprintf('Vps %s was terminated by %s', $vps->ip->ip, Yii::$app->user->identity->fullName));

        return ['ok' => true];
    }

	public function actionView($id)
	{
		$vps = Vps::findOne($id);

		if (!$vps) {
			throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
		}


        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id DESC')->one();

        $used_bandwidth = ($used_bandwidth ? $used_bandwidth->used : 0);

        $ips = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL')
            ->andWhere(['ip.server_id' => [$vps->server_id, $vps->server->parent_id]])
            ->all();

        $data = [
            'server' => $vps->server->getAttributes(),
            'vps' => $vps->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_LOG);

		return $this->render('view', [
			'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth,
            'ips' => $ips,
            'result' => $result,
            'os' => Os::find()->all(),
		]);
	}

    public function actionAccess($key, $id)
    {
        $user = User::find()->where(['auth_key' => $key, 'is_admin' => User::IS_ADMIN])->active()->one();

        if (!$user) {
            throw new NotFoundHttpException;
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        Yii::$app->user->login($user);

        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id DESC')->one();

        $used_bandwidth = ($used_bandwidth ? $used_bandwidth->used : 0);

        $ips = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL')
            ->andWhere(['ip.server_id' => $vps->server_id])
            ->all();

        $this->layout = 'access';

        return $this->render('access', [
            'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth,
            'ips' => $ips,
            'os' => Os::find()->all(),
        ]);
    }

    public function actionStep($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'server' => $vps->server->getAttributes(),
            'vps' => $vps->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_STEP);

        if (empty($result)) {
            return ['ok' => false];
        }

        return ['ok' => true, 'step' => $result->step, 'percent' => $result->percent];
    }

    public function actionInstall($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = (object) Yii::$app->request->post('data');

        // Os
        $os = Os::findOne($data->os);

        // Vps
        $vps = Vps::findOne($id);

		if (!$vps || !$os) {
			throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
		}

        $one = 'abcdefghijklmnopqrstuvwxyz';
        $two = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $three = '1234567890';

        $password = '';

        for ($i=0; $i<3; $i++) {
            $password .= $one[mt_rand(0, strlen($one)-1)];
        }

        for ($i=0; $i<3; $i++) {
            $password .= $two[mt_rand(0, strlen($two)-1)];
        }

        for ($i=0; $i<3; $i++) {
            $password .= $three[mt_rand(0, strlen($three)-1)];
        }

        $vps->os_id = $os->id;
        $vps->password = $password;
        $vps->save(false);

        // raw password
        $vps->password = $password;

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'os' => $vps->os->getAttributes(),
            'datastore' => $vps->datastore->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        if ($vps->plan) {
            $data['plan'] = $vps->plan->getAttributes();
        }
        
        $action = new VpsAction;
        
        $action->vps_id = $vps->id;
        $action->action = VpsAction::ACTION_INSTALL;
        $action->description = $vps->os->name;
        
        $action->save(false);

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $api->setTimeout(15);

        $result = $api->request(Api::ACTION_INSTALL);

        return ['ok' => true, 'password' => $password];
    }

    public function actionAdd($id)
    {
        $data = (object) Yii::$app->request->post('data');

        $vps = Vps::findOne($id);

        if (!$vps) {
            throw new ServerErrorHttpException;
        }

        $ip = Ip::findOne($data->ip);

        if (!$ip) {
            throw new ServerErrorHttpException;
        }

        $model = new VpsIp;

        $model->vps_id = $vps->id;
        $model->ip_id = $ip->id;

        $model->save(false);

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionDel($id)
    {
        $model = VpsIp::find()->where(['ip_id' => $id])->one();

        if (!$model) {
            throw new NotFoundHttpException;
        }

        $id = $model->vps_id;

        $model->delete();

        return $this->redirect(['view', 'id' => $id]);
    }

	public function actionResetBandwidth($id)
	{
		$vps = Vps::findOne($id);

		if ($vps) {

            $vps->notify_at = null;
            $vps->save(false);
            
		    $a = Bandwidth::find()->where(['vps_id' => $vps->id])->orderBy('id DESC')->limit(1)->one();
		    $b = Bandwidth::find()->where(['vps_id' => $vps->id])->orderby('id DESC')->limit(1)->offset(1)->one();

		    if ($a && $b) {
        	    $a->used = $a->pure_used = 0;
        	    $a->save(false);

        	    $b->used = $b->pure_used = 0;
        	    $b->save(false);
	        }
		}

		return $this->redirect(['/admin/vps/view', 'id' => $id]);
	}

    public function actionResetLimit($id)
    {
        $vps = Vps::findOne($id);

        if ($vps) {
            $vps->change_limit = 0;
            $vps->save(false);
        }

        return $this->redirect(['/admin/vps/view', 'id' => $id]);
    }

    public function actionCreate($id)
    {
        $model = new Vps();
        $model->user_id = $id;
        if(Yii::$app->request->isPost) {
            //print_r($_POST);exit;
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->plan_type = $_POST['Vps']['plan_type'];
                if($model->plan_type==VpsPlansTypeCustom)
                {
                    $model->vps_ram = isset($_POST['Vps']['vps_ram']) && intval($_POST['Vps']['vps_ram']) ? $_POST['Vps']['vps_ram'] : 0;
                    $model->vps_hard = isset($_POST['Vps']['vps_hard']) ? $_POST['Vps']['vps_hard'] : 0;
                    if($model->vps_hard <21)
                    {
                        $model->addError('vps_hard',Yii::t('app','hard size must be grater than 21'));
                        return $this->sharedRender($model);
                    }
                    $model->vps_cpu_core = $_POST['Vps']['vps_cpu_core'];
                    $model->vps_cpu_mhz = $_POST['Vps']['vps_cpu_mhz'];
                    $model->vps_band_width = $_POST['Vps']['vps_band_width'];
                    $model->plan_id = 0;
                    //var_dump($model);exit;

                }
                else
                {

                }
                $model->os_id=0;
                $model->password='';
                Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
                if ($model->save()) {
                    //delete recent vps_ip
                    $oldIp = VpsIp::find()->where(['vps_id' => $model->id])->one();
                    if ($oldIp) {
                        $oldIp->delete();
                    }
                    //add new vps_ip
                    $ip = new VpsIp;
                    $ip->vps_id = $model->id;
                    $ip->ip_id = $model->ip_id;
                    $ip->save();
                    Yii::$app->session->addFlash('success', Yii::t('app', 'Your new vps has been created'));

                    if ($model->view) {
                        return $this->redirect(['/admin/vps/view', 'id' => $model->id]);
                    } else {
                        return $this->refresh();
                    }
                }
                else {
                    var_dump($model->errors);
                    exit;
                }
            } else
            {
                var_dump($model->errors);exit;
            }

        }
        $model->plan_type = VpsPlansTypeDefault;
        return $this->sharedRender($model);
    }

    public function actionEdit($id)
    {
        $model = Vps::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }
        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->plan_type = $_POST['Vps']['plan_type'];
            if($model->plan_type==VpsPlansTypeCustom)
            {
                $model->vps_ram = $_POST['Vps']['vps_ram'];
                $model->vps_hard = $_POST['Vps']['vps_hard'];
                $model->vps_cpu_core = $_POST['Vps']['vps_cpu_core'];
                $model->vps_cpu_mhz = $_POST['Vps']['vps_cpu_mhz'];
                $model->vps_band_width = $_POST['Vps']['vps_band_width'];
                $model->plan_id = 0;
                //var_dump($model);exit;

            }
            if ($model->save()) {
                //delete recent vps_ip
                //$oldIp = VpsIp::find()->where(['vps_id' => $model->id])->one();
                //if ($oldIp) {
                //    $oldIp->delete();
                //}
                //add new vps_ip
                //$ip = new VpsIp;
                $ip = VpsIp::find()->where(['vps_id' => $model->id])->one();
                if ($ip) {
                    $ip->vps_id = $model->id;
                    $ip->ip_id = $model->ip_id;
                    $ip->save();
                }
                Yii::$app->session->addFlash('success', Yii::t('app', 'Vps has been edited'));
				//turn off vps
                if($model->status == Vps::STATUS_INACTIVE)
                {
                    $data = [
                        'ip' => $model->ip->getAttributes(),
                        'vps' => $model->getAttributes(),
                        'server' => $model->server->getAttributes(),
                    ];

                    $api = new Api();
                    $api->setUrl(Yii::$app->setting->api_url);
                    $api->setData($data);
                    $result = $api->request(Api::ACTION_SUSPEND);
                    if ($result) {
                        $action = new VpsAction;
                        $action->vps_id = $model->id;
                        $action->action = VpsAction::ACTION_SUSPEND;
                        $action->save(false);
                    }

                }
                else
                {
                    $data = [
                        'ip' => $model->ip->getAttributes(),
                        'vps' => $model->getAttributes(),
                        'server' => $model->server->getAttributes(),
                    ];

                    $api = new Api();
                    $api->setUrl(Yii::$app->setting->api_url);
                    $api->setData($data);
                    $result = $api->request(Api::ACTION_START);
                    if ($result) {
                        $action = new VpsAction;
                        $action->vps_id = $model->id;
                        $action->action = VpsAction::ACTION_START;
                        $action->save(false);
                    }
               }

                if ($model->view) {
                    return $this->redirect(['/admin/vps/view', 'id' => $model->id]);
                } else {
                    return $this->refresh();
                }

            }
        }

        return $this->sharedRender($model);
    }

    public function actionLog($id)
    {
        $vps = Vps::findOne($id);

        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        $logs = VpsAction::find()->where(['vps_id' => $vps->id])->orderBy('id DESC');

        $count = clone $logs;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(10);

        $logs = $logs->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('log', [
            'vps' => $vps,
            'logs' => $logs,
            'pages' => $pages,
        ]);
    }

    public function actionDatastores()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        $datastores = Datastore::find()->where(['server_id' => $id])->all();

        return ArrayHelper::map($datastores, 'id', 'value');
    }

    public function actionIps()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        $server = Server::findOne($id);

        if (!$server) {
            return ['ok' => false];
        }

        $parentId = $server->parent_id;

        $ips = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
            ->andWhere('vps_ip.id IS NULL')
            ->andWhere(['ip.server_id' => [$id, $parentId]])
            ->orderBy('ip.id ASC')
            ->all();

        return ArrayHelper::map($ips, 'id', 'ip');
    }

    public function actionDelete()
    {
        $data = Yii::$app->request->post('data');
        
        foreach ($data as $id) {
            
            $machine = Vps::find()->where(['id' => $id])->with('ip')->one();
            
            if ($machine) {
                
                $deleted = $machine->delete();
                
                if ($deleted) {
                    Log::log(sprintf('Vps %s was deleted by %s', ($machine->ip ? $machine->ip->ip : $machine->id), Yii::$app->user->identity->fullName));   
                }
            }
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    protected function sharedRender($model)
    {
        return $this->render($model->isNewRecord ? 'create' : 'edit', [
            'model' => $model,
            'plans' => Plan::find()->all(),
            'servers' => Server::find()->all(),
            'operationSystems' => Os::find()->all(),
        ]);
    }

    public function actionStart($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_START);

        if (!$result) {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionStop($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_STOP);

        if (!$result) {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionRestart($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_RESTART);

        if (!$result) {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_STATUS);

        if (!$result) {
            return ['ok' => false];
        }

        if ($result->power <> 'on') {
            return ['ok' => false];
        }

        return ['ok' => true];
    }

    public function actionAdvancedStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_STATUS);

        if (!$result) {
            return ['ok' => false];
        }

        $data = ['ok' => true];

        if ($result->power == 'on') {
            $data['power'] = 'Online';
        } else {
            $data['power'] = 'Offline';
        }

        if ($result->network == 'up') {
            $data['network'] = 'Up';
        } else {
            $data['network'] = 'Down';
        }

        return $data;
    }

    public function createPassword($chars)
    {
        $result = null;

        for($i=0; $i<3; $i++) {
            $result .= $chars[mt_rand(0, strlen($chars)-1)];
        }

        return $result;
    }

    public function actionConsole($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $port = mt_rand(Yii::$app->setting->from_port, Yii::$app->setting->to_port);

        $password = $this->createPassword('abcdefghijklmnopqrstuvwxyz') . $this->createPassword('ABCDEFGHIJKLMNOPQRSTUVWXYZ') . $this->createPassword('0123456789');

        $data = ['vps' => $vps->attributes, 'ip' => $vps->ip->attributes, 'server' => $vps->server->attributes, 'port' => $port, 'password' => $password];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $api->setTimeout(40);

        $result = $api->request(Api::ACTION_CONSOLE);
        
        if (!$result) {
            return ['ok' => false];   
        }

        $address = Url::base(true);
        $address = str_replace('https', 'http', $address);

        return ['ok' => true, 'address' => $address, 'port' => $port, 'password' => $result->password];
    }
}
