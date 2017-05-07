<?php

use app\modules\core\components\Migration;

class m161102_083841_zebra_credentials extends Migration
{
    public function up()
    {
        $this->createTable('zebra_api_credentials', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'created_at' =>$this->integer(),
            'is_active' => $this->boolean(),
            'login' =>  $this->string(128),
            'password' =>  $this->string(128),
            'realm' =>  $this->string(128)
        ]);
    }

    public function down()
    {
        $this->dropTable('zebra_api_credentials');
    }
}