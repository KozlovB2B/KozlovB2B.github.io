<?php

namespace app\modules\script\models\ar;

use app\modules\core\helpers\UUID;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "script_image".
 *
 * @property integer $id
 * @property integer $script_id
 * @property integer $created_at
 * @property integer $svg_size
 * @property integer $png_size
 * @property string $filename
 *
 *
 * @property Script $script
 */
class ScriptImage extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'script_image';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getScript()
    {
        return $this->hasOne(Script::className(), ['id' => 'script_id']);
    }

    /**
     * @return string
     */
    protected static function storageFolder()
    {
        $dir = Yii::getAlias('@app/public_html') . '/script-img';

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        return $dir;
    }

    /**
     * Removes files
     */
    public function removeFiles()
    {
        unlink(static::storageFolder() . '/' . $this->filename . '.png');
        unlink(static::storageFolder() . '/' . $this->filename . '.svg');
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $this->removeFiles();

        return true;
    }

    /**
     * @return string
     */
    public static function storageUrl()
    {
        return Yii::getAlias('@web') . '/script-img';
    }

    /**
     * @return string
     */
    protected static function generateFilename()
    {
        return self::storageFolder() . '/' . uniqid() . '.png';
    }

    /**
     * @param $ext
     * @return string
     */
    public function getDownloadUrl($ext)
    {
        return static::storageUrl() . '/' . $this->filename . '.' . $ext;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['script_id'], 'required'],
            [['script_id', 'created_at', 'svg_size', 'png_size'], 'integer'],
            [['filename'], 'string', 'max' => 64],
            [['script_id'], 'exist', 'skipOnError' => true, 'targetClass' => Script::className(), 'targetAttribute' => ['script_id' => 'id']],
        ];
    }

    /**
     * @param $script_id
     * @param $svg
     * @return bool
     */
    public static function create($script_id, $svg)
    {
        static::gc($script_id);

        $model = new ScriptImage();
        $model->script_id = $script_id;
        $model->created_at = time();
        $model->filename = UUID::v4();

        $svg_file = static::storageFolder() . '/' . $model->filename . '.svg';
        $png_file = static::storageFolder() . '/' . $model->filename . '.png';

        $model->svg_size = file_put_contents($svg_file, $svg);

        // sudo apt-get install librsvg2-bin
        // http://manpages.ubuntu.com/manpages/wily/man1/rsvg-convert.1.html
        exec('rsvg-convert --format png --zoom 2.0 --output "' . $png_file . '" "' . $svg_file . '"');

        $model->png_size = filesize($png_file);
        $model->svg_size = filesize($svg_file);

        return $model->save();
    }

    /**
     * @param $script_id
     * @throws \Exception
     */
    protected static function gc($script_id)
    {
        $images_allowed_for_script = 5;
        $scripts = ScriptImage::find()->andWhere('script_id=:script_id', [':script_id' => $script_id])->orderBy(['id' => SORT_DESC])->all();
        $scripts_count = count($scripts);
        if ($scripts_count >= $images_allowed_for_script) {
            for ($i = $images_allowed_for_script -1; $i < $scripts_count; $i++) {
                $scripts[$i]->delete();
            }
        }
    }
}
