<?php
namespace app\modules\user\models\profile;

use yii\helpers\ArrayHelper;

/**
 * Class TeamMemberProfile Абстрактный профиль участника команды - его функциональное назначение и специфичные данные
 *
 * @property string $first_name
 * @property string $last_name
 */
abstract class TeamMemberProfile extends Profile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['first_name', 'last_name'], 'default', 'value' => null],
            [['first_name', 'last_name'], 'safe', 'on' => 'invite'],
            [['first_name', 'last_name'], 'string', 'max' => 32],
            [['first_name', 'last_name'], 'unique', 'targetAttribute' => ['first_name', 'last_name'], 'message' => 'Сотрудник с таким именем и фамлией у уже есть.'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'first_name' => 'Имя',
            'last_name' => 'Фамилия'
        ];
    }

    /**
     * @return string Full designer name and username
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
}