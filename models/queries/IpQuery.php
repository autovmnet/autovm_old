<?php

namespace app\models\queries;

use app\models\Ip;

/**
 * This is the ActiveQuery class for [[\app\models\Ip]].
 *
 * @see \app\models\Ip
 */
class IpQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Ip[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Ip|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
	
	public function isPublic()
	{
		$this->andWhere(['is_public' => Ip::IS_PUBLIC]);
		
		return $this;
	}
}