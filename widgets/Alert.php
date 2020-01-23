<?php

namespace app\widgets;

use Yii;
use yii\base\Widget;

class Alert extends Widget
{
    public function run()
    {
        $data = Yii::$app->session->getAllFlashes();

        return $this->render('alert', [
            'data' => $data,
        ]);
    }
}