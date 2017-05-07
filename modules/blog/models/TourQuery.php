<?php

namespace app\modules\blog\models;

/**
 * This is the ActiveQuery class for [[Tour]].
 *
 * @see Tour
 */
class TourQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Tour[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Tour|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}