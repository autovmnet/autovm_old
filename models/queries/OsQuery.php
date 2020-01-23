<?php

namespace app\models\queries;

use app\models\Os;

/**
 * This is the ActiveQuery class for [[\app\models\Os]].
 *
 * @see \app\models\Os
 */
class OsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    public function active()
    {
        $this->andWhere(['status' => Os::STATUS_ACTIVE]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return \app\models\Os[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Os|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
