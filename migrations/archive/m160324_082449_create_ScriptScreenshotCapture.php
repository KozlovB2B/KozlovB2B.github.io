<?php

use yii\db\Migration;
use yii\db\Schema;

class m160324_082449_create_ScriptScreenshotCapture extends Migration
{
    public function up()
    {
        $this->createTable('ScriptCapture', [
            'id' => $this->primaryKey(),
            'script_id' => Schema::TYPE_INTEGER . ' NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NULL',
            'filename' => $this->string(256),
            'token' => $this->string(64)
        ]);

        $this->createIndex('ScriptCapture_token_idx', 'ScriptCapture', 'token');
        $this->createIndex('ScriptCapture_script_id_idx', 'ScriptCapture', 'script_id');
    }

    public function down()
    {
        $this->dropTable('ScriptCapture');
    }
}
