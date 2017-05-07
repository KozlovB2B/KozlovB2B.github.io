<?php

namespace app\modules\user\models\profile;


use app\modules\user\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use app\modules\user\models\query\DesignerQuery;

/**
 * This is the model class for table "profile_designer".
 *
 * @property string $head_id
 * @property string $first_name
 * @property string $last_name
 *
 * @property-read User $user
 * @property-read Head $headProfile
 */
class Designer extends TeamMemberProfile
{
    /**
     * @var string New password (used by update designer's data form)
     */
    public $new_password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_designer';
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
            [['new_password'], 'string'],
        ]);
    }


    /**
     * @return array List of users for report
     */
    public static function getDesignersList()
    {
        $designers = ArrayHelper::map(Designer::find()->byHead(Yii::$app->getUser()->getId())->all(), 'id', 'fullNameAndLogin');
        $designers[Yii::$app->getUser()->getId()] = User::findOne(Yii::$app->getUser()->getId())->username;
        return $designers;
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
     * @inheritdoc
     * @return DesignerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DesignerQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'new_password' => 'Новый пароль'
        ]);
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
        return '/designer-dashboard';
    }

    /**
     * @inheritdoc
     */
    public function getRole()
    {
        return 'user_designer';
    }
}
