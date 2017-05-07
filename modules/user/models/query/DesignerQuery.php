<?php

namespace app\modules\user\models\query;

use app\modules\user\models\profile\Designer;
use yii\db\ActiveQuery;

/**
 * Class DesignerQuery
 * @package app\modules\user\models
 */
class DesignerQuery extends ActiveQuery
{
    /**
     * @return $this
     */
    public function orderDesc()
    {
        return $this->orderBy(Designer::tableName() . '.id DESC');
    }

    /**
     * @param $head_id
     * @return $this
     */
    public function byHead($head_id)
    {
        return $this->andWhere(Designer::tableName() . '.head_id=:head_id', [":head_id" => $head_id]);
    }

    /**
     * @inheritdoc
     * @return Designer[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Designer|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}