<?php

namespace app\modules\script\models;

use app\modules\integration\models\EnabledList;
use app\modules\script\models\ar\Release;
use app\modules\script\models\ar\VariantsReportAggregate;
use app\modules\user\models\User;
use app\modules\user\models\profile\Operator;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\script\models\ar\Script;
use yii\db\ActiveRecord;
use app\modules\script\models\ar\CallData;
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
 * @property string $api_user User via API
 * @property boolean $using_api
 * @property string $perform_page A web page from where the call was performed (using widget)
 * @property string $record_url URL to record
 * @property int $release_id ID релиза
 * @property string $end_node_uuid
 * @property string $start_node_uuid
 * @property string $fields
 *
 *
 * @property Script $script
 * @property CallEndReason $reason
 * @property Operator $operator
 * @property User $user
 */
class Call extends ActiveRecord
{

    /**
     * @const integer Call stage  "Get to DMP"
     */
    const CALL_STAGE_GET_TO_DMP = 1;

    /**
     * @const integer Call stage  "Establishing contact"
     */
    const CALL_STAGE_ESTABLISHING_CONTACT = 2;

    /**
     * @const integer Call stage  "Collection of information"
     */
    const CALL_STAGE_INFO_COLLECTION = 3;

    /**
     * @const integer Call stage  "Presentation"
     */
    const CALL_STAGE_PRESENTATION = 4;

    /**
     * @const integer Call stage  "Working with excuses"
     */
    const CALL_STAGE_WORKING_WITH_EXCUSES = 5;

    /**
     * @const integer Call stage  "The agreement on the next contact"
     */
    const CALL_STAGE_NEXT_CONTACT_AGREEMENT = 6;

    /**
     * @const integer Call stage  "Exit the conversation"
     */
    const CALL_STAGE_CONVERSATION_EXIT = 7;

    /**
     * @const string After start
     */
    const EVENT_AFTER_START = 'after_start';

    /**
     * @const string After end
     */
    const EVENT_AFTER_END = 'after_end';

    /**
     * @const string After report
     */
    const EVENT_AFTER_REPORT = 'after_report';

    /**
     * @var array Stages list singleton container
     */
    protected static $_stages_list;

    /**
     * @var string
     */
    public $record_file;

    /**
     * @return array
     */
    public static function getStages()
    {
        if (self::$_stages_list === null) {
            self::$_stages_list = [
                Call::CALL_STAGE_GET_TO_DMP => Yii::t('script', 'Get to DMP'),
                Call::CALL_STAGE_ESTABLISHING_CONTACT => Yii::t('script', 'Establishing contact'),
                Call::CALL_STAGE_INFO_COLLECTION => Yii::t('script', 'Collection of information'),
                Call::CALL_STAGE_PRESENTATION => Yii::t('script', 'Presentation'),
                Call::CALL_STAGE_WORKING_WITH_EXCUSES => Yii::t('script', 'Working with excuses'),
                Call::CALL_STAGE_NEXT_CONTACT_AGREEMENT => Yii::t('script', 'The agreement on the next contact'),
                Call::CALL_STAGE_CONVERSATION_EXIT => Yii::t('script', 'Exit the conversation'),
            ];
        }

        return self::$_stages_list;
    }


    /**
     * @param $method
     */
    protected function performIntegrations($method)
    {
        foreach (EnabledList::findOrCreate($this->account_id)->getListAsArray() as $module) {
            $instance = Yii::$app->getModule('integration')->getModule($module);
            if ($instance && method_exists($instance, $method)) {
                $instance->{$method}($this);
            }
        }
    }

    /**
     *
     */
    protected function collectHitsReport()
    {
        $head_manager = UserHeadManager::findHeadManagerByUser();
        if ($head_manager->hits_report) {
            VariantsReportAggregate::collectData($this);
        }
    }

    /**
     *
     */
    public function init()
    {
        $this->on(static::EVENT_BEFORE_UPDATE, function () {
            if (!$this->fields) {
                $this->fields = null;
            }
        });

        $this->on(static::EVENT_AFTER_START, function () {
            $this->performIntegrations('onCallStart');
        });

        $this->on(static::EVENT_AFTER_END, function () {
            $this->performIntegrations('onCallEnd');
            $this->collectHitsReport();
        });

        $this->on(static::EVENT_AFTER_REPORT, function () {
            $this->performIntegrations('onCallReport');
        });

        parent::init();
    }


