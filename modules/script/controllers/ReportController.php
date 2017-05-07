<?php
namespace app\modules\script\controllers;

use app\modules\script\components\ByCallsReport;
use app\modules\script\components\ByScriptsReport;
use app\modules\script\components\VariantsReport;
use app\modules\script\models\ar\Node;
use app\modules\script\models\ar\VariantsReportAggregate;
use app\modules\script\models\Call;
use app\modules\user\models\UserHeadManager;
use Yii;
use app\modules\core\components\CoreController;
use yii\helpers\Html;

/**
 * ReportController all the reports for user
 */
class ReportController extends CoreController
{

    /**
     * Report by scripts
     *
     * @return string
     */
    public function actionByScripts($excel = 0)
    {
        $this->checkAccess("script___report__view");

        $report = new ByScriptsReport();

        if($excel){
            $report->asExcel();
            exit;
        }

        $data_provider = $report->search();

        return $this->render('by_scripts', [
            'report' => $report,
            'data_provider' => $data_provider,
        ]);
    }

    /**
     * Report by calls
     *
     * @return string
     */
    public function actionByCalls($excel = 0)
    {
        $this->checkAccess("script___report__view");

        $report = new ByCallsReport();

        if($excel){
            $report->asExcel();
            exit;
        }

        $data_provider = $report->search();

        return $this->render('by_calls', [
            'report' => $report,
            'data_provider' => $data_provider,
        ]);
    }

    /**
     * Report by calls
     *
     * @return string
     */
    public function actionVariantsReAggregate()
    {
        $this->checkAccess("script___hits_report__view");

        $calls = Call::find()->byAccount(UserHeadManager::findHeadManagerByUser()->id)->all();
        foreach($calls as $c){
            VariantsReportAggregate::collectData($c);
        }

        echo 'Агрегация завершена!';
    }

    /**
     * Report by calls
     *
     * @return string
     */
    public function actionLoadNodesList($script_id)
    {
        $this->checkAccess("script___hits_report__view");

        echo Html::dropDownList('d', null, Node::dropDownData($script_id), ['prompt' => '--']);
    }

    /**
     * Report by calls
     *
     * @return string
     */
    public function actionVariants()
    {
        $this->checkAccess("script___hits_report__view");

        $report = new VariantsReport();

        $data_provider = $report->search();

        return $this->render('variants', [
            'report' => $report,
            'data_provider' => $data_provider,
        ]);
    }
}
