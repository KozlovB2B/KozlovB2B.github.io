<?php

namespace app\modules\blog\models;

use app\modules\core\components\Publishable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * PostSearch represents the model behind the search form about `app\modules\blog\models\Post`.
 */
class TagPostSearch extends TagPost
{

    /**
     * Creates data provider instance with search query applied
     *
     * @return ActiveDataProvider
     */
    public function popularTags()
    {
        $query = TagPost::find();

        $query->groupBy([TagPost::tableName() . '.tag_id']);

        $query->joinWith(['post' => function (ActiveQuery $q) {
            $q->andWhere(Post::tableName() . '.division = :division', ['division' => Yii::$app->params['division']]);
            $q->andWhere(Post::tableName() . '.status_id = :published', ['published' => Publishable::STATUS_PUBLISHED]);
        }]);

        $query->joinWith(['tag' => function (ActiveQuery $q) {
            $q->andWhere(Tag::tableName() . '.frequency > :threshold', ['threshold' => 0]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'tag.frequency' => SORT_DESC,
                ],
                'attributes' => [
                    'tag.frequency' => [
                        'asc' => [Tag::tableName() . '.frequency' => SORT_ASC],
                        'desc' => [Tag::tableName() . '.frequency' => SORT_DESC],
                    ],
                ]
            ],
            'pagination' => [
                'pageSize' => $query->count()
            ]
        ]);


        return $dataProvider;
    }

}
