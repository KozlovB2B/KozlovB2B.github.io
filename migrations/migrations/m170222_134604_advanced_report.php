<?php

use app\modules\core\components\Migration;

class m170222_134604_advanced_report extends Migration
{
    public function up()
    {
        $this->addColumn('SiteUserHeadManager', 'hits_report', $this->smallInteger()->defaultValue(0));

        $this->createTable('call_hits', [
            'id' => $this->primaryKey(),
            'call_id' => $this->integer(),
            'script_id' => $this->integer(),
            'node_id' => $this->string(64),
            'variant_id' => $this->string(64)
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropColumn('SiteUserHeadManager', 'hits_report');

        $this->dropTable('call_hits');
    }
}
