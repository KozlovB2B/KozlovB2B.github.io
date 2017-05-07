<?php

use yii\db\Migration;

class m161124_054609_auto_releases extends Migration
{
    public function up()
    {
        $this->addColumn('script_release', 'build_md5', $this->string(64));
        $this->addColumn('script', 'build_md5', $this->string(64));
        $this->addColumn('SiteUserHeadManager', 'create_builds_manually', $this->smallInteger()->defaultValue(0));

        $this->execute('update SiteUserHeadManager set create_builds_manually = 1');
    }

    public function down()
    {
        $this->dropColumn('script_release', 'build_md5');
        $this->dropColumn('script', 'build_md5');
        $this->dropColumn('SiteUserHeadManager', 'create_builds_manually');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
