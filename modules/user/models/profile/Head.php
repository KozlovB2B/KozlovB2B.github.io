<?php

namespace app\modules\user\models\profile;

use app\modules\user\models\User;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


/**
 * This is the model class for table "profile_operator".
 *
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $phone
 * @property boolean $accept_terms
 *
 *
 * @property User $user
 * @property UserHeadManager $info
 *
 * @method Head ::current()
 */
class Head extends Profile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_head';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasOne(UserHeadManager::className(), ['id' => 'user_id']);
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['accept_terms'], 'integer'],
            [['first_name', 'middle_name', 'last_name'], 'string', 'max' => 32],
            [['phone'], 'string', 'max' => 20],
            [['phone'], 'required', 'on' => ['create', 'accept-invite', 'update-by-user']],
            [['first_name'], 'required', 'on' => ['amo-register']],
            [['phone', 'accept_terms'], 'safe', 'on' => 'invite'],
            ['accept_terms', 'required', 'requiredValue' => 1, 'message' => 'Пожалуйста прочтите и примите условия!', 'on' => ['create', 'accept-invite', 'update-by-user']],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Телефон',
            'first_name' => 'Имя',
            'middle_name' => 'Отчество',
            'last_name' => 'Фамилия',
            'accept_terms' => 'Я ознакомился полностью и принимаю правила ' . Html::a('Пользовательского соглашения по работе с системой', ['/head-terms']),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function layout()
    {
        return "@app/modules/user/views/head/layout";
    }

    /**
     * Экшн куда по-умолчанию отправляется пользователь
     *
     * @return string
     */
    public static function redirect()
    {
//        return Url::to(["/site/site/manual"]);
        return Url::to(["/site/site/head-dashboard"]);
    }

    /**
     * @return string
     */
    public function getFullNameOrUsername()
    {
        if ($this->last_name || $this->first_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        } else {
            return $this->user->username;
        }
    }

    /**
     * @inheritdoc
     */
    public function getRole()
    {
        return 'user_head_manager';
    }
}
