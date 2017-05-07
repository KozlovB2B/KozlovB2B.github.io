<?php

namespace app\modules\blog\models;

use Yii;

/**
 * This is the model class for table "BlogTour".
 *
 * @property integer $id
 * @property integer $status_id
 * @property string $division
 * @property string $heading
 * @property string $teaser
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 */
class Tour extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'BlogTour';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status_id'], 'required'],
            [['status_id', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['content'], 'string'],
            [['division'], 'string', 'max' => 5],
            [['heading'], 'string', 'max' => 45],
            [['teaser'], 'string', 'max' => 8000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status_id' => 'Статус (1 - Черновик, 2 - опубликовано)',
            'division' => 'Division',
            'heading' => 'Heading',
            'teaser' => 'Teaser',
            'content' => 'Content',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'deleted_at' => 'Удален',
        ];
    }
}
