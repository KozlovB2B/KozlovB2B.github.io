<?php
namespace app\modules\user\models\profile;

use yii\db\ActiveRecord;
use app\modules\user\models\User;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class Profile Абстрактный профиль пользователя - его функциональное назначение и специфичные данные
 *
 * @property-read integer $id (user_id)
 * @property integer $user_id
 *
 * @property-read User $user
 */
abstract class Profile extends ActiveRecord
{
    /**
     * @const string Событие, которое вызывается после того, как пользователь принял приглашение в систему
     */
    const AFTER_ACCEPT_INVITE = 'afterInviteAccept';


    /**
     * @return int
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * @var $this
     */
    protected static $_current;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'userIdInteger' => ['user_id', 'integer'],
            'userIdExist' => ['user_id', 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id'], 'on' => ['create']],
        ];
    }

    /**
     * @return $this
     */
    public static function current()
    {
        if (self::$_current === null) {
            self::$_current = self::findOne(Yii::$app->getUser()->getId());
        }

        return self::$_current;
    }

    /**
     * @return array
     */
    public function scenarios()
    {
        $cfg = [
            'update-by-user' => [
                '!user_id',
            ],
            'insert' => [],
            'create' => [],
            'accept-invite' => [],
            'invite' => [],
        ];

        return ArrayHelper::merge($cfg, parent::scenarios());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }


    /**
     * Создание профиля.
     *
     * @return boolean
     */
    public function create()
    {
        return $this->save();
    }

    /**
     * Роль из системы RBAC, которая соответствует профилю
     *
     * @return string
     */
    public function getRole()
    {
        return $this->getCode();
    }

    /**
     * Используется для файлов вьюх и т.п.
     *
     * @return string
     */
    public function getCode()
    {
        return ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $this->getType())), '_');
    }

    /**
     * Тип профиля
     *
     * @return string
     */
    public function getType()
    {
        return $this->formName();
    }

    /**
     * Название профиля
     *
     * @param string $case Падеж (normal, accusative)
     * @return string
     */
    public function getName($case = null)
    {
        return ProfileRelation::getProfileName($this->getType(), $case);
    }

    /**
     * Интерфейс пользователя в зависимости от его профиля
     *
     * @return string
     */
    public static function layout()
    {
        return "@app/modules/user/views/admin/layout";
    }

    /**
     * Интерфейс пользователя в зависимости от его профиля
     *
     * @return string
     */
    public function getLayout()
    {
        return static::layout();
    }

    /**
     * Экшн куда по-умолчанию отправляется пользователь
     *
     * @return string
     */
    public static function redirect()
    {
        return false;
    }

    /**
     * Куда попадает пользователь после регистрации
     *
     * @return string
     */
    public function welcomePage()
    {
        return '/';
    }

    /**
     * Экшн куда по-умолчанию отправляется пользователь
     *
     * @return string
     */
    public function getRedirect()
    {
        return static::redirect();
    }
}