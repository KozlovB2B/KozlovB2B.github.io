<?php

namespace app\modules\api\models;

/**
 * This is the ActiveQuery class for [[ApiUser]].
 *
 * @see ApiUser
 */
class ApiUserQuery extends \yii\db\ActiveQuery
{
    /**
     * @param $login
     * @return $this
     */
    public function byLogin($login)
    {
        $this->andWhere('[[user_login]]=:login', [":login" => $login]);
        return $this;
    }

    /**
     * @param $account
     * @return $this
     */
    public function byAccount($account)
    {
        return $this->andWhere('[[account_id]]=:account', [":account" => $account]);
    }
}