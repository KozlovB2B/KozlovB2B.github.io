<?php

namespace app\modules\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\blog\models\Tour;

/**
 * TourSearch represents the model behind the search form about `app\modules\blog\models\Tour`.
 */
class TourSearch extends Tour
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status_id'], 'integer'],
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
        $query = Tour::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'heading', $this->heading])
            ->andFilterWhere(['like', 'teaser', $this->teaser])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
