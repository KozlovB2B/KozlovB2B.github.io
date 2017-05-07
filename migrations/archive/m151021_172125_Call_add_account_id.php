<?php

use yii\db\Schema;
use yii\db\Migration;

class m151021_172125_Call_add_account_id extends Migration
{
    public function up()
    {
        $this->addColumn("{{%call}}", "account_id", "INT NULL COMMENT 'Account id'");
        $this->createIndex('account_id', '{{%call}}', 'account_id');
    }

    public function down()
    {
        $this->dropColumn("{{%call}}", "account_id");
    }
}
