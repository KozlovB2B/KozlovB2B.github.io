<?php

namespace app\modules\script\models\ar;

use Yii;
use app\modules\core\components\Publishable;
use app\modules\core\components\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use app\modules\user\models\UserHeadManager;
use app\modules\script\models\query\ScriptQuery;

/**
 * This is the model class for table "script".
 *
 * @property integer $id
 * @property integer $status_id
 * @property integer $user_id
 * @property integer $group_id
 * @property string $allowed_users
 * @property string $name
 * @property string $description
 * @property string $cached_content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property float $zoom Current zoom
 * @property string $viewport_center Viewport center
 * @property integer $current_version Current version
 * @property integer $start_node_id Start node
 * @property integer $max_node Max node
 * @property string $data_json Dataset
 * @property integer $original_id Original script id
 * @property integer $original_version Original script version
 * @property integer $import_id Current import script id
 * @property integer $import_version Current import script version
 * @property integer $nodes_count Nodes count
 * @property integer $operator_interface_type_id Operator interface
 * @property string $common_cases Common cases
 * @property string $build Current build
 * @property integer $latest_release Latest release
 * @property string $start_node_uuid Start node UUID
 * @property boolean $v2converted
 * @property string $performer_options
 * @property string $editor_options
 * @property string $build_md5
 *
 * @property-read UserHeadManager $account
 * @property-read Node[] $nodes
 * @property-read Group[] $groups
 * @property-read Release[] $releases
 * @property-read Variant[] $variants
 * @property-read Release $release
 *
 * TriHard NEVER SUBBED TriHard NEVER DONATED TriHard ADBLOCK ON TriHard STOLEN LAPTOP TriHard NEIGHBORS WIFI TriHard FREE ENTERTAINMENT TriHard
 *
 * █▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀█ █═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═█ █═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═█ █═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═█ █═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═█ █═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═█ █═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═█ █═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═█ █═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═╩═╦═█ █▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄█
 * ──────▄▌▐▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▌ ───▄▄██▌█ Газель ███████▌█▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▌ ▀▀(@)▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀(@)▀  ▐▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▌█▄────── ▐ You have been permanently banned... ▌███▄▄── ▐▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▌████████ ─▀(@)▀▀▀▀▀▀▀▀▀▀▀▀▀(@)(@)▀▀▀▀▀▀▀▀(@)▀
 *
 * ──────▄▌▐▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▌ ───▄▄██▌█ Везу метелице жрачку ███████▌█▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▌ ▀▀(@)▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀(@)▀
 */
class Script extends ActiveRecord implements Publishable
{
    /**
     * @const int Const to be written into status_id field after v2 conversion.
     * Coz status_id is unused in v2 workflow.
     */
    const V2_CONVERTED = 5;

    const OPEN_AT = "Open file at http://www.ScriptDesigner.ru/";
    const OPEN_AT_HTTPS = "Open file at https://www.ScriptDesigner.ru/";
    const OPEN_AT_V2 = "Open file at https://ScriptDesigner.ru/";

    /**
     * @const integer Operator's interface type "Default"
     */
    const SCRIPT_OPERATOR_INTERFACE_TYPE_DEFAULT = 1;

    /**
     * @const integer Operator's interface type "Links right"
     */
    const SCRIPT_OPERATOR_INTERFACE_TYPE_LINKS_RIGHT = 2;

    /**
     * @const integer Operator's interface type "Buttons left"
     */
    const SCRIPT_OPERATOR_INTERFACE_TYPE_BUTTONS_LEFT = 3;

    /**
     * @const integer Operator's interface type "Buttons right"
     */
    const SCRIPT_OPERATOR_INTERFACE_TYPE_BUTTONS_RIGHT = 4;

    /**
     *
     */
    const SCRIPT_FILE_EXTENSION = 'scrd';

    /**
     * @const integer ttl for test cache
     * @deprecated тестовые звонки теперь делаются на лету и не нужно их создавать на сервере. удалить и вычистить все что связано с этим
     */
    const TEST_TTL = 6000;

    /**
     * @var string Script data
     */
    public $import_file;

    public $editor___toolbar_tools_search_input;
    public $editor___toolbar_tools_start_node;

