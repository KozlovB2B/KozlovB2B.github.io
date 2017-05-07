<?php

use yii\db\Migration;

class m161016_115001_script_start_node_uid extends Migration
{
    public function up()
    {
        $this->addColumn('script', 'start_node_uuid', $this->string(64)->unique());

        $this->addForeignKey(
            'fk-script_node-start_node_uuid',
            'script',
            'start_node_uuid',
            'script_node',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-script_node-start_node_uuid',
            'script'
        );

        $this->dropColumn('script', 'start_node_uuid');
    }
}
