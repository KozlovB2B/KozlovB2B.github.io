<?php
use yii\db\Migration;

class m160324_082450_alter_ScriptExportLog extends Migration
{
    public function up()
    {
        $this->addColumn('ScriptExportLog', 'ip', $this->string(20));
        $this->addColumn('ScriptExportLog', 'username', $this->string(64));
        $this->addColumn('ScriptExportLog', 'script_name', $this->string(64));
    }

    public function down()
    {
        $this->dropColumn('ScriptExportLog', 'ip');
        $this->dropColumn('ScriptExportLog', 'username');
        $this->dropColumn('ScriptExportLog', 'script_name');
    }
}