<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m151213_111524_BillingWithdrawProcedure extends Migration
{
    public function up()
    {
        $this->createTable('{{%billing_use_withdraw}}', [
            'id' => Schema::TYPE_PK,
            'accounts' => "INT UNSIGNED NULL COMMENT 'Аккаунтов обработано'",
            'total' => "INT UNSIGNED NULL COMMENT 'Всего списано'",
            'errors' => "LONGTEXT NULL COMMENT 'Ошибки'",
            'created_at' => "INT NULL COMMENT 'Date'"
        ], $this->tableOptions);

        $this->update("{{%billing_account}}", ['operators_threshold' => 3]);
        $this->update("{{%billing_account}}", ['rate_id' => 1]);

    }

    public function down()
    {
        $this->dropTable('{{%billing_use_withdraw}}');

        return true;
    }
}