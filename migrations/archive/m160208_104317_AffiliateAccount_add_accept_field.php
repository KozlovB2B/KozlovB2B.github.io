<?php

use yii\db\Schema;
use yii\db\Migration;

class m160208_104317_AffiliateAccount_add_accept_field extends Migration
{
    public function up()
    {
        $this->addColumn("{{%affiliate_account}}", "terms_accepted", "BOOLEAN NOT NULL DEFAULT 0");
    }

    public function down()
    {
        $this->dropColumn("{{%affiliate_account}}", "terms_accepted");

        return true;
    }
}
