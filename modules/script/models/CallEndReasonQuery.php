<?php

namespace app\modules\script\models;

/**
 * This is the ActiveQuery class for [[Call]].
 *
 * @see Call
 */
class CallEndReasonQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function orderDesc()
    {
        $this->orderBy("[[id]] DESC");
        return $this;
    }

    /**
     * @return $this
     */
    public function active()
    {
        $this->andWhere('[[deleted_at]] IS NULL');
        return $this;
    }

    /**
     * @param integer $account_id
     * @return $this
     */
    public function byAccount($account_id)
    {
        $this->andWhere('[[account_id]]=:account_id', [":account_id" => 0]);


        return $this;
    }

    /**
     * @inheritdoc
     * @return Call[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Call|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}