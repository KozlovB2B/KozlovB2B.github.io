<?php

namespace app\modules\integration\rbac;

use rbacc\components\ConfigBase;
use yii\helpers\ArrayHelper;

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
            'integration___integration__manage' => 'Управление интеграциями',
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
            'user_head_manager' => [
                'children' => [
                    'integration___integration__manage'
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