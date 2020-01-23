<?php

namespace app\components;

use Yii;
use yii\web\User as WebUser;

class User extends WebUser
{
    public function afterLogin($identity, $cookieBased, $duration)
    {
        $identity->saveLogin(true);
    }
}