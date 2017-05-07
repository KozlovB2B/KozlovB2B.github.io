<?php

use app\modules\core\components\Migration;

class m161102_083842_zebra_user_settings extends Migration
{
    public function up()
    {
        $this->createTable('zebra_user_settings', [
            'user_id' => $this->primaryKey(),
            'number' =>$this->integer(),
            'name' =>$this->string(64),
        ]);
    }

    public function down()
    {
        $this->dropTable('zebra_user_settings');
    }
}
