<?php

namespace app\modules\api\controllers;
use app\models\Server;

use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;

use app\models\Ip;
use app\modules\api\filters\Auth;
use app\modules\api\components\Status;

class ServerController extends Controller
{
    public function behaviors()
    {
        return [
            Auth::className(),
        ];
    }
    
    public function actionIp()
    {
        $serverIds = Yii::$app->request->post('serverId');
        $serverIds = explode(',', $serverIds);
        
	$serverList = [];

	foreach ($serverIds as $id) {
		$server = Server::find()->where(['id' => $id])->one();

		if ($server) {
			$serverList[] = $server->id;
		}

		if ($server && $server->parent_id) {
			$serverList[] = $server->parent_id;
		}
	}


        $ips = Ip::find()->leftJoin('vps_ip', 'vps_ip.ip_id = ip.id')
                ->andWhere('vps_ip.id IS NULL')
                ->andWhere(['ip.server_id' => $serverList])
                ->orderBy('ip.id ASC')
                ->isPublic()
                ->all();

        //$ips = Ip::find()->where(['server_id' => $serverId])->all();
        
        return [
            'ok' => true, 
            'ips' => ArrayHelper::map($ips, 'id', 'ip'),
        ];
    }
}
