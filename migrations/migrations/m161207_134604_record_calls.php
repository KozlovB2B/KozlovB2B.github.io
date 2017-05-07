<?php

use yii\db\Migration;

class m161207_134604_record_calls extends Migration
{
    public function up()
    {
        $this->addColumn('SiteUserHeadManager', 'record_calls', $this->smallInteger()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('SiteUserHeadManager', 'record_calls');
    }
}
