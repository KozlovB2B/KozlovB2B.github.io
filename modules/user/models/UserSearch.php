<?php

namespace app\modules\user\models;

use app\modules\user\models\profile\ProfileRelation;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
    /** @var int */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $email;

    /** @var string */
    public $creator;

    /** @var string */
    public $profile;

    /** @var integer */
    public $created_at;

    /** @inheritdoc */
    public function rules()
    {
        return [
            'fieldsSafe' => [['id', 'username', 'profile', 'created_at', 'creator', 'email'], 'safe'],
            'createdDefault' => ['created_at', 'default', 'value' => null]
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creator' => 'Создатель',
            'username' => 'Логин',
            'profile' => 'Профиль',
            'email' => 'email',
            'created_at' => 'Дата создания',
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }


        $query->andFilterWhere(['user.id' => $this->id]);

        $query->andFilterWhere(['like', 'user.username', $this->username]);

        $query->andFilterWhere(['like', 'user.email', $this->email]);

        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }

        $query->joinWith(['profileRelation' => function (ActiveQuery $q) {
            $q->andFilterWhere([ProfileRelation::tableName() . '.profile_class' => $this->profile]);
        }]);

        $query->joinWith(['creator' => function (ActiveQuery $q) {
            if($this->creator == 'сам'){
                $q->andWhere('creator.username is null');
            }else if($this->creator){
                $q->andFilterWhere(['creator.username' => $this->creator]);
            }
        }]);


        return $dataProvider;
    }
}
