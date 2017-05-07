<?php

use app\modules\core\components\Migration;


class m161117_120610_fix_content_node extends Migration
{
    public function up()
    {
        $this->alterColumn('script_node',  'content', $this->string(24000)->notNull());
    }

    public function down()
    {
        $this->alterColumn('script_node',  'content', $this->string(24000)->notNull());
    }
}