<?php

namespace app\modules\site\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tooltip".
 *
 * @property integer $tooltip_id
 * @property integer $user_id
 *
 * todo придумать виджет если будут следующие подсказки
 */
class Tooltip extends ActiveRecord
{
    /**
     * @const integer Common cases of script tooltip
     */
    const SCRIPT_COMMON_CASES = 1;

    /**
     * Gets tooltip text
     *
     * @param integer $tooltip_id
     * @return string
     */
    public static function getText($tooltip_id)
    {
        $text = '';

        switch ($tooltip_id) {
            case Tooltip::SCRIPT_COMMON_CASES:
                $text = Yii::t('script', 'Common cases appear in every node when an operator execute it.');
                break;
            default:
                break;
        }

        return $text;
    }

    /**
     * Skip tooltip by user
     *
     * @param integer $tooltip_id
     * @return boolean
     */
    public static function skip($tooltip_id)
    {
        $model = new Tooltip();
        $model->tooltip_id = $tooltip_id;
        $model->user_id = Yii::$app->getUser()->getId();
        return $model->save();
    }

    /**
     * Is given tooltip skipped by user earlier
     *
     * @param int $tooltip_id Tooltip ID to be checked
     * @return bool
     */
    public static function isSkipped($tooltip_id)
    {
        return !!Tooltip::findOne(['tooltip_id' => $tooltip_id, 'user_id' => Yii::$app->getUser()->getId()]);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tooltip';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tooltip_id', 'user_id'], 'required'],
            [['tooltip_id', 'user_id'], 'integer'],
        ];
    }
}
