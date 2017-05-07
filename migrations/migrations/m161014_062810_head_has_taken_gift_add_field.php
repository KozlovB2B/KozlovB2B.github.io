<?php

use yii\db\Migration;

class m161014_062810_head_has_taken_gift_add_field extends Migration
{
    public function up()
    {
        $this->addColumn('SiteUserHeadManager', 'gift_accepted', $this->boolean());
    }

    public function down()
    {
        $this->dropColumn('SiteUserHeadManager', 'gift_accepted');
    }
}
