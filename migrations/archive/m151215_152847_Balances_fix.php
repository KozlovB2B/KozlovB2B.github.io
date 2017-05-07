<?php

use yii\db\Schema;
use app\modules\core\components\Migration;
use app\modules\billing\models\Account;
use app\modules\billing\models\Balance;

class m151215_152847_Balances_fix extends Migration
{
    public function up()
    {
        $this->createTable('{{%billing_balance}}', [
            'id' => Schema::TYPE_PK,
            'balance' => "FLOAT(8, 2) NOT NULL DEFAULT 0 COMMENT 'Balance'"
        ], $this->tableOptions);


        $accounts = Account::find()->all();

        echo 'Generate balances...' . PHP_EOL;

        Yii::$app->getModule('billing');

        foreach ($accounts as $a) {
            $b = new Balance();
            $b->id = $a->id;
            $b->balance = $a->balance;
            $b->save();
        }

        $this->createTable('{{%script_run_stat}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => "INT UNSIGNED NULL COMMENT 'User'",
            'script_id' => "INT UNSIGNED NULL COMMENT 'Script'",
            'day' => "DATE NULL COMMENT 'Day'",
            'runs' => "INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Script runs'"
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%billing_balance}}');
        $this->dropTable('{{%script_run_stat}}');

        return true;
    }
}
