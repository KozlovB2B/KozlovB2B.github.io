<?php

use yii\db\Schema;
use yii\db\Migration;
use \app\modules\billing\models\Account;
use \app\modules\billing\models\Rate;
use \yii\helpers\Html;


class m151209_105656_billing_rate_change_history_add_comment extends Migration
{
    public function up()
    {
        $this->addColumn("{{%billing_rate_change_history}}", "comment", "VARCHAR(1000) NULL COMMENT 'Comment'");

        $this->truncateTable("{{%billing_rate_change_history}}");

        $this->update("{{%billing_account}}", ['last_rate_change' => 0]);

        $this->update("{{%rate}}", ['is_default' => 0]);

        $this->update("{{%rate}}", [
            'is_default' => 1
        ], "name = 'БЕСПЛАТНЫЙ'");

        $this->update("{{%rate}}", [
            'name' => 'ПРОЕКТ 1'
        ], "name = 'ПРОЕКТ1'");

        $this->update("{{%rate}}", [
            'name' => 'БИЗНЕС 3'
        ], "name = 'БИЗНЕС3'");

        $this->update("{{%rate}}", [
            'name' => 'КОМАНДА 10'
        ], "name = 'КОМАНДА10'");

        $accounts = Account::find()->all();

        $default_rate = Rate::getDefault();

        echo 'Apply current default rate to all users...' . PHP_EOL;

        if (!$default_rate) {
            echo 'Sorry... but no default rate...' . PHP_EOL;
        }

        Yii::$app->getModule('billing');

        $add = [
            'export_allowed' => 1,
            'executions_per_day' => 0,
            'executions_per_month' => 0,
        ];

        foreach ($accounts as $a) {
            $a->is_trial = 1;
            $a->trial_till = strtotime(date("Y-m-d 23:59:59", strtotime("+14 days")));

            if (!$a->applyRate($default_rate, $add, 'Автоматическое назначение тарифа после регистрации.')) {
                echo strip_tags(Html::errorSummary($a, ['header' => false, 'footer' => false])) . PHP_EOL;
            }
        }
    }

    public function down()
    {
        $this->dropColumn("{{%billing_rate_change_history}}", "comment");

        return true;
    }
}
