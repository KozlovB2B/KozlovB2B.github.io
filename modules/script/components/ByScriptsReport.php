<?php
namespace app\modules\script\components;


use app\modules\script\models\Call;
use app\modules\user\models\UserHeadManager;
use yii\data\ActiveDataProvider;
use app\modules\core\components\ExcelExport;
use juffin_halli\dataProviderIterator\DataProviderIterator;
use Yii;
use yii\db\ActiveRecord;
use app\modules\script\models\ar\Script;
use yii\helpers\Url;

/**
 * This is the model class for table "call".
 *
 * @property integer $id
 * @property integer $script_id
 * @property integer $script_version
 * @property integer $user_id
 * @property integer $started_at
 * @property integer $start_node_id
 * @property string $call_history
 * @property integer $ended_at
 * @property integer $end_node_id
 * @property integer $end_edge_id
 * @property string $last_word
 * @property integer $duration
 * @property integer $account_id
 * @property string $comment
 * @property string $reason_id
 * @property boolean $is_goal_reached
 * @property boolean $normal_ending
 * @property string $end_node_content End node
 * @property integer $end_node_stage End node stage
 * @property integer $nodes_passed Nodes passed
 * @property string $api_user
 *
 * @property Script $script
 */
class ByScriptsReport extends ActiveRecord
{
    /** @var string */
    public $duration_from;
    public $duration_to;

    /** @var integer */
    public $goal_reached;
    public $goal_not_reached;
    public $script_worked;
    public $script_broken;

    public $total_count;

    protected static $_base_by_calls_report_filter_data;


    protected static function getBaseByCallsReportFilterData()
    {
        if (static::$_base_by_calls_report_filter_data === null) {
            static::$_base_by_calls_report_filter_data = [];

            if (isset($_GET['ByScriptsReport'])) {
                static::$_base_by_calls_report_filter_data = $_GET['ByScriptsReport'];
            }
        }

        return static::$_base_by_calls_report_filter_data;
    }

    public static function getByCallsReportLink($script_id = null, $is_goal_reached = null, $script_worked = null)
    {
        $data = self::getBaseByCallsReportFilterData();

        if ($script_id !== null) {
            $data['script_id'] = $script_id;
        }
        if ($is_goal_reached !== null) {
            $data['is_goal_reached'] = $is_goal_reached;
        }
        if ($script_worked !== null) {
            $data['normal_ending'] = $script_worked;
        }

        return Url::to(['/script/report/by-calls', 'ByCallsReport' => $data]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScript()
    {
        return $this->hasOne(Script::className(), ['id' => 'script_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call';
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
            'fieldsSafe' => [['id', 'user_id', 'api_user', 'script_id', 'is_goal_reached', 'normal_ending', 'started_at', 'duration_from', 'duration_to'], 'safe'],
            'fieldsBoolean' => [['is_goal_reached', 'normal_ending'], 'boolean']
        ];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'id' => \Yii::t('script', 'ID'),
            'user_id' => \Yii::t('script', 'Operator'),
            'api_user' => Yii::t("script", "Who called (API)"),
            'script_id' => \Yii::t('script', 'Script'),
            'is_goal_reached' => \Yii::t('script', 'Aim reached'),
            'normal_ending' => \Yii::t('script', 'Call script quality control'),
            'started_at' => \Yii::t('script', 'Period'),
            'goal_reached' => \Yii::t('script', 'Aim reached'),
            'goal_not_reached' => \Yii::t('script', 'Aim not reached'),
            'script_worked' => \Yii::t('script', 'Well finished'),
            'script_broken' => \Yii::t('script', 'Abnornal termination'),
            'total_count' => \Yii::t('script', 'Total calls'),
        ];
    }

    public function asExcel()
    {
        $filename = 'by_scripts_report_' . $this->started_at;

        $excel = new ExcelExport();
        Yii::$app->getDb()->createCommand("SET NAMES cp1251")->execute();
        $data = new DataProviderIterator($this->search(), 1000);

        $excel->totalCol = 6;
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Script')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Total calls')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Aim reached')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Aim not reached')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Well finished')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Abnornal termination')));


        $excel->GoNewLine();

        /** @var ByScriptsReport $model */
        foreach ($data as $model) {
            $excel->InsertText($model->script->name);
            $excel->InsertText($model->total_count);
            $excel->InsertText($model->goal_reached);
            $excel->InsertText($model->goal_not_reached);
            $excel->InsertText($model->script_worked);
            $excel->InsertText($model->script_broken);
            $excel->GoNewLine();
        }

        $excel->SaveFile($filename);
    }

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = ByScriptsReport::find();

        $head_manager = UserHeadManager::findHeadManagerByUser();

        $query->groupBy(['call.script_id']);

        $query->select([
            'call.script_id',
            'count(*) as total_count',
            'sum(if(call.is_goal_reached, 1, 0)) as goal_reached',
            'sum(if(call.is_goal_reached, 0, 1)) as goal_not_reached',
            'sum(if(call.normal_ending, 1, 0)) as script_worked',
            'sum(if(call.normal_ending, 0, 1)) as script_broken',
        ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);


        $query->andWhere(['call.account_id' => $head_manager->id]);

        if (!($this->load(\Yii::$app->request->get()) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['call.account_id' => $head_manager->id]);
        $query->andFilterWhere(['call.id' => $this->id]);
        $query->andFilterWhere(['call.user_id' => $this->user_id]);
        $query->andFilterWhere(['call.api_user' => $this->api_user]);
        $query->andFilterWhere(['call.script_id' => $this->script_id]);
        $query->andFilterWhere(['call.is_goal_reached' => $this->is_goal_reached]);
        $query->andFilterWhere(['call.normal_ending' => $this->normal_ending]);

        if ($this->started_at) {
            list($from, $to) = explode(' - ', $this->started_at);
            $to = $to . ' 23:59:59';
            $from = $from . ' 00:00:00';
            $query->andWhere(['between', 'call.started_at', strtotime($from), strtotime($to)]);
        }

        if ($this->duration_from) {
            list($hh, $mm, $ss) = explode(':', $this->duration_from);
            $query->andWhere(['>', 'call.duration', 3600 * $hh + 60 * $mm + $ss]);
        }

        if ($this->duration_to) {
            list($hh, $mm, $ss) = explode(':', $this->duration_to);
            $query->andWhere(['<', 'call.duration', 3600 * $hh + 60 * $mm + $ss]);
        }

        return $dataProvider;
    }

}