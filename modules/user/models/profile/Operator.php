<?php

namespace app\modules\user\models\profile;


use app\modules\user\models\User;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\helpers\ArrayHelper;
use app\modules\script\models\SipAccount;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use app\modules\user\models\OperatorQuery;

/**
 * This is the model class for table "profile_operator".
 *
 * @property string $head_id
 * @property string $first_name
 * @property string $last_name
 *
 * @property-read User $user
 * @property-read Head $headProfile
 */
class Operator extends TeamMemberProfile
{
    /**
     * @var string New password (used by update operator's data form)
     */
    public $new_password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_operator';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHeadProfile()
    {
        return $this->hasOne(Head::className(), ['user_id' => 'head_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['head_id'], 'required', 'on' => ['invite', 'create', 'accept-invite', 'update-by-user']],
            [['first_name', 'last_name'], 'safe', 'on' => ['invite', 'create', 'accept-invite', 'update-by-user']],
            [['first_name', 'last_name'], 'string', 'max' => 32],
            [['new_password'], 'string'],
        ]);
    }


    /**
     * @return array List of users for report
     */
    public static function getOperatorsList()
    {
        $head_manager = UserHeadManager::findHeadManagerByUser();
        $operators = ArrayHelper::map(Operator::find()->byHead($head_manager->id)->all(), 'id', 'fullNameAndLogin');
        $designers = ArrayHelper::map(Designer::find()->byHead($head_manager->id)->all(), 'id', 'fullNameAndLogin');
        $result = ArrayHelper::merge($designers, $operators);
        $result[$head_manager->id] = $head_manager->user->username;
        $result[Yii::$app->getUser()->getId()] = User::findOne(Yii::$app->getUser()->getId())->username;
        return $result;
    }

    /**
     * @return ActiveDataProvider
     */
    public static function headList()
    {
        $query = new ActiveQuery(static::className());
        $query->where([
            "head_id" => Yii::$app->getUser()->getId()
        ]);

        $query->orderBy(['user_id' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * @return string Full operator name and username
     */
    public function getFullNameAndLogin()
    {
        $full_name = $this->first_name . ' ' . $this->last_name;

        if (strlen(trim($full_name))) {
            return !empty($this->user) ? $full_name . ' (' . $this->user->username . ')' : $full_name;
        }

        return !empty($this->user) ? $this->user->username : 'unknown user';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSip()
    {
        return $this->hasOne(SipAccount::className(), ['id' => 'user_id']);
    }


    /**
     * @inheritdoc
     * @return OperatorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OperatorQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'new_password' => 'Новый пароль',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия'
        ];
    }

    /**
     * Eup
     */
    public function updateData()
    {
        $saved = $this->save();

        if ($saved && $this->new_password) {
            $this->user->resetPassword($this->new_password);
        }

        return $saved;
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
        return '/operator-dashboard';
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
        return 'user_operator';
    }
}
