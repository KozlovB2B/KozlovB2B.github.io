<?php

use app\modules\core\components\Migration;

/**
 * Handles the creation of table `profile_head`.
 */
class m160915_111856_create_profile_head_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('profile_head', [
            'user_id' => $this->primaryKey(),
            'first_name' => $this->string(32),
            'middle_name' => $this->string(32),
            'last_name' => $this->string(32),
            'phone' => $this->string(20)->notNull(),
            'accept_terms' => $this->boolean()->defaultValue(0)
        ], $this->tableOptions);

        $this->addForeignKey('fk-profile_head-user', 'profile_head', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('fk-profile_head-user');
        $this->dropTable('profile_head');
    }
}