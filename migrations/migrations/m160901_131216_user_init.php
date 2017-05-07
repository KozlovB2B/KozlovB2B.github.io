<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m160901_131216_user_init extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'timezone_id', $this->string(32));
        $this->addColumn('user', 'creator_id', $this->integer());

        $this->createTable('profile_relation', [
            'user_id' => Schema::TYPE_INTEGER,
            'profile_class' => Schema::TYPE_STRING . '(75)',
            'is_current' => Schema::TYPE_BOOLEAN . ' DEFAULT 0'
        ], $this->tableOptions);

        $this->addPrimaryKey("user_id__profile_class", "profile_relation", ["user_id", "profile_class"]);
        $this->addForeignKey('fk_profile_relation', 'profile_relation', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');

        $this->createTable('profile_owner', [
            'user_id' => Schema::TYPE_INTEGER . ' PRIMARY KEY',
            'first_name' => Schema::TYPE_STRING . '(32)',
            'last_name' => Schema::TYPE_STRING . '(32)'
        ], $this->tableOptions);

        $this->createTable('profile_admin', [
            'user_id' => Schema::TYPE_INTEGER . ' PRIMARY KEY',
            'first_name' => Schema::TYPE_STRING . '(32)',
            'last_name' => Schema::TYPE_STRING . '(32)'
        ], $this->tableOptions);

        $this->addForeignKey('fk_profile_admin', 'profile_admin', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_profile_owner', 'profile_owner', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropColumn('user', 'timezone_id');
        $this->dropColumn('user', 'creator_id');

        $this->dropForeignKey('fk_profile_admin', 'profile_admin');
        $this->dropForeignKey('fk_profile_owner', 'profile_owner');
        $this->dropForeignKey('fk_profile_relation', 'profile_relation');

        $this->dropTable('profile_relation');
        $this->dropTable('profile_admin');
        $this->dropTable('profile_owner');
    }
}
