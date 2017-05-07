<?php

namespace app\modules\user\rbac;

use app\modules\user\rbac\rules\UserUpdateNotOwnerOrAdminRule;
use rbacc\components\ConfigBase;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;


/**
 * Class Config
 *
 * @package app\modules\user\rbac
 */
class Config extends ConfigBase
{
    /**
     * Задает правила для модуля пользователи
     *
     * @return array
     */
    protected function definePermissions()
    {
        return [
            'user___profile__switch' => 'Сменить профиль',

            'user___operator__dashboard' => 'Стартовая панель оператора',

            'user___designer__dashboard' => 'Стартовая панель проектировщика',

            'user___admin__dashboard' => 'Стартовая панель админа',

            'user___profile__update_own' => 'Редактировать данные своего профиля',

            'user___user__index' => 'Просматривать список прользователей',

            'user___user__create' => 'Создать пользователя',

            'user___user__create_admin' => 'Пригласить администратора',

            'user___user__create_owner' => 'Пригласить владельца',

            'user___user__update' => 'Просматривать и редактировать данные любого пользователя, в том числе админов или других владельцев',

            'user___user__update_not_owner_or_admin' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Редактировать данные пользователей, которые не являются владельцами или админами',
                'rule' => new UserUpdateNotOwnerOrAdminRule(),
                'children' => ['user___user__update']
            ],

            'user___head__dashboard' => 'Панель управления руководителя'
        ];
    }

    /**
     * Задает роли для модуля пользователи
     *
     * @return array
     */
    protected function defineRoles()
    {
        return [
            'user' => [
                'type' => Item::TYPE_ROLE,
                'description' => 'Пользователь системы',
                'children' => [
                    'user___profile__switch',
                    'user___profile__update_own'
                ],
            ],
            'user_designer' => [
                'type' => Item::TYPE_ROLE,
                'description' => 'Проектировщик',
                'children' => [
                    'user',
                    'user___designer__dashboard',
                ],
            ],
            'user_operator' => [
                'type' => Item::TYPE_ROLE,
                'description' => 'Оператор',
                'children' => [
                    'user',
                    'user___operator__dashboard',
                ],
            ],
            'user_head_manager' => [
                'type' => Item::TYPE_ROLE,
                'description' => 'Руководитель',
                'children' => [
                    'user',
                    'user___head__dashboard',
                ],
            ],
            'admin' => [
                'type' => Item::TYPE_ROLE,
                'description' => 'Администратор',
                'children' => [
                    'user',
                    'user___user__index',
                    'user___user__update_not_owner_or_admin',
                    'user___admin__dashboard',
                    'user___user__create',
                ],
            ],
            'god' => [
                'type' => Item::TYPE_ROLE,
                'description' => 'Владелец',
                'children' => [
                    'admin',
                    'user___user__update',
                    'user___user__create_admin',
                    'user___user__create_owner',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return ArrayHelper::merge($this->definePermissions(), $this->defineRoles());
    }

}