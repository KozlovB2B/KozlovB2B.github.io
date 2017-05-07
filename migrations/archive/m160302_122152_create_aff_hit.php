<?php

use app\modules\core\components\Migration;
use yii\db\Schema;

class m160302_122152_create_aff_hit extends Migration
{
    public function up()
    {
        $this->createTable('{{%aff_hit}}', [
            'id' => Schema::TYPE_INTEGER . ' NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NULL',
            'user_id' => Schema::TYPE_INTEGER . ' NULL',
            'promo_code' =>  Schema::TYPE_STRING . "(12) NULL",
            'link_id' => Schema::TYPE_INTEGER . ' NULL',
            "query_string" => Schema::TYPE_STRING . "(1280) NULL",
            "utm_medium" => Schema::TYPE_STRING . "(128) NULL",
            "utm_source" => Schema::TYPE_STRING . "(128) NULL",
            "utm_campaign" => Schema::TYPE_STRING . "(128) NULL",
            "utm_content" => Schema::TYPE_STRING . "(128) NULL",
            "utm_term" => Schema::TYPE_STRING . "(128) NULL",

            // Данные посетителя
            "ip" => "CHAR(15) NULL COMMENT 'IP'",
            "user_agent" => "VARCHAR(256) NULL COMMENT 'Сигнатура браузера'",
            "browser_language" => "VARCHAR(5) NULL COMMENT 'Язык браузера'",
            "device_type" => "TINYINT(1) UNSIGNED NOT NULL COMMENT 'Тип устройства (1 - комп, 2 - планшет, 3 - мобила)'",
            "os" => "VARCHAR(75) NULL COMMENT 'Операционная система'",
            "browser" => "VARCHAR(75) NULL COMMENT 'Браузер'",
            "ref" => "VARCHAR(1280) NULL COMMENT 'Ссылка, откуда клиент перешел по рекламе'",
            "has_registrations" => "SMALLINT UNSIGNED NOT NULL DEFAULT 0",
            "bills" => "SMALLINT UNSIGNED NOT NULL DEFAULT 0",
            "bills_paid" => "SMALLINT UNSIGNED NOT NULL DEFAULT 0",
            "total_earned" => "INT UNSIGNED NOT NULL DEFAULT 0"
        ], $this->tableOptions);
        $this->addPrimaryKey('id-created_at', '{{%aff_hit}}', ['id', 'created_at']);
        $this->alterColumn('{{%aff_hit}}', 'id', Schema::TYPE_INTEGER . ' NULL AUTO_INCREMENT');
        $this->execute("ALTER TABLE aff_hit PARTITION BY RANGE (created_at) (PARTITION p2016 VALUES LESS THAN (" . strtotime('2017-01-01 00:00:00') . ") ENGINE = InnoDB);");
        $this->execute("ALTER TABLE aff_hit PARTITION BY RANGE (created_at) (PARTITION p2017 VALUES LESS THAN (" . strtotime('2018-01-01 00:00:00') . ") ENGINE = InnoDB);");
        $this->execute("ALTER TABLE aff_hit PARTITION BY RANGE (created_at) (PARTITION p2018 VALUES LESS THAN (" . strtotime('2019-01-01 00:00:00') . ") ENGINE = InnoDB);");
        $this->execute("ALTER TABLE aff_hit PARTITION BY RANGE (created_at) (PARTITION p2019 VALUES LESS THAN (" . strtotime('2020-01-01 00:00:00') . ") ENGINE = InnoDB);");
        $this->execute("ALTER TABLE aff_hit PARTITION BY RANGE (created_at) (PARTITION p2020 VALUES LESS THAN (" . strtotime('2021-01-01 00:00:00') . ") ENGINE = InnoDB);");
        $this->createIndex('hit_user_id', '{{%aff_hit}}', 'user_id');
    }

    public function down()
    {
        $this->dropTable('{{%aff_hit}}');
        return true;
    }
}
