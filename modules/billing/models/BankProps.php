<?php

namespace app\modules\billing\models;

use Yii;

/**
 * This is the model class for table "billing_bank_props".
 *
 * @property integer $id
 * @property integer $account_id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $contact_phone
 * @property string $company_name
 * @property integer $inn
 * @property integer $kpp
 * @property integer $ogrn
 * @property string $bank_name
 * @property integer $bik
 * @property string $corr_score
 * @property string $pay_score
 * @property string $boss_position
 * @property string $boss_last_name
 * @property string $boss_first_name
 * @property string $boss_middle_name
 * @property string $acting_on_the_basis
 * @property string $post_address
 * @property string $juristic_address
 * @property string $real_address
 * @property integer $created_at
 * @property integer $updated_at
 */
class BankProps extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'billing_bank_props';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'inn', 'kpp', 'ogrn', 'created_at', 'updated_at'], 'integer'],
            [['first_name', 'contact_phone', 'company_name', 'inn', 'bank_name', 'bik', 'corr_score', 'pay_score', 'boss_position', 'boss_last_name', 'boss_first_name', 'boss_middle_name', 'acting_on_the_basis', 'post_address'], 'required'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 75],
            [['contact_phone'], 'string', 'max' => 20],
            [['company_name', 'bank_name'], 'string', 'max' => 150],
            [['corr_score', 'pay_score', 'boss_position', 'boss_last_name', 'boss_first_name', 'boss_middle_name', 'acting_on_the_basis'], 'string', 'max' => 40],
            [['post_address', 'juristic_address', 'real_address'], 'string', 'max' => 255]
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
            'first_name' => Yii::t('billing', 'First name'),
            'last_name' => Yii::t('billing', 'Last name'),
            'middle_name' => Yii::t('billing', 'Middle name'),
            'contact_phone' => Yii::t('billing', 'Contact phone'),
            'company_name' => Yii::t('billing', 'Company name'),
            'inn' => Yii::t('billing', 'INN'),
            'kpp' => Yii::t('billing', 'KPP'),
            'ogrn' => Yii::t('billing', 'OGRN'),
            'bank_name' => Yii::t('billing', 'Bank name'),
            'bik' => Yii::t('billing', 'BIK'),
            'corr_score' => Yii::t('billing', 'Corr score'),
            'pay_score' => Yii::t('billing', 'Pay score'),
            'boss_position' => Yii::t('billing', 'Boss position'),
            'boss_last_name' => Yii::t('billing', 'Boss last name'),
            'boss_first_name' => Yii::t('billing', 'Boss first name'),
            'boss_middle_name' => Yii::t('billing', 'Boss Middle name'),
            'acting_on_the_basis' => Yii::t('billing', 'Acting on the basis'),
            'post_address' => Yii::t('billing', 'Post address'),
            'juristic_address' => Yii::t('billing', 'Juristic address'),
            'real_address' => Yii::t('billing', 'Real address'),
            'created_at' => Yii::t('billing', 'Created'),
            'updated_at' => Yii::t('billing', 'Updated'),
        ];
    }
}
