<?php

namespace app\models\queries;

use app\models\LostPassword;

/**
 * This is the ActiveQuery class for [[\app\models\LostPassword]].
 *
 * @see \app\models\LostPassword
 */
class LostPasswordQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\LostPassword[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\LostPassword|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function unsuccessful()
    {
        $this->andWhere(['status' => LostPassword::STATUS_UNSUCCESSFUL]);
        return $this;
    }
}