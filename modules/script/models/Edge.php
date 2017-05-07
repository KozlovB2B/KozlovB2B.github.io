<?php

namespace app\modules\script\models;

use Yii;
use app\modules\core\components\Publishable;
use app\modules\core\components\ActiveRecord;

/**
 * This is the model class for table "edge".
 *
 * @property integer $id
 * @property integer $status_id
 * @property integer $script_id
 * @property integer $source
 * @property integer $target
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 */
class Edge extends ActiveRecord implements Publishable
{
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'edge';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['status_id', 'script_id', "source", "content"], 'required'],
//            [['status_id', 'script_id', 'source', 'target'], 'integer'],
//            [['content'], 'string', 'max' => 75, 'min' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('script', 'ID'),
            'status_id' => Yii::t('script', 'Status (1 - Draft, 2 - Published)'),
            'script_id' => Yii::t('script', 'Parent script'),
            'source' => Yii::t('script', 'Source node'),
            'target' => Yii::t('script', 'Target node'),
            'content' => Yii::t('script', "Client's possible reply"),
            'created_at' => Yii::t('script', 'Created At'),
            'updated_at' => Yii::t('script', 'Updated At'),
            'deleted_at' => Yii::t('script', 'Deleted At'),
        ];
    }
}
