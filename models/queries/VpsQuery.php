<?php

namespace app\models\queries;

use app\models\Vps;

/**
 * This is the ActiveQuery class for [[\app\models\Vps]].
 *
 * @see \app\models\Vps
 */
class VpsQuery extends \yii\db\ActiveQuery
{
    public function active()
    {
        $this->andWhere(['status' => Vps::STATUS_ACTIVE]);
        return $this;
    }

    public function owner($id)
    {
        return $this->andWhere(['server_id' => $id]);
    }

    /**
     * @inheritdoc
     * @return \app\models\Vps[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Vps|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