    /**
     * @return string
     */
    public function getData()
    {
        $data = CallData::findOne($this->id);
        return $data ? $data->data : "";
    }

    /**
     * Name of call stage
     *
     * @param integer $stage_id
     * @return string
     */
    public function getStageName($stage_id)
    {
        $stages = Call::getStages();
        return isset($stages[$stage_id]) ? $stages[$stage_id] : $stage_id;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'call';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['release_id', 'script_id', 'user_id', 'started_at', 'account_id'], 'required'],

            [['start_node_uuid', 'duration', 'is_goal_reached', 'normal_ending', 'end_node_uuid', 'end_node_content', 'nodes_passed', 'end_node_id'], 'required', 'on' => 'report'],

            [['start_node_uuid', 'duration', 'end_node_uuid', 'end_node_content', 'nodes_passed', 'end_node_id'], 'required', 'on' => 'end'],

            [['script_id', 'script_version', 'user_id', 'started_at', 'start_node_id', 'ended_at', 'end_node_id', 'end_edge_id', 'duration', 'reason_id'], 'integer'],
            [['call_history', "comment"], 'string'],
            [['last_word', 'perform_page', 'record_url'], 'string', 'max' => 1000],
            [['fields'], 'string', 'max' => 16000],
            [['api_user'], 'string', 'max' => 32],
            [['record_file'], 'safe'],
            [['is_goal_reached', 'normal_ending', 'using_api'], 'boolean'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScript()
    {
        return $this->hasOne(Script::className(), ['id' => 'script_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReason()
    {
        return $this->hasOne(CallEndReason::className(), ['id' => 'reason_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(Operator::className(), ['user_id' => 'user_id']);
    }


    /**
     * Checking reason and all about it
     *
     * @return bool
     */
    public function checkReason()
    {

        $list = CallEndReason::getListForCurrentAccount();
        if (count($list)) {
            if (!$this->reason_id) {
                $this->addError("reason_id", Yii::t("script", "Call end reason required!"));
                return false;
            }
            /** @var CallEndReason $reason */
            $reason = CallEndReason::findOne($this->reason_id);
            $this->is_goal_reached = $reason->is_goal_reached;

            if ($reason->comment_required && empty($this->comment)) {
                $this->addError("comment", Yii::t("script", "Comment is required for specified reason"));
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('script', 'ID'),
            'script_id' => Yii::t('script', 'Script'),
            'script_version' => Yii::t('script', 'Script version'),
            'api_user' => Yii::t("script", "Who called (API)"),
            'user_id' => Yii::t("script", "Who called"),
            'started_at' => Yii::t('script', 'Started'),
            'start_node_id' => Yii::t('script', 'Start node'),
            'call_history' => Yii::t('script', 'Call history'),
            'ended_at' => Yii::t('script', 'Ended'),
            'end_node_id' => Yii::t('script', 'End node'),
            'end_edge_id' => Yii::t('script', 'End edge'),
            'perform_page' => Yii::t('script', 'Performing page'),
            'last_word' => Yii::t('script', 'Client last word'),
            'duration' => Yii::t('script', 'Duration'),
            'comment' => Yii::t('script', 'Comment'),
            'reason_id' => Yii::t('script', 'Reason'),
            'is_goal_reached' => Yii::t('script', 'Aim reached'),
//            'normal_ending' => Yii::t('script', 'Call according to script / not'),
            'normal_ending' => Yii::t('script', 'Call script quality control'),
            'end_node_content' => Yii::t('script', 'End node'),
            'nodes_passed' => Yii::t('script', 'Nodes passed'),
            'record_url' => Yii::t('script', 'Record'),
        ];
    }

    /**
     * @inheritdoc
     * @return CallQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CallQuery(get_called_class());
    }

    /**
     * Variants for is_goal_reached
     *
     * @return array
     */
    public static function isGoalReachedVariants()
    {
        return [1 => Yii::t('script', 'Aim reached'), 0 => Yii::t('script', 'Aim not reached')];
    }

    /**
     * Variants for is_goal_reached
     *
     * @return array
     */
    public static function normalEndingsVariants()
    {
        return [1 => Yii::t('script', 'Well finished'), 0 => Yii::t('script', 'Abnormal termination')];
    }

    /**
     *
     */
    public function writeRecordUrl()
    {
        if ($this->record_file) {
            $this->record_url = trim(Url::to('/', true), '/') . '/rec/files/' . $this->record_file . '.mp3';
        }
    }

    public static function createRecordsStorageFolder($account_id)
    {
        $records_dir = Yii::getAlias('@webroot') . '/rec/files/' . $account_id;

        if (!is_dir($records_dir)) {
            mkdir($records_dir, 0777, true);
        }
    }

    /**
     * Fill denormalized stat fields
     */
    public function fillDeNormalizedStat()
    {
        $data = json_decode($this->script->data_json);
        $history = json_decode($this->call_history);

        $end_node = null;

        foreach ($data->nodes as $n) {
            if ($n->id == $this->end_node_id) {
                $end_node = $n;
                break;
            }
        }

        if ($end_node) {
            $this->end_node_content = $end_node->content;
            $this->end_node_stage = isset($end_node->call_stage_id) ? $end_node->call_stage_id : null;

        }

        $this->nodes_passed = count($history);
    }

    /**
     * Gets conversation history as plain text
     *
     * @return string
     */
    public function conversationHistoryAsPlainText()
    {
        $start = 0;
        $result = '';
        foreach ($this->getConversationHistory() as $h) {
            $start += $h['t'];

            if ($h['e']) {
                $result .= 'К (' . date('i:s', $start) . '): ' . $h['e'];
                $result .= "\n";
            }

            if ($h['n']) {
                $result .= 'О (' . date('i:s', $start) . '): ' . $h['n'];
                $result .= "\n";
            }
        }
        return $result;
    }


    /**
     * Gets conversation text according to call_history
     *
     * @return array
     */
    public function getConversationHistory()
    {
        return $this->script_version ? $this->getConversationHistoryV1() : $this->getConversationHistoryV2();
    }

    /**
     * Gets conversation text according to call_history
     *
     * @return array
     */
    public function getConversationHistoryV2()
    {
        $history = json_decode($this->call_history);

        /** @var Release $release */
        $release = Release::findOne($this->release_id);

        $build = json_decode($release->build, true);

        $result = [];

//        if ($this->start_node_uuid) {
//            $result[] = [
//                'id' => $this->start_node_id,
//                'n' => !empty($build['nodes'][$this->start_node_uuid]['content']) ? $build['nodes'][$this->start_node_uuid]['content'] : null,
//                'e' => null,
//                't' => 0,
//            ];
//        }

//        var_dump($history);

        foreach ($history as $h) {

            $e = !empty($build['variants'][$h->e]['content']) ? $build['variants'][$h->e]['content'] : null;

            if (!$e) {
                $e = !empty($build['group_variants'][$h->e]['content']) ? $build['group_variants'][$h->e]['content'] : null;;
            }

//            var_dump($h);exit;

            $result[] = [
                'id' => !empty($build['nodes'][$h->n]['number']) ? $build['nodes'][$h->n]['number'] : null,
                'n' => !empty($build['nodes'][$h->n]['content']) ? $build['nodes'][$h->n]['content'] : null,
                'e' => $e,
                't' => $h->t,
            ];
        }

        return $result;
    }


    /**
     * Gets conversation text according to call_history
     *
     * @return array
     */
    public function getConversationHistoryV1()
    {
        $history = json_decode($this->call_history);

        /** @var ScriptVersion $script */
        $script_version = ScriptVersion::find()->where("script_id = " . $this->script_id . " AND version = " . $this->script_version)->one();

        if (!$script_version) {

            if (!$this->script) {
                return [];
            }

            $script_version = new ScriptVersion();
            $script_version->start_node = $this->script->start_node_id;
            $script_version->data = $this->script->data_json;
        }

        $start_node = $script_version->start_node;

        if (!$start_node) {
            $start_node = $this->script->start_node_id;
        }

        $script_data = $script_version->map();

        $result = [];

        if ($start_node) {
            $result[] = [
                'id' => $start_node,
                'n' => !empty($script_data['nodes'][$start_node]->content) ? $script_data['nodes'][$start_node]->content : null,
                'e' => null,
                't' => 0,
            ];
        }

        foreach ($history as $h) {
            $result[] = [
                'id' => $h->n,
                'n' => !empty($script_data['nodes'][$h->n]->content) ? $script_data['nodes'][$h->n]->content : null,
                'e' => !empty($script_data['edges'][$h->e]->content) ? $script_data['edges'][$h->e]->content : null,
                't' => $h->t,
            ];
        }

        return $result;
    }
}