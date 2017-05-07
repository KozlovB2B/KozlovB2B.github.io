<?php

namespace app\modules\script\controllers;

use Yii;
use app\modules\script\models\ar\Field;
use app\modules\script\models\search\FieldSearch;
use app\modules\core\components\CoreController;
use yii\web\NotFoundHttpException;
use app\modules\user\models\UserHeadManager;
use yii\web\Response;

/**
 * FieldController implements the CRUD actions for Field model.
 */
class FieldController extends CoreController
{
    /**
     * @inheritdoc
     */
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
     * @return string
     */
    public function actionIndex()
    {
        $this->checkAccess('script___field__create');

        return $this->render('index', [
            'dataProvider' => (new FieldSearch())->search()
        ]);
    }

    /**
     * @return string
     */
    public function actionCreate()
    {
        $this->checkAccess('script___field__create');

        $model = new Field();
        $saved = false;
        if (Yii::$app->getRequest()->post()) {
            $model->load(Yii::$app->getRequest()->post());
            $model->account_id = UserHeadManager::findHeadManagerByUser()->id;
            $saved = $model->save();
        }

        return $this->renderAjax('_create_modal', [
            'model' => $model,
            'saved' => $saved,
        ]);
    }


    /**
     * @return string
     */
    public function actionNodeFormList()
    {
        $this->checkAccess('script___script__create');

        return $this->renderAjax('_node_form_list');
    }

    /**
     * Updates an existing Field model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess('script___field__update', ['model' => $model]);

        $saved = false;

        if (Yii::$app->getRequest()->post()) {
            $model->load(Yii::$app->getRequest()->post());
            $saved = $model->save();
        }

        return $this->renderAjax('_update_modal', [
            'model' => $model,
            'saved' => $saved
        ]);
    }

    /**
     * Deletes an existing CardType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess('script___field__update', ['model' => $model]);

        return $model->delete();
    }

    /**
     * Finds the Field model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Field the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Field::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
