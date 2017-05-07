<?php

namespace app\modules\blog\models;

use app\modules\core\components\ActiveRecord;
use app\modules\core\components\Publishable;
use romi45\seoContent\components\SeoBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use dosamigos\taggable\Taggable;
use yii\helpers\Inflector;

/**
 * This is the model class for table "BlogPost".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $author_id
 * @property integer $status_id
 * @property string $division
 * @property string $heading
 * @property string $teaser
 * @property string $content
 * @property integer $published_at
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property string $friendly_url
 *
 * @property Author $author
 * @property Tag[] $tags
 * @property Tag $tag
 */
class Post extends ActiveRecord implements Publishable
{

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'taggable' => [
                'class' => Taggable::className(),
            ],
            'seo' => [
                'class' => SeoBehavior::className(),

                // This is default values. Usually you can not specify it
                'titleAttribute' => 'seoTitle',
                'keywordsAttribute' => 'seoKeywords',
                'descriptionAttribute' => 'seoDescription'
            ],
        ]);
    }

    /**
     * @const integer Тип поста "Пост"
     */
    const POST_TYPE_POST = 1;

    /**
     * @const integer Тип поста "Новость"
     */
    const POST_TYPE_NEWS = 2;

    /**
     * @return array Типы постов
     */
    public static function types()
    {
        return [
            static::POST_TYPE_POST => 'Пост',
            static::POST_TYPE_NEWS => 'Новость',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable(TagPost::tableName(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTag()
    {
        return $this->hasOne(Tag::className(), ['id' => 'tag_id'])->viaTable(TagPost::tableName(), ['post_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::className(), ['id' => 'author_id']);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'BlogPost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['seoTitle', 'seoKeywords', 'seoDescription'], 'safe'],
            [['seoTitle'], 'checkSeoTitleIsGlobalUnique'], // It recommend for title to be unique for every pages. You can ignore this recommendation - just delete this rule.
            [['tagNames'], 'safe'],
            [['user_id', 'status_id', 'heading', 'teaser'], 'required'],
            [['user_id', 'author_id', 'status_id', 'published_at', 'created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['teaser', 'content'], 'string'],
            [['division'], 'string', 'max' => 5],
            [['heading'], 'string', 'max' => 75],
            [['friendly_url'], 'string', 'max' => 150, 'min' => 5],
            [['friendly_url'], 'checkFriendlyUrl', 'skipOnEmpty' => false],
            [['friendly_url'], 'unique'],
            ['friendly_url', 'match', 'pattern' => '/^[a-z][-a-z0-9]+$/', 'message' => 'Человеческий URL может содержать только прописные латинские буквы или цифры и симовол -, начинаться должен обязательно с буквы.']
        ];
    }

    public function checkFriendlyUrl()
    {
        if ($this->friendly_url) {
            return true;
        }

        if (!$this->heading) {
            return true;
        }


        $this->friendly_url = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', Inflector::transliterate($this->heading)), '-'));
        $this->addError('friendly_url', 'Человеческий URL был сгенерирован автоматически. Проверьте что все верно.');
        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tagNames' => 'Тэги',
            'type' => 'Тип',
            'user_id' => 'Кто добавил',
            'author_id' => 'Автор',
            'status' => 'Статус',
            'status_id' => 'Статус',
            'division' => 'Дивизион',
            'heading' => 'Заголовок',
            'teaser' => 'Тизер',
            'content' => 'Содержание',
            'published_at' => 'Опубликовано',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'deleted_at' => 'Удален',
            'friendly_url' => 'Человеческий URL',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->status_id == Publishable::STATUS_PUBLISHED && !$this->published_at) {
            $this->published_at = time();
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     * @return PostQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
    }


    /***
     * Название статуса
     *
     * @param $status_id
     * @return mixed
     */
    public static function statusName($status_id)
    {
        return static::getStatuses()[$status_id];
    }

    /**
     * @return array
     */
    public static function getStatuses()
    {
        return [
            Publishable::STATUS_PUBLISHED => 'Опубликован',
            Publishable::STATUS_DRAFT => 'Черновик',
        ];
    }

    /**
     * @return mixed|null Название типа текущего объекта
     */
    public function getStatus()
    {
        return $this->status_id ? Post::statusName($this->status_id) : null;
    }


    /**
     * @return boolean
     */
    public function isPublished()
    {
        return $this->status_id == Publishable::STATUS_PUBLISHED;
    }

    /**
     * @return boolean
     */
    public function isDraft()
    {
        return $this->status_id == Publishable::STATUS_DRAFT;
    }

    /**
     * @return boolean
     */
    public function isCreating()
    {
        return $this->status_id == Publishable::STATUS_CREATING;
    }

    /**
     * @return string Урл для просмотра
     */
    public function getUrl()
    {
        return '/blog/' . ($this->friendly_url ? $this->friendly_url : $this->id);
    }
}
