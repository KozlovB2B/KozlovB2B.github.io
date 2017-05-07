<?php

use yii\db\Migration;

class m160815_112510_Script_add_common_cases_field extends Migration
{
    public function up()
    {
        $this->addColumn("script", "common_cases", $this->text());
    }

    public function down()
    {
        $this->dropColumn("script", "common_cases");
    }
}
