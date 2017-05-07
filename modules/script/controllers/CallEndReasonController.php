<?php

namespace app\modules\script\controllers;

use app\modules\script\models\CallEndReason;
use Yii;
use app\modules\core\components\CoreController;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ScriptController implements the CRUD actions for Script model.
 */
class CallEndReasonController extends CoreController
{
    /**
     * Lists all users's call end reasons
     * @return mixed
     */
    public function actionList()
    {
        $this->checkAccess("script___call_end_reason__manage");
        $account_id = \Yii::$app->getUser()->getId();
        $model = new CallEndReason();

        if ($model->hasNoReasons($account_id)) {
            return $this->renderPartial('_import_default_list_modal', [
                'model' => $model,
            ]);
        }

        $data_provider = new ActiveDataProvider([
            'query' => CallEndReason::find()->byAccount($account_id)->active()->orderDesc(),
        ]);

        $data_provider->pagination->pageSize = $data_provider->getTotalCount();

        return $this->renderPartial('_list_modal', [
            'data_provider' => $data_provider,
            'model' => $model,
        ]);
    }


    /**
     * Lists all users's call end reasons
     * @return mixed
     */
    public function actionListGrid()
    {
        $this->checkAccess("script___call_end_reason__manage");
        $model = new CallEndReason();

        $data_provider = new ActiveDataProvider([
            'query' => CallEndReason::find()->byAccount(\Yii::$app->getUser()->getId())->active()->orderDesc(),
        ]);

        $data_provider->pagination->pageSize = $data_provider->getTotalCount();


        return $this->renderPartial('_list_grid', [
            'data_provider' => $data_provider,
            'model' => $model,
        ]);
    }

    /**
     * Lists all users's call end reasons
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkAccess("script___call_end_reason__manage");
        $model = new CallEndReason();
        $model->account_id = \Yii::$app->getUser()->getId();

        $this->ajaxValidation($model);

        if ($model->save()) {
            $this->result(\Yii::t('script', 'Call end reason added!'));
        }
    }

    /**
     * Lists all users's call end reasons
     * @return mixed
     */
    public function actionImportDefaultList()
    {
        $this->checkAccess("script___call_end_reason__manage");
        $model = new CallEndReason();
        if ($model->importDefaultList(\Yii::$app->getUser()->getId())) {
            $this->result(Yii::t("script", "Default reasons list has been successfully imported!"));
        } else {
            $this->throwException("Cant import default list!");
        }
    }

    /**
     * Updates comment_required property of reason
     *
     * @param integer $id
     * @param boolean $value
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionToggleCommentRequired($id, $value)
    {
        $model = $this->findModel($id);
        $this->checkAccess("script___call_end_reason__update_own", ["call-end-reason" => $model]);
        $model->comment_required = (boolean)$value;
        $model->update(false, ["comment_required"]);
        $this->result("OK");
    }


    /**
     * Deletes an existing CallEndReason model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $this->checkAccess("script___call_end_reason__update_own", ["call-end-reason" => $model]);

        $model->safeDelete();

        $this->result("OK");
    }

    /**
     * Finds the Script model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CallEndReason the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CallEndReason::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
