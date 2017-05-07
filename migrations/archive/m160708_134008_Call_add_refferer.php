<?php

use yii\db\Migration;

class m160708_134008_Call_add_refferer extends Migration
{
    public function up()
    {
        $this->addColumn("{{%call}}", "perform_page", $this->string(1024));
    }

    public function down()
    {
        $this->dropColumn("{{%call}}", "perform_page");
    }
}
