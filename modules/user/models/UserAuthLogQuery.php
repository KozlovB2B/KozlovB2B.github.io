<?php

namespace app\modules\user\models;

/**
 * This is the ActiveQuery class for [[UserAuthLog]].
 *
 * @see UserAuthLog
 */
class UserAuthLogQuery extends \yii\db\ActiveQuery
{
    /**
     *
     *
     * @param $account_id
     * @return $this
     */
    public function byAccount($account_id)
    {
        $this->andWhere('[[account_id]]=:account_id', [":account_id" => $account_id]);

        return $this;
    }

    /**
     * @inheritdoc
     * @return UserAuthLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserAuthLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}