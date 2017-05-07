<?php

namespace app\modules\user\models\profile;

use Yii;
use app\modules\user\models\User;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile_relation".
 *
 * @property integer $user_id
 * @property string $profile_class
 * @property integer $is_current
 *
 * @property-read Profile $profile
 * @property-read User $user
 */
class ProfileRelation extends ActiveRecord
{

    /** @const string Класс профиля "Владелец" */
    const PROFILE_CLASS_OWNER = 'Owner';

    /** @const string Класс профиля "Администратор" */
    const PROFILE_CLASS_ADMIN = 'Admin';

    /** @const string Класс профиля "Оператор" */
    const PROFILE_CLASS_OPERATOR = 'Operator';

    /** @const string Класс профиля "Проектировщик" */
    const PROFILE_CLASS_DESIGNER = 'Designer';

    /** @const string Класс профиля "Руководитель" */
    const PROFILE_CLASS_HEAD = 'Head';

    /** @const string Класс профиля "Бухгалтер" */
    const PROFILE_CLASS_ACCOUNTANT = 'Accountant';

    /** @var Profile */
    protected $_profile;

    /**
     * Названия всех профилей в определенном падеже
     *
     * @param string $case Падеж (normal, accusative)
     * @return array
     */
    public static function profileNames($case = null)
    {
        if ($case === null) {
            $case = 'normal';
        }

        $data = [
            'normal' => [
                ProfileRelation::PROFILE_CLASS_OWNER => 'Владелец',
                ProfileRelation::PROFILE_CLASS_ADMIN => 'Админ',
                ProfileRelation::PROFILE_CLASS_OPERATOR => 'Оператор',
                ProfileRelation::PROFILE_CLASS_DESIGNER => 'Проектировщика',
                ProfileRelation::PROFILE_CLASS_HEAD => 'Руководитель',
                ProfileRelation::PROFILE_CLASS_ACCOUNTANT => 'Бухгалтер',
            ],
            'accusative' => [
                ProfileRelation::PROFILE_CLASS_OWNER => 'владельца',
                ProfileRelation::PROFILE_CLASS_ADMIN => 'администратора',
                ProfileRelation::PROFILE_CLASS_OPERATOR => 'оператора',
                ProfileRelation::PROFILE_CLASS_DESIGNER => 'проектировщика',
                ProfileRelation::PROFILE_CLASS_HEAD => 'руководителя',
                ProfileRelation::PROFILE_CLASS_ACCOUNTANT => 'бухгалтера',
            ]
        ];

        if (!isset($data[$case])) {
            throw new InvalidParamException('Указан неверный падеж. Возможные падежи: normal, accusative');
        }

        return $data[$case];
    }


    /**
     * Выдает название профиля
     *
     * @param string $class Название класса профиля
     * @param string $case Падеж
     * @return string Название профиля
     * @throws InvalidConfigException
     */
    public static function getProfileName($class, $case = null)
    {
        $profiles = ProfileRelation::profileNames($case);

        if (!isset($profiles[$class])) {
            throw new InvalidConfigException('Указан неизвестный класс профиля ' . $class);
        }

        return $profiles[$class];
    }

    /**
     * Название профиля текущего объекта
     *
     * @param string $case Падеж
     * @return string
     */
    public function getName($case = null)
    {
        return ProfileRelation::getProfileName($this->profile_class, $case);
    }

    /**
     * Список профилей, доступный в форме добавления пользователя администратором
     *
     * @return array
     */
    public static function profilesForCreatingForm()
    {
        $result = [];

        $cfg = [
            ProfileRelation::PROFILE_CLASS_OPERATOR,
            ProfileRelation::PROFILE_CLASS_DESIGNER,
            ProfileRelation::PROFILE_CLASS_HEAD,
            ProfileRelation::PROFILE_CLASS_ACCOUNTANT,
        ];

        if (Yii::$app->getUser()->can('user___user__create_admin')) {
            $cfg[] = ProfileRelation::PROFILE_CLASS_ADMIN;
        }

        if (Yii::$app->getUser()->can('user___user__create_owner')) {
            $cfg[] = ProfileRelation::PROFILE_CLASS_OWNER;
        }

        foreach ($cfg as $c) {
            $result[$c] = ProfileRelation::getProfileName($c);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile_relation';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['user_id', 'profile_class'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'profile_class'], 'required'],
            [['user_id', 'is_current'], 'integer'],
            [['profile_class'], 'string', 'max' => 75],
            [['user_id', 'profile_class'], 'unique', 'targetAttribute' => ['user_id', 'profile_class'], 'message' => 'У заданного пользователя уже есть такой профиль!'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'profile_class' => 'Profile Class',
            'is_current' => 'Is Current'
        ];
    }

    /**
     * Если мы создаем первый профиль для пользователя - он автоматически назначается текущим
     *
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!$this->is_current) {
            $others_exist = ProfileRelation::find()->where(['user_id' => $this->user_id])->exists();

            if (!$others_exist) {
                $this->is_current = 1;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * Если мы сохранили реляцию профиля как текущую - все остальные реляции профилей по этому пользователю помечаются как не текущие
     *
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($this->is_current) {
            Yii::$app->getDb()->createCommand()->update($this->tableName(), [
                'is_current' => 0
            ], 'user_id = :user_id AND profile_class != :profile_class', [':user_id' => $this->user_id, ':profile_class' => $this->profile_class])->execute();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Если это единственный оставшийся профиль пользователя - его нельзя удалить
     *
     * @return bool
     */
    public function beforeDelete()
    {
        $is_last = !ProfileRelation::find()->where(['user_id' => $this->user_id])->andWhere('profile_class != :profile_class', [':profile_class' => $this->profile_class])->exists();
        if ($is_last) {
            $this->addError('user_id', 'Нельзя удалить единственный профиль пользователя!');
            return false;
        }

        return parent::beforeDelete();
    }

    /**
     * После удаления реляции профиля - первый попавшийся другой профиль этого пользователя назначается текущим
     */
    public function afterDelete()
    {
        /** @var ProfileRelation $other_profile */
        $other_profile = ProfileRelation::find()->where(['user_id' => $this->user_id])->one();
        $other_profile->is_current = 1;
        $other_profile->save(false);
    }

    /**
     * Выдает полное название класса профиля по его короткому названию
     *
     * @param $class
     * @return string
     */
    public static function profileClassFullName($class)
    {
        return 'app\modules\user\models\profile\\' . $class;
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        if ($this->_profile === null) {
            /** @var Profile $className */
            $className = self::profileClassFullName($this->profile_class);

            $this->_profile = $className::findOne($this->user_id);
        }

        return $this->_profile;
    }
}

