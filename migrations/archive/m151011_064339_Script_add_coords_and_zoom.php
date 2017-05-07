<?php

use yii\db\Schema;
use yii\db\Migration;

class m151011_064339_Script_add_coords_and_zoom extends Migration
{
    public function up()
    {
        $this->addColumn("{{%script}}", "zoom", "FLOAT NOT NULL DEFAULT 1 COMMENT 'Current zoom'");
        $this->addColumn("{{%script}}", "viewport_center", "VARCHAR(250) NOT NULL DEFAULT '[0, 0]' COMMENT 'Viewport center'");
    }

    public function down()
    {
        $this->dropColumn("{{%script}}", "zoom");
        $this->dropColumn("{{%script}}", "viewport_center");
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
