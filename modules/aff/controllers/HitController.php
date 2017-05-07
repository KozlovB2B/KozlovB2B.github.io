<?php

namespace app\modules\aff\controllers;

use app\modules\aff\components\AffBaseController;
use app\modules\aff\models\AdEffectReport;
use Yii;
use app\modules\aff\models\Hit;
use app\modules\aff\models\HitSearch;
use yii\web\NotFoundHttpException;

/**
 * HitController implements the CRUD actions for Hit model.
 */
class HitController extends AffBaseController
{
    /**
     * Lists all Hit models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess("aff___hit__index");
        $searchModel = new HitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hit model.
     * @param integer $id
     * @param integer $created_at
     * @return mixed
     */
    public function actionView($id, $created_at)
    {
        $this->checkAccess("aff___hit__index");
        return $this->render('view', [
            'model' => $this->findModel($id, $created_at),
        ]);
    }

    /**
     * AD effect report
     *
     * @return string
     */
    public function actionAdEffect($excel = 0)
    {
        $this->checkAccess("aff___hit__index");

        $report = new AdEffectReport();

        if($excel){
            $report->asExcel();
            exit;
        }

        $data_provider = $report->dataProvider();

        return $this->render('ad_effect', [
            'report' => $report,
            'data_provider' => $data_provider,
        ]);
    }

    /**
     * Finds the Hit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $created_at
     * @return Hit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $created_at)
    {
        if (($model = Hit::findOne(['id' => $id, 'created_at' => $created_at])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
