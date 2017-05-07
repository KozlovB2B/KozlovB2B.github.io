<?php

use yii\db\Schema;
use yii\db\Migration;

class m160205_090956_Eng_version extends Migration
{
    public function up()
    {
        $this->addColumn("{{%SiteUserHeadManager}}", "division", "VARCHAR(5) NOT NULL DEFAULT 'ru-RU' COMMENT 'Division'");
        $this->addColumn("{{%billing_balance}}", "currency", "VARCHAR(3) NOT NULL DEFAULT 'RUR' COMMENT 'Currency'");
    }

    public function down()
    {
        $this->dropColumn("{{%SiteUserHeadManager}}", "division");
        $this->dropColumn("{{%billing_balance}}", "currency");

        return true;
    }
}
