<?php

use yii\db\Migration;

class m161027_083012_script_perormer_options_ad_field extends Migration
{
    public function up()
    {
        $this->addColumn('script', 'performer_options', $this->string(8000));
    }

    public function down()
    {
        $this->dropColumn('script', 'performer_options');
    }
}
