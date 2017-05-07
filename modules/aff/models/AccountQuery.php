<?php

namespace app\modules\aff\models;

use \yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Account]].
 *
 * @see Account
 */
class AccountQuery extends ActiveQuery
{
    /**
     * Find all active user scripts criteria
     *
     * @param integer $user_id
     * @return $this
     */
    public function activeByUserCriteria($user_id)
    {
        $this->with('user');
        $this->with('billing');
        $this->andWhere('[[affiliate_id]]=:user_id', [":user_id" => $user_id]);

        $this->orderBy("[[total_affiliate_earned]] DESC");

        return $this;
    }

    /**
     * @param $promo_code
     * @return Account
     */
    public function parent($promo_code)
    {
        $this->andWhere('[[promo_code]]=:promo_code', [":promo_code" => $promo_code]);
        return $this->one();
    }

    /**
     * @inheritdoc
     * @return Account[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Account|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}