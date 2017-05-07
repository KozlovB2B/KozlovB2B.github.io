<?php

namespace app\modules\billing\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "billing_blocking".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $deadline
 * @property integer $first_warning_sent
 * @property integer $second_warning_sent
 * @property integer $created_at
 * @property integer $performed_at
 * @property integer $cancelled_at
 */
class BillingBlocking extends \yii\db\ActiveRecord
{

    public function behaviors()
    {
        return [
            [
                "class" => TimestampBehavior::className(),
                "updatedAtAttribute" => false,
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing_blocking';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'deadline'], 'required'],
            [['account_id', 'deadline', 'first_warning_sent', 'second_warning_sent', 'created_at', 'performed_at', 'cancelled_at'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'account_id' => Yii::t('billing', 'Account'),
            'deadline' => Yii::t('billing', 'Blocking deadline'),
            'first_warning_sent' => Yii::t('billing', 'First warning sent'),
            'second_warning_sent' => Yii::t('billing', 'Second warning sent'),
            'created_at' => Yii::t('billing', 'Created'),
            'performed_at' => Yii::t('billing', 'Performed'),
            'cancelled_at' => Yii::t('billing', 'Cancelled'),
        ];
    }
}
