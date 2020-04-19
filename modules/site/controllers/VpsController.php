<?php

namespace app\modules\site\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\User;
use app\models\Os;
use app\models\Vps;
use app\models\Iso;
use app\extensions\Api;
use app\models\VpsAction;
use app\models\Bandwidth;
use app\models\Datastore;
use app\filters\LicenseFilter;
use app\modules\site\filters\OnlyUserFilter;

class VpsController extends Controller
{
    public function behaviors()
    {
        return [
            [
                'class' => OnlyUserFilter::className(),
                'except' => ['access'],
            ],
        ];
    }

    public function actionIndex($id)
    {
        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->active()->one();
        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id DESC')->one();

        $used_bandwidth = ($used_bandwidth ? $used_bandwidth->used : 0);

        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        return $this->renderAjax('index', [
            'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth
        ]);
    }

    public function actionAccess($id, $key)
    {
        $user = User::find()->where(['auth_key' => $key])->active()->one();

        if (!$user) {
            return false;
        }

        Yii::$app->user->login($user);

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->active()->one();
        $used_bandwidth = Bandwidth::find()->where(['vps_id'=>$id])->active()->orderBy('id DESC')->one();

        $used_bandwidth = ($used_bandwidth ? $used_bandwidth->used : 0);

        if (!$vps) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        $this->layout = 'access';

        return $this->render('access', [
            'vps' => $vps,
            'used_bandwidth'=>$used_bandwidth
        ]);
    }

    public function actionBandwidth()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $times = [];

        for ($i=10; $i>=0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $times[$date] = ['date' => $date, 'total' => 0];
        }

        $time = time() - (20*86400);

        $sql = "SELECT used as total, FROM_UNIXTIME(created_at, '%Y-%m-%d') as date FROM bandwidth WHERE vps_id = :id AND status = :status AND created_at >= $time";

        Yii::$app->db->createCommand('SET time_zone = "+03:30"')->execute();

        $result = Yii::$app->db->createCommand($sql);
        $result->bindValue(':id', $vpsId);
        $result->bindValue(':status', Bandwidth::STATUS_ACTIVE);
        $result = $result->queryAll();

        foreach ($result as $data) {
            if (isset($times[$data['date']])) {
                $times[$data['date']]['total'] = $data['total']; // mb
            }
        }

