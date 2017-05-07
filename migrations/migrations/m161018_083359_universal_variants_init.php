<?php

use app\modules\core\components\Migration;

class m161018_083359_universal_variants_init extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {

        $this->createTable('script_group', [
            'id' => $this->string(64)->unique(),
            'script_id' => $this->integer()->notNull(),
            'top' => $this->integer()->notNull()->defaultValue(0),
            'left' => $this->integer()->notNull()->defaultValue(0),
            'name' => $this->string(32)->notNull(),
            'deleted_at' => $this->integer()
        ], $this->tableOptions);

        $this->addPrimaryKey('pk-script_group', 'script_group', 'id');

        $this->createIndex('idx-script_group-script_id', 'script_group', 'script_id');
        $this->createIndex('idx-script_group-deleted_at', 'script_group', 'deleted_at');

        $this->addForeignKey(
            'fk-script_group-script_id',
            'script_group',
            'script_id',
            'script',
            'id',
            'CASCADE'
        );

        $this->createTable('script_group_variant', [
            'id' => $this->string(64)->unique(),
            'script_id' => $this->integer()->notNull(),
            'group_id' => $this->string(64)->notNull(),
            'target_id' => $this->string(64),
            'content' => $this->string(128)->notNull(),
            'deleted_at' => $this->integer()
        ], $this->tableOptions);

        $this->addPrimaryKey('pk-script_group_variant', 'script_group_variant', 'id');

        $this->createIndex('idx-script_group_variant-script_id', 'script_group_variant', 'script_id');
        $this->createIndex('idx-script_group_variant-group_id', 'script_group_variant', 'group_id');
        $this->createIndex('idx-script_group_variant-target_id', 'script_group_variant', 'target_id');
        $this->createIndex('idx-script_group_variant-deleted_at', 'script_group_variant', 'deleted_at');

        $this->addForeignKey(
            'fk-script_group_variant-script_id',
            'script_group_variant',
            'script_id',
            'script',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-script_group_variant-group_id',
            'script_group_variant',
            'group_id',
            'script_group',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-script_group_variant-target_id',
            'script_group_variant',
            'target_id',
            'script_node',
            'id',
            'CASCADE',
            'SET NULL'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-script_group-script_id',
            'script_group'
        );

        $this->dropForeignKey(
            'fk-script_group_variant-target_id',
            'script_group_variant'
        );

        $this->dropForeignKey(
            'fk-script_group_variant-script_id',
            'script_group_variant'
        );

        $this->dropForeignKey(
            'fk-script_group_variant-group_id',
            'script_group_variant'
        );

        $this->dropTable('script_group');
        $this->dropTable('script_group_variant');
    }
}
