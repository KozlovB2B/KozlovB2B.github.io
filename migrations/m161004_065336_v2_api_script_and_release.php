<?php

use app\modules\core\components\Migration;

class m161004_065336_v2_api_script_and_release extends Migration
{
    public function safeUp()
    {
        $this->addColumn('script', 'build', 'mediumtext');
        $this->addColumn('script', 'latest_release', $this->integer());
        $this->createIndex('idx-script-latest_release', 'script', 'latest_release');

        $this->createTable('script_release', [
            'id' => $this->primaryKey(),
            'script_id' => $this->integer(),
            'name' => $this->string(64),
            'version' => $this->string(20),
            'build_version' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'build' => 'mediumtext',
            'created_at' => $this->integer()
        ], $this->tableOptions);

        $this->addForeignKey(
            'fk-script-latest_release',
            'script',
            'latest_release',
            'script_release',
            'id',
            'SET NULL'
        );

        $this->createIndex('idx-script_release-script_id', 'script_release', 'script_id');

        $this->addForeignKey(
            'fk-script_release-script_id',
            'script_release',
            'script_id',
            'script',
            'id',
            'CASCADE'
        );

        $this->createTable('editor_session', [
            'id' => $this->string(64)->unique(),
            'user_id' => $this->integer(),
            'username' => $this->string(64),
            'script_id' => $this->integer(),
            'undo_stack' => 'mediumtext',
            'redo_stack' => 'mediumtext',
            'created_at' => $this->integer()
        ], $this->tableOptions);

        $this->addPrimaryKey('pk-editor_session', 'editor_session', 'id');
        $this->createIndex('idx-editor_session-user_id', 'editor_session', 'user_id');
        $this->createIndex('idx-editor_session-script_id', 'editor_session', 'script_id');
        $this->createIndex('idx-editor_session-created_at', 'editor_session', 'created_at');

        $this->addForeignKey(
            'fk-editor_session-user_id',
            'editor_session',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-editor_session-script_id',
            'editor_session',
            'script_id',
            'script',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-script-latest_release',
            'script'
        );

        $this->dropIndex('idx-script-latest_release', 'script');

        $this->dropColumn('script', 'build');
        $this->dropColumn('script', 'latest_release');




        $this->dropForeignKey(
            'fk-editor_session-user_id',
            'editor_session'
        );


        $this->dropForeignKey(
            'fk-editor_session-script_id',
            'editor_session'
        );

        $this->dropForeignKey(
            'fk-script_release-script_id',
            'script_release'
        );

        $this->dropTable('editor_session');
        $this->dropTable('script_release');
    }
}
