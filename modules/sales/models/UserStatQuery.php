<?php

namespace app\modules\sales\models;
use app\modules\core\components\ActiveQuery;

/**
 * This is the ActiveQuery class for [[SalesUserStat]].
 *
 * @see SalesUserStat
 */
class UserStatQuery extends ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return UserStat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return UserStat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}