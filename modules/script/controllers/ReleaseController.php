<?php

namespace app\modules\script\controllers;

use app\modules\core\components\CoreController;
use app\modules\script\components\ScriptConverter;
use app\modules\script\models\ar\Release;
use app\modules\script\models\ar\Script;
use Yii;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ScriptController implements the CRUD actions for Script model.
 */
class ReleaseController extends CoreController
{

    public function behaviors()
    {
        return [
            [
                'class' => 'yii\filters\ContentNegotiator',
                'only' => ['delete'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ]
        ];
    }




    /**
     * Создание релиза
     *
     * @param $id
     * @return string
     */
    public function actionCreate($id)
    {
        $model = new Release();
        $model->script_id = $id;

        $this->checkAccess("script___script__update", ['script' => $model->script]);

        if (!$model->script->v2converted) {
            ScriptConverter::convert($model->script_id);
            $model->script->refresh();
        }

        $model->build = $model->script->getBuild();
        $model->build_md5 = $model->script->build_md5;

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $model->save();
        }

        return $this->renderAjax('_create_modal', ['model' => $model]);
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("script___script__update", ['script' => $model->script]);

        $model->deleted_at = time();

        if ($model->update(false, ['deleted_at'])) {
            return true;
        }

        throw new Exception(implode(',', $model->getFirstErrors()));
    }

    /**
     * Finds the Script model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Release the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Release::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
