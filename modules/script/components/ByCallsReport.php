<?php
/**
 * Report by calls
 */

namespace app\modules\script\components;


use app\modules\script\models\Call;
use app\modules\user\models\UserHeadManager;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use app\modules\core\components\ExcelExport;
use juffin_halli\dataProviderIterator\DataProviderIterator;
use Yii;

class ByCallsReport extends Model
{

    /** @var int */
    public $id;

    /** @var int */
    public $user_id;

    /** @var string */
    public $api_user;

    /** @var int */
    public $script_id;

    /** @var boolean */
    public $is_goal_reached;

    /** @var boolean */
    public $normal_ending;

    /** @var string */
    public $started_at;

    /** @var string */
    public $duration_from;
    public $duration_to;



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
            'normal_ending' => \Yii::t('script', 'Call according to script / not'),
            'started_at' => \Yii::t('script', 'Period'),
        ];
    }

    public function asExcel()
    {

        $filename = 'calls_' . $this->started_at;

        $excel = new ExcelExport();

        Yii::$app->getDb()->createCommand("SET NAMES cp1251")->execute();

        $excel->totalCol = 10;

        $data = new DataProviderIterator($this->search(), 1000);

        $excel->totalCol = 10;
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Id')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Date')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Duration')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Script')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Who called')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Aim reached')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Call script quality control')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Nodes passed')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'End node')));
        $excel->InsertText(iconv('UTF-8', 'CP1251', \Yii::t('script', 'Comment')));

        $excel->GoNewLine();
        /** @var Call $model */
        foreach ($data as $model) {
            $excel->InsertText($model->id);
            $excel->InsertText(date('d.m.Y H:i:s', $model->started_at));
            $excel->InsertText(gmdate("i:s", $model->duration));
            $excel->InsertText($model->script->name);
            $excel->InsertText($model->user->username);
            $excel->InsertText(iconv('UTF-8', 'CP1251', $model->is_goal_reached ? Yii::t('script', 'Yes') : Yii::t('script', 'No')));
            $excel->InsertText(iconv('UTF-8', 'CP1251', $model->normal_ending ? Yii::t('script', 'Yes') : Yii::t('script', 'No')));
            $excel->InsertText($model->nodes_passed);


            if ($model->end_node_stage) {
                $end_node_content = '#' . $model->end_node_id . ' (' . $model->getStageName($model->end_node_stage) . ')';
            } else {
                $end_node_content = '#' . $model->end_node_id;
            }

            $end_node_content .= ' ' . $model->end_node_content;

            $excel->InsertText($end_node_content);

            $excel->InsertText($model->comment);
            $excel->GoNewLine();
        }

        $excel->SaveFile($filename);

    }

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Call::find();
        $head_manager = UserHeadManager::findHeadManagerByUser();

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

//        var_dump($this->user_id);exit;

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