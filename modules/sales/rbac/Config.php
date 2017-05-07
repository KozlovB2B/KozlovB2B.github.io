<?php

namespace app\modules\sales\rbac;

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
            'sales___access' => 'Доступ к модулю продажи'
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
            'admin' => [
                'children' => [
                    'sales___access',
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