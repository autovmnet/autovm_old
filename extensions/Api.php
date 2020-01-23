<?php

namespace app\extensions;

use yii\helpers\ArrayHelper;

class Api
{
    const ACTION_INSTALL = '/install';
    const ACTION_START = '/start';
    const ACTION_STOP = '/stop';
    const ACTION_SUSPEND = '/suspend';
    const ACTION_DELETE = '/delete';
    const ACTION_RESTART = '/reboot';
    const ACTION_STATUS = '/status';
    const ACTION_BANDWIDTH = '/bandwidth';
    const ACTION_MONITOR = '/usage';
    const ACTION_EXTEND = '/extend';
    const ACTION_CONSOLE = '/console';
    const ACTION_ALL = '/all-status';
    const ACTION_STEP = '/step';
    const ACTION_CHECK = '/validate';
    const ACTION_DS = '/storage-list';
    const ACTION_CREATE_SHOT = '/create-shot';
    const ACTION_REVERSE_SHOT = '/revert-shot';
    const ACTION_UPDATE = '/update';
    const ACTION_LOG = '/log';

    protected $url,
              $data,
              $timeout;

    public $result;
    
    public $host;
    
    public function __construct($data = null)
    {
        $this->data = $data;
    }


    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setTimeout($time)
    {
        $this->timeout = $time;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function request($action)
    {
        $c = curl_init();

        $this->host = ArrayHelper::getValue($this->data, 'server.server_address');
        $this->host = trim($this->host);

        curl_setopt($c, CURLOPT_URL, $this->host.$action);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);

        if ($time = $this->getTimeout()) {
            curl_setopt($c, CURLOPT_TIMEOUT, $time);
        } else {
            curl_setopt($c, CURLOPT_TIMEOUT, 3600);
        }

        curl_setopt($c, CURLOPT_FOLLOWLOCATION, true);

        if ($this->data) {
            curl_setopt($c, CURLOPT_POST, true);
            curl_setopt($c, CURLOPT_POSTFIELDS, $this->buildParams($this->data));
        }
        
        $result = curl_exec($c);
        $result = @json_decode($result);
            
        curl_close($c);

        $this->result = $result;

        if (empty($result->ok)) {
            return false;
        }
        
        if (empty($result->data)) {
            return $result;   
        }

        return $result->data;
    }

    public function buildParams($params)
    {
        return http_build_query($params);
    }
}