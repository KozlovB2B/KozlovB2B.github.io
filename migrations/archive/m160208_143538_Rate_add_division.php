<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_143538_Rate_add_division extends Migration
{
    public function up()
    {
        $this->addColumn("{{%rate}}", "division", "VARCHAR(5) NOT NULL DEFAULT 'ru-RU' COMMENT 'Division'");
        $this->addColumn("{{%rate}}", "currency", "VARCHAR(3) NOT NULL DEFAULT 'RUR' COMMENT 'Currency'");


        $this->insert("{{%rate}}", [
            'name' => 'FREE',
            'operators_threshold' => 0,
            'monthly_fee' => 0,
            'created_at' => time(),
            'is_default' => 0,
            'executions_per_day' => 10,
            'executions_per_month' => 50,
            'export_allowed' => 0,
            'currency' => 'USD',
            'division' => 'en-US',
        ]);

        $this->insert("{{%rate}}", [
            'name' => 'STARTING UP',
            'operators_threshold' => 0,
            'monthly_fee' => 19,
            'created_at' => time(),
            'is_default' => 0,
            'export_allowed' => 1,
            'currency' => 'USD',
            'division' => 'en-US',
        ]);

        $this->insert("{{%rate}}", [
            'name' => 'BUSINESS 3',
            'operators_threshold' => 2,
            'monthly_fee' => 47,
            'created_at' => time(),
            'is_default' => 1,
            'export_allowed' => 1,
            'currency' => 'USD',
            'division' => 'en-US',
        ]);

        $this->insert("{{%rate}}", [
            'name' => 'BUSINESS 10',
            'operators_threshold' => 9,
            'monthly_fee' => 99,
            'created_at' => time(),
            'is_default' => 0,
            'export_allowed' => 1,
            'currency' => 'USD',
            'division' => 'en-US',
        ]);
    }

    public function down()
    {
        $this->dropColumn("{{%rate}}", "division");
        $this->dropColumn("{{%rate}}", "currency");

        return true;
    }
}
