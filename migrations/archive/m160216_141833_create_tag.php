<?php

use yii\db\Migration;
use yii\db\Schema;

class m160216_141833_create_tag extends Migration
{
    /**
     * @see https://github.com/2amigos/yii2-taggable-behavior
     *
     * First you need to create a tbl_tag (you can choose the name you wish) table with the following format, and build the correspondent ActiveRecord class (i.e. Tag):
     *
     * +-----------+
     * |  tbl_tag  |
     * +-----------+
     * | id        |
     * | frequency |
     * | name      |
     * +-----------+
     * After, if you wish to link tags to a certain ActiveRecord (lets say Tour), you need to create the table that will link the Tour Model to the Tag:
     *
     * +-------------------+
     * | tbl_tour_tag_assn |
     * +-------------------+
     * | tour_id           |
     * | tag_id            |
     * +-------------------+
     */
    public function up()
    {
        $this->createTable('BlogTag', [
            'id' => $this->primaryKey(),
            'frequency' => $this->integer(),
            'name' => $this->string(75),
        ]);

        $this->createTable('BlogTagPost', [
            'post_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'tag_id' => Schema::TYPE_INTEGER . ' NOT NULL'
        ]);

        $this->addPrimaryKey('BlogTagPost_post_id__tag_id', 'BlogTagPost', ['post_id', 'tag_id']);
    }

    public function down()
    {
        $this->dropTable('BlogTag');
        $this->dropTable('BlogTagPost');
    }
}
