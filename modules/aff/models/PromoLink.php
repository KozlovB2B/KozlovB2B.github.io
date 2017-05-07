<?php

namespace app\modules\aff\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

/**
 * This is the model class for table "aff_promo_link".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $deleted_at
 * @property integer $user_id
 * @property string $promo_code
 * @property string $host
 * @property string $query_string
 * @property string $url
 * @property string $utm_medium
 * @property string $utm_source
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $utm_term
 * @property integer $hits
 * @property integer $money
 */
class PromoLink extends \yii\db\ActiveRecord
{
    /**
     * @var string Custom utm medium
     */
    public $utm_medium_other;

    public function behaviors()
    {
        return [
            [
                "class" => TimestampBehavior::className(),
                "updatedAtAttribute" => false,
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'deleted_at' => function () {
                        return time();
                    }
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'aff_promo_link';
    }

    public function beforeSave($insert)
    {
        if ($insert) {

            if ($this->utm_medium == 'other') {
                $this->utm_medium = $this->utm_medium_other;
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['host', 'query_string', 'url', 'user_id', 'promo_code'], 'required'],
            [['created_at', 'user_id', 'hits', 'money'], 'integer'],
            [['promo_code'], 'string', 'max' => 12],
            [['host'], 'string', 'max' => 280],
            [['query_string'], 'string', 'max' => 1000],
            [['url'], 'string', 'max' => 1280],
            [['utm_medium', 'utm_source', 'utm_campaign', 'utm_content', 'utm_term', 'utm_medium_other'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url' => Yii::t('aff', 'Link'),
            'utm_medium' => Yii::t('aff', 'Advertising method'),
            'utm_source' => Yii::t('aff', 'Traffic source'),
            'utm_campaign' => Yii::t('aff', 'Advertising campaign'),
            'utm_content' => Yii::t('aff', 'Advertisement'),
            'utm_term' => Yii::t('aff', 'Terms'),
            'hits' => Yii::t('aff', 'Hits'),
            'money' => Yii::t('aff', 'Money earned'),
        ];
    }

    /**
     * @return array
     */
    public function getUtmMediumVariants()
    {
        return [
            "cpc" => Yii::t('aff', 'Pay per click (cpc)'),
            "cpm" => Yii::t('aff', 'CPM'),
            "social" => Yii::t('aff', 'Social network'),
            "display" => Yii::t('aff', 'Banners'),
            "email" => Yii::t('aff', 'E-mail'),
            "mobile" => Yii::t('aff', 'Mobile advertising'),
            "remarketing" => Yii::t('aff', 'Remarketing'),
            "other" => Yii::t('aff', 'Other...')
        ];
    }

    /**
     * @return string
     */
    public function getUtmMediumTranslated()
    {
        $variants = $this->getUtmMediumVariants();
        return isset($variants[$this->utm_medium]) ? $variants[$this->utm_medium] : $this->utm_medium;
    }

    public function incrementTotalEarned($earned)
    {
        if (!$this->money) {
            $this->money = 0;
        }
        $this->money += $earned;
        $this->update(false, ['money']);
    }
}
