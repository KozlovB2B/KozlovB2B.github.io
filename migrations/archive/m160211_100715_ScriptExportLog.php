<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m160211_100715_ScriptExportLog extends Migration
{
    public function up()
    {
        $this->createTable('{{%ScriptExportLog}}', [
            'id' => Schema::TYPE_PK,
            "user_id" => "INT NOT NULL COMMENT 'Пользователь'",
            "script_id" => "INT NOT NULL COMMENT 'Скрипт'",
            "success" => "BOOLEAN COMMENT 'Удачно или нет'",
            "created_at" => "INT COMMENT 'Дата'"
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%ScriptExportLog}}');

        return true;
    }
}
