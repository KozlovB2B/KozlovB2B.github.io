<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m150907_190759_SiteModule_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%SiteUserHeadManager}}', [
            'id' => Schema::TYPE_PK,
            'phone' => Schema::TYPE_STRING . '(20) NOT NULL'
        ], $this->tableOptions);

        $this->createTable('{{%SiteUserOperator}}', [
            'id' => Schema::TYPE_PK,
            'head_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL'
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%SiteUserHeadManager}}');
        $this->dropTable('{{%SiteUserOperator}}');


        return true;
    }


}
