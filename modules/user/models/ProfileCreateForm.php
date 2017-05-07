<?php
namespace app\modules\user\models;

use app\modules\user\models\profile\ProfileRelation;
use Yii;
use yii\base\Model;
use yii\helpers\Html;
use app\modules\user\models\profile\Profile;

/**
 * Class ProfileCreateForm Создание дополнительного профиля для пользователя
 *
 * @package app\modules\user\models
 */
class ProfileCreateForm extends Model
{
    /** @var string Какой профиль будем создавать */
    public $profile_class;

    /** @var User */
    public $user;

    /** @var Profile */
    public $profile;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['profile_class', 'required']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'profile_class' => 'Профиль'
        ];
    }

    /**
     * @var array Список профилей доступных для добавления (тех которых еще нет у пользователя)
     */
    protected $_profiles_available;

    /**
     * Список профилей доступных для добавления
     * (тех которых еще нет у пользователя)
     *
     * @return array
     */
    public function profilesAvailable()
    {
        if ($this->_profiles_available === null) {
            $this->_profiles_available = ProfileRelation::profileNames();

            foreach ($this->user->profileRelations as $r) {
                unset($this->_profiles_available[$r->profile_class]);
            }
        }

        return $this->_profiles_available;
    }

    /**
     * Устанавливает профиль для создания если ничего не выбрано
     */
    public function setDefaultProfile()
    {
        $this->profile_class = array_keys($this->profilesAvailable())[0];
    }

    /**
     * @return Profile
     */
    public function getProfile()
    {
        if ($this->profile) {
            return $this->profile;
        }

        if (!$this->profile_class) {
            $this->setDefaultProfile();
        }

        $this->profile = Yii::createObject([
            'class' => ProfileRelation::profileClassFullName($this->profile_class),
            'user_id' => $this->user->id
        ]);

        return $this->profile;
    }


    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'user___profile__create_form';
    }

    /**
     * Создает профиль
     *
     * @return bool
     */
    public function create()
    {
        if (!$this->getProfile()->validate()) {
            $this->addError('profile', 'Не удалось создать профиль: ' . strip_tags(Html::errorSummary($this->profile, ['header' => false])));

            return false;
        }

        $transaction = Yii::$app->getDb()->beginTransaction();

        try {
            $relation = new ProfileRelation();
            $relation->user_id = $this->user->id;
            $relation->profile_class = $this->profile_class;

            if (!$relation->save()) {
                $this->addErrors($relation->getErrors());
                $transaction->rollBack();
                return false;
            }

            $this->getProfile()->user_id = $this->user->id;

            if (!$this->getProfile()->create()) {
                $this->addError('profile_class', 'Не удалось создать профиль: ' . strip_tags(Html::errorSummary($this->profile, ['header' => false])));

                $transaction->rollBack();
                return false;
            }

            $role = Yii::$app->getAuthManager()->getRole($this->getProfile()->getRole());

            if (!$role) {
                throw new \RuntimeException('Не найдена роль ' . $this->getProfile()->getRole());
            }

            Yii::$app->getAuthManager()->assign($role, $this->user->id);

            $transaction->commit();

            return true;
        } catch (\Exception $ex) {
            $transaction->rollBack();
            $this->addError('profile_class', $ex->getMessage());
            return false;
        }
    }
}
