<?php

use yii\db\Migration;

class m160809_092155_Call_add_record extends Migration
{
    public function up()
    {
        $this->addColumn("{{%call}}", "record_url", $this->string(1024));
    }

    public function down()
    {
        $this->dropColumn("{{%call}}", "record_url");
    }
}
