<?php

namespace app\modules\billing\rbac;

use rbacc\components\ConfigBase;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;


use app\modules\billing\rbac\rules\InvoiceManageOwnRule;
use app\modules\billing\rbac\rules\BillingChangeRateRule;

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

            'billing__rate__change' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Смена тарифа',
                'rule' => new BillingChangeRateRule()
            ],

            'billing___invoice__manage' => 'Управление счетами',
            'billing___balance_operations__cashflow_report' => 'Просмотр отчета по списаниям',


            'billing___invoice__manage_own' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Управление своими счетами',
                'rule' => new InvoiceManageOwnRule(),
                'children' => ['billing___invoice__manage']
            ],

            'billing___account__manage' => 'Управление аккаунтом биллинга',
            'billing__rate__set' => 'Установка тарифа пользователю',
            'billing___account__manage_own' => 'Управление своим аккаунтом биллинга',
            'billing___rate__manage' => 'Управление тарифами',
            'billing___balance_operations__index_all' => 'Просмотр всех операций по балансу',
            'billing___rate_change_history__index_all' => 'Просмотр всех смен тарифов',
            'billing___use_withdraw__index' => 'xz',
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
                    'billing__rate__change',
                    'billing___invoice__manage_own',
                    'billing___account__manage_own'
                ],
            ],
            'admin' => [
                'children' => [
                    'billing___invoice__manage',
                    'billing___account__manage',
                    'billing__rate__set',
                    'billing___rate__manage',
                    'billing___balance_operations__index_all',
                    'billing___rate_change_history__index_all',
                    'billing___use_withdraw__index',
                    'billing___balance_operations__cashflow_report',
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