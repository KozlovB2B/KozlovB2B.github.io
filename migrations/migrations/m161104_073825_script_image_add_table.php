<?php

use app\modules\core\components\Migration;

class m161104_073825_script_image_add_table extends Migration
{
    public function up()
    {
        $this->createTable('script_image', [
            'id' => $this->primaryKey(),
            'script_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'svg_size' => $this->integer(),
            'png_size' => $this->integer(),
            'filename' => $this->string(64)
        ]);

        $this->addForeignKey(
            'fk-script_image-script_id',
            'script_image',
            'script_id',
            'script',
            'id',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-script_image-script_id',
            'script_image'
        );
        $this->dropTable('script_image');
    }
}
