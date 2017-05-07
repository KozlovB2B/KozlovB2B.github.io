<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m151021_172127_Reasons extends Migration
{
    public function up()
    {
        $this->createTable('{{%call_end_reason}}', [
            'id' => Schema::TYPE_PK,
            'account_id' => "INT UNSIGNED NOT NULL COMMENT 'Account'",
            'name' => "VARCHAR(255) NOT NULL COMMENT 'Name'",
            'comment_required' => "TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Is comment required'",
            'created_at' => "INT NULL COMMENT 'Created'",
            'deleted_at' => "INT NULL COMMENT 'Deleted'",
        ], $this->tableOptions);

        $this->truncateTable('{{%call}}');
    }

    public function down()
    {
        $this->dropTable('{{%call_end_reason}}');

        return true;
    }
}
