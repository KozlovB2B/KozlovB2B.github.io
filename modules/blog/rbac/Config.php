<?php

namespace app\modules\blog\rbac;

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
            'blog___blog__admin' => 'Администрирование блога'
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
                    'blog___blog__admin',
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