<?php

namespace app\modules\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * PostSearch represents the model behind the search form about `app\modules\blog\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'user_id', 'author_id', 'status_id', 'published_at', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['division', 'heading', 'teaser', 'content'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'type_id' => $this->type_id,
            'user_id' => $this->user_id,
            'author_id' => $this->author_id,
            'status_id' => $this->status_id,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'heading', $this->heading])
            ->andFilterWhere(['like', 'teaser', $this->teaser])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }

    /**
     * Returns data provider for blog
     *
     * @param string $tag
     * @return ActiveDataProvider
     */
    public function blogSearch($tag = null)
    {
        $query = Post::find()->published()->forCurrentDivision();

        if ($tag) {
            $query->joinWith(['tag']);
            $query->andFilterWhere(['like', Tag::tableName() . '.name', urldecode($tag)]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'published_at' => SORT_DESC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