    /**
     * @var string Script data
     */
    public $data;


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(UserHeadManager::className(), ['id' => 'account_id']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelease()
    {
        return $this->hasOne(Release::className(), ['id' => 'latest_release']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNodes()
    {
        return $this->hasMany(Node::className(), ['script_id' => 'id'])->onCondition(Node::tableName() . '.deleted_at IS NULL');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['script_id' => 'id'])->onCondition(Group::tableName() . '.deleted_at IS NULL');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReleases()
    {
        return $this->hasMany(Release::className(), ['script_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariants()
    {
        return $this->hasMany(Variant::className(), ['script_id' => 'id'])->onCondition(Variant::tableName() . '.deleted_at IS NULL');
    }

    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->status_id == Publishable::STATUS_PUBLISHED;
    }

    /**
     * @return boolean
     */
    public function isDraft()
    {
        return $this->status_id == Publishable::STATUS_DRAFT;
    }

    /**
     * @return boolean
     */
    public function isCreating()
    {
        return $this->status_id == Publishable::STATUS_CREATING;
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            Publishable::STATUS_PUBLISHED => Yii::t('site', 'Published'),
            Publishable::STATUS_DRAFT => Yii::t('site', 'Draft'),
        ];
    }

    /**
     * @return int
     */
    public function getStatusName()
    {
        $statuses = $this->getStatuses();

        return isset($statuses[$this->status_id]) ? $statuses[$this->status_id] : $this->status_id;
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script';
    }


    /**
     * @return array Operator interface types
     */
    public static function operatorInterfaceTypes()
    {
        return [
            Script::SCRIPT_OPERATOR_INTERFACE_TYPE_DEFAULT => Yii::t('script', 'Buttons at the bottom'),
            Script::SCRIPT_OPERATOR_INTERFACE_TYPE_BUTTONS_LEFT => Yii::t('script', 'Buttons on the left'),
            Script::SCRIPT_OPERATOR_INTERFACE_TYPE_BUTTONS_RIGHT => Yii::t('script', 'Buttons on the right'),
            Script::SCRIPT_OPERATOR_INTERFACE_TYPE_LINKS_RIGHT => Yii::t('script', 'Links on the right')
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["common_cases"], 'safe'],
            [["data_json"], 'required', 'on' => 'publish'],
            [['status_id', 'user_id'], 'required'],
            [['status_id', 'user_id', 'group_id', 'created_at', 'updated_at', 'deleted_at', 'operator_interface_type_id'], 'integer'],
            [['max_node', 'start_node_id'], 'integer', 'integerOnly' => false],
            [['cached_content', "data", "viewport_center", "data_json"], 'string'],
            [['allowed_users'], 'string', 'max' => 2000],
            [['performer_options'], 'string', 'max' => 8000],
            [['name'], 'string', 'max' => 75],
            [['start_node_uuid', 'build_md5'], 'string', 'max' => 64],
            [['description'], 'string', 'max' => 255],
            [['build'], 'string']
        ];
    }

    public function init()
    {
        $this->on(static::EVENT_BEFORE_VALIDATE, function () {
            if (!$this->start_node_uuid) {
                $this->start_node_uuid = null;
            }
        });

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'editor___toolbar_tools_search_input' => Yii::t('script', 'search by id or text'),
            'operator_interface_type_id' => Yii::t('script', 'Operator interface'),
            'import_file' => Yii::t('script', 'File for import'),
            'id' => Yii::t('script', 'Script ID'),
            'status_id' => Yii::t('script', 'State'),
            'user_id' => Yii::t('script', 'Script creator'),
            'group_id' => Yii::t('script', 'Script users group for usage'),
            'allowed_users' => Yii::t('script', 'Everyone using the script in addition to the creator'),
            'name' => Yii::t('script', 'Name of script'),
            'latest_release' => Yii::t('script', 'Latest Release'),
            'description' => Yii::t('script', 'Description'),
            'cached_content' => Yii::t('script', 'Pre-generated JSON for fast init call'),
            'created_at' => Yii::t('script', 'Date of creation'),
            'updated_at' => Yii::t('site', 'Updated At'),
            'deleted_at' => Yii::t('site', 'Deleted At'),
            'current_version' => Yii::t('script', 'Current version'),
            'start_node_uuid' => Yii::t('script', 'Starting node'),
            'start_node_id' => Yii::t('script', 'Starting node'),
            'nodes_count' => Yii::t('script', 'Nodes count'),
            'common_cases' => Yii::t('script', 'Common cases'),
        ];
    }

    /**
     * @inheritdoc
     * @return ScriptQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ScriptQuery(get_called_class());
    }

    /**
     * Nodes count calculating
     */
    public function calculateNodesCount()
    {
        $data = json_decode($this->data_json);
        $this->nodes_count = isset($data->nodes) ? count($data->nodes) : 0;
    }

    /**
     * Getting viewbox width and height for screen
     *
     * @return array
     */
    public function printDimensions()
    {
        $data = json_decode($this->data_json);

        $min_left = 0;
        $max_left = 0;
        $min_top = 0;
        $max_top = 0;

        foreach ($data->nodes as $node) {
            if ($min_left > $node->left) {
                $min_left = $node->left;
            }
            if ($max_left < $node->left) {
                $max_left = $node->left;
            }
            if ($min_top > $node->top) {
                $min_top = $node->top;
            }
            if ($max_top < $node->top) {
                $max_top = $node->top;
            }
        }

        $w = abs($max_left - $min_left);
        $h = abs($max_top - $min_top);

//        var_dump($min_left, $max_left, $min_top, $max_top, $w, $h);
        $factor = $w > 3000 ? 2 : 4;
        return ['w' => $w * $factor, 'h' => $h * $factor];
    }


    /**
     * Nodes list for start node select element
     *
     * @return array
     */
    public function getNodesForStartNodeSelect()
    {
        $result = [];
        $data = json_decode($this->data_json);
        if (!empty($data->nodes) && is_array($data->nodes)) {
            foreach ($data->nodes as $n) {
                $result[$n->id] = '#' . $n->id . ' ' . mb_substr(str_replace('&nbsp;', '', $n->content), 0, 30, "UTF-8");
            }
        }
        return $result;
    }

    /**
     * @return string
     */
    public function getOpenAtString()
    {
        return "Open file at http://ScriptDesigner.ru/";
    }

    /**
     * Data for exporting
     *
     * @return string
     */
    public function getExportData()
    {
        return base64_encode($this->getBuild());
    }

    /**
     * Modify new imported script name as copy if need
     */
    public function modifyNameAsCopy()
    {
        $query = new Query();
        $query->select('name')->from(Script::tableName())->where("deleted_at IS NULL AND user_id = :user_id AND status_id != :creating", [":user_id" => $this->user_id, ":creating" => Publishable::STATUS_CREATING]);
        $data = $query->createCommand()->queryColumn();

        $pattern = "/" . Yii::t('script', ' \(copy (\d+)\)') . "/i";

        $original_name = preg_replace($pattern, "", $this->name);

        $num = 0;

        while (in_array($this->name, $data)) {
            $num++;
            $this->name = $original_name . Yii::t('script', ' (copy {n})', ['n' => $num]);
        }
    }


    /**
     * Call data
     *
     * @return array
     */
    public function callData()
    {
        $result = [];
        $result['script'] = json_decode($this->data_json, true);
        $result["start_node"] = $this->start_node_id;
        $result["version"] = $this->current_version;
        $result["script_name"] = $this->name;
        $result["common_cases"] = $this->common_cases ? json_decode($this->common_cases) : null;

        switch ($this->operator_interface_type_id) {
            case Script::SCRIPT_OPERATOR_INTERFACE_TYPE_DEFAULT:
            case Script::SCRIPT_OPERATOR_INTERFACE_TYPE_BUTTONS_LEFT:
            case Script::SCRIPT_OPERATOR_INTERFACE_TYPE_BUTTONS_RIGHT:
                $edge_tpl = "<div class='{visited} btn btn-primary btn-sm script___call__edge_button' data-target='{target}' data-id='{id}'>{content}</div>";
                break;
            case Script::SCRIPT_OPERATOR_INTERFACE_TYPE_LINKS_RIGHT:

                $edge_tpl = "<a href='#' class='{visited} script___call__edge_button' data-target='{target}' data-id='{id}'>{content}</a>";
                break;
            default:
                $edge_tpl = "<div class='{visited} btn btn-primary btn-sm script___call__edge_button' data-target='{target}' data-id='{id}'>{content}</div>";
                break;
        }

        $result["edge_tpl"] = $edge_tpl;
//        $result["edge_tpl"] = '<tr class="{visited}"><td>'.$edge_tpl.'</td><td>#{target} <span class="visited-indicator">('.Yii::t('script', 'visited').')</span></td></tr>';

        return $result;
    }

    /**
     * Cleaning spam from script file content
     *
     * @param string $file_data
     * @return string
     */
    public static function cleanUpAdFromScriptFile($file_data)
    {
        return str_replace('Open file at http://ScriptDesigner.ru/', '', $file_data);
    }

    /**
     * Flush build. Every script data change flush current build
     *
     * @param int $id
     */
    public static function flushBuild($id)
    {
        static::updateAll(['build' => null, 'build_md5' => null], "id = :id", [':id' => $id]);
    }

    /**
     * Get latest build
     *
     * @return string
     */
    public function getBuild()
    {
        if (!$this->build) {

            $data = [];
            $data['script'] = [];
            $data['script']['id'] = $this->id;
            $data['script']['original_id'] = $this->original_id;
            $data['script']['name'] = $this->name;
            $data['script']['max_node'] = $this->max_node;
            $data['script']['start_node_uuid'] = $this->start_node_uuid;
            $data['script']['performer_options'] = $this->performer_options;
            $data['script']['editor_options'] = $this->editor_options;
            $data['variants'] = [];
            $data['nodes'] = [];
            $data['groups'] = [];
            $data['group_variants'] = [];

            foreach ($this->nodes as $n) {
                $data['nodes'][$n->id] = $n->getAttributes();

                foreach ($n->variants as $v) {
                    $data['variants'][$v->id] = $v->getAttributes();
                }
            }

            foreach ($this->groups as $g) {
                $data['groups'][$g->id] = $g->getAttributes();

                foreach ($g->variants as $gv) {
                    $data['group_variants'][$gv->id] = $gv->getAttributes();
                }
            }


            $this->build = json_encode($data, JSON_UNESCAPED_UNICODE);
            $this->build_md5 = md5($this->build);

            $this->update(false, ['build', 'build_md5']);
        }

        return $this->build;
    }

    public static function dropDownData(){
        return ArrayHelper::map(Script::find()->allByUserCriteria(Yii::$app->getUser()->getId())->all(), 'id', 'name');
    }
}
