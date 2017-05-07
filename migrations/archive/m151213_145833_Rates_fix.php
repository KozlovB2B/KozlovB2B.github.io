<?php

use yii\db\Schema;
use yii\db\Migration;

class m151213_145833_Rates_fix extends Migration
{
    public function up()
    {
        $this->update("{{%rate}}", [
            'operators_threshold' => 2
        ], "id = 3");

        $this->update("{{%rate}}", [
            'operators_threshold' => 9
        ], "id = 4");
    }

    public function down()
    {
        return false;
    }
}
