<?php

namespace app\modules\aff\models;

use app\modules\billing\models\Rate;
use app\modules\user\models\User;
use Yii;
use app\modules\user\models\UserHeadManager;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/**
 * This is the model class for table "affiliate_account".
 *
 * @property integer $id
 * @property integer $total_earned
 * @property string $promo_code
 * @property integer $affiliate_id
 * @property integer $total_affiliate_earned
 * @property boolean $terms_accepted
 * @property integer $registration_hit
 *
 *
 * @property Account $affiliateAccount
 * @property User $affiliate
 * @property User $user
 * @property Hit $hit
 * @property \app\modules\billing\models\Account $billing
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * Current user aff account
     *
     * @var Account
     */
    protected static $_current;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliateAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'affiliate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAffiliate()
    {
        return $this->hasOne(User::className(), ['id' => 'affiliate_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHit()
    {
        return $this->hasOne(Hit::className(), ['id' => 'registration_hit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBilling()
    {
        return $this->hasOne(\app\modules\billing\models\Account::className(), ['id' => 'id']);
    }

    /**
     * @return int Current commission percentage
     */
    public function getPercent()
    {
        return 30;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'affiliate_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        Yii::$app->getModule('aff');

        return [
            [['total_earned', 'affiliate_id', 'total_affiliate_earned'], 'integer'],
            [['terms_accepted'], 'required', 'on' => 'accept_terms', 'requiredValue' => 1, 'message' => Yii::t('aff', 'Please read and agree with all the terms.')],
            [['promo_code'], 'string', 'max' => 6]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('aff', 'ID'),
            'total_earned' => Yii::t('aff', 'Total money earned'),
            'promo_code' => Yii::t('aff', 'Promo code'),
            'affiliate_id' => Yii::t('aff', 'Parent affiliate id'),
            'terms_accepted' => Yii::t('aff', 'Terms accepted'),
            'total_affiliate_earned' => Yii::t('aff', 'Parent affiliate earned'),
        ];
    }

    /**
     * @inheritdoc
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AccountQuery(get_called_class());
    }

    /**
     * Register new affiliate account
     *
     * @param UserHeadManager $user
     * @return bool
     */
    public static function register(UserHeadManager $user)
    {
        $exist = Account::findOne($user->id);

        if ($exist) {
            return false;
        }

        $model = new Account();
        $model->id = $user->id;
        $model->total_earned = 0;

        $model->affiliate_id = null;
        $model->total_affiliate_earned = 0;

        if (isset($_COOKIE["aff_promo"]) && $_COOKIE["aff_promo"]) {
            $parent = Account::find()->parent($_COOKIE["aff_promo"]);
            if ($parent) {
                $model->affiliate_id = $parent->id;
            }
        }

        if (isset($_COOKIE["aff_hit"]) && $_COOKIE["aff_hit"]) {
            $model->registration_hit = $_COOKIE["aff_hit"];
            if ($model->hit) {
                if (!$model->hit->has_registrations) {
                    $model->hit->has_registrations = 0;
                }
                $model->hit->has_registrations++;
                $model->hit->update(false, ['has_registrations']);
            } else {
                $model->registration_hit = null;
            }
        }

        $model->generatePromoCode();
        $promo_exist = Account::findOne(['promo_code' => $model->promo_code]);

        // Generating new promo code til its be unique
        while ($promo_exist) {
            $model->generatePromoCode();
            $promo_exist = Account::findOne(['promo_code' => $model->promo_code]);
        }

        $model->save(false);

        return true;
    }

    public function generatePromoCode()
    {
        $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $this->promo_code = "";
        for ($i = 0; $i < 6; $i++) {
            $this->promo_code .= $chars[mt_rand(0, strlen($chars) - 1)];
        }
    }

    public function getLink()
    {

        $link = Yii::$app->getRequest()->getHostInfo() . "?p=" . $this->promo_code;

        return Html::a($link, $link);
    }

    /**
     * User's attracted users
     */
    public static function userAttractedUsersDataProvider()
    {
        return new ActiveDataProvider([
            'query' => Account::find()->activeByUserCriteria(\Yii::$app->getUser()->getId()),
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
    }

    /**
     * Current user aff account
     *
     * @return Account
     */
    public static function current()
    {
        if (static::$_current === null) {
            static::$_current = Account::findOne(Yii::$app->getUser()->getId());
        }
        return static::$_current;
    }
}
