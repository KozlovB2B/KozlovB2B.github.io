<?php

use app\modules\core\components\Migration;

class m161108_101137_user_designer_init extends Migration
{
    public function up()
    {
        $this->createTable('profile_designer', [
            'user_id' => $this->primaryKey(),
            'head_id' => $this->integer(),
            'first_name' => $this->string(32),
            'last_name' => $this->string(32)
        ], $this->tableOptions);

        $this->addForeignKey('fk_profile_designer', 'profile_designer', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropForeignKey('fk_profile_designer', 'profile_designer');
        $this->dropTable('profile_designer');
    }
}
