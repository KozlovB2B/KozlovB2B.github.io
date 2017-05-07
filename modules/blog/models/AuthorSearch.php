<?php

namespace app\modules\blog\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\blog\models\Author;

/**
 * AuthorSearch represents the model behind the search form about `app\modules\blog\models\Author`.
 */
class AuthorSearch extends Author
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['division', 'name', 'avatar', 'about'], 'safe'],
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
     * Creates data provider instance
     *
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Author::find()->active();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        return $dataProvider;
    }
}
