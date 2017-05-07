<?php
namespace app\modules\integration\modules\apiv2\controllers;

use app\modules\integration\modules\apiv2\components\BaseController;
use Yii;
use yii\web\NotFoundHttpException;
use app\modules\script\models\Call;

/**
 * ScriptController
 */
class CallController extends BaseController
{

    /**
     * Добавляет ссылку на звуковую запись звонка к звонку
     *
     * @param int $id
     * @param string $url
     * @return string
     */
    public function actionAttachRecord($id, $url)
    {
        $model = $this->findCallModel($id);

        $model->record_url = $url;

        if ($model->update(true, ['record_url'])) {
            return $this->result();
        }

        $this->error(implode(',', $model->getFirstErrors()));

        return null;
    }

    /**
     * Finds the Script model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return null|Call the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCallModel($id)
    {
        if (($model = Call::find()->byAccount($this->_user_head_manager->id)->byId($id)->one()) !== null) {
            return $model;
        }

        $this->error('Звонок не найден!');

        return null;
    }
}