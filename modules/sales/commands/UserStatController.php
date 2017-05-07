<?php
namespace app\modules\sales\commands;

use Yii;
use yii\console\Controller;
use app\modules\user\models\UserHeadManager;
use app\modules\sales\models\UserStat;

/**
 * User stat command
 *
 * @property \app\modules\sales\Module $module
 *
 * @author Roman Agilov <agilovr@gmail.com>
 */
class UserStatController extends Controller
{
    /**
     * todo rewirte when we hit 10k users
     *
     * Every hour at 55 min aggregate all stats for every client
     *
     * Cron config: 55 * * * *  php /path/to/yii sales/user-stat/aggregate
     */
    public function actionAggregate()
    {
        /** @var UserHeadManager[] $models */
        $models = UserHeadManager::find()->all();
        foreach ($models as $m) {
            echo $m->id . PHP_EOL;
            UserStat::aggregateByUser($m->id);
        }

        echo "Execution time: " . sprintf('%0.5f', Yii::getLogger()->getElapsedTime()) . " с." . PHP_EOL;
        echo "Memory usage:" . round(memory_get_peak_usage() / (1024 * 1024), 2) . " MB" . PHP_EOL;
    }
}