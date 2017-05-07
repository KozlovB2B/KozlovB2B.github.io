<?php

use app\modules\core\components\Migration;

class m161004_065337_v2_api_node_and_variant extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {

        $this->createTable('script_node', [
            'id' => $this->string(64)->unique(),
            'script_id' => $this->integer()->notNull(),
            'number' => $this->integer()->unsigned(),
            'top' => $this->integer()->notNull()->defaultValue(0),
            'left' => $this->integer()->notNull()->defaultValue(0),
            'content' => $this->string(4096)->notNull(),
            'call_stage_id' => $this->integer(),
            'is_goal' => $this->boolean()->notNull()->defaultValue(0),
            'normal_ending' => $this->boolean()->notNull()->defaultValue(0),
            'deleted_at' => $this->integer()
        ], $this->tableOptions);

        $this->addPrimaryKey('pk-script_node', 'script_node', 'id');

        $this->createIndex('idx-script_node-script_id', 'script_node', 'script_id');
        $this->createIndex('idx-script_node-deleted_at', 'script_node', 'deleted_at');

        $this->addForeignKey(
            'fk-script_node-script_id',
            'script_node',
            'script_id',
            'script',
            'id',
            'CASCADE'
        );

        $this->createTable('script_variant', [
            'id' => $this->string(64)->unique(),
            'script_id' => $this->integer()->notNull(),
            'node_id' => $this->string(64)->notNull(),
            'target_id' => $this->string(64),
            'content' => $this->string(128)->notNull(),
            'deleted_at' => $this->integer()
        ], $this->tableOptions);

        $this->addPrimaryKey('pk-script_variant', 'script_variant', 'id');

        $this->createIndex('idx-script_variant-script_id', 'script_variant', 'script_id');
        $this->createIndex('idx-script_variant-node_id', 'script_variant', 'node_id');
        $this->createIndex('idx-script_variant-target_id', 'script_variant', 'target_id');
        $this->createIndex('idx-script_variant-deleted_at', 'script_variant', 'deleted_at');

        $this->addForeignKey(
            'fk-script_variant-script_id',
            'script_variant',
            'script_id',
            'script',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-script_variant-target_id',
            'script_variant',
            'target_id',
            'script_node',
            'id',
            'CASCADE',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-script_variant-node_id',
            'script_variant',
            'node_id',
            'script_node',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-script_node-script_id',
            'script_node'
        );
        $this->dropForeignKey(
            'fk-script_variant-target_id',
            'script_variant'
        );

        $this->dropForeignKey(
            'fk-script_variant-script_id',
            'script_variant'
        );

        $this->dropForeignKey(
            'fk-script_variant-node_id',
            'script_variant'
        );

        $this->dropTable('script_node');
        $this->dropTable('script_variant');
    }
}
