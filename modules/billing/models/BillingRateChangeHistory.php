<?php

namespace app\modules\billing\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use app\modules\user\models\User;
use app\modules\user\models\UserHeadManager;

/**
 * This is the model class for table "billing_rate_change_history".
 *
 * @property integer $id
 * @property integer $account_id
 * @property integer $rate_from
 * @property integer $rate_to
 * @property string $rate_from_data
 * @property string $rate_to_data
 * @property integer $created_at
 * @property string $comment
 *
 * @property User $user
 * @property UserHeadManager $userHeadManager
 * @property Rate $rateFrom
 * @property Rate $rateTo
 */
class BillingRateChangeHistory extends \yii\db\ActiveRecord
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
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'account_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserHeadManager()
    {
        return $this->hasOne(UserHeadManager::className(), ['id' => 'account_id']);
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing_rate_change_history';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateFrom()
    {
        return $this->hasOne(Rate::className(), ['id' => 'rate_from']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRateTo()
    {
        return $this->hasOne(Rate::className(), ['id' => 'rate_to']);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'rate_to', 'rate_to_data', 'comment'], 'required'],
            ['comment', 'string', 'min' => 1, 'max' => 1000],
            [['account_id', 'rate_from', 'rate_to', 'created_at'], 'integer'],
            [['rate_from_data', 'rate_to_data'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'comment' => Yii::t('billing', 'Comment'),
            'account_id' => Yii::t('billing', 'Account'),
            'rate_from' => Yii::t('billing', 'From'),
            'rate_to' => Yii::t('billing', 'To'),
            'rate_from_data' => Yii::t('billing', 'Rate from data'),
            'rate_to_data' => Yii::t('billing', 'Rate to data'),
            'created_at' => Yii::t('billing', 'Date'),
        ];
    }

    /**
     * @inheritdoc
     * @return BillingRateChangeHistoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new BillingRateChangeHistoryQuery(get_called_class());
    }

    /**
     * All balance operations for user
     *
     * @return ActiveDataProvider
     */
    public static function userHistoryDataProvider($id = null)
    {
        if (!$id) {
            $id = Yii::$app->getUser()->getId();
        }

        return new ActiveDataProvider(['query' => BillingRateChangeHistory::find()->allByUser($id), 'sort' => ['defaultOrder' => ['id' => SORT_DESC]]]);
    }
}
