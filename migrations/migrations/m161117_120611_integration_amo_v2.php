<?php

use app\modules\core\components\Migration;


class m161117_120611_integration_amo_v2 extends Migration
{
    public function up()
    {
        $this->createTable('integration_amo_user', [
            'user_id' => $this->primaryKey(),
            'created_at' => $this->integer(),
            'head_id' => $this->integer(),
            'amouser' => $this->string(128),
            'amohash' => $this->string(128),
            'subdomain' => $this->string(128)
        ], $this->tableOptions);

        $this->addForeignKey('fk_integration_amo_user', 'integration_amo_user', 'user_id', 'user', 'id', 'CASCADE', 'RESTRICT');

        $this->migrateOldData();
    }

    public function down()
    {
        $this->dropForeignKey('fk_integration_amo_user', 'integration_amo_user');
        $this->dropTable('integration_amo_user');
    }

    protected function migrateOldData()
    {
        $data = Yii::$app->getDb()->createCommand('SELECT * FROM amo_api_credentials')->queryAll();

        echo "    > Conversion..." . PHP_EOL.PHP_EOL;

        foreach ($data as $d) {
            $model = new \app\modules\integration\modules\amo\models\AmoUser();
            $model->user_id = $d['user_id'];
            $model->head_id = $model->user_id;
            $model->created_at = $d['created_at'];
            $model->amouser = $d['user'];
            $model->amohash = $d['key'];
            $model->subdomain = $d['domain'];
            $model->save(false);
            echo "\t" . $model->user_id . "\t" . $model->amouser. "\t" . $model->subdomain . PHP_EOL.PHP_EOL;
        }
    }
}