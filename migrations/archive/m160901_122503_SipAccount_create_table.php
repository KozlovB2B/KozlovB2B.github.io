<?php

use app\modules\core\components\Migration;

class m160901_122503_SipAccount_create_table extends Migration
{
    public function up()
    {
        $this->createTable('SipAccount', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer(),
            'display_name' =>$this->string(64),
            'private_identity' =>$this->string(64),
            'public_identity' =>$this->string(64),
            'password' =>$this->string(128),
            'realm' =>$this->string(128),
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('SipAccount');
    }
}
