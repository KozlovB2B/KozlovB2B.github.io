<?php

use app\modules\core\components\Migration;

class m160921_122505_optimize_indexes_2 extends Migration
{
    public function up()
    {
        $this->createIndex('idx-call-is_goal_reached', 'call', 'is_goal_reached');
        $this->createIndex('idx-call-normal_ending', 'call', 'normal_ending');
        $this->createIndex('idx-call-started_at', 'call', 'started_at');
        $this->createIndex('idx-call-duration', 'call', 'duration');
    }

    public function down()
    {
        $this->dropIndex('idx-call-is_goal_reached', 'call');
        $this->dropIndex('idx-call-normal_ending', 'call');
        $this->dropIndex('idx-call-started_at', 'call');
        $this->dropIndex('idx-call-duration', 'call');
    }
}
