<?php

namespace app\modules\script\components;

use Yii;
use yii\base\Exception;
use app\modules\script\models\ar\Script;
use app\modules\core\components\Publishable;
use app\modules\user\models\UserHeadManager;

/**
 * Class V1Importer
 * Импортер скриптов первой версии
 *
 * @package app\modules\script\components
 */
class V1Importer
{
    /**
     * Импортиует скрипт со структурой данных версии 1
     *
     * @param array $data
     * @return Script
     * @throws Exception
     */
    public static function import($data)
    {
        $head_manager = UserHeadManager::findHeadManagerByUser();
        $model = new Script();
        $model->user_id = $head_manager->id;
        $model->status_id = Publishable::STATUS_DRAFT;
        $model->import_id = $data['id'];
        $model->original_id = $data['original_id'];
        $model->name = $data['name'];
        $model->start_node_id = $data['start_node_id'];
        $model->max_node = $data['max_node'];
        $model->common_cases = !empty($data['common_cases']) ? json_encode($data['common_cases'], JSON_UNESCAPED_UNICODE) : null;
        $model->data_json = json_encode($data['data_json'], JSON_UNESCAPED_UNICODE);

        $model->modifyNameAsCopy();

        if (!$model->save()) {
            throw new Exception(implode(',', $model->getFirstErrors()));
        }

        return $model;
    }
}