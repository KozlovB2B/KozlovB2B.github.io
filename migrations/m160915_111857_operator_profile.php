<?php

use app\modules\core\components\Migration;
use yii\db\Schema;

class m160915_111857_operator_profile extends Migration
{

    public function up()
    {
        $this->createTable('profile_operator', [
            'user_id' => Schema::TYPE_INTEGER . ' PRIMARY KEY',
            'head_id' => $this->integer(),
            'first_name' => $this->string(32),
            'last_name' => $this->string(32)
        ], $this->tableOptions);

        $this->createIndex('idx-profile_operator-head_id', 'profile_operator', 'head_id');
        $this->addForeignKey('fk-profile_operator-head', 'profile_operator', 'head_id', 'profile_head', 'user_id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('fk_profile_operator', 'profile_operator', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk-profile_operator-head', 'profile_operator');
        $this->dropForeignKey('fk_profile_operator', 'profile_operator');
        $this->dropTable('profile_operator');
    }
}
