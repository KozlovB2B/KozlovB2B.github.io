<?php

use yii\db\Migration;

class m160225_093654_add_fields_to_script_version extends Migration
{
    public function up()
    {
        $this->addColumn('{{%script_version}}', 'start_node', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('{{%script_version}}', 'start_node');
    }
}
