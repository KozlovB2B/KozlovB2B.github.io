<?php

use yii\db\Migration;

class m160303_153141_affiliate_account_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn('affiliate_account', 'registration_hit', $this->integer());
    }

    public function down()
    {
        $this->dropColumn('affiliate_account', 'registration_hit');

        return false;
    }
}
