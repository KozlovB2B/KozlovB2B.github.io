<?php

namespace app\modules\script\models\query;

use yii\db\ActiveQuery;
use app\modules\script\models\ar\Release;

/**
 * This is the ActiveQuery class for [[Release]].
 *
 * @see Release
 */
class ReleaseQuery extends ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Release[]
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Release
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
