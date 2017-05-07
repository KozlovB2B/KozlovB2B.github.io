<?php

use yii\db\Schema;
use yii\db\Migration;

class m160204_104459_Script_add_execution_interface_type_id_field extends Migration
{
    public function up()
    {
        $this->addColumn("{{%script}}", "operator_interface_type_id", "TINYINT NOT NULL DEFAULT 1 COMMENT 'Operator interface'");
    }

    public function down()
    {
        $this->dropColumn("{{%script}}", "operator_interface_type_id");

        return true;
    }
}
