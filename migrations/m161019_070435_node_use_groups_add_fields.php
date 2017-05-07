<?php

use yii\db\Migration;

class m161019_070435_node_use_groups_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('script_node', 'groups', $this->string(1024));
    }

    public function down()
    {
        $this->dropColumn('script_node', 'groups');
    }
}
