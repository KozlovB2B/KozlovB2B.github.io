<?php

namespace app\modules\aff\models;

/**
 * This is the ActiveQuery class for [[PayoutRequest]].
 *
 * @see PayoutRequest
 */
class PayoutRequestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PayoutRequest[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PayoutRequest|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}