<?php

use app\modules\core\components\Migration;

class m170131_061852_bik extends Migration
{
    public function up()
    {
        $this->alterColumn('billing_bank_props', 'bik', $this->string(20));
    }

    public function down()
    {
        $this->alterColumn('billing_bank_props', 'bik', $this->integer());
    }
}