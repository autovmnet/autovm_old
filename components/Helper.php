<?php

namespace app\components;

use Yii;
use yii\base\Component;

class Helper extends Component
{
    public function randomString($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz1234567890';

        $result = '';
        for($i=0; $i<$length; $i++) {
            $result .= $chars[mt_rand($i, strlen($chars) -1)];
        }

        return $result;
    }
    
    public function calcTime($seconds)
    {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds - ($days * 86400)) / 3600);
        $mins = floor(($seconds - (($days * 86400) + ($hours * 3600))) / 60);
        $secs = floor($seconds - (($days * 86400) + ($hours * 3600) + ($mins * 60))); 
        
        return [
            'days' => $days, 
            'hours' => $hours, 
            'mins' => $mins,
            'secs' => $secs,
        ];
    }
}