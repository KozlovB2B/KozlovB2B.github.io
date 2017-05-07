<?php

use app\modules\core\components\Migration;

class m160819_083842_OnlinePbx_user_settings extends Migration
{
    public function up()
    {
        $this->createTable('onlinepbx_user_settings', [
            'user_id' => $this->primaryKey(),
            'number' =>$this->integer()
        ]);
    }

    public function down()
    {
        $this->dropTable('onlinepbx_user_settings');
    }
}
