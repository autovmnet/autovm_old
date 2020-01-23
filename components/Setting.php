<?php

namespace app\components;

use Yii;
use yii\base\Component;

use app\models\Setting as Model;

class Setting extends Component
{
    private $_settings = [];

    public function init()
    {
        $settings = Model::find()->all();

        foreach ($settings as $setting) {
            $this->_settings[$setting->key] = $setting->value;
        }
    }

    public function __get($key)
    {
        if (isset($this->_settings[$key])) {
            return $this->_settings[$key];
        }

        return false;
    }

    public function __set($key, $value)
    {
        $this->_settings[$key] = $value;
    }

    public function all()
    {
        return $this->_settings;
    }
}