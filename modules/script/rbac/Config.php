<?php

namespace app\modules\script\rbac;

use rbacc\components\ConfigBase;
use yii\rbac\Item;
use yii\helpers\ArrayHelper;

use app\modules\script\rbac\rules\ScriptUpdateOwnRule;
use app\modules\script\rbac\rules\CanExecuteScriptRule;
use app\modules\script\rbac\rules\CallEndReasonUpdateOwnRule;
use app\modules\script\rbac\rules\BillingScriptExportAllowedRule;
use app\modules\script\rbac\rules\SipAccountManageOwnRule;
use app\modules\script\rbac\rules\UpdateOwnRule;


/**
 * Class Config
 *
 * Scripts RBAC configuration class
 */
class Config extends ConfigBase
{
    /**
     * Задает правила для модуля рекламодатель
     *
     * @return array
     */
    protected function definePermissions()
    {
        return [
            'script___script__update' => 'Обновить скрипт',
            'script___script__delete' => 'Обновить скрипт',


            'script___script__update_own' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Обновить данные своего скрипта',
                'rule' => new ScriptUpdateOwnRule(),
                'children' => ['script___script__update']
            ],

            'script___script__delete_own' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Удалить свой скрипт',
                'rule' => new ScriptUpdateOwnRule(),
                'children' => ['script___script__delete']
            ],

            'script___script__create' => 'Создать скрипт',
            'script___script__index' => 'Просмотр списка скриптов',
            'script___release__index' => 'Просмотр публикаций',



            'script___script__export' => 'Экспорт скриптов',
            'script___script__export_own' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Экспорт своего скрипта',
                'rule' => new BillingScriptExportAllowedRule(),
                'children' => ['script___script__export']
            ],


            'script___call_end_reason__manage' => 'Deprecated',
            'script___call_end_reason__update' => 'Deprecated',

            'script___call_end_reason__update_own' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Deprecated',
                'rule' => new CallEndReasonUpdateOwnRule(),
                'children' => ['script___call_end_reason__update']
            ],

            'script___call__view' => 'Просмотр звонка',

            'script___sip_account__manage' => 'Управление SIP аккаунтами',
            'script___sip_account__manage_children' => 'Управление SIP аккаунтами своих операторов',

            'script___sip_account__manage_self' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Deprecated',
                'rule' => new SipAccountManageOwnRule(),
                'children' => ['script___sip_account__manage']
            ],

            'script___call__perform' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Deprecated',
                'rule' => new CanExecuteScriptRule()
            ],


            'script___call__statistics' => 'Стата по звонкам',
            'script___report__view' => 'Просмотр отчета???',
            'script___script_export_log__index' => 'Просмотр журнала экспорта скриптов',
            'script___gift__accept' => 'Принять подарок',

            'script___field__create' => 'Создать поле',
            'script___field__update' => 'Обновить поле',
            'script___field__update_own' => [
                'type' => Item::TYPE_PERMISSION,
                'description' => 'Обновить данные своего поля',
                'rule' => new UpdateOwnRule(),
                'children' => ['script___field__update']
            ]

        ];
    }

    /**
     * Задает роли для модуля рекламодатель
     *
     * @return array
     */
    protected function defineRoles()
    {
        return [
            'user_operator' => [
                'children' => [
                    'script___call__perform',
                    'script___release__index',
                    'script___script__index',
                    'script___sip_account__manage_self',
                ],
            ],
            'user_designer' => [
                'children' => [
                    'script___call__perform',
                    'script___script__index',
                    'script___script__update_own',
                    'script___script__create',
                    'script___script__export_own',
                    'script___call__view',
                    'script___call__statistics',
                    'script___report__view',
                    'script___field__create',
                    'script___field__update_own'
                ],
            ],
            'user_head_manager' => [
                'children' => [
                    'script___call__perform',
                    'script___gift__accept',
                    'script___script__index',
                    'script___script__update_own',
                    'script___script__delete_own',
                    'script___script__create',
                    'script___script__export_own',
                    'script___call_end_reason__manage', // deprecated
                    'script___call_end_reason__update_own', // deprecated
                    'script___call__view',
                    'script___call__statistics',
                    'script___report__view',
                    'script___sip_account__manage_children',
                    'script___field__create',
                    'script___field__update_own'
                ],
            ],
            'admin' => [
                'children' => [
                    'script___script_export_log__index'
                ],
            ],

        ];
    }

    /**
     * @return array
     */
    public function getData()
    {
//        var_dump($this->definePermissions());exit;
        return ArrayHelper::merge($this->definePermissions(), $this->defineRoles());
    }

}