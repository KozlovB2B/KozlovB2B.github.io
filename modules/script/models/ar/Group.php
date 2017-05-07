<?php

namespace app\modules\script\models\ar;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "script_group".
 *
 * @property string $id
 * @property integer $script_id
 * @property integer $top
 * @property integer $left
 * @property string $name
 * @property integer $deleted_at
 * @property string $variants_sort_index
 *
 * @property GroupVariant[] $variants
 */
class Group extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_group';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariants()
    {
        return $this->hasMany(GroupVariant::className(), ['group_id' => 'id'])->onCondition(GroupVariant::tableName() . '.deleted_at IS NULL');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'script_id', 'name'], 'required'],
            [['script_id', 'top', 'left', 'deleted_at'], 'integer'],
            [['id'], 'string', 'max' => 64],
            [['name'], 'string', 'max' => 128],
            [['variants_sort_index'], 'string', 'max' => 8000],
            [['id'], 'unique'],
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
            'name' => Yii::t('script', 'Name'),
            'content' => Yii::t('script', 'Content')
        ];
    }
}
