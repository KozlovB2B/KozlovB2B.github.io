<?php

namespace app\modules\script\models\query;

use app\modules\core\components\Publishable;
use app\modules\script\models\ar\Script;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\script\models\ar\Script]].
 *
 * @see \app\modules\script\models\ar\Script
 */
class ScriptQuery extends ActiveQuery
{
    /**
     * By user
     *
     * @param integer $user_id
     * @return $this
     */
    public function byUser($user_id)
    {
        $this->andWhere('[[user_id]]=:user_id', [":user_id" => $user_id]);

        return $this;
    }

    /**
     * By ID
     *
     * @param integer $id
     * @return $this
     */
    public function byID($id)
    {
        $this->andWhere('[[id]]=:id', [":id" => $id]);

        return $this;
    }

    /**
     * Find all active user scripts criteria
     *
     * @param integer $user_id
     * @return $this
     */
    public function allByUserCriteria($user_id)
    {
        $this->andWhere('[[status_id]]!=:creating', [":creating" => Publishable::STATUS_CREATING]);
        $this->andWhere('[[user_id]]=:user_id', [":user_id" => $user_id]);
        $this->orderBy("[[id]] DESC");

        return $this;
    }

    /**
     * @param $account_id
     * @return $this
     */
    public function byAccount($account_id)
    {
        return $this->andWhere(Script::tableName() . '.user_id = :account_id', [":account_id" => $account_id]);
    }

    /**
     * @return $this
     */
    public function notConverted()
    {
        return $this->andWhere(Script::tableName() . '.v2converted = 0');
    }

    /**
     * @return $this
     */
    public function published()
    {
        return $this->andWhere(Script::tableName() . '.latest_release IS NOT NULL');
    }


    /**
     * @return $this
     */
    public function active()
    {
        return $this->andWhere(Script::tableName() . '.deleted_at IS NULL');
    }

    /**
     * Find all active user scripts criteria
     *
     * @param integer $user_id
     * @return $this
     */
    public function activeByUserCriteria($user_id)
    {
        $this->andWhere('[[user_id]]=:user_id', [":user_id" => $user_id]);
        $this->andWhere('[[deleted_at]] IS NULL');
        $this->orderBy("[[id]] DESC");

        return $this;
    }

    /**
     * Find all active user scripts criteria
     *
     * @param integer $user_id
     * @return $this
     */
    public function publishedByUserCriteria($user_id)
    {
        $this->andWhere('[[status_id]]=:published', [":published" => Publishable::STATUS_PUBLISHED]);
        $this->andWhere('[[user_id]]=:user_id', [":user_id" => $user_id]);
        $this->andWhere('[[deleted_at]] IS NULL');
        $this->orderBy("[[id]] DESC");

        return $this;
    }


    /**
     * @inheritdoc
     * @return Script[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Script|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
