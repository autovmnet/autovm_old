<?php

namespace app\models\queries;

use app\models\Bandwidth;

/**
 * This is the ActiveQuery class for [[\app\models\Bandwidth]].
 *
 * @see \app\models\Bandwidth
 */
class BandwidthQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Bandwidth[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Bandwidth|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function owner($id)
    {
        return $this->andWhere(['vps_id' => $id]);
    }

    public function descending()
    {
        return $this->orderBy('id DESC');
    }

	public function active()
	{
		$this->andWhere(['status' => Bandwidth::STATUS_ACTIVE]);

		return $this;
	}
}
