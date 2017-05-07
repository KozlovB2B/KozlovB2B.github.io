<?php

use yii\db\Migration;

class m160809_092156_export_log extends Migration
{
    public function up()
    {
        $this->addColumn("ScriptExportLog", "type_id", $this->smallInteger(1)->defaultValue(1));
        $this->addColumn("ScriptExportLog", "source_script_id", $this->integer());
    }

    public function down()
    {
        $this->dropColumn("ScriptExportLog", "type_id");
        $this->dropColumn("ScriptExportLog", "source_script_id");
    }
}
