<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m160117_132416_SalesUserStat_add_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%SalesUserStat}}', [
            'id' => Schema::TYPE_PK,
            "current_balance"=> "INT NOT NULL DEFAULT 0 COMMENT 'Текущий баланс'",

            "comment"=> "VARCHAR(5000) NULL COMMENT 'Комментарий'",
            "scripts_created"=> "INT NOT NULL DEFAULT 0 COMMENT 'Всего скриптов было создано'",
            "current_scripts_count"=> "INT NOT NULL DEFAULT 0 COMMENT 'Текущее количество скриптов'",
            "current_nodes_count"=> "INT NOT NULL DEFAULT 0 COMMENT 'Текущее суммарное количество узлов'",
            "logins_today"=> "INT NOT NULL DEFAULT 0 COMMENT 'Логинов сегодня'",
            "logins_yesterday"=> "INT NOT NULL DEFAULT 0 COMMENT 'Логинов вчера'",
            "logins_week"=> "INT NOT NULL DEFAULT 0 COMMENT 'Логинов за неделю'",
            "executions_today"=> "INT NOT NULL DEFAULT 0 COMMENT 'Прогонов сегодня'",
            "executions_yesterday"=> "INT NOT NULL DEFAULT 0 COMMENT 'Прогонов вчера'",
            "executions_week"=> "INT NOT NULL DEFAULT 0 COMMENT 'Прогонов за неделю'",
            "last_login"=> "INT NULL COMMENT 'Дата последнего захода'",
        ], $this->tableOptions);

        $this->addColumn("{{%script}}", "nodes_count", "INT NOT NULL DEFAULT 0 COMMENT 'Nodes count'");



    }

    public function down()
    {
        $this->dropTable('{{%SalesUserStat}}');
        $this->dropColumn('{{%script}}', "nodes_count");

        return true;
    }
}
