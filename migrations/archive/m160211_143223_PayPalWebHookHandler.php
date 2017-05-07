<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m160211_143223_PayPalWebHookHandler extends Migration
{
    public function up()
    {
        $this->createTable('{{%PayPalInvoice}}', [
            'id' => Schema::TYPE_PK,
            "user_id" => "INT NOT NULL COMMENT 'Пользователь'",
            "amount" => "INT COMMENT 'Сумма'",
            "pay_pal_transaction" => "VARCHAR(255) COMMENT 'Номер транзакции PP'",
            "currency" => "VARCHAR(3) COMMENT 'Валюта'",
            "created_at" => "INT COMMENT 'Дата'",
            "paid_at" => "INT COMMENT 'Оплачено'",
            "cancelled_at" => "INT COMMENT 'Отменено'"
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%PayPalInvoice}}');

        return true;
    }
}
