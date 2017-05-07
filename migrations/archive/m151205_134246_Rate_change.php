<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m151205_134246_Rate_change extends Migration
{
    /**
     * Для всех новый пользователей: нужно устанавливать на 14 дней тарифный план БИЗНЕС3
     * Ограничение  (дополнительное ко всем ограничениям БИЗНЕС3):
     * нельзя экспортировать скрипты в файл (втягивать можно)
     *
     * Тарифные планы и ограничения:
     *
     * БЕСПЛАТНЫЙ 0руб/мес
     * Ограничения:
     * есть, но не работают логины операторов
     * нельзя делать более 10 прогонов скрипта в день, включая тестовые в демо-режиме
     * не больше 50 проговор скриптов в месяц
     * нельзя делать экспорт/сохранение скриптов (втягивать можно)
     *
     * ПРОЕКТ1 500 руб/мес
     * Ограничения:
     * есть, но не работают логины операторов
     *
     * БИЗНЕС3 1370 руб/мес
     * работают не более 3х операторов (остальные выключаются, как неактивные)
     *
     * КОМАНДА10 2970 руб/мес
     * работают не более 10 операторов (остальные выключаются, как неактивные)
     * СПИСАНИЯ: ежедневные.
     * БЛОКИРОВКА: по достижению/превышению минимального порога. По-умолчанию минимальный порог - 0руб
     * (если денег нет, пользователь может вручную перейти на тариф БЕСПЛАТНЫЙ)
     * ИНДИКАЦИЯ:
     * В верхней панели вместо "пробный период" нужно писать Тариф НАЗВАНИЕ до: ДАТА ОТКЛЮЧЕНИЯ
     * ПРЕДУПРЕЖДЕНИЯ1,2:
     * высылать е-мейл за 3 и 1 день до блокировки.
     * Тема: Ваш аккаунт будет заблокирован через Х дней
     * Содержание: "Уважаемый..... Закончились деньги в сервисе ScriptDesigner. Чтобы продолжить работу без остановок, Пожалуйста, срочно пополните счет в сервисе ScriptDesigner. Пройдите по ссылке и выберите наиболее удобный способ оплаты".
     * ПРЕДУПРЕЖДЕНИЕ3:
     * в день блокировки с копией на ScriptDesigner@b2bbasis.ru
     * Тема: Ваш аккаунт заблокирован!!!
     */
    public function up()
    {
        $this->addColumn("{{%rate}}", "is_default", "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Script executions per day'");
        $this->addColumn("{{%rate}}", "executions_per_day", "INT NULL COMMENT 'Script executions per day'");
        $this->addColumn("{{%rate}}", "executions_per_month", "INT NULL COMMENT 'Script executions per month'");
        $this->addColumn("{{%rate}}", "export_allowed", "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Exporting allowed'");
        $this->addColumn("{{%rate}}", "archived_at", "INT NULL COMMENT 'Archived'");

        $this->insert("{{%rate}}", [
            'name' => 'БЕСПЛАТНЫЙ',
            'operators_threshold' => 0,
            'monthly_fee' => 0,
            'created_at' => time(),
            'is_default' => 0,
            'executions_per_day' => 10,
            'executions_per_month' => 50,
            'export_allowed' => 0
        ]);

        $this->insert("{{%rate}}", [
            'name' => 'ПРОЕКТ1',
            'operators_threshold' => 0,
            'monthly_fee' => 500,
            'created_at' => time(),
            'is_default' => 0,
            'export_allowed' => 1
        ]);

        $this->insert("{{%rate}}", [
            'name' => 'БИЗНЕС3',
            'operators_threshold' => 3,
            'monthly_fee' => 1370,
            'created_at' => time(),
            'is_default' => 1,
            'export_allowed' => 1
        ]);

        $this->insert("{{%rate}}", [
            'name' => 'КОМАНДА10',
            'operators_threshold' => 10,
            'monthly_fee' => 2970,
            'created_at' => time(),
            'is_default' => 0,
            'export_allowed' => 1
        ]);

        $this->addColumn("{{%billing_account}}", "min_balance", "INT NOT NULL DEFAULT 0 COMMENT 'Min balance'");
        $this->addColumn("{{%billing_account}}", "rate_name", "VARCHAR(255) NOT NULL COMMENT 'Rate name'");
        $this->addColumn("{{%billing_account}}", "monthly_fee", "INT NULL COMMENT 'Monthly fee'");
        $this->addColumn("{{%billing_account}}", "operators_threshold", "INT NULL COMMENT 'Max active operators'");
        $this->addColumn("{{%billing_account}}", "executions_per_day", "INT NULL COMMENT 'Script executions per day'");
        $this->addColumn("{{%billing_account}}", "executions_per_month", "INT NULL COMMENT 'Script executions per month'");
        $this->addColumn("{{%billing_account}}", "export_allowed", "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Exporting allowed'");
        $this->addColumn("{{%billing_account}}", "blocked", "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Blocked'");
        $this->addColumn("{{%billing_account}}", "last_rate_change", "INT NULL COMMENT 'Last rate change'");

        $this->createTable('{{%billing_rate_change_history}}', [
            'id' => Schema::TYPE_PK,
            'account_id' => "INT NOT NULL COMMENT 'Account'",
            'rate_from' => "INT NULL COMMENT 'Rate from'",
            'rate_to' => "INT NOT NULL COMMENT 'Rate to'",
            'rate_from_data' => "TEXT NULL COMMENT 'Rate from data'",
            'rate_to_data' => "TEXT NOT NULL COMMENT 'Rate to data'",
            'created_at' => "INT NULL COMMENT 'Date'"
        ], $this->tableOptions);

        $this->createTable('{{%billing_blocking}}', [
            'id' => Schema::TYPE_PK,
            'account_id' => "INT NOT NULL COMMENT 'Account'",
            'deadline' => "INT UNSIGNED NOT NULL COMMENT 'Blocking deadline'",
            'first_warning_sent' => "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'First warning sent'",
            'second_warning_sent' => "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Second warning sent'",
            'created_at' => "INT NULL COMMENT 'Created'",
            'performed_at' => "INT NULL COMMENT 'Performed'",
            'cancelled_at' => "INT NULL COMMENT 'Cancelled'",
        ], $this->tableOptions);

    }

    public function down()
    {
        $this->dropColumn("{{%rate}}", "executions_per_day");
        $this->dropColumn("{{%rate}}", "executions_per_month");
        $this->dropColumn("{{%rate}}", "export_allowed");
        $this->dropColumn("{{%rate}}", "archived_at");

        $this->dropColumn("{{%billing_account}}", "min_balance");
        $this->dropColumn("{{%billing_account}}", "rate_name");
        $this->dropColumn("{{%billing_account}}", "monthly_fee");
        $this->dropColumn("{{%billing_account}}", "operators_threshold");
        $this->dropColumn("{{%billing_account}}", "executions_per_day");
        $this->dropColumn("{{%billing_account}}", "executions_per_month");
        $this->dropColumn("{{%billing_account}}", "export_allowed");
        $this->dropColumn("{{%billing_account}}", "blocked");
        $this->dropColumn("{{%billing_account}}", "last_rate_change");

        $this->dropTable("{{%billing_blocking}}");
        $this->dropTable("{{%billing_rate_change_history}}");
        return true;
    }
}
