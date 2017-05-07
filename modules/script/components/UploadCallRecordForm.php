<?php
namespace app\modules\script\components;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class UploadCallRecordForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $record;

    public function rules()
    {
        return [
            [['record'], 'file', 'skipOnEmpty' => false, 'extensions' => 'wav, ogg, mp3', 'checkExtensionByMimeType' => false],
        ];
    }

    public function upload($account_id)
    {
        $records_dir = Yii::getAlias('@webroot') . '/call-records';

        $user_dir = 'u' . $account_id;

        $upload_dir = $records_dir . '/' . $user_dir;

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = uniqid() . '.' . $this->record->extension;

        $url = Yii::getAlias('@call-records') . '/' . $user_dir . '/' . $file_name;

        if ($this->validate()) {

            $this->record->saveAs($upload_dir . '/' . $file_name);

            return $url;
        } else {
            return false;
        }
    }
}