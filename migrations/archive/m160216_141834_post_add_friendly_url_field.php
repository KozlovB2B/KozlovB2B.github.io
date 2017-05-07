<?php

use yii\db\Migration;

class m160216_141834_post_add_friendly_url_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%BlogPost}}', 'friendly_url', $this->string(150));
        $this->createIndex('BlogPost_friendly_url_unique', '{{%BlogPost}}', 'friendly_url', true);
    }

    public function down()
    {
        $this->dropColumn('{{%BlogPost}}', 'friendly_url');
    }
}