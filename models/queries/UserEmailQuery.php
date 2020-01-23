<?php

namespace app\models\queries;

use app\models\UserEmail;

/**
 * This is the ActiveQuery class for [[\app\models\UserEmail]].
 *
 * @see \app\models\UserEmail
 */
class UserEmailQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\UserEmail[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\UserEmail|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function primary()
    {
        $this->andWhere(['is_primary' => UserEmail::IS_PRIMARY]);
        return $this;
    }

    public function confirmed()
    {
        $this->andWhere(['is_confirmed' => UserEmail::IS_CONFIRMED]);
        return $this;
    }
    
    public function unconfirmed()
    {
        $this->andWhere(['is_confirmed' => UserEmail::IS_NOT_CONFIRMED]);
        return $this;
    }
    
    public function descScope()
    {
        $this->orderBy(['id' => SORT_DESC]);
        
        return $this;
    }
}