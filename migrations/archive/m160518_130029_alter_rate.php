<?php

use yii\db\Migration;

class m160518_130029_alter_rate extends Migration
{
    public function up()
    {
        $this->addColumn("{{%rate}}", "user_id", $this->integer());
    }

    public function down()
    {
        $this->dropColumn("{{%rate}}", "user_id");
    }
}
