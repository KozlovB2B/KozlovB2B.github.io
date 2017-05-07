<?php

use app\modules\core\components\Migration;

class m160819_083841_OnlinePbx_credentials extends Migration
{
    public function up()
    {
        $this->createTable('onlinepbx_api_credentials', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'created_at' =>$this->integer(),
            'is_active' => $this->boolean(),
            'domain' =>  $this->string(64),
            'phone' =>  $this->string(20),
            'key' =>  $this->string(128)
        ]);
    }

    public function down()
    {
        $this->dropTable('onlinepbx_api_credentials');
    }
}
