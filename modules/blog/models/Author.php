<?php

namespace app\modules\blog\models;

use app\modules\core\components\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "BlogAuthor".
 *
 * @property integer $id
 * @property string $division
 * @property string $name
 * @property string $avatar
 * @property string $about
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 */
class Author extends ActiveRecord
{
    /**
     * @var UploadedFile Загружаемый файл аватара
     */
    public $avatar_file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'BlogAuthor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'division'], 'required'],
            [['avatar_file'], 'required', 'on' => 'insert'],
            [['avatar_file'], 'file'],
            [['created_at', 'updated_at', 'deleted_at'], 'integer'],
            [['division'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 75],
            [['avatar'], 'string', 'max' => 225],
            [['about'], 'string', 'max' => 8000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'division' => 'Дивизион',
            'name' => 'Имя',
            'avatar_file' => 'Аватар',
            'avatar' => 'Аватар',
            'about' => 'Об авторе',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлен',
            'deleted_at' => 'Удален',
        ];
    }

    /**
     * @inheritdoc
     * @return AuthorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthorQuery(get_called_class());
    }

    public function getNameAndDivision(){
        return sprintf('%s (%s)', $this->name, $this->division);
    }


    public static function postFormList(Post $post)
    {
        $finder = Author::find()->active();

        if ($post->author_id) {
            $finder->orWhere('id = ' . $post->author_id);
        }

        return ArrayHelper::map($finder->all(), 'id', 'nameAndDivision');
    }
}
