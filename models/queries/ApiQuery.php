<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\Api]].
 *
 * @see \app\models\Api
 */
class ApiQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Api[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Api|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}