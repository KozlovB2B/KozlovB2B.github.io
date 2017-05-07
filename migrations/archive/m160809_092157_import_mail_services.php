<?php

use app\modules\core\components\Migration;

class m160809_092157_import_mail_services extends Migration
{
    public function up()
    {

        $this->createTable('email_services', [
            "id" => $this->primaryKey(),
            "domain" => $this->string(24),
            "name" => $this->string(32),
            "url" => $this->string(64)
        ], $this->tableOptions);

        $data_file = dirname(__FILE__) . '/data/email_services.csv';

        $handle = fopen($data_file, 'r');
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $this->insert('email_services', ["domain" => $data[0], "name" => $data[1], "url" => $data[2]]);
        }

        fclose($handle);
    }

    public function down()
    {
        $this->dropTable("email_services");
    }
}
