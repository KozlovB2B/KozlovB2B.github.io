<?php

use yii\db\Schema;
use app\modules\core\components\Migration;
use \app\modules\site\models\UserHeadManager;
use app\modules\aff\models\Account as AffAccount;
use app\modules\billing\models\Account as BillingAccount;

class m151101_111808_Billing_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%rate}}', [
            'id' => Schema::TYPE_PK,
            'name' => "VARCHAR(255) NOT NULL COMMENT 'Name'",
            'operators_threshold' => "INT UNSIGNED NOT NULL COMMENT 'Operators threshold'",
            'monthly_fee' => "INT UNSIGNED NOT NULL COMMENT 'Monthly fee'",
            'created_at' => "INT NULL COMMENT 'Created'",
            'deleted_at' => "INT NULL COMMENT 'Created'",
        ], $this->tableOptions);

        $this->createTable('{{%billing_account}}', [
            'id' => Schema::TYPE_PK,
            'rate_id' => "INT UNSIGNED NULL COMMENT 'Rate'",
            'balance' => "INT NOT NULL DEFAULT 0 COMMENT 'Balance'",
            'available' => "INT NOT NULL DEFAULT 0 COMMENT 'Available'",
            'hold' => "INT NOT NULL DEFAULT 0 COMMENT 'Hold'",
            'is_trial' => "BOOLEAN NULL COMMENT 'Is free trial now'",
            'trial_till' => "INT NULL COMMENT 'Free trial end time'",
            'paid_till' => "INT NULL COMMENT 'Current rate paid till'",
        ], $this->tableOptions);

        $this->createTable('{{%affiliate_account}}', [
            'id' => Schema::TYPE_PK,
            'total_earned' => "INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Total money earned'",
            'promo_code' => "VARCHAR(6) NULL COMMENT 'Promo code'",
            'affiliate_id' => "INT UNSIGNED NULL COMMENT 'Parent affiliate id'",
            'total_affiliate_earned' => "INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Parent affiliate earned'"
        ], $this->tableOptions);

        $this->createIndex('promo_code', '{{%affiliate_account}}', 'promo_code');
        $this->createIndex('affiliate_id', '{{%affiliate_account}}', 'affiliate_id');


        $this->createTable('{{%rate_change_log}}', [
            'id' => Schema::TYPE_PK,
            'rate_from' => "INT UNSIGNED NULL COMMENT 'Rate'",
            'rate_to' => "INT UNSIGNED NULL COMMENT 'Rate'",
            'balance_at_the_moment' => "INT UNSIGNED NULL COMMENT 'Balance at he moment'",
            'created_at' => "INT NULL COMMENT 'Change date'",
        ], $this->tableOptions);

        $this->createTable('{{%balance_operations}}', [
            'id' => Schema::TYPE_PK,
            'balance_id' => "INT UNSIGNED NULL COMMENT 'User'",
            'is_accrual' => "INT NOT NULL COMMENT 'Is accrual'",
            'type_id' => "INT NOT NULL COMMENT 'Operation type'",
            'amount' => "INT NOT NULL COMMENT 'Amount'",
            'comment' => "VARCHAR(255) NULL COMMENT 'Comment'",
            'created_at' => "INT NULL COMMENT 'Operation date'",
        ], $this->tableOptions);

        $this->createIndex('balance_id', '{{%balance_operations}}', 'balance_id');

        $this->createTable('{{%payout_request}}', [
            'id' => Schema::TYPE_PK,
            'balance_id' => "INT UNSIGNED NULL COMMENT 'User'",
            'status_id' => "INT NOT NULL COMMENT 'Request status (1 - Registered, 2 - In work, 3 - Completed, 4 - Cancelled)'",
            'amount' => "INT UNSIGNED NOT NULL COMMENT 'Amount'",
            'comment' => "VARCHAR(255) NULL COMMENT 'Comment'",
            'created_at' => "INT NULL COMMENT 'Creating date'",
            'in_work_at' => "INT NULL COMMENT 'Taking in work date'",
            'completed_at' => "INT NULL COMMENT 'Perform date'",
            'cancelled_at' => "INT NULL COMMENT 'Cancel date'",
        ], $this->tableOptions);

        $users = UserHeadManager::find()->all();

        foreach($users as $u){
            AffAccount::register($u);
            BillingAccount::register($u);
        }
    }

    public function down()
    {
        $this->dropTable('{{%rate}}');
        $this->dropTable('{{%billing_account}}');
        $this->dropTable('{{%affiliate_account}}');
        $this->dropTable('{{%rate_change_log}}');
        $this->dropTable('{{%balance_operations}}');
        $this->dropTable('{{%payout_request}}');

        return true;
    }
}
