<?php

namespace app\modules\blog\models;

use Yii;

/**
 * This is the model class for table "BlogTourMenu".
 *
 * @property integer $id
 * @property string $division
 * @property integer $priority
 * @property string $link_text
 * @property integer $tour_id
 */
class TourMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'BlogTourMenu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['priority', 'tour_id'], 'required'],
            [['priority', 'tour_id'], 'integer'],
            [['division'], 'string', 'max' => 5],
            [['link_text'], 'string', 'max' => 45]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'division' => 'Division',
            'priority' => 'Порядок',
            'link_text' => 'Link Text',
            'tour_id' => 'Тур',
        ];
    }

    /**
     * @inheritdoc
     * @return TourMenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TourMenuQuery(get_called_class());
    }
}
