<?php

use yii\db\Migration;

class m160225_143732_updae_paid_till extends Migration
{
    public function up()
    {
        $this->update('billing_account', ['paid_till' => mktime(0, 0, 0, date("m") + 1, date('d'), date("Y"))], 'monthly_fee > 0');
    }

    public function down()
    {
        $this->update('billing_account', ['paid_till' => null]);
        return true;
    }
}
