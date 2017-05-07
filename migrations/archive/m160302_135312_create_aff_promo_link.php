<?php

use app\modules\core\components\Migration;
use yii\db\Schema;

class m160302_135312_create_aff_promo_link extends Migration
{
    public function up()
    {
        $this->createTable('aff_promo_link', [
            'id' => $this->primaryKey(),
            'created_at' => Schema::TYPE_INTEGER . ' NULL',
            'deleted_at' => Schema::TYPE_INTEGER . ' NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NULL',
            'promo_code' => Schema::TYPE_STRING . "(12) NULL",
            "host" => Schema::TYPE_STRING . "(280) NULL",
            "query_string" => Schema::TYPE_STRING . "(1000) NULL",
            "url" => Schema::TYPE_STRING . "(1280) NULL",
            "utm_medium" => Schema::TYPE_STRING . "(128) NULL",
            "utm_source" => Schema::TYPE_STRING . "(128) NULL",
            "utm_campaign" => Schema::TYPE_STRING . "(128) NULL",
            "utm_content" => Schema::TYPE_STRING . "(128) NULL",
            "utm_term" => Schema::TYPE_STRING . "(128) NULL",
            "hits" => "INT UNSIGNED NOT NULL DEFAULT 0",
            "money" => "INT UNSIGNED NOT NULL DEFAULT 0",
        ], $this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('aff_promo_link');
    }
}
