<?php

namespace app\modules\candy\controllers;

use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public $enableCsrfValidation = false;
    
    public function behaviors()
    {
        return ['app\filters\Format'];   
    }
    
    public function actionStep()
    {
        return $this->python('step.py');
    }
    
    public function actionLog()
    {
        return $this->python('log.py');   
    }
    
    public function actionInstall()
    {
        return $this->python('install.py', true);   
    }
    
    public function actionStart()
    {
        return $this->python('start.py');   
    }
    
    public function actionStop()
    {
        return $this->python('stop.py');   
    }
    
    public function actionReboot()
    {
        return $this->python('reboot.py');   
    }
    
    public function actionSuspend()
    {
        return $this->python('suspend.py');   
    }
    
    public function actionDelete()
    {
        return $this->python('delete.py');   
    }
    
    public function actionStatus()
    {
        return $this->python('status.py');   
    }
    
    public function actionCreateShot()
    {
        return $this->python('create_snapshot.py');   
    }
    
    public function actionRevertShot()
    {
        return $this->python('revert_snapshot.py');   
    }
    
    public function actionUsage()
    {
        return $this->python('usage.py');   
    }
    
    public function actionAllStatus()
    {
        return $this->python('all_status.py');
    }
    
    public function actionExtend()
    {
        return $this->python('extend.py');   
    }
    
    public function actionBandwidth()
    {
        return $this->python('bandwidth.py');   
    }
    
    public function actionStorageList()
    {
        return $this->python('storage_list.py');   
    }
    
    public function actionValidate()
    {
        return $this->python('validate.py');   
    }
    
    public function actionMount()
    {
        return $this->python('mount.py');   
    }
    
    public function actionUnmount()
    {
        return $this->python('unmount.py');   
    }
    
    public function actionUpdate()
    {
        return $this->python('update.py');   
    }
    
    public function actionConsole()
    {
        return $this->python('console.py');   
    }
    
    protected function python($python, $background = false)
    {
        $python = Yii::getAlias("@app/modules/candy/python/$python");
        
        $params = http_build_query($_POST);
        
        if ($background) {
            $command = ['nohup', 'python2.7'];   
        } else {
            $command = ['python2.7'];   
        }
        
        $append = [$python, $params];
        
        if ($background) {
            $append[] = '&';  
        }
        
        $command = escapeshellcmd(implode(' ', array_merge($command, $append)));
        
        # Execute command
        $result = shell_exec($command);
        
        if (empty($result)) {
            return ['ok' => false];   
        }
        
        return json_decode($result);
    }
}