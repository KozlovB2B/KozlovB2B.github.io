<?php

namespace app\modules\billing\models;

/**
 * This is the ActiveQuery class for [[BillingRateChangeHistory]].
 *
 * @see BillingRateChangeHistory
 */
class BillingRateChangeHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * All operations by user
     *
     * @param $user
     * @return $this
     */
    public function allByUser($user)
    {
        $this->andWhere('[[account_id]]=' . $user);
        return $this;
    }

    /**
     * @inheritdoc
     * @return BillingRateChangeHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BillingRateChangeHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}