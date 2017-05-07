<?php

use yii\db\Migration;

class m161101_115211_node_add_created_at extends Migration
{
    public function up()
    {
        $this->addColumn('script_variant', 'created_at', $this->integer()->unsigned());
        $this->addColumn('script_group_variant', 'created_at', $this->integer()->unsigned());
    }

    public function down()
    {
        $this->dropColumn('script_variant', 'created_at');
        $this->dropColumn('script_group_variant', 'created_at');
    }
}