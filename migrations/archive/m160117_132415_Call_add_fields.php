<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_132415_Call_add_fields extends Migration
{
    public function up()
    {
        $this->alterColumn("{{%SiteUserHeadManager}}", "test_executions_today", "INT NOT NULL DEFAULT 0 COMMENT 'Test executions today'");
        $this->alterColumn("{{%SiteUserHeadManager}}", "executions_today", "INT NOT NULL DEFAULT 0 COMMENT 'Test executions today'");
        $this->addColumn("{{%SiteUserHeadManager}}", "test_executions_this_month", "INT NOT NULL DEFAULT 0 COMMENT 'Test executions this month'");
        $this->addColumn("{{%SiteUserHeadManager}}", "executions_this_month", "INT NOT NULL DEFAULT 0 COMMENT 'Executions this month'");
    }

    public function down()
    {
        $this->dropColumn("{{%SiteUserHeadManager}}", "test_executions_this_month");
        $this->dropColumn("{{%SiteUserHeadManager}}", "executions_this_month");

        return true;
    }
}
