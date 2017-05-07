<?php
use yii\db\Migration;

class m160324_082451_create_api_user extends Migration
{
    public function up()
    {
        $this->createTable('ApiUser', [
            'id' => $this->primaryKey(),
            'user_login' => $this->string(32),
            'account_id' => $this->integer(),
            'created_at' => $this->integer()
        ]);

        $this->createIndex('api_user__user_login_idx', 'ApiUser', 'user_login');
        $this->createIndex('api_user__account_id_idx', 'ApiUser', 'account_id');

        $this->addColumn('call', 'api_user', $this->string(32));
        $this->addColumn('call', 'using_api', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        $this->dropTable('ApiUser');
        $this->dropColumn('call', 'api_user');
        $this->dropColumn('call', 'using_api');
    }
}