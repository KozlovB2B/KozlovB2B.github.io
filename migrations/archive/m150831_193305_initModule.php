<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m150831_193305_initModule extends Migration
{
    public function up()
    {
        $this->dropForeignKey("auth_assignment_ibfk_1", "auth_assignment");
    }

    public function down()
    {
        return false;
    }
}
