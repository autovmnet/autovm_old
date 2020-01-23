<?php

namespace app\models\queries;

use app\models\Datastore;

/**
 * This is the ActiveQuery class for [[\app\models\Datastore]].
 *
 * @see \app\models\Datastore
 */
class DatastoreQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \app\models\Datastore[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\models\Datastore|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
    
    public function defaultScope()
    {
        $this->andWhere(['is_default' => Datastore::IS_DEFAULT]);
        
        return $this;
    }
}