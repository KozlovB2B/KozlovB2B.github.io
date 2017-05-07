<?php
namespace app\modules\script\components;


use app\modules\script\models\ar\Node;
use app\modules\script\models\ar\VariantsReportAggregate;
use app\modules\script\models\Call;
use app\modules\user\models\UserHeadManager;
use yii\data\ActiveDataProvider;
use Yii;
use yii\db\ActiveRecord;
use app\modules\script\models\ar\Script;


class VariantsReport extends ActiveRecord
{
    /** @var string */
    public $script_id;
    public $node_id;

    public $variant_name;


    public $duration_from;
    public $duration_to;

    /** @var integer */
    public $is_goal_reached;
    public $normal_ending;

    public $total_count;

    public $started_at;

    /**
     * @return Yii\db\ActiveQuery
     */
    public function getCall()
    {
        return $this->hasOne(Call::className(), ['id' => 'call_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return VariantsReportAggregate::tableName();
    }

    public static function total($provider, $fieldName)
    {
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        return $total;
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            'fieldsSafe' => [['node_id', 'script_id', 'is_goal_reached', 'normal_ending', 'started_at', 'duration_from', 'duration_to'], 'safe'],
            'fieldsBoolean' => [['is_goal_reached', 'normal_ending'], 'boolean']
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'node_id' => Yii::t('script', 'Node'),
            'script_id' => Yii::t('script', 'Script'),
            'is_goal_reached' => Yii::t('script', 'Aim reached'),
            'normal_ending' => Yii::t('script', 'Call script quality control'),
            'started_at' => Yii::t('script', 'Period'),
            'total_count' => Yii::t('script', 'Total hits'),
        ];
    }

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = VariantsReport::find()->joinWith(['call']);

        $tn = VariantsReport::tableName();

        $call_tn = Call::tableName();

        $query->groupBy([$tn . '.variant_id']);

        $query->select([
            $tn . '.variant_id as variant_name',
            'count(*) as total_count'
        ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);


        if (!($this->load(Yii::$app->request->get()) && $this->validate())) {
            return $dataProvider;
        }

        if ($this->node_id) {
            $node = Node::findOne($this->node_id);
            if ($node->script_id != $this->script_id) {
                $this->node_id = null;
            }
        }

        $query->andFilterWhere([$tn . '.script_id' => $this->script_id]);
        $query->andFilterWhere([$tn . '.node_id' => $this->node_id]);
        $query->andFilterWhere([$call_tn . '.is_goal_reached' => $this->is_goal_reached]);
        $query->andFilterWhere([$call_tn . '.normal_ending' => $this->normal_ending]);

        if ($this->started_at) {
            list($from, $to) = explode(' - ', $this->started_at);
            $to = $to . ' 23:59:59';
            $from = $from . ' 00:00:00';
            $query->andWhere(['between', $call_tn . '.started_at', strtotime($from), strtotime($to)]);
        }

        if ($this->duration_from) {
            list($hh, $mm, $ss) = explode(':', $this->duration_from);
            $query->andWhere(['>', $call_tn . '.duration', 3600 * $hh + 60 * $mm + $ss]);
        }

        if ($this->duration_to) {
            list($hh, $mm, $ss) = explode(':', $this->duration_to);
            $query->andWhere(['<', $call_tn . '.duration', 3600 * $hh + 60 * $mm + $ss]);
        }

        return $dataProvider;
    }

}