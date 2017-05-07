<?php

use app\modules\core\components\Migration;

class m161215_061852_call_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('call', 'fields', $this->string(16000));
    }

    public function down()
    {
        $this->dropColumn('call', 'fields');
    }
}