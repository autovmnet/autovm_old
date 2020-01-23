<?php

namespace app\modules\site\models\forms;

use Yii;
use yii\base\Model;

use app\models\User;
use app\models\UserEmail;

class LostPasswordForm extends Model
{
    public $email;
    public $verifyCode;

    private $_user;

    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'validateEmail'],
            ['verifyCode', 'captcha', 'captchaAction' => '/site/default/captcha'],
        ];
    }

    public function validateEmail()
    {
        if(!$this->getUser()) {
            $this->addError('email', Yii::t('app', 'The email that you entered is incorrect'));
        }
    }

    public function getUser()
    {
        if($this->_user) {
            return $this->_user;
        }

        $email = UserEmail::find()->where(['email' => $this->email])->primary()->one();

        if($email) {
            $this->_user = User::find()->where(['id' => $email->user_id])->active()->one();
        }

        return $this->_user;
    }
}
