<?php

use yii\db\Schema;
use app\modules\core\components\Migration;

class m160215_141016_create_Blog_module extends Migration
{
    public function up()
    {
        $this->createTable('{{%BlogPost}}', [
            'id' => Schema::TYPE_PK,
            "type_id" => "INT NOT NULL COMMENT 'Тип поста (1 - пост, 2 - новость)'",
            "user_id" => "INT NOT NULL COMMENT 'Кто добавил'",
            "author_id" => "INT NULL COMMENT 'Автор'",
            "status_id" => "INT NOT NULL COMMENT 'Статус (1 - Черновик, 2 - опубликовано)'",
            "division" => $this->string(5),
            "heading" => $this->string(75),
            "teaser" => $this->text(),
            "content" => $this->text(),
            "published_at" => "INT COMMENT 'Опубликовано'",
            "created_at" => "INT COMMENT 'Создан'",
            "updated_at" => "INT COMMENT 'Обновлен'",
            "deleted_at" => "INT COMMENT 'Удален'"
        ], $this->tableOptions);

        $this->createTable('{{%BlogTour}}', [
            'id' => Schema::TYPE_PK,
            "status_id" => "INT NOT NULL COMMENT 'Статус (1 - Черновик, 2 - опубликовано)'",
            "division" => $this->string(5),
            "heading" => $this->string(45),
            "teaser" => $this->string(8000),
            "content" => $this->text(),
            "created_at" => "INT COMMENT 'Создан'",
            "updated_at" => "INT COMMENT 'Обновлен'",
            "deleted_at" => "INT COMMENT 'Удален'"
        ], $this->tableOptions);

        $this->createTable('{{%BlogTourMenu}}', [
            'id' => Schema::TYPE_PK,
            "division" => $this->string(5),
            "priority" => "INT NOT NULL COMMENT 'Порядок'",
            "link_text" => $this->string(45),
            "tour_id" => "INT NOT NULL COMMENT 'Тур'"
        ], $this->tableOptions);

        $this->createTable('{{%BlogAuthor}}', [
            'id' => Schema::TYPE_PK,
            "division" => $this->string(5),
            "name" => $this->string(75),
            "avatar" => $this->string(225),
            "about" => $this->string(8000),
            "created_at" => "INT COMMENT 'Создан'",
            "updated_at" => "INT COMMENT 'Обновлен'",
            "deleted_at" => "INT COMMENT 'Удален'"
        ], $this->tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%BlogPost}}');
        $this->dropTable('{{%BlogTour}}');
        $this->dropTable('{{%BlogTourMenu}}');
        $this->dropTable('{{%BlogAuthor}}');

        return true;
    }
}
