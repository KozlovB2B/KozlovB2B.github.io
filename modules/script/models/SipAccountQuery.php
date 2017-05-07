<?php

namespace app\modules\script\models;

/**
 * This is the ActiveQuery class for [[SipAccount]].
 *
 * @see SipAccount
 */
class SipAccountQuery extends \yii\db\ActiveQuery
{
    public function byAccount($account_id)
    {
        return $this->andWhere('[[account_id]]=:account_id', [':account_id' => $account_id]);
    }

    /**
     * @inheritdoc
     * @return SipAccount[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return SipAccount|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
