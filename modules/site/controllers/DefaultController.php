<?php

namespace app\modules\site\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\db\Query;

use app\models\LostPassword;
use app\models\UserPassword;
use app\modules\site\models\forms\LoginForm;
use app\modules\site\filters\OnlyGuestFilter;
use app\modules\site\models\forms\LostPasswordForm;

class DefaultController extends Controller
{
    public $defaultAction = 'login';

    public function behaviors()
    {
        return [
            [
                'class' => OnlyGuestFilter::className(),
                'only' => ['login', 'lost-password'],
            ],
        ];
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'height' => 40,
                'minLength' => 3,
                'maxLength' => 5,
                'foreColor' => 0xCCCCCC,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionLang($id)
    {
        $session = Yii::$app->getSession();

        $session->set('language', $id);

        return $this->goHome();
    }

    public function actionLogin()
    {
        $model = new LoginForm;

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->refresh();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLostPassword()
    {
        $model = new LostPasswordForm;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $lost = new LostPassword;
            $lost->user_id = $model->getUser()->id;
            $lost->status = LostPassword::STATUS_UNSUCCESSFUL;
            $lost->setKey();
            $lost->setExpireDate();

            if ($lost->save(false)) {

                // creating reset password url
                $url = Yii::$app->urlManager->createAbsoluteUrl(['/site/default/reset', 'key' => $lost->key]);

                // send email
                $mailer = Yii::$app->mailer->compose('lost-password', ['url' => $url]);
                $mailer->setSubject(Yii::t('app', 'Lost Password'));
                $mailer->setTo($model->email);
                $mailer->send();

                // set success message
                Yii::$app->session->addFlash('success', Yii::t('app', 'Please check your email address'));

                return $this->refresh();
            }
        }

        return $this->render('lost-password', [
            'model' => $model,
        ]);
    }

    public function actionReset($key)
    {
        $lost = LostPassword::find()->where(['key' => $key])->unsuccessful()->one();

        if (!$lost) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }

        $model = new UserPassword;
        $model->setScenario(UserPassword::SCENARIO_RESET_PASSWORD);
        $model->user_id = $lost->user_id;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // set new password
            $model->setPassword($model->password);

            if ($model->save(false)) {
                $lost->status = LostPassword::STATUS_SUCCESSFUL;
                $lost->save(false);

                // set success message
                Yii::$app->session->addFlash('success', Yii::t('app', 'Your password has been changed'));

                return $this->redirect(['/site/default/login']);
            }
        }

        return $this->render('reset', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return Yii::$app->response->redirect(Url::to(['/']));
    }
}
