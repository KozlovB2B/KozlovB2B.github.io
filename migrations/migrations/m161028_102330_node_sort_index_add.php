<?php

use yii\db\Migration;

class m161028_102330_node_sort_index_add extends Migration
{
    public function up()
    {
        $this->addColumn('script_node', 'variants_sort_index', $this->string(8000));
        $this->addColumn('script_group', 'variants_sort_index', $this->string(8000));
    }

    public function down()
    {
        $this->dropColumn('script_node', 'variants_sort_index');
        $this->dropColumn('script_group', 'variants_sort_index');
    }
}
