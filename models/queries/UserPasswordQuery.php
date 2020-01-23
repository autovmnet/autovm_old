<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\UserPassword]].
 *
 * @see \app\models\UserPassword
 */
class UserPasswordQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\UserPassword[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\UserPassword|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}