<?php

use yii\db\Schema;
use yii\db\Migration;

class m151129_094457_Call_normal_ending extends Migration
{
    public function up()
    {
        $this->addColumn("{{%call}}", "normal_ending", "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Normal ending'");
    }

    public function down()
    {
        $this->dropColumn("{{%call}}", "normal_ending");

        return true;
    }
}
