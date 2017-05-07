<?php

namespace app\modules\aff\controllers;

use app\modules\aff\components\AffBaseController;
use app\modules\aff\models\Account;
use Yii;
use app\modules\aff\models\PromoLink;
use app\modules\aff\models\PromoLinkSearch;
use yii\web\NotFoundHttpException;
use yii\bootstrap\Html;

/**
 * PromoLinkController implements the CRUD actions for PromoLink model.
 */
class PromoLinkController extends AffBaseController
{

    /**
     * Lists all PromoLink models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->checkAccess("aff___promo_link__index");

        $searchModel = new PromoLinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PromoLink model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->checkAccess("aff___promo_link__index");
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PromoLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->checkAccess("aff___promo_link__index");
        $model = new PromoLink();
        $model->user_id = Yii::$app->getUser()->getId();
        $model->promo_code = Account::current()->promo_code;
        $model->host = Yii::$app->request->hostInfo;

        if (Yii::$app->request->post()) {
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->result(\Yii::t('aff', 'Link created!'));
            } else {
                $this->throwException(Html::errorSummary($model));
            }
        }

        return $this->renderPartial('_create_modal', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PromoLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->checkAccess("aff___promo_link__index");
        $this->findModel($id)->softDelete();

        return $this->redirect('/aff/promo-links');
    }

    /**
     * Finds the PromoLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PromoLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PromoLink::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
