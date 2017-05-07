<?php
namespace app\modules\script\components;


use app\modules\core\components\Publishable;
use app\modules\script\models\ar\Script;
use app\modules\user\models\UserHeadManager;
use Yii;
use yii\base\Component;
use yii\base\Exception;

/**
 * Class Gift
 *
 * Adding default script for user after registration
 *
 * @package app\modules\script\components
 */
class Gift extends Component
{

    /**
     * Create script for specified user
     *
     * @param integer $user_id
     * @throws Exception
     */
    public static function accept($user_id)
    {
        $file = Yii::getAlias('@webroot') . Yii::getAlias('@default-script') . DIRECTORY_SEPARATOR . Yii::$app->params['division'] . '.' . Script::SCRIPT_FILE_EXTENSION;

        if (file_exists($file)) {
            self::addScript($user_id, $file, Yii::t('script', 'default-script-name'));
        }

        $file = Yii::getAlias('@webroot') . Yii::getAlias('@default-script') . DIRECTORY_SEPARATOR . Yii::$app->params['division'] . '-2.' . Script::SCRIPT_FILE_EXTENSION;

        if (file_exists($file)) {
            self::addScript($user_id, $file, Yii::t('script', 'default-script-name-2'));
        }

        return UserHeadManager::acceptGift($user_id);
    }

    /**
     * @param $user_id
     * @param $file
     * @param $script_name
     * @return Script
     * @throws Exception
     */
    protected static function addScript($user_id, $file, $script_name)
    {
        if (!file_exists($file)) {
            throw new Exception('Default script file does not exist!');
        }

        $data = json_decode(base64_decode(Script::cleanUpAdFromScriptFile(file_get_contents($file))), true);

        // По наличию элемента $data['script'] различаем скрипт со структурой первой версии от второй
        if (!empty($data['script'])) {
            return V2Importer::import($data);
        } else {
            return V1Importer::import($data);
        }

//        $model = new Script();
//        $model->user_id = $user_id;
//        $model->status_id = Publishable::STATUS_DRAFT;
//        $model->import_id = $data->id;
//        $model->import_version = $data->version;
//        $model->original_id = $data->original_id;
//        $model->original_version = $data->original_version;
//        $model->name = $data->name;
//        $model->description = $data->description;
//        $model->start_node_id = $data->start_node_id;
//        $model->max_node = $data->max_node;
//        $model->max_edge = $data->max_edge;
//        $model->data_json = json_encode($data->data_json, JSON_UNESCAPED_UNICODE);
//        $model->name = $script_name;
//        $model->save(false);
    }

}