<?php

use app\modules\core\components\Migration;

class m160921_122504_optimize_indexes_1 extends Migration
{
    public function up()
    {
        $this->createIndex('idx-script-user_id', 'script', 'user_id');
        $this->createIndex('idx-script-status_id', 'script', 'status_id');
        $this->createIndex('idx-script-deleted_at', 'script', 'deleted_at');
        $this->createIndex('idx-script-user_id-status_id-deleted_at', 'script', ['user_id', 'status_id', 'deleted_at']);
    }

    public function down()
    {
        $this->dropIndex('idx-script-user_id', 'script');
        $this->dropIndex('idx-script-status_id', 'script');
        $this->dropIndex('idx-script-deleted_at', 'script');
        $this->dropIndex('idx-script-user_id-status_id-deleted_at', 'script');
    }
}
