<?php

namespace app\modules\billing\controllers;

use app\modules\billing\models\Invoice;
use app\modules\core\components\CoreController;
use Yii;
use yii\web\NotFoundHttpException;
use kartik\mpdf\Pdf;
use yii\helpers\Url;
use app\modules\billing\models\InvoiceSearch;

/**
 * Class PaymentController
 * @package app\modules\billing\controllers
 */
class InvoiceController extends CoreController
{
    /**
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionAdmin()
    {
        Url::remember('', 'billing___invoice__admin');
        $this->checkAccess('billing___invoice__manage');

        /** @var InvoiceSearch $search */
        $search = \Yii::createObject(InvoiceSearch::className());
        $data_provider = $search->search(\Yii::$app->request->get());

        return $this->render('admin', [
            'search' => $search,
            'data_provider' => $data_provider,
        ]);
    }


    /**
     * Updates an existing Script model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        $this->checkAccess("billing___invoice__manage", ['invoice' => $model]);

        // get your HTML raw content without any layouts or scripts
        $content = $this->renderPartial('view', ['model'=>$model]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
            // set mPDF properties on the fly
            'options' => ['title' => Yii::t('billing', 'Invoice {no}', ['no' => $model->name])],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>[''],
//                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionConfirm($id)
    {
        $this->findModel($id)->confirm();
        Yii::$app->getSession()->setFlash('success', 'Счет помечен как оплаченный, пользователю начислены деньги');
        return $this->redirect(Url::previous('billing___invoice__admin'));
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDecline($id)
    {
        $this->findModel($id)->decline();
        Yii::$app->getSession()->setFlash('success', 'Счет отменен');
        return $this->redirect(Url::previous('billing___invoice__admin'));
    }

    /**
     * @param $id
     * @return Invoice
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        /** @var Invoice $model */
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
