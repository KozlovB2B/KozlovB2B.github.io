<?php

use yii\db\Schema;
use yii\db\Migration;

class m160117_132414_Call_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn("{{%call}}", "end_node_content", "VARCHAR(500) NULL COMMENT 'End node'");
        $this->addColumn("{{%call}}", "end_node_stage", "VARCHAR(75) NULL COMMENT 'End node stage'");
        $this->addColumn("{{%call}}", "nodes_passed", "INT NULL COMMENT 'Nodes passed'");
        $this->addColumn("{{%SiteUserHeadManager}}", "test_executions_today", "INT NOT NULL DEFAULT 0 COMMENT 'Test executions today'");
        $this->alterColumn("{{%SiteUserHeadManager}}", "executions_today", "INT NOT NULL DEFAULT 0 COMMENT 'Test executions today'");
    }

    public function down()
    {
        $this->dropColumn("{{%call}}", "end_node_content");
        $this->dropColumn("{{%call}}", "end_node_stage");
        $this->dropColumn("{{%call}}", "nodes_passed");
        $this->dropColumn("{{%SiteUserHeadManager}}", "test_executions_today");

        return true;
    }
}
