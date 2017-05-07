<?php

use yii\db\Migration;

class m151108_152702_Script_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn("{{%script}}", "max_node", "INT NOT NULL DEFAULT 0 COMMENT 'Max node id'");
        $this->addColumn("{{%script}}", "max_edge", "INT NOT NULL DEFAULT 0 COMMENT 'Max edge id'");
        $this->addColumn("{{%script}}", "data_json", "TEXT NULL COMMENT 'Script dataset'");

    }

    public function down()
    {
        $this->dropColumn("{{%script}}", "max_node");
        $this->dropColumn("{{%script}}", "max_edge");
        $this->dropColumn("{{%script}}", "data_json");
    }
}
