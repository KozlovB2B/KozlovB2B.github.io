<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m151106_185528_Instruction extends Migration
{
    public function up()
    {
        $this->createTable('{{%instruction}}', [
            'id' => Schema::TYPE_PK,
            'status_id' => "INT UNSIGNED NOT NULL COMMENT 'Publication status'",
            'video' => "VARCHAR(1000) NULL COMMENT 'Video'",
            'description' => "VARCHAR(255) NOT NULL COMMENT 'Description'",
            'content' => "TEXT NULL COMMENT 'Content'",
            'created_at' => "INT NULL COMMENT 'Created'",
            'updated_at' => "INT NULL COMMENT 'Updated'",
            'deleted_at' => "INT NULL COMMENT 'Deleted'",
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%instruction}}');

        return true;
    }
}
