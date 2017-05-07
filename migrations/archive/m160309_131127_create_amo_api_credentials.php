<?php

use yii\db\Migration;
use yii\db\Schema;

class m160309_131127_create_amo_api_credentials extends Migration
{
    public function up()
    {
        $this->createTable('amo_api_credentials', [
            'id' => $this->primaryKey(),
            'user_id' => Schema::TYPE_INTEGER . ' NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NULL',
            'is_active' => Schema::TYPE_BOOLEAN . ' NULL',
            'user' =>  $this->string(128),
            'domain' =>  $this->string(128),
            'key' =>  $this->string(128),
            'config' =>  $this->text(),
            'cookie' =>  $this->text(),
        ]);
    }

    public function down()
    {
        $this->dropTable('amo_api_credentials');
    }
}
