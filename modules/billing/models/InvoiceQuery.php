<?php

namespace app\modules\billing\models;

/**
 * This is the ActiveQuery class for [[Invoice]].
 *
 * @see Invoice
 */
class InvoiceQuery extends \yii\db\ActiveQuery
{
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
     * @return Invoice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Invoice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}