<?php

namespace app\modules\script\models\form;

use app\modules\script\models\ar\Script;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\modules\script\components\V2Importer;
use app\modules\script\components\V1Importer;

/**
 * Class ImportForm
 *
 * Форма загрузки аватарки
 *
 * @package app\modules\user\models
 */
class ImportForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;


    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => Script::SCRIPT_FILE_EXTENSION, 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * Загружает аву и уменьшает изображение
     *
     * @return Script
     * @throws \yii\base\ErrorException
     */
    public function import()
    {
        if ($this->validate()) {

            $file_content = trim(str_replace(Script::OPEN_AT, '', file_get_contents($this->file->tempName)));
//echo base64_decode($file_content);exit;
            $data = json_decode(base64_decode($file_content), true);

            // По наличию элемента $data['script'] различаем скрипт со структурой первой версии от второй
            if (!empty($data['script'])) {
                return V2Importer::import($data);
            } else {
                return V1Importer::import($data);
            }
        } else {
            return false;
        }
    }
}
