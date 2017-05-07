<?php

use yii\db\Schema;
use yii\db\Migration;

class m151015_201206_Script_add_start_node_add_field extends Migration
{
    public function up()
    {
        $this->addColumn("{{%script}}", "start_node_id", "INT NULL COMMENT 'Start node'");

    }

    public function down()
    {
        $this->dropColumn("{{%script}}", "start_node_id");

    }
}
