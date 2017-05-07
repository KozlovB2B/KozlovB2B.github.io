<?php

use yii\db\Schema;
use yii\db\Migration;

class m151101_095433_Billing_init extends Migration
{
    public function up()
    {
//        $this->truncateTable("{{%call_end_reason}}");

        $this->addColumn("{{%call_end_reason}}", "comment_title_replacement", "VARCHAR(75) NULL COMMENT 'Comment field name will be replaced with this'");
        $this->addColumn("{{%call_end_reason}}", "is_goal_reached", "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Is goal reached'");

        $this->batchInsert("{{%call_end_reason}}",[
            'account_id',
            'name',
            'comment_required',
            'created_at',
            'comment_title_replacement',
            'is_goal_reached',
        ],[
            [0, 'Цель достигнута', 0, time(), null, 1],
            [0, 'Скрипт сломался, но цель достугнута', 1, time(), 'Где сломался и как нужно изменить скрипт?', 1],
            [0, 'Цель не достигнута, нет подходящих вариантов в скрипте', 1, time(), 'Какой вариант ответа клиента необходимо добавить?', 0],
            [0, 'Скрипт не сломался, цель не достигнута', 0, time(), 'Как нужно изменить скрипт?', 0]
        ]);

        $this->addColumn("{{%call}}", "is_goal_reached", "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Is goal reached'");

    }

    public function down()
    {
        $this->dropColumn("{{%call_end_reason}}", "comment_title_replacement");
        $this->dropColumn("{{%call_end_reason}}", "is_goal_reached");
        $this->dropColumn("{{%call}}", "is_goal_reached");
    }
}
