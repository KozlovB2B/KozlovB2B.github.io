<?php

use yii\db\Migration;

class m160229_105754_create_script_api_token extends Migration
{
    public function up()
    {
        $this->createTable('script_api_token', [
            'id' => $this->primaryKey(),
            'token' => $this->string(64),
        ]);

        $this->createIndex('script_api_token_unique', 'script_api_token', 'token', true);
    }

    public function down()
    {
        $this->dropTable('script_api_token');
    }
}
