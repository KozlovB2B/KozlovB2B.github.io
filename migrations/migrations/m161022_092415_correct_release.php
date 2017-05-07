<?php

use yii\db\Migration;

class m161022_092415_correct_release extends Migration
{
    public function up()
    {
        $this->dropColumn('script_release', 'build_version');
        $this->addColumn('script_release', 'deleted_at', $this->integer());
    }

    public function down()
    {

        $this->addColumn('script_release', 'build_version', $this->integer()->unsigned()->notNull()->defaultValue(0));
        $this->dropColumn('script_release', 'deleted_at');
    }
}
