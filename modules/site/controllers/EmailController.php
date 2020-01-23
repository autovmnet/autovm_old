<?php

namespace app\modules\site\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

use app\models\UserEmail;
use app\modules\site\filters\OnlyUserFilter;

class EmailController extends Controller
{
    public function behaviors()
    {
        return [
            OnlyUserFilter::className(),
        ];
    }
    
    public function actionIndex()
    {
        $emails = UserEmail::find()->where(['user_id' => Yii::$app->user->id])->orderBy('id DESC');
        
        $count = clone $emails;
        $pages = new Pagination(['totalCount' => $count->count()]);
        $pages->setPageSize(5);
        
        $emails = $emails->offset($pages->offset)->limit($pages->limit)->all();
        
        return $this->render('index', [
            'emails' => $emails,
            'pages' => $pages,
        ]);
    }
    
    public function actionCreate()
    {
        $model = new UserEmail;
        
        $model->setKey();
        $model->user_id = Yii::$app->user->id;
        $model->is_primary = UserEmail::IS_NOT_PRIMARY;
        $model->is_confirmed = UserEmail::IS_NOT_CONFIRMED;
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            if ($model->save(false)) {
                Yii::$app->session->addFlash('success', Yii::t('app', 'New email has been created'));
                
                return $this->redirect(['/site/email/index']);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionPrimary($id)
    {
        $email = UserEmail::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->one();
        
        if (!$email) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }
        
        $transaction = Yii::$app->db->beginTransaction();
        
        try {
            $update = UserEmail::updateAll(['is_primary' => UserEmail::IS_NOT_PRIMARY], 'user_id = :id', [':id' => Yii::$app->user->id]);
            
            if (!$update) {
                throw new \Exception('Cannot save emails');
            }
            
            $email->is_primary = UserEmail::IS_PRIMARY;
            
            if (!$email->save(false)) {
                throw new \Exception('Cannot save email');
            }
            
            $transaction->commit();
            
            Yii::$app->session->addFlash('success', Yii::t('app', 'Your primary email has been changed'));
            
        } catch (\Exception $e) {
            $transaction->rollBack();
        }
        
        return $this->redirect(['/site/email/index']);
    }
    
    public function actionSend($id)
    {
        $email = UserEmail::find()->where(['user_id' => Yii::$app->user->id, 'id' => $id])->unconfirmed()->one();
        
        if (!$email) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }
        
        $url = Yii::$app->urlManager->createAbsoluteUrl(['site/email/confirm', 'key' => $email->key]);
        
        Yii::$app->mailer->compose('email-confirmation', ['url' => $url])
            ->setTo($email->email)
            ->setSubject(Yii::t('app', 'Email Confirmation'))
            ->send();
            
        Yii::$app->session->addFlash('success', Yii::t('app', 'Please check your email address'));
        
        return $this->redirect(['/site/email/index']);
    }
    
    public function actionConfirm($key) 
    {
        $email = UserEmail::find()->where(['key' => $key])->unconfirmed()->one();
        
        if (!$email) {
            throw new NotFoundHttpException(Yii::t('app', 'Not found anything'));
        }
        
        $email->is_confirmed = UserEmail::IS_CONFIRMED;
        
        if ($email->save(false)) {
            Yii::$app->session->addFlash('success', Yii::t('app', 'Your email has been confirmed'));
        }
        
        return $this->redirect(['/site/email/index']);
    }
}