<?php

use yii\db\Migration;

class m161020_135835_call_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('call', 'release_id', $this->integer());
        $this->addColumn('call', 'end_node_uuid', $this->string(64));
        $this->addColumn('call', 'start_node_uuid', $this->string(64));

        $this->createIndex('idx-call-release_id', 'call', 'release_id');

        $this->addForeignKey(
            'fk-call-release_id',
            'call',
            'release_id',
            'script_release',
            'id',
            'SET NULL'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-call-release_id',
            'call'
        );

        $this->dropColumn('call', 'release_id');
        $this->dropColumn('call', 'end_node_uuid');
        $this->dropColumn('call', 'start_node_uuid');
    }
}
