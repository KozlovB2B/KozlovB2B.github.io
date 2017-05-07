<?php

namespace app\modules\script\models\query;

use yii\db\ActiveQuery;
use app\modules\script\models\ar\Variant;

/**
 * This is the ActiveQuery class for [[Variant]].
 *
 * @see Variant
 */
class VariantQuery extends ActiveQuery
{
    /**
     * @param $target_id
     * @return $this
     */
    public function byTarget($target_id)
    {
        return $this->andWhere('target_id = :target_id', [':target_id' => $target_id]);
    }
    
    /**
     * @param $node_id
     * @return $this
     */
    public function byNode($node_id)
    {
        return $this->andWhere('node_id = :node_id', [':node_id' => $node_id]);
    }
    
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
     * @return Variant[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Variant|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
