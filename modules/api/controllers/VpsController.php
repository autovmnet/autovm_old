<?php

namespace app\modules\api\controllers;

use Yii;
use yii\web\Controller;
use app\extensions\Api;

use app\models\Log;
use app\models\Ip;
use app\models\Os;
use app\models\User;
use app\models\UserEmail;
use app\models\Vps;
use app\models\VpsIp;
use app\models\VpsAction;
use app\models\Server;
use app\models\Plan;
use app\models\Bandwidth;
use app\models\Datastore;
use app\modules\api\filters\Auth;
use app\modules\api\components\Status;

class VpsController extends Controller
{
    public function behaviors()
    {
        return [
            Auth::className(),
        ];
    }

    public function actionCheck()
    {
        $request = Yii::$app->request;

        $server = Server::findOne($request->post('serverId'));
        $datastore = Datastore::findOne($request->post('datastoreId'));
        $plan = Plan::findOne($request->post('planId'));

        $result['ok'] = true;
        $result['server'] = ($server ? true : false);
        $result['datastore'] = ((empty($request->post('datastoreId')) || $datastore) ? true : false);

        if ($request->post('planId')) {
            $result['plan'] = ($plan ? true : false);
        } else {
            $result['plan'] = true;
        }

        return $result;
    }

    public function actionInfo()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        return [
            'ok' => true,
            'id' => $vps->id,
            'ip' => $vps->ip->ip,
            'vps' => $vps,
            'plan' => $vps->plan,
            'os' => $vps->os,
	    'url' => Yii::$app->urlManager->createAbsoluteUrl(['/admin/vps/view', 'id' => $vps->id]),
        ];
    }

    public function actionAccess()
	{
		$vps = Vps::findOne(Yii::$app->request->post('id'));

		if (!$vps) {
			return ['ok' => false];
		}

		$url = Yii::$app->urlManager->createAbsoluteUrl(['/site/vps/access', 'key' => $vps->user->auth_key, 'id' => $vps->id]);

		return ['ok' => true, 'url' => $url];
	}

    public function actionAdminAccess()
    {
        $email = UserEmail::find()->where(['email' => Yii::$app->request->post('email')])->one();

        if (!$email) {
            return ['ok' => false];
        }

        $user = User::find()->where(['id' => $email->user_id])->active()->one();

        if (!$user) {
            return ['ok' => false];
        }

        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false];
        }

        $url = Yii::$app->urlManager->createAbsoluteUrl(['/admin/vps/access', 'key' => $user->auth_key, 'id' => $vps->id]);

        return ['ok' => true, 'url' => $url];
    }

    public function actionOs()
    {
        $os = Os::find()->all();

        if (!$os) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        $data = [];

        foreach ($os as $item) {
            $data[$item->id] = $item->name;
        }

        return [
            'ok' => true,
            'data' => $data,
        ];
    }

    public function findBestServer($servers)
    {
        $request = Yii::$app->request;

        foreach ($servers as $id) {
            $server = Server::findOne($id);

            if ($server) {

                $ip = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')->andWhere('vps_ip.id IS NULL')->andWhere(['ip.server_id' => [$server->id, $server->parent_id]])->isPublic();

                if ($net = $request->post('networkType')) {
                    $ip->andWhere(['ip.network' => $net]);
                }

                $ip = $ip->one();

                if ($ip) {
                    return $id;
                }
            }
        }
    }

    public function actionCreate()
    {
	    require_once Yii::getAlias('@app/extensions/jdf.php');

        $request = Yii::$app->request;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $serverId = $request->post('serverId');
            $datastoreId = $request->post('datastoreId');

            $serverId = explode(',', $serverId);
            $serverId = $this->findBestServer($serverId);

            $ipId = $request->post('ipId');
            $ip = Ip::find()->where(['id' => $ipId])->one();

            if ($ipId) {
                if (!$ip) {
                    throw new \Exception('Cannot found ip');
                }
            }
 
            //if ($ip) {
            //    $server = Server::findOne($ip->server_id);
            //} else {
            //    $server = Server::findOne($serverId);
            //}

            $server = Server::findOne($serverId);

            if (!$server) {
                throw new \Exception('Cannot found server');
            }

            $ip = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
                ->andWhere('vps_ip.id IS NULL')
                ->andWhere(['ip.server_id' => [$server->id, $server->parent_id]])
                ->isPublic();

            if ($net = $request->post('networkType')) {
                $ip->andWhere(['ip.network' => $net]);
            }

            if ($ipId = $request->post('ipId')) {
                $ip->andWhere(['ip.id' => $ipId]);
            }

            $ip = $ip->one();

            if (!$ip) {
                throw new \Exception('Cannot found ip');
            }

            $vpsIp = VpsIp::find()->where(['ip_id' => $ip->id])->one();

            if ($vpsIp) {
                throw new \Exception('Mistake to find available ip');
            }

            if (!$datastoreId) {
                //$datastoreId = Datastore::findAvailable($server->id);
                //$datastoreId = Datastore::find()->select('id')->where(['server_id' => $server->id])->column();
                $datastoreId = Datastore::findBest($server->id);
            }

            if (!$datastoreId) {
                throw new \Exception('Cannot found datastore');
            }

            $osId = $request->post('osId');
            $osName = $request->post('osName');

            if ($osId) {
                $os = Os::find()->where(['id' => $osId])->one();
            }

            if ($osName) {
                $os = Os::find()->where(['name' => $osName])->one();
            }

            $vps = new Vps;

            $vps->user_id = $request->post('userId');
            $vps->server_id = $server->id;
            $vps->datastore_id = $datastoreId;

            if (isset($os)) {
                $vps->os_id = $os->id;
            }

            $hostName = $request->post('hostname');

            if ($hostName) {
                $vps->hostname = $hostName;
            }

            $vps->password = $request->post('password');

            if ($planId = $request->post('planId')) {
                $vps->plan_id = $planId;
                $vps->plan_type = VpsPlansTypeDefault;
            } else {
		        $vps->plan_type = VpsPlansTypeCustom;
                $vps->vps_ram = $request->post('vpsRam');
                $vps->vps_cpu_mhz = $request->post('vpsCpuMhz');
                $vps->vps_cpu_core = $request->post('vpsCpuCore');
                $vps->vps_hard = $request->post('vpsHard');
                $vps->vps_band_width = $request->post('vpsBandwidth');
            }

	        $vps->reset_at = date('j');

            if (!$vps->save(false)) {
                throw new \Exception('Cannot save vps');
            }

            $vpsIp = new VpsIp;

            $vpsIp->ip_id = $ip->id;
            $vpsIp->vps_id = $vps->id;

            if (!$vpsIp->save(false)) {
                throw new \Exception('Cannot save vps ip');
            }

            # Installing
            if ($request->post('install')) {

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

                $api = new Api;
                $api->setUrl(Yii::$app->setting->api_url);
                $api->setData($data);

                $result = $api->request(Api::ACTION_INSTALL);

                if (!$result) {
                    throw new \Exception('Cannot install os');
                }
            }

            $transaction->commit();

            return [
                'ok' => true,
                'id' => $vps->id,
                'ip' => $ip->ip,
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();

            return ['ok' => false, 'e' => $e->getMessage(), 'status' => Status::ERROR_SYSTEM];
        }
    }

    public function actionInstall()
    {
        if (!($password = Yii::$app->request->post('password')) || !($vpsId = Yii::$app->request->post('id'))) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        $vps = Vps::find()->where(['id' => $vpsId])->active()->one();

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        #$datastore = Datastore::find()->where(['server_id' => $vps->server->id])->defaultScope()->one();
        #
        #if (!$datastore) {
        #    return ['ok' => false, 'status' => Status::NOT_FOUND];
        #}

        $os = Os::findOne(Yii::$app->request->post('os'));

        if (!$os) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        $vps->os_id = $os->id;
        $vps->password = $password;

        if (!$vps->save(false)) {
            return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
        }

        // raw password
        $vps->password = $password;

        $data = [
            'ip' => $vps->ip->getAttributes(),
            'vps' => $vps->getAttributes(),
            'os' => $vps->os->getAttributes(),
            'datastore' => $vps->datastore->getAttributes(),
            #'defaultDatastore' => $datastore->getAttributes(),
            'server' => $vps->server->getAttributes(),
        ];

        if ($vps->plan) {
            $data['plan'] = $vps->plan->getAttributes();
        }

        $api = new Api;
        $api->setUrl(Yii::$app->setting->api_url);
        $api->setData($data);

        $result = $api->request(Api::ACTION_INSTALL);

        if ($result) {
            return ['ok' => true];
        }

        return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
    }

    public function actionResetBandwidth()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        $a = Bandwidth::find()->where(['vps_id' => $vps->id])->orderBy('id DESC')->limit(1)->one();
	    $b = Bandwidth::find()->where(['vps_id' => $vps->id])->orderby('id DESC')->limit(1)->offset(1)->one();

	    if ($a && $b) {
    	    $a->used = $a->pure_used = 0;
    	    $a->save(false);

    	    $b->used = $b->pure_used = 0;
    	    $b->save(false);
	    }

        return ['ok' => true];
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;

        $vps = Vps::findOne($request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($planId = $request->post('planId')) {
                $vps->plan_id = $planId;
                $vps->plan_type = VpsPlansTypeDefault;
            } else {
		        $vps->plan_type = VpsPlansTypeCustom;
                $vps->vps_ram = $request->post('ram');
                $vps->vps_cpu_mhz = $request->post('cpuMhz');
                $vps->vps_cpu_core = $request->post('cpuCore');
                $vps->vps_hard = $request->post('hard');
                $vps->vps_band_width = $request->post('bandwidth');
            }

            if (!$vps->save(false)) {
                throw new \Exception('Cannot save vps');
            }

            $data = [
                'ip' => $vps->ip->getAttributes(),
                'vps' => $vps->getAttributes(),
                'server' => $vps->server->getAttributes(),
                'datastore' => $vps->datastore->getAttributes(),
            ];

            if ($vps->plan) {
                $data['plan'] = $vps->plan->getAttributes();
            }

            $api = new Api;
            $api->setUrl(Yii::$app->setting->api_url);
            $api->setData($data);

            $result = $api->request(Api::ACTION_UPDATE);

            if (!$result) {
                throw new \Exception('Cannot update vps');
            }

            $transaction->commit();

            return ['ok' => true];

        } catch (\Exception $e) {
            $transaction->rollBack();

            return ['ok' => false, 'e' => $e->getMessage(), 'status' => Status::ERROR_SYSTEM];
        }
    }

    public function actionActive()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $vps->status = Vps::STATUS_ACTIVE;

            if (!$vps->save(false)) {
                throw new \Exception('Cannot save vps');
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
                throw new \Exception('Cannot start vps');
            }

            $action = new VpsAction;

            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_UNSUSPEND;
            $action->description = 'whmcs';

            $action->save(false);

            $transaction->commit();

            return ['ok' => true];

        } catch (\Exception $e) {
            $transaction->rollBack();

            return ['ok' => false, 'e' => $e->getMessage(), 'status' => Status::ERROR_SYSTEM];
        }
    }

    public function actionInactive()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            $vps->status = Vps::STATUS_INACTIVE;

            if (!$vps->save(false)) {
                throw new \Exception('Cannot save vps');
            }

            $data = [
                'ip' => $vps->ip->getAttributes(),
                'vps' => $vps->getAttributes(),
                'server' => $vps->server->getAttributes(),
            ];

            $api = new Api;
            $api->setUrl(Yii::$app->setting->api_url);
            $api->setData($data);

            $result = $api->request(Api::ACTION_SUSPEND);

            if (!$result) {
                throw new \Exception('Cannot stop vps');
            }

            $action = new VpsAction;

            $action->vps_id = $vps->id;
            $action->action = VpsAction::ACTION_SUSPEND;
            $action->description = 'whmcs';

            $action->save(false);

            $transaction->commit();

            return ['ok' => true];

        } catch (\Exception $e) {
            $transaction->rollBack();

            return ['ok' => false, 'e' => $e->getMessage(), 'status' => Status::ERROR_SYSTEM];
        }
    }

    public function actionStart()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
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
            return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
        }

        return ['ok' => true];
    }

    public function actionStop()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
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
            return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
        }

        return ['ok' => true];
    }

    public function actionRestart()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
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
            return ['ok' => false, 'status' => Status::ERROR_SYSTEM];
        }

        return ['ok' => true];
    }

    public function actionDelete()
    {
        $vps = Vps::findOne(Yii::$app->request->post('id'));

        if (!$vps) {
            return ['ok' => false, 'status' => Status::NOT_FOUND];
        }

        if (Yii::$app->setting->terminate <= 1) {

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

        }

        $vps->delete();

        Log::log(sprintf('Vps %s was deleted by whmcs', $vps->ip->ip));

        return ['ok' => true];
    }
}
