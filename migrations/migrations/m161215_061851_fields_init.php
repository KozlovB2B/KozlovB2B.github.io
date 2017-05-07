<?php

use app\modules\core\components\Migration;

class m161215_061851_fields_init extends Migration
{
    public function up()
    {
        $this->createTable('script_field', [
            'id' => $this->primaryKey(),
            'code' => $this->string(64),
            'account_id' => $this->integer()->notNull(),
            'name' => $this->string(32)->notNull(),
            'type' => $this->string(32)->notNull(),
            'type_data' => $this->string(1024)
        ], $this->tableOptions);

        $this->addForeignKey('fk_field__user', 'script_field', 'account_id', 'user', 'id', 'CASCADE');

        $this->createIndex('idx_field__account_id', 'script_field', 'account_id');
        $this->createIndex('idx_field__card__account_id__code', 'script_field', ['account_id', 'code'], true);
    }

    public function down()
    {
        $this->dropForeignKey('fk_field__user', 'script_field');
        $this->dropTable('script_field');
    }
}