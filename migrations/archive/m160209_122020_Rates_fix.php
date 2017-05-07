<?php

use yii\db\Schema;
use yii\db\Migration;

class m160209_122020_Rates_fix extends Migration
{
    public function up()
    {
        $this->update("{{%rate}}", [
            'operators_threshold' => 2,
            'is_default' => 0
        ], "name = 'BUSINESS 3'");

        $this->update("{{%rate}}", [
            'operators_threshold' => 9
        ], "name = 'BUSINESS 10'");

        $this->update("{{%rate}}", ['is_default' => 1], "name = 'FREE'");

    }

    public function down()
    {
        return false;
    }
}
