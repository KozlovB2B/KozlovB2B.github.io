<?php

use app\modules\core\components\Migration;
use yii\db\Schema;


class m160909_145521_create_avatar extends Migration
{
    public function up()
    {
        $this->createTable('avatar', [
            'user_id' => Schema::TYPE_INTEGER . ' PRIMARY KEY',
            'filename' => Schema::TYPE_STRING . '(128)'
        ], $this->tableOptions);

        $this->addForeignKey('fk_avatar', 'avatar', 'user_id', 'user', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_avatar', 'avatar');
        $this->dropTable('avatar');
    }
}
