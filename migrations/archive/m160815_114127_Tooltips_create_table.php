<?php

use app\modules\core\components\Migration;

class m160815_114127_Tooltips_create_table extends Migration
{
    public function up()
    {
        $this->createTable('tooltip', [
            "tooltip_id" => $this->smallInteger(4),
            "user_id" => $this->integer()
        ], $this->tableOptions);

        $this->addPrimaryKey('tooltip_pk', 'tooltip', ["tooltip_id", "user_id"]);
    }

    public function down()
    {
        $this->dropTable("tooltip");
    }
}