<?php

namespace app\modules\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\blog\models\TourMenu;

/**
 * TourMenuSearch represents the model behind the search form about `app\modules\blog\models\TourMenu`.
 */
class TourMenuSearch extends TourMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority', 'tour_id'], 'integer'],
            [['division', 'link_text'], 'safe'],
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
        $query = TourMenu::find();

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
            'priority' => $this->priority,
            'tour_id' => $this->tour_id,
        ]);

        $query->andFilterWhere(['like', 'division', $this->division])
            ->andFilterWhere(['like', 'link_text', $this->link_text]);

        return $dataProvider;
    }
}
