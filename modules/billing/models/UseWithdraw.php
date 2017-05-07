<?php

namespace app\modules\billing\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "billing_use_withdraw".
 *
 * @property integer $id
 * @property integer $accounts
 * @property integer $total
 * @property string $errors
 * @property integer $created_at
 */
class UseWithdraw extends \yii\db\ActiveRecord
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
        return 'billing_use_withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['accounts', 'total', 'created_at'], 'integer'],
            [['errors'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('billing', 'ID'),
            'accounts' => Yii::t('billing', 'Аккаунтов обработано'),
            'total' => Yii::t('billing', 'Всего списано'),
            'errors' => Yii::t('billing', 'Ошибки'),
            'created_at' => Yii::t('billing', 'Дата'),
        ];
    }

    /**
     * @inheritdoc
     * @return UseWithdrawQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UseWithdrawQuery(get_called_class());
    }
}
