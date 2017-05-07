<?php

namespace app\modules\billing\models;

/**
 * This is the ActiveQuery class for [[BalanceOperations]].
 *
 * @see BalanceOperations
 */
class BalanceOperationsQuery extends \yii\db\ActiveQuery
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
        $this->andWhere('[[balance_id]]=' . $user);
        return $this;
    }

    /**
     * Only withdraw
     *
     * @return $this
     */
    public function withdraw()
    {
        return $this->andWhere('[[is_accrual]]=0');
    }

    /**
     * @inheritdoc
     * @return BalanceOperations[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return BalanceOperations|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}