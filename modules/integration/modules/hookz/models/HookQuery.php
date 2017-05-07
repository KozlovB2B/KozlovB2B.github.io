<?php

namespace app\modules\integration\modules\hookz\models;

/**
 * This is the ActiveQuery class for [[Hook]].
 *
 * @see Hook
 */
class HookQuery extends \yii\db\ActiveQuery
{
    /**
     * @param $head_id
     * @return $this
     */
    public function byHead($head_id)
    {
        return $this->andWhere('head_id=:head_id', [':head_id' => $head_id]);
    }

    /**
     * @param $event
     * @return $this
     */
    public function byEvent($event)
    {
        return $this->andWhere('event=:event', [':event' => $event]);
    }

    /**
     * @inheritdoc
     * @return Hook[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Hook|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
