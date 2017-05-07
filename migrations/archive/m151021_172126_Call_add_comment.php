<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m151021_172126_Call_add_comment extends Migration
{
    public function up()
    {
        $this->addColumn("{{%call}}", "comment", "VARCHAR(225) NULL COMMENT 'Comment'");
        $this->addColumn("{{%call}}", "reason_id", "INT NULL COMMENT 'Reason'");
        $this->createIndex('reason_id', '{{%call}}', 'reason_id');
    }

    public function down()
    {
        $this->dropColumn("{{%call}}", "comment");
        $this->dropColumn("{{%call}}", "reason_id");
    }

}
