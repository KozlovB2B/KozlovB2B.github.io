<?php

namespace app\modules\script\controllers;

use app\modules\core\components\CoreController;
use app\modules\script\models\ar\ScriptImage;
use Yii;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ScriptImageController
 * @package app\modules\script\controllers
 */
class ScriptImageController extends CoreController
{
    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['create', 'delete'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }

    /**
     * @param $script_id
     * @return string
     */
    public function actionListModal($script_id)
    {
        $this->checkAccess("script___script__create");

        $data_provider = new ActiveDataProvider([
            'query' => ScriptImage::find()->andWhere('script_id=:script_id', [':script_id' => $script_id])->orderBy(['id' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $this->renderAjax('_list_modal', [
            'data_provider' => $data_provider,
            'script_id' => $script_id
        ]);
    }

    /**
     * @param $script_id
     * @return bool
     */
    public function actionCreate($script_id)
    {
        $this->checkAccess("script___script__create");

        return ScriptImage::create($script_id, Yii::$app->getRequest()->post('svg'));
    }

    /**
     * @param $id
     * @return false|int
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("script___script__update", ['script' => $model->script]);

        return $model->delete();
    }

    /**
     * Finds the Operator model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ScriptImage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ScriptImage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested operator does not exist.');
        }
    }
}
