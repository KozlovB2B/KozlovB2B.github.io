<?php

namespace app\modules\script\models;

/**
 * This is the ActiveQuery class for [[Call]].
 *
 * @see Call
 */
class CallQuery extends \yii\db\ActiveQuery
{
    /**
     * Find all active user scripts criteria
     *
     * @param integer $user_id
     * @return $this
     */
    public function byUser($user_id)
    {
        $this->andWhere('[[user_id]]=:user_id', [":user_id" => $user_id]);
        $this->orderBy("[[id]] DESC");

        return $this;
    }

    /**
     * @param integer $account_id
     * @return $this
     */
    public function byAccount($account_id)
    {
        $this->andWhere('[[account_id]]=:account_id', [":account_id" => $account_id]);
        $this->orderBy("[[id]] DESC");

        return $this;
    }


    /**
     * @param integer $id
     * @return $this
     */
    public function byId($id)
    {
        $this->andWhere('[[id]]=:id', [":id" => $id]);

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