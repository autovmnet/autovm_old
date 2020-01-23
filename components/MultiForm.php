<?php

namespace app\components;

use Yii;

class MultiForm
{
    protected $models = [];
    protected $errors = [];
    
    public function __set($name, $model)
    {
        $this->models[$name] = $model;
    }
    
    public function __get($name)
    {
        if (isset($this->models[$name])) {
            return $this->models[$name];
        }
        
        return null;
    }
    
    public function validate($data)
    {
        $valid = true;
        
        foreach ($this->models as $name => $model) {
            $model->load($data);
        } 
        
        foreach ($this->models as $name => $model) {
            if (!$model->validate()) {
                $valid = false;
                $this->errors[$name] = $model->getErrors();
            }
        }
        
        return $valid;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}