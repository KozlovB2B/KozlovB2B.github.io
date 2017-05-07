<?php

namespace app\modules\script\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Script]].
 *
 * @see Script
 */
class NodeQuery extends ActiveQuery
{
    /**
     * Active nodes
     *
     * @return $this
     */
    public function active()
    {
        return $this->andWhere('deleted_at IS NULL');
    }

    /**
     * Find all active nodes by given script
     *
     * @param integer $script_id
     * @return $this
     */
    public function byScript($script_id)
    {
        return $this->andWhere('script_id=:script_id', [":script_id" => $script_id]);
    }
}