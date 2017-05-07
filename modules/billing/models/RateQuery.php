<?php

namespace app\modules\billing\models;

/**
 * This is the ActiveQuery class for [[Rate]].
 *
 * @see Rate
 */
class RateQuery extends \yii\db\ActiveQuery
{
    /**
     * Active
     *
     * @return $this
     */
    public function active()
    {
        $this->andWhere('[[archived_at]] IS NULL');
        $this->andWhere('[[deleted_at]] IS NULL');
        return $this;
    }

    /**
     * Current default
     *
     * @return $this
     */
    public function currentDefault()
    {
        $this->andWhere('[[is_default]]=1');
        return $this;
    }

    /**
     * Current default
     *
     * @return $this
     */
    public function free()
    {
        $this->andWhere('[[monthly_fee]]=0');
        return $this;
    }

    /**
     * Current default
     *
     * @return $this
     */
    public function forCurrentDivision()
    {
        return $this->forDivision(\Yii::$app->params['division']);
    }

    /**
     * For division
     *
     * @param string $division
     * @return $this
     */
    public function forDivision($division)
    {
        $this->andWhere('division = :d', [':d' => $division]);
        $this->forAll();
        return $this;
    }

    /**
     * For all
     *
     * @return $this
     */
    public function forAll()
    {
        return $this->andWhere('user_id IS NULL');
    }

    /**
     * For user
     *
     * @param int $user_id
     * @return $this
     */
    public function forUser($user_id)
    {
        return $this->andWhere('user_id = :d', [':d' => $user_id]);
    }

    /**
     * @inheritdoc
     * @return Rate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Rate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}