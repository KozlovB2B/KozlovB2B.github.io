<?php

namespace app\modules\aff\models;

use Yii;

/**
 * This is the model class for table "aff_hit".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $user_id
 * @property string $promo_code
 * @property integer $link_id
 * @property string $query_string
 * @property string $utm_medium
 * @property string $utm_source
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property string $ip
 * @property string $user_agent
 * @property string $browser_language
 * @property integer $device_type
 * @property string $os
 * @property string $browser
 * @property string $ref
 * @property integer $has_registrations
 * @property integer $bills
 * @property integer $bills_paid
 * @property integer $total_earned
 *
 * @property PromoLink $link
 */
class Hit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'aff_hit';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLink()
    {
        return $this->hasOne(PromoLink::className(), ['id' => 'link_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'device_type'], 'required'],
            [['created_at', 'user_id', 'link_id', 'device_type', 'has_registrations', 'bills', 'bills_paid', 'total_earned'], 'integer'],
            [['promo_code'], 'string', 'max' => 12],
            [['query_string', 'ref'], 'string', 'max' => 1280],
            [['utm_medium', 'utm_source', 'utm_campaign', 'utm_content', 'utm_term'], 'string', 'max' => 128],
            [['ip'], 'string', 'max' => 15],
            [['user_agent'], 'string', 'max' => 256],
            [['browser_language'], 'string', 'max' => 5],
            [['os', 'browser'], 'string', 'max' => 75]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('aff', 'ID'),
            'utm_medium' => Yii::t('aff', 'Advertising method'),
            'utm_source' => Yii::t('aff', 'Traffic source'),
            'utm_campaign' => Yii::t('aff', 'Advertising campaign'),
            'utm_content' => Yii::t('aff', 'Advertisement'),
            'utm_term' => Yii::t('aff', 'Terms'),
            
            
            'created_at' => Yii::t('aff', 'Hit date'),
//            'user_id' => Yii::t('aff', 'User ID'),
            'promo_code' => Yii::t('aff', 'Promo Code'),
            'link_id' => Yii::t('aff', 'Link ID'),
            'query_string' => Yii::t('aff', 'Query string'),
//            'utm_medium' => Yii::t('aff', 'Utm Medium'),
//            'utm_source' => Yii::t('aff', 'Utm Source'),
//            'utm_campaign' => Yii::t('aff', 'Utm Campaign'),
//            'utm_content' => Yii::t('aff', 'Utm Content'),
//            'utm_term' => Yii::t('aff', 'Utm Term'),
            'ip' => Yii::t('aff', 'Ip'),
            'user_agent' => Yii::t('aff', 'User Agent'),
            'browser_language' => Yii::t('aff', 'Browser language'),
            'device_type' => Yii::t('aff', 'Device type'),
            'os' => Yii::t('aff', 'Os'),
            'browser' => Yii::t('aff', 'Browser'),
            'ref' => Yii::t('aff', 'Ref'),
            'has_registrations' => Yii::t('aff', 'Registrations'),
            'bills' => Yii::t('aff', 'Bills'),
            'bills_paid' => Yii::t('aff', 'Bills paid'),
            'total_earned' => Yii::t('aff', 'Earned'),
        ];
    }

    /**
     * @param $earned
     * @throws \Exception
     */
    public function incrementTotalEarned($earned)
    {
        if (!$this->total_earned) {
            $this->total_earned = 0;
        }
        $this->total_earned += $earned;
        $this->update(false, ['total_earned']);

        if ($this->link) {
            $this->link->incrementTotalEarned($earned);
        }
    }
}
