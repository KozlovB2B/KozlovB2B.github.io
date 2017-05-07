<?php

namespace app\modules\integration\modules\amo\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\user\models\UserHeadManager;

/**
 * Class AmoUserSearch
 * @package app\modules\integration\modules\amo\models
 */
class AmoUserSearch extends AmoUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'head_id', 'created_at'], 'integer'],
            [['amouser', 'subdomain', 'amohash'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = AmoUser::find();

        $hm = UserHeadManager::findHeadManagerByUser();

        $query->andWhere('head_id = ' . $hm->id);
        $query->andWhere('user_id != ' . $hm->id);

        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }
}
