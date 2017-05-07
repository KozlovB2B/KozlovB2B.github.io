<?php

use yii\db\Migration;

class m161020_074406_user_v2_mark extends Migration
{
    public function up()
    {
        $this->addColumn('user', 'v2', $this->boolean()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('user', 'v2');
    }
}
