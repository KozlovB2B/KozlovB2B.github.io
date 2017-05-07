<?php

namespace app\modules\script\models\query;

use yii\db\ActiveQuery;
use app\modules\script\models\ar\Node;

/**
 * This is the ActiveQuery class for [[Node]].
 *
 * @see app\modules\script\models\ar\Node
 */
class NodeQuery extends ActiveQuery
{
    /**
     * @param $script_id
     * @return $this
     */
    public function byScript($script_id)
    {
        return $this->andWhere('script_id = :script_id', [':script_id' => $script_id]);
    }

    /**
     * @param $id
     * @return $this
     */
    public function byId($id)
    {
        return $this->andWhere('id = :id', [':id' => $id]);
    }

    /**
     * @param $number
     * @return $this
     */
    public function byNumber($number)
    {
        return $this->andWhere('number = :number', [':number' => $number]);
    }

    /**
     * @inheritdoc
     * @return Node[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Node|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
