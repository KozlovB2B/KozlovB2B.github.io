<?php

namespace app\modules\integration\modules\amo\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[AmoUser]].
 *
 * @see AmoUser
 */
class AmoUserQuery extends ActiveQuery
{
    /**
     * @param $amohash
     * @return AmoUser
     */
    public static function byHash($amohash)
    {
        return AmoUser::find()->where('amohash=:amohash', ['amohash' => $amohash])->one();
    }

    /**
     * @inheritdoc
     * @return AmoUser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AmoUser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
