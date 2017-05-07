<?php

use yii\db\Schema;
use yii\db\Migration;

class m151008_120030_Node_add_coords extends Migration
{
    public function up()
    {
        $this->addColumn("{{%node}}", "top", "FLOAT COMMENT 'Top offset'");
        $this->addColumn("{{%node}}", "left", "FLOAT COMMENT 'Left offset'");
    }

    public function down()
    {
        $this->dropColumn("{{%node}}", "top");
        $this->dropColumn("{{%node}}", "left");
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
