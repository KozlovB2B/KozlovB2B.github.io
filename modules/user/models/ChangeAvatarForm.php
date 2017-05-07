<?php
namespace app\modules\user\models;

use app\modules\core\components\image\Image;
use Yii;
use yii\base\Model;
use yii\web\ForbiddenHttpException;
use yii\web\UploadedFile;

/**
 * Class ChangeAvatarForm
 *
 * Форма загрузки аватарки
 *
 * @package app\modules\user\models
 */
class ChangeAvatarForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    /** @var Avatar */
    public $avatar;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * Загружает аву и уменьшает изображение
     *
     * @return bool
     * @throws \yii\base\ErrorException
     */
    public function upload()
    {
        if ($this->validate()) {

            $filename = uniqid(time()) . '.' . $this->file->extension;
            $file_path = $this->avatar->getStorageDirectory() . '/' . $filename;
            $this->file->saveAs($file_path);

            $image = Image::factory($file_path);
            $image->resize(Avatar::AVATAR_SIZE, Avatar::AVATAR_SIZE, Image::INVERSE);
            $image->crop(Avatar::AVATAR_SIZE, Avatar::AVATAR_SIZE);
            $image->save($file_path);

            if (!$this->avatar->getIsNewRecord() && $this->avatar->filename !== Avatar::DEFAULT_PNG && file_exists($this->avatar->getPath())) {
                unlink($this->avatar->getPath());
            }

            $this->avatar->filename = $filename;
            $this->avatar->save(false);

            return true;
        } else {
            return false;
        }
    }


    /** @inheritdoc */
    public function init()
    {
       $this->avatar = Avatar::current();

        parent::init();
    }
}
