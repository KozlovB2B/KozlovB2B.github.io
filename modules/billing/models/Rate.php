<?php

namespace app\modules\billing\models;

use Yii;
use app\modules\core\components\ActiveRecord;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "rate".
 *
 * @property integer $id
 * @property string $name
 * @property integer $operators_threshold
 * @property integer $monthly_fee
 * @property integer $created_at
 * @property integer $deleted_at
 * @property integer $is_default
 * @property integer $executions_per_day
 * @property integer $executions_per_month
 * @property integer $export_allowed
 * @property integer $archived_at
 * @property string $division Division
 * @property string $currency Currency
 * @property integer $user_id
 */
class Rate extends ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ]

        ];
    }

    /**
     * @const integer User can change his rate not so often
     */
    const MIN_DELAY_BETWEEN_RATE_CHANGE = 86400;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'operators_threshold', 'monthly_fee'], 'required'],
            [['division'], 'string', 'max' => 5],
            [['currency'], 'string', 'max' => 3],
            ['export_allowed', 'boolean'],
            [['operators_threshold', 'monthly_fee', 'created_at', 'deleted_at', 'is_default', 'executions_per_day', 'executions_per_month', 'export_allowed', 'archived_at'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'name' => Yii::t('billing', 'Name'),
            'operators_threshold' => Yii::t('billing', 'Operators threshold'),
            'monthly_fee' => Yii::t('billing', 'Monthly fee'),
            'created_at' => Yii::t('site', 'Created'),
            'deleted_at' => Yii::t('site', 'Deleted'),
            'is_default' => Yii::t('billing', 'Script executions per day'),
            'executions_per_day' => Yii::t('billing', 'Script executions per day'),
            'executions_per_month' => Yii::t('billing', 'Script executions per month'),
            'export_allowed' => Yii::t('billing', 'Exporting allowed'),
            'archived_at' => Yii::t('billing', 'Archived'),
        ];
    }

    public static function getActiveRates()
    {
        return Rate::find()->active()->all();
    }

    /**
     * @return array Safe attributes for add to apply rate
     */
    public static function getAllowedAdditionalData()
    {
        return ['operators_threshold', 'monthly_fee', 'executions_per_day', 'executions_per_month', 'export_allowed'];
    }

    /**
     * Current default account.
     * If no default active account - gets first active account
     *
     * @return Rate|array|null
     */
    public static function getDefault()
    {
        $default = Rate::find()->active()->currentDefault()->forCurrentDivision()->one();

        return $default ? $default : Rate::find()->active()->one();
    }

    /**
     * Returns current free rate for division
     *
     * @param string $division
     * @return Rate|array|null
     */
    public static function getFreeForDivision($division)
    {
        return Rate::find()->active()->free()->forDivision($division)->one();
    }


    /**
     * @inheritdoc
     * @return RateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RateQuery(get_called_class());
    }
}