<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m160202_153356_Session_add_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%session}}', [
            "id" => "VARCHAR(64) NOT NULL PRIMARY KEY",
            "expire" => "INT NOT NULL",
            "user_id" => "INT NULL",
            "ip" => "VARCHAR(15) NULL",
            "data" => "LONGBLOB",
        ], $this->tableOptions);

        $this->createIndex("session_expire", "{{%session}}", "expire");
        $this->createIndex("session_user_id", "{{%session}}", "user_id");


        $this->createTable('{{%MultiSessionGuard}}', [
            'id' => Schema::TYPE_PK,
            "token" => "VARCHAR(40) NOT NULL",
            "user_id" => "INT NULL",
            "ip" => "VARCHAR(15) NULL",
            "created_at" => "INT NULL",
        ], $this->tableOptions);

        $this->createIndex("MultiSessionGuard_token", "{{%MultiSessionGuard}}", "token");
        $this->createIndex("MultiSessionGuard_user_id", "{{%MultiSessionGuard}}", "user_id");

    }

    public function down()
    {
        $this->dropTable('{{%session}}');
        $this->dropTable('{{%MultiSessionGuard}}');

        return true;
    }
}
