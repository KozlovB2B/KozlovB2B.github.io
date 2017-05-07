<?php

namespace app\modules\aff\rbac;

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
            'aff___account__manage_own' => 'Управление своим аккаунтом партнерки',
            'aff___hit__index' => 'Просмотр своих посещений',
            'aff___promo_link__index' => 'Просмотр своих промо ссылок',
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
                    'aff___account__manage_own',
                    'aff___hit__index',
                    'aff___promo_link__index',
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