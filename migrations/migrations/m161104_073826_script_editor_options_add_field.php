<?php

use yii\db\Migration;

class m161104_073826_script_editor_options_add_field extends Migration
{
    public function up()
    {
        $this->addColumn('script', 'editor_options', $this->string(8000));
        $this->addColumn('SiteUserHeadManager', 'editor_options', $this->string(8000));
    }

    public function down()
    {
        $this->dropColumn('script', 'editor_options');
        $this->dropColumn('SiteUserHeadManager', 'editor_options');
    }
}