<?php

use yii\db\Migration;

class m161111_101313_group_name_max_name_size extends Migration
{
    public function up()
    {
        $this->alterColumn('script_group',  'name', $this->string(128)->notNull());
    }

    public function down()
    {
        $this->alterColumn('script_group',  'name', $this->string(128)->notNull());
    }

}
