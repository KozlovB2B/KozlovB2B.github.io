<?php

namespace app\modules\script\models\ar;

use Yii;
use app\modules\script\models\query\NodeQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "script_node".
 *
 * @property string $id
 * @property integer $script_id
 * @property integer $number
 * @property integer $top
 * @property integer $left
 * @property string $content
 * @property integer $call_stage_id
 * @property integer $is_goal
 * @property integer $normal_ending
 * @property integer $deleted_at
 * @property string $groups
 * @property string $variants_sort_index
 *
 * @property Script $script
 * @property Variant[] $variants
 * @property Variant[] $pointingVariants
 */
class Node extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_node';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'unique'],
            ['id', 'string', 'max' => 64],
            [['script_id', 'content'], 'required'],
            [['script_id', 'number', 'top', 'left', 'call_stage_id', 'is_goal', 'normal_ending'], 'integer'],
            [['content'], 'string', 'max' => 20000],
            [['groups'], 'string', 'max' => 1024],
            [['variants_sort_index'], 'string', 'max' => 8000],
            [['script_id'], 'exist', 'skipOnError' => true, 'targetClass' => Script::className(), 'targetAttribute' => ['script_id' => 'id']],
            [['number'], 'unique', 'targetAttribute' => ['number', 'script_id'], 'message' => 'У вас уже есть узел с таким номером!'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('script', 'ID'),
            'is_goal' => Yii::t('script', 'Reaching this node is an aim'),
            'normal_ending' => Yii::t('script', 'A phone call can be finished at this node a normal way'),
            'call_stage_id' => Yii::t('script', 'Conversation (calls) stage'),
            'script_id' => Yii::t('script', 'Parent script'),
            'title' => Yii::t('script', 'Title'),
            'groups' => Yii::t('script', 'Use variant groups'),
            'content' => Yii::t('script', 'Content')
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
    public function getPointingVariants()
    {
        return $this->hasMany(Variant::className(), ['target_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariants()
    {
        return $this->hasMany(Variant::className(), ['node_id' => 'id'])->onCondition(Variant::tableName() . '.deleted_at IS NULL');
    }

    /**
     * @inheritdoc
     * @return NodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NodeQuery(get_called_class());
    }


    public function getDropDownContent()
    {
        return '#' . $this->number . ' ' . mb_substr(strip_tags($this->content), 0, 50, 'utf-8');
    }

    public static function dropDownData($script_id)
    {
        return ArrayHelper::map(Node::find()->byScript($script_id)->all(), 'id', 'dropDownContent');

    }
}
