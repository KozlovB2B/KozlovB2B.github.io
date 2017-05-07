<?php

namespace app\modules\integration\modules\hookz\controllers;

use app\modules\core\components\CoreController;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\integration\modules\hookz\models\Hook;
use yii\web\Response;
use yii\web\NotFoundHttpException;

/**
 * ApiCredentialsController implements the CRUD actions for ApiCredentials model.
 */
class HookController extends CoreController
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
     * Creates a new ApiCredentials model.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkAccess('integration___integration__manage');

        $head_manager = UserHeadManager::findHeadManagerByUser();

        /** @var Hook $model */
        $model = new Hook();
        $model->head_id = $head_manager->id;

        $saved = false;

        if (Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $saved = $model->save();
        }

        return $this->renderAjax('_create_modal', ['model' => $model, 'saved' => $saved]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $this->checkAccess('integration___integration__manage');

        $model = $this->findModel($id);

        $saved = false;

        if (Yii::$app->request->post()) {

            $model->load(Yii::$app->request->post());

            $saved = $model->save();
        }

        return $this->renderAjax('_update_modal', ['model' => $model, 'saved' => $saved]);
    }

    /**
     * Creates a new ApiCredentials model.
     * @return mixed
     */
    public function actionList()
    {
        $this->checkAccess('integration___integration__manage');
        return $this->renderAjax('_list');
    }


    /**
     * Deletes an existing Script model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->checkAccess('integration___integration__manage');

        return $this->findModel($id)->delete();
    }

    /**
     * Finds the ApiCredentials model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Hook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hook::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
