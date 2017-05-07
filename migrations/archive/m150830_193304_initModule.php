<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m150830_193304_initModule extends Migration
{
    public function up()
    {
        $this->createTable('{{%script}}', [
            'id' => Schema::TYPE_PK,
            'status_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Status (1 - Draft, 2 - Published)'",
            'user_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Script creator'",
            'group_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Script users group for usage'",
            'allowed_users' => Schema::TYPE_STRING . "(2000) NULL COMMENT 'Everyone using the script in addition to the creator'",
            'name' => Schema::TYPE_STRING . '(75) NOT NULL ',
            'description' => Schema::TYPE_STRING . '(255) NOT NULL',
            'cached_content' => Schema::TYPE_TEXT . " NULL COMMENT 'Pre-generated JSON for fast init call'",
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'deleted_at' => Schema::TYPE_INTEGER . ' NULL',
        ], $this->tableOptions);

        $this->createTable('{{%script_permissions}}', [
            'id' => Schema::TYPE_PK,
            'script_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Script'",
            'user_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'User'",
        ], $this->tableOptions);

        $this->createTable('{{%node}}', [
            'id' => Schema::TYPE_PK,
            'script_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Parent script'",
            'status_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Status (1 - Draft, 2 - Published)'",

            'title' => Schema::TYPE_STRING . '(75) NULL',
            'content' => Schema::TYPE_STRING . '(4000) NULL',
            'alternative_content_1' => Schema::TYPE_STRING . '(4000) NULL',
            'alternative_content_2' => Schema::TYPE_STRING . '(4000) NULL',
            'alternative_content_3' => Schema::TYPE_STRING . '(4000) NULL',
            'alternative_content_4' => Schema::TYPE_STRING . '(4000) NULL',

            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'deleted_at' => Schema::TYPE_INTEGER . ' NULL',
        ], $this->tableOptions);

        $this->createTable('{{%edge}}', [
            'id' => Schema::TYPE_PK,
            'status_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Status (1 - Draft, 2 - Published)'",

            'script_id' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Parent script'",
            'source' => Schema::TYPE_INTEGER . " NOT NULL COMMENT 'Source node'",
            'target' => Schema::TYPE_INTEGER . " NULL COMMENT 'Target node'",
            'content' => Schema::TYPE_STRING . '(75) NOT NULL',

            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'deleted_at' => Schema::TYPE_INTEGER . ' NULL',
        ], $this->tableOptions);

        $this->createIndex('node_script_index', '{{%node}}', 'script_id');
        $this->createIndex('script_permissions_script_index', '{{%script_permissions}}', 'script_id');
        $this->createIndex('script_permissions_user_index', '{{%script_permissions}}', 'user_id');
        $this->createIndex('edge_script_index', '{{%edge}}', 'script_id');
        $this->createIndex('edge_source_index', '{{%edge}}', 'source');
        $this->createIndex('edge_target_index', '{{%edge}}', 'target');
    }

    public function down()
    {
        $this->dropTable('{{%script}}');
        $this->dropTable('{{%node}}');
        $this->dropTable('{{%edge}}');
        $this->dropTable('{{%script_permissions}}');
    }
}
