<?php

use yii\db\Migration;
use app\modules\script\models\ar\Script;

class m161026_102111_script_v2_convert_fix extends Migration
{
    public function up()
    {
        $this->addColumn('script', 'v2converted', $this->boolean()->defaultValue(0));

        Script::updateAll(['v2converted' => 1], 'status_id=' . Script::V2_CONVERTED);
    }

    public function down()
    {
        $this->dropColumn('script', 'v2converted');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
