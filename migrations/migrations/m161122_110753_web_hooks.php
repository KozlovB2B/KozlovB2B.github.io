<?php

use app\modules\core\components\Migration;

class m161122_110753_web_hooks extends Migration
{
    public function up()
    {
        $this->createTable('call_data', [
            'id' => $this->primaryKey(),
            'data' => $this->text()
        ], $this->tableOptions);

        $this->addForeignKey('fk_call_data__call', 'call_data', 'id', 'call', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('integration_web_hook', [
            'id' => $this->primaryKey(),
            'head_id' => $this->integer(),
            'event' => $this->integer(),
            'get' => $this->string(1024),
            'post' => $this->string(16000),
        ], $this->tableOptions);

        $this->addForeignKey('fk_integration_web_hook__head', 'integration_web_hook', 'head_id', 'user', 'id', 'CASCADE', 'RESTRICT');

        $this->createIndex('idx-integration_web_hook-head_id-event', 'integration_web_hook', ['head_id', 'event']);

        /**
         *
         */
        $this->createTable('integration_enabled_list', [
            'id' => $this->primaryKey(),
            'list' => $this->string(16000),
        ], $this->tableOptions);

        $this->addForeignKey('fk_integration_enabled_list', 'integration_enabled_list', 'id', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_call_data__call', 'call_data');
        $this->dropForeignKey('fk_integration_web_hook__head', 'integration_web_hook');
        $this->dropForeignKey('fk_integration_enabled_list', 'integration_enabled_list');
        $this->dropTable('call_data');
        $this->dropTable('integration_web_hook');
        $this->dropTable('integration_enabled_list');
    }
}
