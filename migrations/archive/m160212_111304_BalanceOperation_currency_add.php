<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_111304_BalanceOperation_currency_add extends Migration
{
    public function up()
    {
        $this->addColumn("{{%balance_operations}}", "currency", "VARCHAR(3) NOT NULL DEFAULT 'RUR' COMMENT 'Currency'");
        $this->alterColumn("{{%balance_operations}}", "amount", "FLOAT(8, 2) NOT NULL COMMENT 'Amount'");
    }

    public function down()
    {
        $this->dropColumn("{{%balance_operations}}", "currency");

        return true;
    }
}
