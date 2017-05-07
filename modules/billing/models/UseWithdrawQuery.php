<?php

namespace app\modules\billing\models;

/**
 * This is the ActiveQuery class for [[UseWithdraw]].
 *
 * @see UseWithdraw
 */
class UseWithdrawQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UseWithdraw[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UseWithdraw|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}