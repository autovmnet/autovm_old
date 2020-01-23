<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\Log;
use app\modules\admin\filters\OnlyAdminFilter;
use yii\data\ActiveDataProvider;

class LogController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyAdminFilter::className(),
        ];
    }

    public function actionIndex()
    {
        $logs = Log::find()->orderBy('id DESC');

        $dataProvider = new ActiveDataProvider([
              'query' => $logs,
              'pagination' => [
                'pageSize' => 10,
              ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}
