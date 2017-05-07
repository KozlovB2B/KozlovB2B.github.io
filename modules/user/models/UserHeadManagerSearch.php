<?php
namespace app\modules\user\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Query;
use Yii;

/**
 * UserHeadManagerSearch represents the model behind the search form about User.
 */
class UserHeadManagerSearch extends Model
{
    /** @var int */
    public $id;

    /** @var string */
    public $username;

    /** @var string */
    public $phone;

    /** @var int */
    public $balance;

    /** @var string */
    public $email;

    /** @var integer */
    public $created_at;

    /** @var string */
    public $registration_ip;

    /** @var string */
    public $division;


    /** @inheritdoc */
    public function rules()
    {
        return [
            'fieldsSafe' => [['id', 'balance', 'phone', 'username', 'email', 'registration_ip', 'created_at', 'division'], 'safe'],
            'createdDefault' => ['created_at', 'default', 'value' => null]
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'username' => \Yii::t('user', 'Username'),
            'email' => \Yii::t('user', 'Email'),
            'created_at' => \Yii::t('user', 'Registration time'),
            'registration_ip' => \Yii::t('user', 'Registration ip'),
            'division' => Yii::t('site', 'Division'),
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = UserHeadManager::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);



        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

//        var_dump($this->id);exit;
        $query->andFilterWhere(['SiteUserHeadManager.id' => $this->id]);
        $query->andFilterWhere(['SiteUserHeadManager.division' => $this->division]);
        $query->andFilterWhere(['like', 'SiteUserHeadManager.phone', $this->phone]);


        // filter by country name
        $query->joinWith(['user' => function (ActiveQuery $q) {
            $q->andFilterWhere(['like', 'user.username', $this->username])
                ->andFilterWhere(['like', 'user.email', $this->email])
                ->andFilterWhere(['user.registration_ip' => $this->registration_ip]);

            if ($this->created_at !== null) {
                $date = strtotime($this->created_at);
                $q->andFilterWhere(['between', 'user.created_at', $date, $date + 3600 * 24]);
            }
        }]);

        // filter by country name
        $query->joinWith(['balance' => function (ActiveQuery $q) {
            $q->andFilterWhere(['billing_balance.balance' => $this->balance]);

        }]);

//        if ($this->created_at !== null) {
//            $date = strtotime($this->created_at);
//            $query->andFilterWhere(['between', 'user.created_at', $date, $date + 3600 * 24]);
//        }
//
//        $query->andFilterWhere(['like', 'user.username', $this->username])
//            ->andFilterWhere(['like', 'user.email', $this->email])
//            ->andFilterWhere(['user.registration_ip' => $this->registration_ip]);


        return $dataProvider;
    }
}
