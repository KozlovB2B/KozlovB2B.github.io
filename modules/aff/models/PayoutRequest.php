<?php

namespace app\modules\aff\models;

use Yii;

/**
 * This is the model class for table "payout_request".
 *
 * @property integer $id
 * @property integer $balance_id
 * @property integer $status_id
 * @property integer $amount
 * @property string $comment
 * @property integer $created_at
 * @property integer $in_work_at
 * @property integer $completed_at
 * @property integer $cancelled_at
 */
class PayoutRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payout_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance_id', 'status_id', 'amount', 'created_at', 'in_work_at', 'completed_at', 'cancelled_at'], 'integer'],
            [['status_id', 'amount'], 'required'],
            [['comment'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('aff', 'ID'),
            'balance_id' => Yii::t('aff', 'User'),
            'status_id' => Yii::t('aff', 'Request status (1 - Registered, 2 - In work, 3 - Completed, 4 - Cancelled)'),
            'amount' => Yii::t('aff', 'Amount'),
            'comment' => Yii::t('aff', 'Comment'),
            'created_at' => Yii::t('aff', 'Creating date'),
            'in_work_at' => Yii::t('aff', 'Taking in work date'),
            'completed_at' => Yii::t('aff', 'Perform date'),
            'cancelled_at' => Yii::t('aff', 'Cancel date'),
        ];
    }

    /**
     * @inheritdoc
     * @return PayoutRequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PayoutRequestQuery(get_called_class());
    }
}
