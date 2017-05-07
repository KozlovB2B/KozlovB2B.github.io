<?php

use app\modules\core\components\Migration;

class m161107_081333_clone_node_add_table extends Migration
{
    public function up()
    {
        $this->createTable('script_node_clone', [
            'id' => $this->string(64)->unique(),
            'script_id' => $this->integer()->notNull(),
            'from' => $this->string(64),
            'to' => $this->string(64),
            'to_data' => $this->text(),
            'created_at' => $this->integer(),
            'deleted_at' => $this->integer()
        ], $this->tableOptions);

        $this->addPrimaryKey('pk-script_node_clone', 'script_node_clone', 'id');
    }

    public function down()
    {
        $this->dropTable('script_node_clone');
    }
}