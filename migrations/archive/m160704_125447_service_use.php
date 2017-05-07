<?php

use yii\db\Migration;

class m160704_125447_service_use extends Migration
{
    public function up()
    {
        $this->createTable('ServiceUsageLog', [
            'id' => $this->primaryKey(),
            'account_id' => $this->integer()->notNull(),
            'service_id' => $this->integer()->notNull(),
            'amount' => $this->float(),
            'day' => $this->date(),
            'month' => $this->string()
        ]);

        $this->createIndex('ServiceUsageLog__account_id_idx', 'ServiceUsageLog', 'account_id');
        $this->createIndex('ServiceUsageLog__month_idx', 'ServiceUsageLog', 'month');
    }

    public function down()
    {
        $this->dropTable('ServiceUsageLog');
    }
}
