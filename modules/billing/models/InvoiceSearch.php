<?php
namespace app\modules\billing\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * InvoiceSearch represents the model behind the search form about User.
 */
class InvoiceSearch extends Model
{
    /** @var int */
    public $id;

    /** @var int */
    public $amount;

    /** @var int */
    public $status_id;

    /** @var string */
    public $username;

    /** @var integer */
    public $created_at;


    /** @inheritdoc */
    public function rules()
    {
        return [
            'fieldsSafe' => [['id', 'amount', 'status_id', 'username', 'created_at'], 'safe']
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Invoice::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {

            $this->status_id = Invoice::INVOICE_STATUS_IN_PROCESS;

            $query->andFilterWhere(['billing_invoice.status_id' => $this->status_id]);

            return $dataProvider;
        }

        $query->andFilterWhere(['billing_invoice.id' => $this->id]);
        $query->andFilterWhere(['billing_invoice.amount' => $this->amount]);
        $query->andFilterWhere(['billing_invoice.status_id' => $this->status_id]);

        if ($this->created_at) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'billing_invoice.created_at', $date, $date + 3600 * 24]);
        }

        $query->joinWith(['user' => function (ActiveQuery $q) {
            $q->andFilterWhere(['like', 'user.username', $this->username]);
        }]);

        return $dataProvider;
    }
}
