<?php

namespace app\modules\script\models\ar;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "script_node_clone".
 *
 * @property string $id
 * @property integer $script_id
 * @property string $from
 * @property string $to
 * @property string $to_data
 * @property integer $created_at
 * @property integer $deleted_at
 *
 * @property Node $source
 * @property Node $target
 */
class NodeClone extends ActiveRecord
{
    /**
     *
     */
    public function init()
    {
        $this->on(static::EVENT_AFTER_INSERT, function () {
            $this->writeClone();
        });

        $this->on(static::EVENT_AFTER_UPDATE, function () {
            $this->toggleClone();
        });

        parent::init();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTarget()
    {
        return $this->hasOne(Node::className(), ['id' => 'to']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Node::className(), ['id' => 'from']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_node_clone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'script_id', 'from', 'to', 'created_at', 'to_data'], 'required'],
            [['script_id', 'created_at', 'deleted_at'], 'integer'],
            [['id', 'from', 'to'], 'string', 'max' => 64],
            [['id'], 'unique'],
        ];
    }

    /**
     * Writes a clone to DB
     */
    protected function writeClone()
    {
        $data = json_decode($this->to_data, true);

        $data['node']['class'] = Node::className();

        /** @var Node $node */
        $node = Yii::createObject($data['node']);
        if (!$node->save()) {
            throw new Exception(implode(',', $node->getFirstErrors()));
        }

        foreach ($data['variants'] as $v) {
            $v['class'] = Variant::className();
            /** @var Node $node */
            $variant = Yii::createObject($v);
            if (!$variant->save()) {
                throw new Exception(implode(',', $variant->getFirstErrors()));
            }
        }
    }

    /**
     * Soft delete/restore clone
     */
    protected function toggleClone()
    {
        $this->target->deleted_at = $this->deleted_at ? time() : null;
        $this->target->update(false, ['deleted_at']);
    }
}