        return array_values($times);
    }

    public function actionSelectOs()
    {
        $id = Yii::$app->request->post('vpsId');


        $operationSystems = Os::find()->active()->orderBy('name', 'ASC')->all();

        return $this->renderAjax('select-os', [
            'operationSystems' => $operationSystems,
            'vpsId' => $id,
        ]);
    }

    public function actionLoadHost()
    {
        $id = Yii::$app->request->post('vpsId');

        $vps = Vps::findOne($id);

        return $this->renderAjax('load-host', compact('vps'));
    }

    public function actionChangeHost()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        if (empty($id)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $vps->hostname = Yii::$app->request->post('host');
        $vps->save(false);

        /*$data = [
    		'server' => $vps->server->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_CREATE_SHOT);

    	if (!$result) {
    		return ['ok' => false];
    	}*/

        return ['ok' => true];
    }

    public function actionOs($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $os = Os::findOne($id);

        if (!$os) {
            return ['ok' => false];
        }

        if (stripos($os->name, 'windows') !== false) {
            $status = 1;
        } else {
            $status = 2;
        }

        return ['ok' => true, 'status' => $status];
    }

    public function actionSelectShot()
    {
        $id = Yii::$app->request->post('vpsId');

        $vps = Vps::find()->where(['id' => $id, 'user_id' => Yii::$app->user->id])->one();

        return $this->renderAjax('select-shot', compact('id', 'vps'));
    }

    public function actionCreateShot()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        if (empty($id)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        if ($vps->getCannotSnapshot()) {
            return ['ok' => false];
        }

        $data = [
    		'server' => $vps->server->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_CREATE_SHOT);

    	if (!$result) {
    		return ['ok' => false];
    	}

        return ['ok' => true];
    }

    public function actionReverseShot()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        if (empty($id)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
    		'server' => $vps->server->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_REVERSE_SHOT);

    	if (!$result) {
    		return ['ok' => false];
    	}

        return ['ok' => true];
    }

    public function actionSelectIso()
    {
        $id = Yii::$app->request->post('vpsId');

        $items = Iso::find()->all();

        return $this->renderAjax('select-iso', [
            'items' => $items,
            'vpsId' => $id,
        ]);
    }

    public function actionIso()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');
        $iso = Yii::$app->request->post('iso');

        if (empty($id) || empty($iso)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $iso = Iso::findOne($iso);

        if (!$iso) {
            return ['ok' => false];
        }

        $datastore = Datastore::find()->where(['server_id' => $vps->server->id, 'is_default' => 1])->one();

        if (!$datastore) {
            return ['ok' => false];
        }

        $data = [
    		'server' => $vps->server->getAttributes(),
            'datastore' => $datastore->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'iso' => $iso->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_ISO);

    	if (!$result) {
    		return ['ok' => false];
    	}

        return ['ok' => true];
    }

    public function actionIsou()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        if (empty($id)) {
            return ['ok' => false];
        }

        $vps = Vps::findOne($id);

        if (!$vps) {
            return ['ok' => false];
        }

        $data = [
    		'server' => $vps->server->getAttributes(),
    		'vps' => $vps->getAttributes(),
            'ip' => $vps->ip->getAttributes(),
    	];

    	$api = new Api;
    	$api->setUrl(Yii::$app->setting->api_url);
    	$api->setData($data);

    	$result = $api->request(Api::ACTION_ISOU);

    	if (!$result) {
    		return ['ok' => false];
    	}

        return ['ok' => true];
    }

    public function actionStep($id)
    {
    	Yii::$app->response->format = Response::FORMAT_JSON;

    	$vps = Vps::find()->where(['id' => $id])->active()->one();

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

    public function actionInstall()
    {
        Yii::$app->session->set('password',  Yii::$app->request->post('password'));

        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($password = Yii::$app->request->post('password')) || !($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0, 'error' => 'password'];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0, 'error' => 'vps'];
        }

        $limit = Yii::$app->setting->change_limit;

        if ($limit <= $vps->change_limit) {
            return ['status' => 0, 'error' => 'limit'];
        }

        #$datastore = Datastore::find()->where(['server_id' => $vps->server->id])->defaultScope()->one();

        #if (!$datastore) {
        #    return ['status' => 0];
        #}

        $os = Os::findOne(Yii::$app->request->post('osId'));

        if (!$os) {
            return ['status' => 0, 'error' => 'os'];
        }

        // validate password
        if (!preg_match('/(?=.*[A-Z]+)(?=.*[a-z]+)(?=.*[0-9]+)/', $password)) {
            return ['status' => 1];
        }

        $vps->os_id = $os->id;
        $vps->password = $password;

        if (!$vps->save(false)) {
            return ['status' => 0, 'error' => 'save'];
        }

        // raw password
        $vps->password = $password;

        $extend = Yii::$app->request->post('extend');

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'os' => $vps->os->getAttributes(),
            'datastore' => $vps->datastore->getAttributes(),
            #'defaultDatastore' => $datastore->getAttributes(),
            'server' => $vps->server->getAttributes(),
            'extend' => $extend ? 1 : 2,
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

        $vps->change_limit += 1;
        $vps->save(false);

        return ['status' => 0, 'error' => 'none'];
    }

    public function actionExtendForm()
    {
        $vpsId = Yii::$app->request->post('vpsId');

        return $this->renderAjax('extend-form', compact('vpsId'));
    }

    public function actionExtend()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ((!$vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
        }
        
        if (!$vps->os) {
            return ['status' => 0];   
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'os' => $vps->os->getAttributes(),
            'server' => $vps->server->getAttributes(),
            'username' => Yii::$app->request->post('username'),
            'password' => Yii::$app->request->post('password'),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_EXTEND);

        if ($result) {
            return ['status' => 1];
        }

        return ['status' => 0];
    }

    public function actionConsoleForm()
    {
        $vpsId = Yii::$app->request->post('vpsId');
        $port = mt_rand(Yii::$app->setting->from_port, Yii::$app->setting->to_port);

        return $this->renderAjax('console-form', compact('port', 'vpsId'));
    }

    public function actionConsole()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if ((!$vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            //'os' => $vps->os->getAttributes(),
            'server' => $vps->server->getAttributes(),
            'password' => Yii::$app->request->post('password'),
            'port' => Yii::$app->request->post('port'),
        ];

        $api = new Api;

        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $api->setTimeout(40);

        $result = $api->request(Api::ACTION_CONSOLE);
                
        if (!$result) {
            return ['status' => 0];   
        }

        $address = Url::base(true);
        $address = str_replace('https', 'http', $address);

        return ['status' => 1, 'address' => $address, 'password' => $result->password];
    }

    public function actionStart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
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

        if (!empty($api->result->type) && $api->result->type == 'os') {
            return ['status' => 0, 'message' => Yii::t('app', 'OS was not found')];
        }

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_START;
            $action->save(false);

            return ['status' => 1];
        }

        return ['status' => 0];
    }

    public function actionStop()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
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

        if (!empty($api->result->type) && $api->result->type == 'os') {
            return ['status' => 0, 'message' => Yii::t('app', 'VM did not found, Please install an Operating System.')];
        }

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_STOP;
            $action->save(false);

            return ['status' => 1];
        }

        return ['status' => 0];
    }

    public function actionRestart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
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

        if (!empty($api->result->type) && $api->result->type == 'os') {
            return ['status' => 0, 'message' => Yii::t('app', 'VM did not found, Please install an Operating System.')];
        }

        if ($result) {
            $action = new VpsAction;
            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_RESTART;
            $action->save(false);

            return ['status' => 1];
        }

        return ['status' => 0];
    }

    public function actionStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return ['status' => 0];
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['status' => 0];
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
            return ['status' => 0];
        }

        if ($result->power == 'on') {
            return ['status' => 1];
        } else {
            return ['status' => 2];
        }
    }

    public function actionAdvancedStatus()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $userId = Yii::$app->user->id;
        $vpsId = Yii::$app->request->post('vpsId');

        $vps = Vps::find()->where(['user_id' => $userId, 'id' => $vpsId])->active()->one();

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

    public function actionMonitor()
    {
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return false;
        }

        $vps = Vps::find()->where(['user_id' => Yii::$app->user->id, 'id' => $vpsId])->active()->one();

        if (!$vps) {
            return false;
        }

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_MONITOR);

        if (!$result) {
            return false;
        }

        return $this->renderAjax('monitor', [
            'vps' => $vps,
            'ram' => $result->ram,
            'usedRam' => $result->usedRam,
            'cpu' => $result->cpu,
            'usedCpu' => $result->usedCpu,
            'uptime' => Yii::$app->helper->calcTime($result->uptime),
        ]);
    }

    public function actionActionLog()
    {
        if (!($vpsId = Yii::$app->request->post('vpsId'))) {
            return false;
        }

        $actions = VpsAction::find()->where(['vps_id' => $vpsId])->orderBy('id DESC');

        $count = clone $actions;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(5);

        $actions = $actions->offset($pages->offset)->limit($pages->limit)->all();

        return $this->renderAjax('action-log', [
            'actions' => $actions,
            'pages' => $pages,
            'vpsId' => $vpsId,
        ]);
    }
}
