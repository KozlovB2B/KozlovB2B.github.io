<?php

namespace app\modules\script\models\ar;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "script_group_variant".
 *
 * @property string $id
 * @property integer $script_id
 * @property string $group_id
 * @property string $target_id
 * @property string $content
 * @property integer $deleted_at
 * @property integer $created_at
 *
 * @property Group $parent
 * @property Node $target
 * @property Script $script
 */
class GroupVariant extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_group_variant';
    }

    public function init()
    {
        $this->on(static::EVENT_BEFORE_VALIDATE, function () {

            if (!$this->script_id) {
                $this->script_id = $this->parent->script_id;
            }

            if (!$this->target_id) {
                $this->target_id = null;
            }
        });

        parent::init();
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarget()
    {
        return $this->hasOne(Node::className(), ['id' => 'target_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Group::className(), ['id' => 'group_id']);
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
    public function rules()
    {
        return [
            [['id', 'script_id', 'group_id', 'content'], 'required'],
            [['script_id', 'deleted_at', 'created_at'], 'integer'],
            [['id', 'group_id', 'target_id'], 'string', 'max' => 64],
            [['content'], 'string', 'max' => 128],
            [['id'], 'unique'],
            [['target_id'], 'exist', 'skipOnError' => true, 'targetClass' => Node::className(), 'targetAttribute' => ['target_id' => 'id']],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => Group::className(), 'targetAttribute' => ['group_id' => 'id']],
            [['script_id'], 'exist', 'skipOnError' => true, 'targetClass' => Script::className(), 'targetAttribute' => ['script_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('script', 'ID'),
            'script_id' => Yii::t('script', 'Parent script'),
            'group_id' => Yii::t('script', 'Group'),
            'target_id' => Yii::t('script', 'Target node'),
            'content' => Yii::t('script', "Client's possible reply"),
            'deleted_at' => Yii::t('script', 'Deleted At'),
        ];
    }
}
