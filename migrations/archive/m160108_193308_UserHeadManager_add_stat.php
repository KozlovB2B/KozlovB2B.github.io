<?php

use yii\db\Schema;
use app\modules\core\components\Migration;


class m160108_193308_UserHeadManager_add_stat extends Migration
{
    public function up()
    {
        $this->addColumn("{{%SiteUserHeadManager}}", "comment", "VARCHAR(5000) NULL COMMENT 'Comment'");
        $this->addColumn("{{%SiteUserHeadManager}}", "scripts_created", "INT NULL COMMENT 'Scripts created'");
        $this->addColumn("{{%SiteUserHeadManager}}", "current_scripts_count", "INT NULL COMMENT 'Current scripts count'");
        $this->addColumn("{{%SiteUserHeadManager}}", "current_nodes_count", "INT NULL COMMENT 'Current nodes count'");
        $this->addColumn("{{%SiteUserHeadManager}}", "logins_today", "INT NULL COMMENT 'Logins today'");
        $this->addColumn("{{%SiteUserHeadManager}}", "logins_yesterday", "INT NULL COMMENT 'Logins yesterday'");
        $this->addColumn("{{%SiteUserHeadManager}}", "logins_week", "INT NULL COMMENT 'Logins week'");
        $this->addColumn("{{%SiteUserHeadManager}}", "executions_today", "INT NULL COMMENT 'Executions today'");
        $this->addColumn("{{%SiteUserHeadManager}}", "executions_yesterday", "INT NULL COMMENT 'Executions yesterday'");
        $this->addColumn("{{%SiteUserHeadManager}}", "executions_week", "INT NULL COMMENT 'Executions week'");
        $this->addColumn("{{%SiteUserHeadManager}}", "last_login", "INT NULL COMMENT 'Last login'");


        $this->createTable('{{%billing_invoice}}', [
            'id' => Schema::TYPE_PK,
            'account_id' => "INT UNSIGNED NULL COMMENT 'Account'",
            'name' => "VARCHAR(20) NULL COMMENT 'Name'",
            'status_id' => "TINYINT NOT NULL DEFAULT 1 COMMENT 'Status'",
            'pay_for' => "VARCHAR(255) NOT NULL COMMENT 'Pay for'",
            'amount' => "INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Amount'",

            'created_at' => "INT UNSIGNED NULL COMMENT 'Created'",
            'updated_at' => "INT UNSIGNED NULL COMMENT 'Updated'"
        ], $this->tableOptions);


        $this->createTable('{{%billing_invoice_bank_props}}', [
            'id' => Schema::TYPE_PK,
            'invoice_id' => "INT UNSIGNED NULL COMMENT 'Invoice'",
            'is_payer' => "BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Is it payer'",

            'first_name' => "VARCHAR(75) NOT NULL COMMENT 'First name'",
            'last_name' => "VARCHAR(75) NULL COMMENT 'Last name'",
            'middle_name' => "VARCHAR(75) NULL COMMENT 'Middle name'",
            'contact_phone' => "VARCHAR(20) NOT NULL COMMENT 'Contact phone'",
            'company_name' => "VARCHAR(150) NOT NULL COMMENT 'Company name'",
            'inn' => "VARCHAR(40) NOT NULL COMMENT 'INN'",
            'kpp' => "VARCHAR(40) NULL COMMENT 'KPP'",
            'ogrn' => "VARCHAR(40) NULL COMMENT 'OGRN'",
            'bank_name' => "VARCHAR(150) NOT NULL COMMENT 'Bank name'",
            'bik' => "INT NOT NULL COMMENT 'BIK'",
            'corr_score' => "VARCHAR(40) NOT NULL COMMENT 'Corr score'",
            'pay_score' => "VARCHAR(40) NOT NULL COMMENT 'Pay score'",

            'boss_position' => "VARCHAR(40) NOT NULL COMMENT 'Boss position'",
            'boss_last_name' => "VARCHAR(40) NOT NULL COMMENT 'Boss last name'",
            'boss_first_name' => "VARCHAR(40) NOT NULL COMMENT 'Boss first name'",
            'boss_middle_name' => "VARCHAR(40) NOT NULL COMMENT 'Boss Middle name'",
            'acting_on_the_basis' => "VARCHAR(40) NOT NULL COMMENT 'Acting on the basis'",
            'post_address' => "VARCHAR(255) NOT NULL COMMENT 'Post address'",
            'juristic_address' => "VARCHAR(255) NULL COMMENT 'Juristic address'",
            'real_address' => "VARCHAR(255) NULL COMMENT 'Real address'"
        ], $this->tableOptions);

        $this->createTable('{{%billing_bank_props}}', [
            'id' => Schema::TYPE_PK,
            'account_id' => "INT UNSIGNED NULL COMMENT 'Account'",
            'first_name' => "VARCHAR(75) NOT NULL COMMENT 'First name'",
            'last_name' => "VARCHAR(75) NULL COMMENT 'Last name'",
            'middle_name' => "VARCHAR(75) NULL COMMENT 'Middle name'",
            'contact_phone' => "VARCHAR(20) NOT NULL COMMENT 'Contact phone'",
            'company_name' => "VARCHAR(150) NOT NULL COMMENT 'Company name'",
            'inn' => "VARCHAR(40) NOT NULL COMMENT 'INN'",
            'kpp' => "VARCHAR(40) NULL COMMENT 'KPP'",
            'ogrn' => "VARCHAR(40) NULL COMMENT 'OGRN'",
            'bank_name' => "VARCHAR(150) NOT NULL COMMENT 'Bank name'",
            'bik' => "INT NOT NULL COMMENT 'BIK'",
            'corr_score' => "VARCHAR(40) NOT NULL COMMENT 'Corr score'",
            'pay_score' => "VARCHAR(40) NOT NULL COMMENT 'Pay score'",

            'boss_position' => "VARCHAR(40) NOT NULL COMMENT 'Boss position'",
            'boss_last_name' => "VARCHAR(40) NOT NULL COMMENT 'Boss last name'",
            'boss_first_name' => "VARCHAR(40) NOT NULL COMMENT 'Boss first name'",
            'boss_middle_name' => "VARCHAR(40) NOT NULL COMMENT 'Boss Middle name'",
            'acting_on_the_basis' => "VARCHAR(40) NOT NULL COMMENT 'Acting on the basis'",
            'post_address' => "VARCHAR(255) NOT NULL COMMENT 'Post address'",
            'juristic_address' => "VARCHAR(255) NULL COMMENT 'Juristic address'",
            'real_address' => "VARCHAR(255) NULL COMMENT 'Real address'",


            'created_at' => "INT UNSIGNED NULL COMMENT 'Created'",
            'updated_at' => "INT UNSIGNED NULL COMMENT 'Updated'"
        ], $this->tableOptions);

    }

    public function down()
    {
        $this->dropColumn("{{%SiteUserHeadManager}}", "comment");
        $this->dropColumn("{{%SiteUserHeadManager}}", "scripts_created");
        $this->dropColumn("{{%SiteUserHeadManager}}", "current_scripts_count");
        $this->dropColumn("{{%SiteUserHeadManager}}", "current_nodes_count");
        $this->dropColumn("{{%SiteUserHeadManager}}", "logins_today");
        $this->dropColumn("{{%SiteUserHeadManager}}", "logins_yesterday");
        $this->dropColumn("{{%SiteUserHeadManager}}", "logins_week");
        $this->dropColumn("{{%SiteUserHeadManager}}", "executions_today");
        $this->dropColumn("{{%SiteUserHeadManager}}", "executions_yesterday");
        $this->dropColumn("{{%SiteUserHeadManager}}", "executions_week");
        $this->dropColumn("{{%SiteUserHeadManager}}", "last_login");

        $this->dropTable("{{%billing_bank_props}}");
        $this->dropTable("{{%billing_invoice}}");
        $this->dropTable("{{%billing_invoice_bank_props}}");

        return true;
    }
}
