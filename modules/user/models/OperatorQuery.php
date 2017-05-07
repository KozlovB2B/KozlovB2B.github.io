<?php

namespace app\modules\user\models;

use app\modules\user\models\profile\Operator;
use yii\db\ActiveQuery;

/**
 * Class OperatorQuery
 * @package app\modules\user\models
 */
class OperatorQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function orderDesc()
    {
        return $this->orderBy(Operator::tableName() . '.id DESC');
    }

    /**
     * @param $head_id
     * @return $this
     */
    public function byHead($head_id)
    {
        return $this->andWhere(Operator::tableName() . '.head_id=:head_id', [":head_id" => $head_id]);
    }

    /**
     * @inheritdoc
     * @return Operator[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Operator|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}