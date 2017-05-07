<?php
use yii\db\Migration;

class m160202_122350_UserOperator_name_add_fields extends Migration
{
    public function up()
    {
        $this->addColumn("{{%SiteUserOperator}}", "first_name", "VARCHAR(20) NULL COMMENT 'First name'");
        $this->addColumn("{{%SiteUserOperator}}", "last_name", "VARCHAR(20) NULL COMMENT 'Last name'");
    }

    public function down()
    {
        $this->dropColumn("{{%SiteUserOperator}}", "first_name");
        $this->dropColumn("{{%SiteUserOperator}}", "last_name");

        return true;
    }
}
