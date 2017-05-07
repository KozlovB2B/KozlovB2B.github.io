<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m151013_201707_Call_add_table extends Migration
{
    public function up()
    {
        $this->addColumn("{{%script}}", "current_version", "INT NOT NULL DEFAULT 1 COMMENT 'Current version'");

        $this->createTable('{{%call}}', [
            'id' => Schema::TYPE_PK,
            'script_id' => "INT UNSIGNED NOT NULL COMMENT 'Script'",
            'script_version' => "INT UNSIGNED NULL COMMENT 'Script version'",
            'user_id' => "INT UNSIGNED NOT NULL COMMENT 'User'",
            'started_at' => "INT UNSIGNED NOT NULL COMMENT 'Started'",
            'start_node_id' => "INT UNSIGNED NULL COMMENT 'Start node'",
            'call_history' => "TEXT NOT NULL COMMENT 'Call history'",
            'ended_at' => "INT UNSIGNED NULL COMMENT 'Ended'",
            'end_node_id' => "INT UNSIGNED NOT NULL COMMENT 'End node'",
            'end_edge_id' => "INT UNSIGNED NULL COMMENT 'End edge'",
            'last_word' => "VARCHAR(1000) NOT NULL COMMENT 'Client last word'",
            'duration' => "INT UNSIGNED NULL COMMENT 'Duration'",

        ], $this->tableOptions);

        $this->createIndex('script_id', '{{%call}}', 'script_id');
        $this->createIndex('user_id', '{{%call}}', 'user_id');


        $this->createTable('{{%script_version}}', [
            'id' => Schema::TYPE_PK,
            'script_id' => "INT UNSIGNED NOT NULL COMMENT 'Script'",
            'version' => "INT UNSIGNED NOT NULL COMMENT 'Script version'",
            'md5' => "VARCHAR(32) NOT NULL COMMENT 'Script data hash'",
            'data' => "TEXT NOT NULL COMMENT 'Script data'",
            'created_at' => "INT UNSIGNED NOT NULL COMMENT 'Created'",
        ], $this->tableOptions);

        $this->createIndex('script_id__version', '{{%script_version}}', 'script_id, version');

    }

    public function down()
    {
        $this->dropTable('{{%call}}');
        $this->dropTable('{{%script_version}}');
        $this->dropColumn("{{%script}}", "current_version");


        return true;
    }
}
