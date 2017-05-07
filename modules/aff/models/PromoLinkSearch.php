<?php

namespace app\modules\aff\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\aff\models\PromoLink;

/**
 * PromoLinkSearch represents the model behind the search form about `app\modules\aff\models\PromoLink`.
 */
class PromoLinkSearch extends PromoLink
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'user_id', 'hits', 'money'], 'integer'],
            [['promo_code', 'host', 'query_string', 'url', 'utm_medium', 'utm_source', 'utm_campaign', 'utm_content', 'utm_term'], 'safe'],
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
        $query = PromoLink::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        $query->where('user_id=' . Yii::$app->getUser()->getId());
        $query->where('deleted_at IS NULL');


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'user_id' => $this->user_id,
            'hits' => $this->hits,
            'money' => $this->money,
        ]);

        $query->andFilterWhere(['like', 'promo_code', $this->promo_code])
            ->andFilterWhere(['like', 'host', $this->host])
            ->andFilterWhere(['like', 'query_string', $this->query_string])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'utm_medium', $this->utm_medium])
            ->andFilterWhere(['like', 'utm_source', $this->utm_source])
            ->andFilterWhere(['like', 'utm_campaign', $this->utm_campaign])
            ->andFilterWhere(['like', 'utm_content', $this->utm_content])
            ->andFilterWhere(['like', 'utm_term', $this->utm_term]);

        return $dataProvider;
    }
}
