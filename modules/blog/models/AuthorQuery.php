<?php

namespace app\modules\blog\models;

/**
 * This is the ActiveQuery class for [[Author]].
 *
 * @see Author
 */
class AuthorQuery extends \yii\db\ActiveQuery
{
    /**
     * @return $this
     */
    public function active()
    {
        $this->andWhere('[[deleted_at]] IS NULL');
        return $this;
    }

    /**
     * @inheritdoc
     * @return Author[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Author|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}