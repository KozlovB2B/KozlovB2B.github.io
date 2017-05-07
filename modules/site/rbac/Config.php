<?php

namespace app\modules\site\rbac;

use app\modules\site\rbac\rules\UserOperatorUpdateChildrenRule;
use rbacc\components\ConfigBase;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;

use app\modules\site\rbac\rules\UserOperatorCreateRule;
use app\modules\site\rbac\rules\VariantsReportRule;


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
            'admin___access' => 'Админский доступ',
            'site___user_operator__manage' => 'Управление операторами',
            'site___tooltip__skip' => 'Убирать подсказки',
            'site___user_operator__create' => 'Создать оператора',
            'site___user_operator__update' => 'Обновить оператора',


            'site___user_operator__update_own' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Обновлять данные своих операторов',
                'rule' => new UserOperatorUpdateChildrenRule(),
                'children' => ['site___user_operator__update']
            ],
            'site___user_operator__create_own' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Создать своего оператора?',
                'rule' => new UserOperatorCreateRule(),
                'children' => ['site___user_operator__create']
            ],

            'script___hits_report__view' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Отчет по популярности вариантов',
                'rule' => new VariantsReportRule()
            ],


            'site___instruction__manage' => 'Управление инструкциями',
            'site___instruction__view' => 'Просмотр инструкций',
            'user___account__manage' => 'Управление аккаунтом пользователя',
            'user___account__profile' => 'Просмотр своего профиля',

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
                'children' => [
                    'site___tooltip__skip'
                ],
            ],
            'user_head_manager' => [
                'children' => [
                    'site___user_operator__manage',
                    'script___hits_report__view',
                    'site___user_operator__update_own',
                    'site___user_operator__create_own',
                    'site___instruction__view',
                    'user___account__profile'
                ],
            ],
            'admin' => [
                'children' => [
                    'admin___access',
                    'site___user_operator__create',
                    'site___instruction__manage',
                    'user___account__manage',
                ],
            ]
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