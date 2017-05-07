<?php

use yii\db\Schema;
use yii\db\Migration;

class m151111_180847_Script_add_exporting_fields extends Migration
{
    public function up()
    {
        $this->addColumn("{{%script}}", "original_id", "INT NULL COMMENT 'Original script id'");
        $this->addColumn("{{%script}}", "original_version", "INT NULL COMMENT 'Original script version'");
        $this->addColumn("{{%script}}", "import_id", "INT NULL COMMENT 'Current import script id'");
        $this->addColumn("{{%script}}", "import_version", "INT NULL COMMENT 'Current import script version'");
    }

    public function down()
    {
        $this->dropColumn("{{%script}}", "original_id");
        $this->dropColumn("{{%script}}", "original_version");
        $this->dropColumn("{{%script}}", "import_id");
        $this->dropColumn("{{%script}}", "import_version");
    }
}
