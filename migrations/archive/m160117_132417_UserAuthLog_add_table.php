<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m160117_132417_UserAuthLog_add_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%UserAuthLog}}', [
            'id' => Schema::TYPE_PK,
            "user_id"=> "INT NOT NULL COMMENT 'Account id'",
            "account_id"=> "INT NOT NULL COMMENT 'Account id'",
            "ip"=> "VARCHAR(15) NULL COMMENT 'IP'",
            "user_agent"=> "VARCHAR(500) NULL COMMENT 'User agent'",
            "created_at"=> "INT NULL COMMENT 'Login date'"
        ], $this->tableOptions);

        $this->createIndex("UserAuthLog_account_id","{{%UserAuthLog}}", "account_id");
    }

    public function down()
    {
        $this->dropTable('{{%UserAuthLog}}');

        return true;
    }
}
