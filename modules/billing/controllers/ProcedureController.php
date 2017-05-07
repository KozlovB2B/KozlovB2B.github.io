<?php
namespace app\modules\billing\controllers;

use app\modules\billing\models\Account;
use app\modules\billing\models\ServiceUsageLog;
use app\modules\billing\models\ServiceUsageLogRecreater;
use Yii;
use yii\console\Controller;
use app\modules\user\models\UserHeadManager;

class ProcedureController extends Controller
{
    /**
     * @param null $command
     * @return int
     */
    public function actionIndex($command = null)
    {
        echo "Добро пожаловать в контроллер по операциям с биллингом." . PHP_EOL;

        return 0;
    }


    /**
     * End trials
     *
     * DEV php yii billing/end-trials
     *
     * PROD php /home/ss/apps/ss/yii_prod billing/end-trials
     *
     * @return int
     */
    public function actionEndTrials()
    {
        Yii::info('End trials', __METHOD__);
        return Account::endTrials();
    }


    /**
     * End trials
     *
     * DEV php yii billing/end-trials
     *
     * PROD php /home/ss/apps/ss/yii_prod billing/flush-day-execution-counters
     *
     * @return int
     */
    public function actionFlushDayExecutionCounters()
    {
        Yii::info('Flush execution counters', __METHOD__);
        return UserHeadManager::flushExecutionCounters();
    }

    /**
     * End trials
     *
     * DEV php yii billing/end-trials
     *
     * PROD php /home/ss/apps/ss/yii_prod billing/flush-month-execution-counters
     *
     * @return int
     */
    public function actionFlushMonthExecutionCounters()
    {
        Yii::info('Flush execution counters', __METHOD__);
        return UserHeadManager::flushExecutionCountersMonth();
    }


    /**
     * Withdraw users balances for service using and blocking debtors
     *
     * DEV php yii billing/withdraw-for-service-use
     *
     * PROD php /home/ss/apps/ss/yii_prod billing/withdraw-for-service-use
     *
     * @return int
     */
    public function actionWithdrawForServiceUse()
    {
        Yii::info('Withdraw service using commission', __METHOD__);
        return Account::blockDebtors() + Account::withdrawForServiceUsing();
    }


    /**
     * Everyday write service usage log
     *
     * DEV php yii billing/write-service-usage-log
     *
     * PROD php /home/ss/apps/ss/yii_prod billing/write-service-usage-log
     *
     * @return int
     */
    public function actionWriteServiceUsageLog()
    {
        Yii::info('Write service usage log', __METHOD__);
        return ServiceUsageLog::saveLog();
    }


    /**
     *
     * @param int $id User id
     * @param int $summ sum
     * @return int
     * @throws \yii\base\Exception
     */
    public function actionTopUpBalance($id, $sum)
    {
        Yii::info('Top up balance for user', __METHOD__);
        $result = Account::topUpUserBalance($id, $sum);

        if($result){
            echo 'User balance has been up!' . PHP_EOL;
        }

        return 1;
    }

    /**
     *
     */
    public function actionRecreateServiceUsage()
    {
        Yii::info('actionRecreateServiceUsage', __METHOD__);
        ServiceUsageLogRecreater::recreate();
    }
}