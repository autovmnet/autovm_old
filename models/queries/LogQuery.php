<?php

namespace app\models\queries;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Log]].
 *
 * @see \app\models\Log
 */
class LogQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return \app\models\Log[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Log|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
