<?php

namespace app\modules\blog\models;

use app\modules\core\components\Publishable;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Post]].
 *
 * @see Post
 */
class PostQuery extends ActiveQuery
{
    /**
     * Search only published
     *
     * @return $this
     */
    public function published()
    {
        $this->andWhere('[[status_id]] = :published', ['published' => Publishable::STATUS_PUBLISHED]);
        return $this;
    }

    /**
     * Search post for current division
     *
     * @return $this
     */
    public function forCurrentDivision()
    {
        $this->andWhere('[[division]]=:division', ['division' => Yii::$app->params['division']]);
        return $this;
    }

    /**
     * @inheritdoc
     * @return Post[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Post|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}