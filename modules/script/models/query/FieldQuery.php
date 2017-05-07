<?php

namespace app\modules\script\models\query;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\modules\script\models\ar\Field]].
 *
 * @see \app\modules\script\models\ar\Field
 */
class FieldQuery extends ActiveQuery
{
    /**
     * @param $account_id
     * @return $this
     */
    public function byAccount($account_id)
    {
        return $this->andWhere('account_id=:account_id', [':account_id' => $account_id]);
    }

    /**
     * @inheritdoc
     * @return \app\modules\script\models\ar\Field[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\script\models\ar\Field|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}