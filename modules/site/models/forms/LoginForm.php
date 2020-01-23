<?php

namespace app\modules\site\models\forms;

use Yii;
use yii\base\Model;

use app\models\User;
use app\models\UserEmail;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $verifyCode;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword', 'skipOnError' => true],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            $valid = $user && $user->password->validatePassword($this->password);

            if (!$valid) {
                $this->addError($attribute, Yii::t('app', 'Incorrect email or password'));
            }

            if ($user && !$valid) {
                $user->saveLogin(false);
            }
        }
    }

    /**
     * Logs in a user using the provided email and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $email = UserEmail::find()->where(['email' => $this->email])->primary()->one();

            if($email) {
                $this->_user = User::find()->where(['id' => $email->user_id])->active()->one();
            }
        }

        return $this->_user;
    }
}
