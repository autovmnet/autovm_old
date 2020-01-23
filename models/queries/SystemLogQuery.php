<?php

namespace app\models\queries;

/**
 * This is the ActiveQuery class for [[\app\models\SystemLog]].
 *
 * @see \app\models\SystemLog
 */
class SystemLogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\SystemLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\SystemLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}