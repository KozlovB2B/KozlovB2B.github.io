<?php

namespace app\modules\billing\controllers;

use app\modules\billing\components\CashflowReport;
use app\modules\billing\components\cronopay\TransactionNotify;
use app\modules\billing\components\paypal\Invoice;
use app\modules\billing\models\BalanceOperations;
use Yii;
use yii\data\ActiveDataProvider;
use app\modules\core\components\CoreController;

/**
 * BalanceOperationsController implements the CRUD actions for Script model.
 */
class BalanceOperationsController extends CoreController
{
    /**
     * Notifications from Cronopay for admin
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->checkAccess("billing___balance_operations__index_all");

        return $this->render('index', [
            'data_provider' => new ActiveDataProvider([
                'query' => BalanceOperations::find(),
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ])
        ]);
    }
    /**
     * Notifications from Cronopay for admin
     *
     * @return string
     */
    public function actionCashflowReport($excel = 0)
    {
        $this->checkAccess("billing___balance_operations__cashflow_report");

        $report = new CashflowReport();

        if($excel){
            $report->asExcel();
            exit;
        }

        return $this->render('cashflow-report', [
            'report' => $report,
            'data_provider' => $report->search(),
        ]);
    }

    /**
     * Notifications from Cronopay for admin
     *
     * @return string
     */
    public function actionExcel()
    {
        $this->checkAccess("billing___balance_operations__index_all");
        $model = new BalanceOperations();
        $model->asExcel();
    }

    /**
     * Notifications from Cronopay for admin
     *
     * @return string
     */
    public function actionCronopayNotifications()
    {
        $this->checkAccess("billing___balance_operations__index_all");

        return $this->render('cronopay_notifications', [
            'data_provider' => new ActiveDataProvider([
                'query' => TransactionNotify::find(),
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ])
        ]);
    }

    /**
     * Notifications from Cronopay for admin
     *
     * @return string
     */
    public function actionPaypalInvoice()
    {
        $this->checkAccess("billing___balance_operations__index_all");

        return $this->render('paypal_invoices', [
            'data_provider' => new ActiveDataProvider([
                'query' => Invoice::find(),
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
            ])
        ]);
    }
}
