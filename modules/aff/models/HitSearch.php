<?php

namespace app\modules\aff\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\aff\models\Hit;

/**
 * HitSearch represents the model behind the search form about `app\modules\aff\models\Hit`.
 */
class HitSearch extends Hit
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'user_id', 'link_id', 'device_type', 'has_registrations', 'bills', 'bills_paid', 'total_earned'], 'integer'],
            [['promo_code', 'query_string', 'utm_medium', 'utm_source', 'utm_campaign', 'utm_content', 'utm_term', 'ip', 'user_agent', 'browser_language', 'os', 'browser', 'ref'], 'safe'],
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
        $query = Hit::find();

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

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'link_id' => $this->link_id,
            'device_type' => $this->device_type,
            'has_registrations' => $this->has_registrations,
            'bills' => $this->bills,
            'bills_paid' => $this->bills_paid,
            'total_earned' => $this->total_earned,
        ]);

        $query->andFilterWhere(['like', 'promo_code', $this->promo_code])
            ->andFilterWhere(['like', 'query_string', $this->query_string])
            ->andFilterWhere(['like', 'utm_medium', $this->utm_medium])
            ->andFilterWhere(['like', 'utm_source', $this->utm_source])
            ->andFilterWhere(['like', 'utm_campaign', $this->utm_campaign])
            ->andFilterWhere(['like', 'utm_content', $this->utm_content])
            ->andFilterWhere(['like', 'utm_term', $this->utm_term])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent])
            ->andFilterWhere(['like', 'browser_language', $this->browser_language])
            ->andFilterWhere(['like', 'os', $this->os])
            ->andFilterWhere(['like', 'browser', $this->browser])
            ->andFilterWhere(['like', 'ref', $this->ref]);

        return $dataProvider;
    }
}
